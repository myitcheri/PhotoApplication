<?php
namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Paradise\PhotoAppBundle\Controller\ManageSessionController;
use \Imagick;

class GalleryController extends Controller
{
        /**
         * @Route("/", name="photo_gallery")
         * @Template()
         */
        public function indexAction()
        {
                $manage = new ManageSessionController();
                $this->ParadiseUserID = $manage->getUserId();
                
                return $this->render('ParadisePhotoAppBundle:Gallery:index.html.twig',array('ParadiseUserID'=> $this->ParadiseUserID));
        }
    
        /**
         * @Route("/load/gallery", name="load_gallery")
         * @Template()
         */
        public function loadIndexAction()
        {
                $manage = new ManageSessionController();
                $this->ParadiseUserID = $manage->getUserId();
            
                $image = new Imagick;

                $em = $this->getDoctrine()->getManager();

                $conn = $this->get('database_connection'); 

                $globaSearchQuery = '';
                        
                $searchWith = $_GET['search'];   
                        
                if($searchWith != 'ALL')
                {
                        $searchWith = explode('|',$searchWith);
                        
                        $globalSearch = array();
                        
                        $globalSearch = array_filter($searchWith); 
                        
                        if(count($globalSearch) != 0)
                        {
                            $globaSearchQuery = "AND photo.id IN (".$this->getGlobalSearchResult($globalSearch).") ";
                        }
                        else
                        {
                            $globaSearchQuery = '';
                        }    
                }    
               
                $details = $conn->fetchAll("SELECT prev.preview_img, photo.id, photo.photo_desc
                                            FROM previews AS prev, photos AS photo
                                            WHERE prev.unique_id = photo.unique_id 
                                                AND photo.id NOT IN (
                                                        SELECT photo_id
                                                        FROM reported_photos
                                                        WHERE STATUS =1
                                                    )
                                            ".$globaSearchQuery."        
                                            ORDER BY photo.id DESC");
               
                $paginator = $this->get('knp_paginator');

                $pagination = $paginator->paginate($details, $this->get('request')->query->get('page', 1), 30);

                //Generate preview photos
                $render = array($details); 
                $comments = array();

                for($i=0; $i < count($pagination); $i++){
                        
                        $image->readimageblob($pagination[$i]['preview_img']);

                        $image->setimageresolution(300,300);

                        $image->resizeImage(250,null,1,1);

                        $render['id'][$pagination[$i]['id']] = base64_encode($image->getimageblob());
                        
                         //count comments on photos
                        $countComments = $em->getRepository('ParadisePhotoAppBundle:Comments')->findBy(array('photoId'=>$pagination[$i]['id']));

                        $comments['id'][$pagination[$i]['id']] = count($countComments);
                } 

                return $this->render('ParadisePhotoAppBundle:Gallery:load-index.html.twig',
                                      array('data' => $pagination, 
                                            'render'=>$render, 
                                            'comments'=>$comments,
                                            'ParadiseUserID'=> $this->ParadiseUserID));
        }
        
        /**
        * Return Photo ID's of Global Search result
        */
        public function getGlobalSearchResult($globalSearch){
            
                $conn = $this->get('database_connection'); 
                $extraQuery ='';    
                
                 for($i=0; $i< count($globalSearch); $i++)
                 {
                        $catchData = explode(',',$globalSearch[$i]);
                        
                        if($i == 0){
                            $extraQuery .= "WHERE (tagtype_id=".$catchData[0]." AND tagref_id=".$catchData[2].")";
                        }
                        
                        if($catchData[2] != 0 && $i > 0)
                        {    
                                $extraQuery .= " OR (tagtype_id=".$catchData[0]." AND tagref_id=".$catchData[2].")";
                        }
                        
                }
                
                $searchResult = $conn->fetchAll("SELECT photo_id FROM tags ".$extraQuery." ");
                
                for($i=0; $i< count($searchResult); $i++)
                {
                       $photoIDs[] = $searchResult[$i]['photo_id'];
                        
                }
                
                $results = array_unique($photoIDs);
                
                if(count($results) != 0)
                {
                    return implode(',',$results);
                }
                else
                {
                     return '';
                }    
                
        }
        
        /**
        * @Route("/details/{id}", name="photo_details")
        * @Template()
        */
        public function detailsAction($id) {  

                $em = $this->getDoctrine()->getManager();

                $details = $em->getRepository('ParadisePhotoAppBundle:Photos')->find($id);

                //$query = $em->getRepository('ParadisePhotoAppBundle:thumbnails')->findAll();
                
                $conn = $this->get('database_connection'); 

                $query = $conn->fetchAll("SELECT thumb.thumbnail_img, photo.id
                                          FROM thumbnails AS thumb, photos AS photo
                                          WHERE thumb.unique_id = photo.unique_id
                                          AND photo.id NOT IN (
                                                SELECT photo_id
                                                FROM reported_photos
                                                WHERE STATUS =1
                                                )
                                          ORDER BY photo.id DESC");

                $paginator = $this->get('knp_paginator');

                $results = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 30);

                //generate thumbnails photos
                $image = new Imagick;

                $thumbnails = array();

                for($i=0; $i < count($results); $i++){
                        
                        $image->readimageblob($results[$i]['thumbnail_img']);

                        $image->setimageresolution(300,300);

                        $image->resizeImage(137,null,1,1);

                        $thumbnails['id'][$results[$i]['id']] =  base64_encode($image->getimageblob());

                } 

                $tag_cloud = $this->tagCloudAction();

                return $this->render('ParadisePhotoAppBundle:Gallery:details.html.twig',
                                        array('data' => $details, 
                                              'thumbnails'=> $thumbnails, 
                                              'filters' => $results,
                                              'tag_cloud'=>json_encode($tag_cloud)));
        }
    
    
    
        /**
        * @Route("/load/{id}", name="load_details")
        * @Template()
        */
        public function loadAction($id)
        {
                $this->ParadiseUserID = '';
                $manage = new ManageSessionController();
                $this->ParadiseUserID = $manage->getUserId();
            
                $em = $this->getDoctrine()->getManager();

                $details = $em->getRepository('ParadisePhotoAppBundle:Photos')->find($id);

                //generate photo
                $image = new Imagick;

                $image->readimageblob(stream_get_contents($details->getOrgImg()));

                $render = base64_encode($image->getimageblob());

                $tags = $this->getPhotoTags($id);    

                //get comments 
                $comments = $em->getRepository('ParadisePhotoAppBundle:Comments')->findBy(array('photoId'=>$id),array('datepost' => 'DESC'));
                
                //validate if user already like
                $like = $em->getRepository('ParadisePhotoAppBundle:Likes')->findBy(array('photoId'=>$id,'userId'=>$this->ParadiseUserID));
                
                $countLike = $em->getRepository('ParadisePhotoAppBundle:Likes')->findBy(array('photoId'=>$id));
                
                if(count($like) != 0)
                { 
                    $totalLike = count($countLike)-1;
                    $countLike = 'You and '.$totalLike.' other(s) like this photo.';
                }
                else
                {
                    $totalLike = count($countLike);
                    $countLike = $totalLike.' other(s) like this photo.';
                }    

                return $this->render('ParadisePhotoAppBundle:Gallery:load.html.twig', 
                                        array('data' => $details, 
                                              'render' => $render, 
                                              'comments'=>$comments,
                                              'countLike'=>$countLike,
                                              'photoTags'=>json_encode($tags[0]),
                                              'selectTags'=>json_encode($tags[1]),
                                              'validateLike'=>$like,
                                              'ParadiseUserID'=> $this->ParadiseUserID));  
        }

        /**
        * Get tags of photo
        */
        public function getPhotoTags($id)
        {
                $conn = $this->get('database_connection'); 

                $tags = $conn->fetchAll("SELECT ts.*, tty.code FROM tags AS ts, tagtype AS tty WHERE ts.tagtype_id = tty.id AND ts.photo_id=".$id);

                $photoTags = array();

                $selectTags = array();

                if ($tags)
                { 
                        $total = count($tags);

                        for($i=0; $i< $total; $i++){

                                $refId = $tags[$i]['tagref_id'];

                                $tagtype = $tags[$i]['code'];

                                $tagtypeId = $tags[$i]['tagtype_id'];
                                
                                $userId = $tags[$i]['user_id'];

                                $tagContent = $this->queryTagsContent($refId,$tagtype);

                                $unique = $tagtypeId.','.$tagContent.','.$refId;

                                /*if($tagtypeId==4)
                                {
                                    $unique = $tagtypeId.','.$tagContent.',0';
                                }
                                else
                                {
                                    $unique = $tagtypeId.','.$tagContent.','.$refId;
                                }*/

                                $photoTags[] = array('unique'=>$unique, 'title'=>$tagContent, 'category'=>$tagtype.','.$userId.'');

                                $selectTags[] = $unique;
                        }
                }
                
                return array($photoTags,$selectTags);
        }

        /**
        * Get tag cloud content
        */
        public function tagCloudAction()
        {
                $conn = $this->get('database_connection'); 
                
                $tags = $conn->fetchAll("SELECT ts.*, COUNT(ts.id) AS SCORE, tty.code FROM tags AS ts, tagtype AS tty WHERE ts.tagtype_id = tty.id GROUP BY ts.tagref_id, ts.tagtype_id ORDER BY SCORE DESC");

                $json = array();

                if ($tags){
                    
                        $total = count($tags);

                        for($i=0; $i< $total; $i++){
                            
                            $tagTitle = $this->queryTagsContent($tags[$i]['tagref_id'],$tags[$i]['code']);

                            $json[] = array('text'=>$tagTitle,'weight'=>$tags[$i]['SCORE']);
                            
                        }
                }

                return $json;
        }

        /**
        * Get title of Tags
        */
        public function queryTagsContent($refID,$tagType)
        {
                $em = $this->getDoctrine()->getManager(); 

                switch ($tagType){

                    case "events":

                        $tags = $em->getRepository('ParadisePhotoAppBundle:Events')->findBy(array('id'=>$refID));

                        return $tags[0]->getTitle();

                        break;

                    case "keywords":

                        $tags = $em->getRepository('ParadisePhotoAppBundle:Keywords')->findBy(array('id'=>$refID));

                        return $tags[0]->getTitle();

                        break;
                }
        }

}

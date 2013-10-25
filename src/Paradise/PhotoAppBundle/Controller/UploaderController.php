<?php

namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
Use Paradise\PhotoAppBundle\Controller\ManageSessionController;
use \Imagick;

class UploaderController extends Controller
{
        /**
         * @Route("/uploader",name="upload")
         * @Template()
         */
        public function indexAction()
        {
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

                return $this->render('ParadisePhotoAppBundle:Gallery:upload.html.twig',
                                        array('thumbnails'=> $thumbnails, 
                                              'filters' => $results,
                                              'tag_cloud'=>json_encode($tag_cloud)));
                
        }
        
        /**
        * @Route("/uploader/save")
        */
        public function uploaderAction()
        {
                $manage = new ManageSessionController();
                $this->ParadiseUserID = $manage->getUserId();
           
                $file       = $_POST['value'];
                $name       = $_POST['name'];
                $filesize   =  number_format($_POST['filesize'] / 1048576, 2); //1024 for KB
                $filetype   = $_POST['filetype'];
                $desc       = $_POST['desc'];
                $randomName = $_POST['uniqueName'];
                
                $photo = fopen($file, 'rb');
                
                $conn = $this->get('database_connection'); 

                $conn->insert('photos', array('org_img'     => stream_get_contents($photo),
                                              'filename'    => $name,
                                              'unique_id'   => $randomName,
                                              'filesize'    => $filesize.' MB',
                                              'filetype'    => $filetype,
                                              'photo_desc'  => $desc,
                                              'user_id'     => $this->ParadiseUserID));  

                //Generate small image
                $this->thumbnailAction($file,$randomName);

                $this->previewAction($file,$randomName);
                
                die('Photo uploaded successfully');
        }
        
        public function bulkTaggingAction($tags, $photoID)
        {
                $manage = new ManageSessionController();
                $this->ParadiseUserID = $manage->getUserId();
            
                $conn = $this->get('database_connection');
                
                if(!empty($tags))
                {
                    $tags = explode("|", $tags);
                    
                    $countTags = count($tags) - 1;
                    
                    for($i=0; $i < $countTags; $i++)
                    {
                            $data = explode(',',$tags[$i]); //[0] tagtype [1] text [2] ref_id
                            
                            $tagtype = $data[0];
                            $tag     = $data[1];
                            $refID   = $data[2];
                            
                            if($refID==0) //if tag type is keywords 
                            { 
                                  $refID = $this->getKeywordId($tag); 
                            }
                            
                            $conn->insert('tags', array('photo_id' => $photoID, 
                                                        'tagref_id' => $refID, 
                                                        'tagtype_id'=> $tagtype, 
                                                        'user_id'=>$this->ParadiseUserID)); //PARADISE USER ID
                    }
                }   
        }
        
        //Generate thumbnails
        public function thumbnailAction($file,$randomName)
        {
                $photo = fopen($file, 'rb');

                $image = new Imagick;

                $image->readimageblob(stream_get_contents($photo));

                $image->thumbnailImage(137, 0);

                $blob = base64_encode($image->getimageblob());

                $thumbnail = fopen('data://text/plain;base64,'.$blob, 'r');

                $conn = $this->get('database_connection'); 

                $conn->insert('thumbnails', array('thumbnail_img' => stream_get_contents($thumbnail),'unique_id'=>$randomName));  
        }

        //Generate preview image
        public function previewAction($file,$randomName)
        {
                $photo = fopen($file, 'rb');

                $image = new Imagick;

                $image->readimageblob(stream_get_contents($photo));

                $image->thumbnailImage(250, 0);

                $blob = base64_encode($image->getimageblob());

                $preview = fopen('data://text/plain;base64,'.$blob, 'r');

                $conn = $this->get('database_connection'); 

                $conn->insert('previews', array('preview_img' => stream_get_contents($preview),'unique_id'=>$randomName));  

        }

        /**
        * Get tag cloud content
        */
        public function tagCloudAction()
        {
                $conn = $this->get('database_connection'); 

                $tags = $conn->fetchAll("SELECT ts.*, COUNT(ts.id) AS SCORE, tty.code FROM tags AS ts, tagtype AS tty WHERE ts.tagtype_id = tty.id GROUP BY ts.tagref_id, ts.tagtype_id ORDER BY SCORE DESC");

                $json = array();

                if ($tags)
                {
                    $total = count($tags);

                    for($i=0; $i< $total; $i++)
                    {
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

                switch ($tagType)
                {
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
        
        /**
        * Get Keyword ID
        */
        public function getKeywordId($keyword)
        {
                $conn = $this->get('database_connection');  
                
                $em = $this->getDoctrine()->getManager();

                $details = $em->getRepository('ParadisePhotoAppBundle:Keywords')->findBy(array('title'=>$keyword));  

                if(COUNT($details)==0)
                {
                        $conn->insert('keywords', array('title'=>$keyword,'user_id'=>1)); //DUMMY USER ID
                }

                $keyword = $em->getRepository('ParadisePhotoAppBundle:Keywords')->findBy(array('title'=>$keyword));

                return $keyword[0]->getId();
        }

}

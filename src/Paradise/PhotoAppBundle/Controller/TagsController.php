<?php

namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class TagsController extends Controller
{
        /**
        * @Route("/tags")
        */
        public function indexAction()
        {
                $filter = $_GET['searchword'];

                $keywords = $this->getKeywords($filter);
                
                $events = $this->getEvents($filter);

                $tags = array_merge_recursive($keywords,$events);

                $response = new Response(json_encode(array('tags'=>$tags))); 

                $response->headers->set('Content-Type', 'application/json');

                return $response;
        }

        /**
        * @Route("/tags/create/", name="create_tags")
        */
        public function tagsCreateAction()
        {
                $conn = $this->get('database_connection'); 
                
                $em = $this->getDoctrine()->getManager();

                $photoID = $_GET['id'];
                $userID = $_GET['uid'];
                
                $data = explode(',',$_GET['q']);
                
                $refID = $data[2];
                
                $tagTypeID = $data[0];

                if($refID==0) //if tag type is keywords 
                { 
                        $refID = $this->getKeywordId($data[1]); 
                }
                
                //validate if tag already exist
                $validate = $em->getRepository('ParadisePhotoAppBundle:Tags')->findBy(array('tagrefId'=>$refID,'tagtype'=>$tagTypeID,'photo'=>$photoID));

                if($validate == null)
                {
                        $conn->insert('tags', array('photo_id' => $photoID, 'tagref_id' => $refID, 'tagtype_id'=> $tagTypeID, 'user_id'=>$userID)); //PARADISE USER ID
                        
                        return die('Tag is saved to database.');
                }
                else 
                {
                        return die('Tag already exist.');
                }
        }
        
        /**
        * @Route("/tags/bulk/")
        */
        public function tagsBulkCreateAction()
        {
                $conn = $this->get('database_connection'); 
                
                $em = $this->getDoctrine()->getManager();

                $data = explode(',',$_GET['q']);
                
                $tagTypeID = $data[0];
                $searchWord = $data[1];
                $refID = $data[2];
            
                $photoIDs = $data = explode('|',$_GET['ids']);
                
                $countID = count($photoIDs)-1;
                
                for($i=0; $i<$countID; $i++)
                {
                    $photoID = $conn->fetchAll("SELECT id FROM photos WHERE unique_id='".$photoIDs[$i]."'");
                    
                    if($refID==0) //if tag type is keywords 
                    { 
                            $refID = $this->getKeywordId($searchWord); 
                    }
                    
                    $validate = $em->getRepository('ParadisePhotoAppBundle:Tags')->findBy(array('tagrefId'=>$refID,'tagtype'=>$tagTypeID,'photo'=>$photoID[0]['id']) );

                    if($validate == null)
                    {
                            $conn->insert('tags', array('photo_id' => $photoID[0]['id'], 'tagref_id' => $refID, 'tagtype_id'=> $tagTypeID, 'user_id'=>1)); //DUMMY USER ID
                    }
                    else 
                    {
                            return die('Tag already exist.');
                   }
                }
                return die('Bulk tagging successful.');    
        }
        
        /**
        * @Route("/tags/bulkdelete/")
        */
        public function deleteBulkCreateAction()
        {
                $conn = $this->get('database_connection'); 
            
                $em = $this->getDoctrine()->getManager();

                $data = explode(',',$_GET['q'][0]);
                
                $tagTypeID = $data[0];
                $searchWord = $data[1];
                $refID = $data[2];
            
                $photoIDs = $data = explode('|',$_GET['ids']);
                
                $countID = count($photoIDs)-1;
                
                for($i=0; $i<$countID; $i++)
                {
                   if($refID == 0)
                   {
                           $tags = $em->getRepository('ParadisePhotoAppBundle:Keywords')->findBy(array('title'=>$searchWord));

                           $refID = $tags[0]->getId();
                   }
                   
                   $photoID = $conn->fetchAll("SELECT id FROM photos WHERE unique_id='".$photoIDs[$i]."'");

                   $query = $em->createQuery('DELETE FROM ParadisePhotoAppBundle:Tags tags WHERE tags.tagtype=:tagtype AND tags.tagrefId=:ref_id AND tags.photo=:photo_id');

                   $query->setParameter('photo_id',$photoID[0]['id']);

                   $query->setParameter('tagtype',$tagTypeID);

                   $query->setParameter('ref_id',  str_replace('"','',$refID));

                   $query->execute();
                   
                   
                }
                return die('Delete bulk tags successful.'); 
        }
        

        /**
        * Get Keywords
        */
        public function getKeywords($filter)
        {
                $em = $this->getDoctrine()->getManager();
                
                $queryTags = $em->createQuery('SELECT t FROM ParadisePhotoAppBundle:Keywords t WHERE t.title LIKE :title');
                
                $tags = $queryTags->setParameter('title', '%'.$filter.'%')->getResult();

                if ($tags)
                {    
                        $total = count($tags);
                        $json = array();

                        for($i=0; $i< $total; $i++)
                        {
                                $json[] = array('unique'=>'4,'.$tags[$i]->getTitle().','.$tags[$i]->getId(),
                                                'title'=>$tags[$i]->getTitle(), 
                                                'category'=>'keywords,enableDelete');
                        }

                        return $json;
                }
                else
                {    
                        return array();
                }
        }

        /**
        * Get Events
        */
        public function getEvents($filter)
        {
                $em = $this->getDoctrine()->getManager();
                
                $queryTags = $em->createQuery('SELECT t FROM ParadisePhotoAppBundle:Events t WHERE t.title LIKE :title');
                
                $tags = $queryTags->setParameter('title', '%'.$filter.'%')->getResult();

                if ($tags)
                {
                        $total = count($tags);
                        
                        $json = array();

                        for($i=0; $i< $total; $i++)
                        {
                                $json[] = array('unique'=>'3,'.$tags[$i]->getTitle().','.$tags[$i]->getId(),
                                                'title'=>$tags[$i]->getTitle(), 
                                                'category'=>'events,enableDelete');
                        }

                        return $json;
                }
                else
                {    
                        return array();
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

        /**
        * @Route("/tags/delete/", name="delete_tags")
        */
        public function tagsDeleteAction()
        {
                $q = $_GET['q'][0];
                $data = explode(',',$q);
                $id = $_GET['id']; 

                $em = $this->getDoctrine()->getManager(); 

                if($data[2] == 0)
                {
                        $tags = $em->getRepository('ParadisePhotoAppBundle:Keywords')->findBy(array('title'=>$data[1]));

                        $ref_id = $tags[0]->getId();
                }
                else
                {
                        $ref_id = $data[2];
                }

                $query = $em->createQuery('DELETE FROM ParadisePhotoAppBundle:Tags tags WHERE tags.tagtype=:tagtype AND tags.tagrefId=:ref_id AND tags.photo=:photo_id');
                
                $query->setParameter('photo_id',$id);
                
                $query->setParameter('tagtype',$data[0]);
                
                $query->setParameter('ref_id',  str_replace('"','',$ref_id));
                
                $query->execute();

                return die('Tag is deleted from database.');
        }
}

<?php

namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{
        /**
        * @Route("/like", name="like_photo")
        */
        public function likePhotoAction()
        {
                $photoID = $_GET['photoID'];
                $userID = $_GET['userID'];

                $conn = $this->get('database_connection');  
                
                $em = $this->getDoctrine()->getManager();
                
                $validate = $em->getRepository('ParadisePhotoAppBundle:Likes')->findBy(array('userId' => $userID, 'photoId' => $photoID));

                if($validate == null)
                {
                        $conn->insert('likes', array('user_id' => $userID, 'photo_id' => $photoID)); 
                        
                        $likes = $em->getRepository('ParadisePhotoAppBundle:Likes')->findBy(array('photoId'=>$photoID));
                        
                        if(count($likes) != 0)
                        { 
                            $totalLikes = count($likes);
                        }
                        else
                        {
                            $totalLikes = 0;
                        }    
                        
                        return new Response($totalLikes);
                }
                else 
                {
                        return die('You already like this photo.');
                }
                
               

               
        }
        
        /**
        * @Route("/deletelike", name="deletelike_photo")
        */
        public function deteLikePhotoAction()
        {
                $photoID = $_GET['photoID'];
                $userID = $_GET['userID'];

                $em = $this->getDoctrine()->getManager();
                
                $query = $em->createQuery('DELETE FROM ParadisePhotoAppBundle:Likes likes WHERE likes.photoId=:photo_id AND likes.userId=:user_id');

                $query->setParameter('photo_id',$photoID);
                $query->setParameter('user_id',$userID);
                $query->execute();

                $likes = $em->getRepository('ParadisePhotoAppBundle:Likes')->findBy(array('photoId'=>$photoID));
               
                if(count($likes) != 0)
                { 
                    $totalLikes = count($likes);
                }
                else
                {
                    $totalLikes = 0;
                }    

                return new Response($totalLikes);
        }
}

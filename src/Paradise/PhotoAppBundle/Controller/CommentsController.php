<?php

namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{

        /**
        * @Route("/comment/delete/", name="delete_comment")
        */
        public function deleteCommentAction()
        {
                $commentID = $_GET['id'];
                $photoID = $_GET['photoID'];

                $em = $this->getDoctrine()->getManager(); 

                $query = $em->createQuery('DELETE FROM ParadisePhotoAppBundle:Comments comments WHERE comments.id=:id');
                
                $query->setParameter('id',$commentID);
                
                $query->execute();

                $comments = $em->getRepository('ParadisePhotoAppBundle:Comments')->findBy(array('photoId'=>$photoID));
                
                $result = array('msg'=>'Comment is deleted from database.','totalComments'=>count($comments));

                return new Response(json_encode($result));
        }

        /**
        * @Route("/comment/create/", name="create_comment")
        */
        public function createCommentAction()
        {
                $photoID = $_GET['id'];
                $comment = $_GET['comment'];
                $userID = $_GET['uid'];

                $conn = $this->get('database_connection');  

                $conn->insert('comments', array('photo_id' => $photoID, 'comment' => $comment, 'user_id'=>$userID)); //PARADISE USER ID 

                $em = $this->getDoctrine()->getManager(); 
                
                $query = $em->getRepository('ParadisePhotoAppBundle:Comments')->findBy(array('photoId'=>$photoID),array('datepost' => 'DESC'));

                $result = array('commentID'=>$query[0]->getId(),
                                'msg'=>$query[0]->getComment(),
                                'userId'=>$query[0]->getUserId(),
                                'datepost'=>$query[0]->getDatepost()->format('d M, Y'),
                                'totalComments'=>count($query));

                return new Response(json_encode($result));
        }
}

<?php

namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FavoritesController extends Controller
{
        /**
        * @Route("/favorites/", name="add_to_favorites")
        * @Template()
        */
        public function favoriteAction()
        {
                $conn = $this->get('database_connection'); 
                
                $em = $this->getDoctrine()->getManager();
                
                $photoID = $_GET['photoID'];
                $userID = $_GET['userID'];

                $validate = $em->getRepository('ParadisePhotoAppBundle:Favorites')->findBy(array('photoId'=>$photoID,'userId'=>$userID));

                if($validate == null)
                {
                        $conn->insert('favorites', array('photo_id' => $photoID, 'user_id' => $userID)); //DUMMY USER ID
                        
                        return die('Save to database successful.');
                }
                else 
                {
                        return die('Favorite already exist.');
                }
        }
}

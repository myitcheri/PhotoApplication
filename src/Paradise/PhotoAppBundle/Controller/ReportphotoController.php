<?php

namespace Paradise\PhotoAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Imagick;

class ReportphotoController extends Controller
{
        /**
        * @Route("/reportPhoto")
        * @Template()
        */
        public function indexAction()
        {
                $photoID = $_GET['photoID'];
                
                $userID = $_GET['userID'];

                $image = new Imagick;

                $em = $this->getDoctrine()->getManager();

                $details = $em->getRepository('ParadisePhotoAppBundle:Photos')->find($photoID);

                $preview = $em->getRepository('ParadisePhotoAppBundle:Previews')->findBy(array('uniqueId'=>$details->getUniqueId()));
                
                $image->readimageblob(stream_get_contents($preview[0]->getPreviewImg()));
                
                $image->resizeImage(260,null,1,1);

                $render = base64_encode($image->getimageblob());

                return $this->render('ParadisePhotoAppBundle:Gallery:reportphoto.html.twig',array('render'=>$render, 'photo'=>$details, 'userID'=>$userID)); 
        }
        
        /**
        * @Route("/inappropriate/", name="photo_inappropriate")
        * @Template()
        */
        public function inappropriateAction()
        {
                $photoID = $_GET['photoID'];
                
                $userID  = $_GET['userID'];
                
                $reason  = $_GET['reason'];

                $conn = $this->get('database_connection'); 

                $conn->insert('reported_photos', array('photo_id' => $photoID, 'user_id' => $userID, 'reason'=>$reason, 'status'=>1));
                
                return die('Photo is reported as inappropriate.');
        }
        
        /**
        * @Route("/validate/status/", name="")
        * @Template()
        */
        public function validatePhotoAction()
        {
               $photoID = $_GET['photoID'];
               
               $em = $this->getDoctrine()->getManager();
            
               $details = $em->getRepository('ParadisePhotoAppBundle:ReportedPhotos')->findBy(array('photoId'=>$photoID, 'status'=>1));
               
               if($details)
               {
                   die('TRUE');
               }
               else
               {
                   die('FALSE');
               }
        }
}

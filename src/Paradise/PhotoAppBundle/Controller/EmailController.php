<?php
/* src/Paradise/PhotoAppBundle/Controller/EmailController.php */
/**
 * Send Image to Email
 *
 * @author Paul Andre Francisco
 * @date   Sept 19, 2013
 */

namespace Paradise\PhotoAppBundle\Controller;

Use Symfony\Component\Filesystem\Filesystem;
Use Symfony\Component\Filesystem\Exception\IOException;
Use Symfony\Bundle\FrameworkBundle\Controller\Controller;
Use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
Use \Imagick;

define("PATH_TO_DELETE", "C:\\xampp\\htdocs\\photoapplication\\tmp\\emailer\\"); //  FOR WINDOWS
//define("PATH_TO_REMOVE", "/home/development/photoapp_development/web/bootstrap/for_download/"); FOR NGINX

class EmailController extends Controller
{
    public function getImage() { return "ParadisePhotoAppBundle:Photos"; }
    /**
    * @Route("/renderEmail")
    * @Template()
    */
    public function renderEmailContainerAction()
    {
        gc_enable();
        $request = $this->getRequest();

        //$this->photoId = $request->request->get('photoID'); print $this->photoId; die();
        $this->photoId = $_GET['photoID'];
        $filename = "";
        try
        {
            $this->entityManager = $this->getDoctrine()->getManager();
            $this->queryBuilder  = $this->entityManager->createQueryBuilder();
            $this->result = $this->queryBuilder->select('img.id, img.orgImg, img.filename')
                 ->from(self::getImage(), 'img')
                 ->where('img.id = ?1')
                 ->setParameter(1, $this->photoId)
                 ->addOrderBy('img.id')
                 ->setMaxResults(1)
                 ->getQuery()
                 ->getResult();

            //die(print_r($this->result));

            $this->imgId = $this->result[0]['id'];
            $this->filename = json_encode($this->result[0]['filename']);
            $this->image = new Imagick();
            $this->image->readimageblob(stream_get_contents($this->result[0]['orgImg']));
            $this->encode[0]['img'] = $this->image->getimageblob();

            $filename = $this->result[0]['filename'];
        }
        catch (\QueryException $exc)
        {
            echo $exc->getTraceAsString();
        }

        try
        {
            $this->fileSystem = new Filesystem();

            if(is_dir(PATH_TO_DELETE))
            {
                $this->fileSystem->remove(array(PATH_TO_DELETE));
            }

            $this->fileSystem->mkdir(PATH_TO_DELETE);
            $this->fileSystem->chmod(PATH_TO_DELETE, 0755, 0000, true);
            file_put_contents(PATH_TO_DELETE . json_decode($this->filename), $this->encode[0]['img']);
        }
        catch (IOException $exc)
        {
            echo $exc->getTraceAsString();
        }

        //die($filename);
        return $this->render('ParadisePhotoAppBundle:Gallery:email.helper.html.twig', array("filename" => $filename));
    }

    /**
    * @Route("/sendEmail")
    *
    */
    public function sendEmailAction()
    {
        gc_enable();

        $request  = $this->getRequest();
        $this->fileName    = $request->request->get('filename');
        $this->fileImage   = $request->request->get('image');
        $this->validEmails = $request->request->get('email');
        $this->validCC     = $request->request->get('cc');
        $this->validBCC    = $request->request->get('bcc');
        $this->body        = $request->request->get('body');
        $this->subject     = $request->request->get('subject');

        $this->validEmails = $this->validEmails != null ? $this->validEmails = explode(",", $this->validEmails) : die("Invalid email");
        $this->recipients = array();
        for($i = 0; $i < count($this->validEmails); $i++)
        {
            $this->recipients[] = $this->validEmails[$i];
        }

        $this->validCC = $this->validCC != null ? $this->validCC = explode(",", $this->validCC) : null;
        $this->cc = array();
        for($cc = 0; $cc < count($this->validCC); $cc++)
        {
            $this->cc[] = $this->validCC[$cc];
        }

        $this->validBCC = $this->validBCC != null ? $this->validBCC = explode(",", $this->validBCC) : null;
        $this->bcc = array();
        for($bcc = 0; $bcc < count($this->validBCC); $bcc++)
        {
            $this->bcc[] = $this->validBCC[$bcc];
        }

        $this->body = $this->body == null ? "Blank message from sender" : $this->body;

        $this->subject = $this->subject == null ? "Photo Application" : $this->subject;

        //$this->recipients = array('myit.paul@gmail.com','myit.cheri@gmail.com','ryan.nolasco@thetshop.com.au','carlo.tesoro@thetshop.com.au','laarni.oriente@thetshop.com.au','sabrina.lim@thetshop.com.au');

        $message = \Swift_Message::newInstance()
        ->setSubject($this->subject)
        ->setFrom('myit.paul@gmail.com', "Paul Andre Francisco")
        ->setTo($this->recipients)
        ->setCc($this->cc)
        ->setBcc($this->bcc)
        ->setBody( 'Test Email Message:  '. $this->body .'.       Image has been sent to ' . count($this->recipients) . ' recipient/s     ' )
        ->attach(\Swift_Attachment::fromPath(PATH_TO_DELETE . $this->fileName));

        $this->get('mailer')->send($message);

        gc_collect_cycles();
        gc_disable();

        return new Response('Email Sent...');
    }

    /**
    * @Route("/deleteFolder")
    *
    */
    public function deleteFolderAction()
    {
        gc_enable();

        $this->fileSystem = new Filesystem();
        //$request  = $this->getRequest();
        //$this->fileName  = $request->request->get('filename');

        try
        {
            $this->fileSystem->remove(array(PATH_TO_DELETE));

            gc_collect_cycles();
            gc_disable();
        }
        catch (IOException $e)
        {
            echo $e->getTraceAsString();
        }
        die();
    }
}
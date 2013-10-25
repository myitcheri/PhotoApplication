<?php
/*src/Paradise/PhotoAppBundle/Controller/ManageSessionController.php*/

/**
 * @Date: 09/30/2013
 * @Description: Manage Session from non-symfony application
 * @Get the data from GLOBAL VAR $_SESSION and assign to Symfony session
 * @USAGE: Instantiate this class and get the SESSION NEEDED
 * @ADD SESSION AND ADD GETTER IF NEEDED
 * @session->set("var_name", "var_value")
 * @session->get("var_name")
 *
 * @author pfrancisco
 */

namespace Paradise\PhotoAppBundle\Controller;

Use Symfony\Component\HttpFoundation\Session\Session;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\Controller;
Use Symfony\Component\HttpFoundation\Response;

class ManageSessionController extends Controller
{
    public function __construct()
    {
        try
        {
            session_set_cookie_params(600, '/', '.local.dev');
            $this->session = new Session();
            $this->session->start();

            $this->session->set('session_id', $this->session->getId());
            $this->session->set("user_id", $_SESSION['user_id']);
            
            if($this->session->get('user_id') == '')
            {
                die(header("location: https://paradise.myitcentre.com.au/paradise/index.php"));
            }
            
            /*$this->session->set('user_id', $_SESSION["Paradise"]["UserID"]);
            $this->session->set('first_name', $_SESSION["Paradise"]["FirstName"]);
            $this->session->set('last_name', $_SESSION["Paradise"]["LastName"]);
            $this->session->set('email_address', $_SESSION["Paradise"]["EmailAddress"]);
            $this->session->set('workgroup_id', $_SESSION["Paradise"]["WorkGroup"]);*/
        }
        catch (\SessionUnavailableException $exc)
        {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * @Route("/session/userId")
     *
     */
    public function getLocalId()
    {
        return ($this->session->get('local_id'));
    }

    public function getUserId()
    {
        return ($this->session->get('user_id'));

        /*Use return new responsen when testing*/
    }

    public function getFirstName()
    {
        return ($this->session->get('first_name'));
        /*Use return new responsen when testing*/
    }

    public function getLastName()
    {
        return ($this->session->get('last_name'));
        /*Use return new responsen when testing*/
    }
    public function getEmailAdd()
    {
        return ($this->session->get('email_address'));
        /*Use return new responsen when testing*/
    }

    public function getWorkGroupId()
    {
        return ($this->session->get('workgroup_id'));
        /*Use return new responsen when testing*/
    }

    public function killSession()
    {
        return ($this->session->invalidate());
        /*for testing*/
    }
}
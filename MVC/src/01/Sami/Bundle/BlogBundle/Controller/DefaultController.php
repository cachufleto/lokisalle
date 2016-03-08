<?php

namespace Sami\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        echo 'J\'ai tapé dans le controller dans l\'action index !';
        exit();
        
        //return $this->render('SamiBlogBundle:Default:index.html.twig');
    }

    public function jaifaimAction($information, $autre_information)
    {
 
        $date = new \DateTime();

        return $this->render('SamiBlogBundle:Default:jaifaim.html.twig', array('info' => $information,'autreinfo' => $autre_information, 'dateDuJour' => $date));
    }

}

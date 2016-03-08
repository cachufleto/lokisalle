<?php

namespace Sami\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExerciceController extends Controller
{

    public function action1Action($nombre1, $nombre2){
        $calcul = $nombre1 * $nombre2;
        return $this->render('SamiBlogBundle:Exercice:action1.html.twig', array('resultat' => $calcul, 'nb1' => $nombre1, 'nb2' => $nombre2));
    }

    public function action2Action($arg1, $arg2, $arg3){
        $concatenation = $arg1 . $arg2;

        return $this->render('SamiBlogBundle:Exercice:action2.html.twig', array('concat' => $concatenation, 'arg3' => $arg3));
    }
 
    public function action3Action($unedate){
        $timestamp = strtotime($unedate);
        return $this->render('SamiBlogBundle:Exercice:action3.html.twig', array('tstamp' => $timestamp, 'date' => $unedate));
    }

    
}

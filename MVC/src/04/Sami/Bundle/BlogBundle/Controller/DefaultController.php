<?php

namespace Sami\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sami\Bundle\BlogBundle\Entity\Articles;
use Sami\Bundle\BlogBundle\Entity\Users;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $doctrine = $this->getDoctrine();

        $entityRepository = $doctrine->getRepository('SamiBlogBundle:Articles');

        $tousMesArticles = $entityRepository->findAll();
        
        /*
        $unArticle = $entityRepository->find(1); //trouve un article par son id
        var_dump($unArticle);
        //
        $plusieursArticles = $entityRepository->findByTitle('premier titre'); //trouve un ou plusieurs articles par leurs titres
        var_dump($plusieursArticles);
        exit();
        */

        return $this->render('SamiBlogBundle:Default:index.html.twig', array('mesArticles' => $tousMesArticles));
    }


    public function createAction(){

       $user = new Users();
       $user->setEmail('test@yopmail.com');
       $user->setPassword('654321');
       $user->setSignature('Batman');
       
       $article = new Articles();
       $article->setTitle('Article créé !');
       $article->setBody('Corps de l\'article créé !');
       $article->setPublished(new \DateTime());
       $article->setUser($user);

       $doctrine = $this->getDoctrine();
       $entityManager = $doctrine->getManager();
       //ajoute l'objet dans $user à la liste des objets à enregistrer en base.
       $entityManager->persist($user);
       //ajoute l'objet dans $article à la liste des objets à enregistrer en base.
       $entityManager->persist($article);
       //enregistre en base la liste des objets à enregistrer.
       $entityManager->flush();
       

       return $this->render('SamiBlogBundle:Default:create.html.twig');
    }

    public function updateAction($id){
        $doctrine = $this->getDoctrine();

        $entityRepository = $doctrine->getRepository('SamiBlogBundle:Articles');

        $monArticle = $entityRepository->find($id);

        $success = false;
        if($monArticle){

            $monArticle->setTitle('Titre modifié');

            $entityManager = $doctrine->getManager();

            $entityManager->persist($monArticle);

            $entityManager->flush();

            $success = true;
        }

        return $this->render('SamiBlogBundle:Default:update.html.twig', array('success' => $success));
    }

    public function deleteAction($id){
        $doctrine = $this->getDoctrine();

        $entityRepository = $doctrine->getRepository('SamiBlogBundle:Articles');

        $monArticle = $entityRepository->find($id);

        $success = false;
        if($monArticle){

            $entityManager = $doctrine->getManager();

            $entityManager->remove($monArticle);

            $entityManager->flush();

            $success = true;
        }

        return $this->render('SamiBlogBundle:Default:delete.html.twig', array('success' => $success));
    }
    


    public function jaifaimAction($information, $autre_information)
    {
 
        $date = new \DateTime();

        return $this->render('SamiBlogBundle:Default:jaifaim.html.twig', array('info' => $information,'autreinfo' => $autre_information, 'dateDuJour' => $date));
    }

}

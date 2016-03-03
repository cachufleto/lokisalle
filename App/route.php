<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 03/03/2016
 * Time: 10:06
 *
 * la spécialisation des logiques
 * controleur.1 <- model.1
 * controleur.1 <- view.1
 * controleur.2 <- model.2 <- model.1
 * controleur.2 <- view.2 <- view.1
 * controleur.3 <- view.3
 * controleur.3 <- controleur.2 [<- model.2 <- model.1]
 *
 * ------- ROUTER ----------------
 *
 * Le routeur
 * utilise les information de la rute -> http
 * pour déterminer le controleur
 * pour déterminer l'action
 *
 * -------- SUPER MODEL ----------
 * partage des des information configuration, paramettres, environnement
 * partage des des methodes acces BDD, valeurs des configuration ...
 *
 * SUPER CONTROLER <- SUPER MODEL
 * SUPER CONTROLER <- SUPER MODEL <- fichier conf
 * SUPER CONTROLER <- SUPER VIEW <- layaout
 * SUPER CONTROLER <- fichier router
 *
 * temporisation
 * ob_start
 * .....
 * $_V <- ob_get_content
 * ob_emptide
 * echo $_V
 */
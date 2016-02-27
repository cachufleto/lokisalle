<?php

function usersValiderInscription($jeton)
{
    $incription = usersSelectMembreJeton($jeton);

    if ($incription->num_rows == 1) {

        $membre = $incription->fetch_row();

        if(usersValideJeton($membre['id_membre'])){
            return $_trad['redirigeVerConnexion'];
        } else {
            return $_trad['uneErreurEstSurvenue'];
        }

    } else {
        return $_trad['pasDeJeton'];
    }
}

function usersValiderJeton($jeton)
{
    global $_trad;

    if (isset($jeton['jeton']) && !empty($jeton['jeton'])) {

        return usersValiderInscription($jeton['jeton']);

    } else {
        return $_trad['pasDeJeton'];
    }
}


function usersProfil($_id, $_valider, $_modifier)
{
    global $_trad, $_formulaire;

    // extraction des données SQL
    if (modCheck('_formulaire', $_id, 'membres')) {

        // traitement POST du formulaire
        $msg = ($_valider) ? postCheck('_formulaire', TRUE) : '';

        if ('OK' == $msg) {
            // on renvoi ver connexion
            $msg = $_trad['lesModificationOntEteEffectues'];
            // on évite d'afficher les info du mot de passe
            unset($_formulaire['mdp']);
            return formulaireAfficherInfo($_formulaire);

        } else {

            if (!empty($msg) || $_modifier) {

                $_formulaire['valide']['defaut'] = $_trad['defaut']['MiseAJ'];

                return formulaireAfficherMod($_formulaire);

            } elseif (!empty($_POST['valide']) && $_POST['valide'] == 'Annuler') {
                header('Location:?nav=users');
                exit();
            } else {

                unset($_formulaire['mdp']);
                return formulaireAfficherInfo($_formulaire);

            }

        }

    } else {

        return 'Erreur 500: ' . $_trad['erreur']['NULL'];

    }

}

function connexion(){

    global $_trad,  $_formulaire, $minLen;
    $msg = '';
    $erreur = false;
    $sql_Where = '';
    $control = true;

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;
        $obligatoire = (!empty($info['obligatoire']))? true : false ;

        if('valide' != $key)
            if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']))
            {

                $erreur = true;
                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] .$label.
                    ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                    ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

            } elseif (testObligatoire($info) && empty($valeur)){

                $erreur = true;
                $_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];

            } else {

                switch($key){
                    case 'mdp':
                        $crypte = $key;
                        break;

                    case 'rapel':
                        $control = ($valeur == 'ok')? true : false;
                        break;

                    case 'pseudo':

                        if (!testAlphaNumerique($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'];
                        }

                        break;

                    default:
                        if($obligatoire && !testLongeurChaine($valeur) )
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];
                        }
                        break;
                }
                // Construction de la requettes
                if($key != 'rapel' && $key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
            }
    }

    if($erreur) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {  // le pseudo n'existe pas en BD donc on peut lancer l'inscription

        $msg .= '<br />'.$_trad['erreur']['uneErreurEstSurvenue'];

    } else {

        // verifions si dans la requete lancee, si le pseudo s'il existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne creee donc un pseudo existe
        // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
        if($session = usersSelectConnexion())
        {

            if(isset($crypte)){
                $_formulaire[$crypte]['sql'] = $session[$crypte];
                if(hashDeCrypt($_formulaire[$crypte])){
                    // overture d'une session Membre
                    ouvrirSession($session, $control);
                    $msg = 'OK';
                    // on reinitialise les tentatives de connexion
                    unset($_SESSION['connexion']);
                }
            }


        } elseif($membre->num_rows == 0) {
            $msg .= '<br/ >'. $_trad['erreur']['erreurConnexion'];
            $_SESSION['connexion'] -= 1;

        } else {

            $msg .= '<br />'. $_trad['erreur']['inconueConnexion'];

        }


    }
    return $msg;
}

function usersChangerMotPasse(){

    global $_trad, $_formulaire, $minLen;
    $message = '';
    $sql_Where = '';

    foreach ($_formulaire as $key => $info){

        $label = $_trad[$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

        if('valide' != $key)
            if (isset($info['maxlength']) && (strlen($valeur) < $minLen  || strlen($valeur) > $info['maxlength']))
            {

                $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                    ': doit avoir un nombre de caracter compris entre ' . $minLen .
                    ' et ' . $info['maxlength'] . ' </p></div>';

            }else{
                switch($key){
                    case 'mdp':
                        $crypte = $key;
                        break;

                    case 'pseudo':
                        $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
                        if (!$verif_caractere  && !empty($valeur)) // $verif_caractere si c vrai sa donne un TRUE
                        {
                            $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                                ', Caractere acceptés: A à Z et 0 à 9 </p></div>';
                            // un message sans ecresser les messages existant avant. On place dans $msg des chaines de caracteres
                        }

                        break;

                }
                // Construction de la requettes
                if($key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
            }
    }

    if(empty($message)) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {
        // lançons une requete nommée membre dans la BD pour voir si un pseudo est bien saisi.
        $sql = "SELECT * FROM membre WHERE $sql_Where ";
        $membre = executeRequete ($sql); // la variable $pseudo existe grace a l'extract fait prealablemrent.

        // verifions si dans la requete lancee, si le pseudo existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne cree donc un pseudo existe
        if($membre->num_rows == 1) // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
        {
            $session = $membre->fetch_assoc();
            if(isset($crypte) && hashDeCrypt($_formulaire[$crypte])){
                $_formulaire[$crypte]['sql'] = $session[$crypte];
                if(hashDeCrypt($_formulaire[$crypte])){
                    // overture d'une session Membre
                    ouvrirSession($session);
                    $message = 'OK';
                }
            }
        } else {  // le pseudo n'existe pas en BD

            $message .= '<div class="bg-danger message"> <p>Une erreur est survenue ! </p>';

        }

    }

    return $message;
}


function usersConnexion()
{

    global $_trad, $_formulaire, $minLen;
    $message = '';
    $sql_Where = '';

    foreach ($_formulaire as $key => $info){

        $label = $_trad[$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

        if('valide' != $key)
            if (isset($info['maxlength']) && (strlen($valeur) < $minLen  || strlen($valeur) > $info['maxlength'])) // $msg.= doit etre declarer vide avant
            {

                $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                    ': doit avoir un nombre de caracter compris entre ' . $minLen .
                    ' et ' . $info['maxlength'] . ' </p></div>';

            }else{
                switch($key){
                    case 'mdp':
                        $crypte = $key;
                        break;

                    case 'pseudo':
                        $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
                        if (!$verif_caractere  && !empty($valeur)) // $verif_caractere si c vrai sa donne un TRUE
                        {
                            $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                                ', Caractere acceptés: A à Z et 0 à 9 </p></div>';
                            // un message sans ecresser les messages existant avant. On place dans $msg des chaines de caracteres
                        }

                        break;

                }
                // Construction de la requettes
                if($key != 'mdp') {
                    $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
                }
            }
    }

    if(empty($message)) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {
        // lançons une requete nommée membre dans la BD pour voir si un pseudo est bien saisi.
        $sql = "SELECT * FROM membre WHERE $sql_Where ";
        $membre = executeRequete($sql); // la variable $pseudo existe grace a l'extract fait prealablemrent.

        // verifions si dans la requete lancee, si le pseudo existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne cree donc un pseudo existe
        if($membre->num_rows == 1) // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
        {
            $session = $membre->fetch_assoc();
            if(isset($crypte) && hashDeCrypt($_formulaire[$crypte])){
                $_formulaire[$crypte]['sql'] = $session[$crypte];
                if(hashDeCrypt($_formulaire[$crypte])){
                    // overture d'une session Membre
                    ouvrirSession($session);
                    $message = 'OK';
                }
            }
        } else {  // le pseudo n'existe pas en BD

            $message .= '<div class="bg-danger message"> <p>Une erreur est survenue ! </p>';

        }

    }

    return $message;
}

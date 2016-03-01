<?php
include MODEL . 'Users.php';

/**
 * @param $jeton
 * @return mixed
 */
function usersValiderInscription()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    $msg = '';

    $jeton = isset($_GET['jeton'])? $_GET['jeton']  : '';

    $incription = (!empty($jeton))? usersSelectMembreJeton($jeton) : false;

    if (!empty($jeton) && $incription && $incription->num_rows == 1) {

        $membre = $incription->fetch_row();

       if (usersValideJeton($membre['id_membre'])){
            $msg = $_trad['redirigeVerConnexion'];
        } else {
            $msg = $_trad['uneErreurEstSurvenue'];
        }

    } else {
        $msg = $_trad['pasDeJeton'];
    }

    include TEMPLATE . 'validerinscription.html.php';
}

/**
 * @param $jeton
 * @return mixed
 */
function usersValiderJeton($jeton)
{
    $_trad = siteSelectTrad();

    if (isset($jeton['jeton']) && !empty($jeton['jeton'])) {

        return usersValiderInscription($jeton['jeton']);

    } else {
        return $_trad['pasDeJeton'];
    }
}

/**
 * @param $_id
 * @param $_valider
 * @param $_modifier
 * @return string
 */
function usersProfil()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    if(isSuperAdmin()){
        include ADM . 'param' . DIRECTORY_SEPARATOR . 'profil.param.php';
    } else {
        include PARAM . 'profil.param.php';
    }
    $msg = $form = '';
    // extraction des données SQL
    if (usersModCheck($_formulaire, $_id)) {
        // traitement POST du formulaire
        _debug($_valider, '$_valider', __FUNCTION__);
        $msg = ($_valider) ? postCheck($_formulaire, TRUE) : '';
        if ('OK' == $msg) {
            // on renvoi ver connexion
            $msg = $_trad['lesModificationOntEteEffectues'];
            // on évite d'afficher les info du mot de passe
            unset($_formulaire['mdp']);
            $form = formulaireAfficherInfo($_formulaire);
        } else {
            if (!empty($msg) || $_modifier) {
                $_formulaire['valide']['defaut'] = $_trad['defaut']['MiseAJ'];
                $form = formulaireAfficherInfo($_formulaire);
            } else if (!empty($_POST['valide']) && $_POST['valide'] == 'Annuler') {
                header('Location:?nav=users');
                exit();
            } else {
                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);
            }
        }
    } else {
        $msg = 'Erreur 500: ' . $_trad['erreur']['NULL'];
    }
    include TEMPLATE . 'profil.html.php';
}

/**
 * @return string
 */
function connexion(){


    global $__nav, $minLen;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    include PARAM . 'connexion.php';
    $erreur = false;
    $sql_Where = '';
    $control = true;
    $msg = usersConnexionValider($_formulaire);
    _debug($_formulaire, '$_formulaire', __FUNCTION__);
    foreach ($_formulaire as $key => $info){
        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;
        $obligatoire = (!empty($info['obligatoire']))? true : false ;
       if ('valide' != $key)
            if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']))
            {
                $erreur = true;
                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] .$label.
                    ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                    ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];
                _debug($info, '$info 1 '.$key, __FUNCTION__);
            } else if (testObligatoire($info) && empty($valeur)){
                $erreur = true;
                $_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];
                _debug($info, '$info 2 '.$key, __FUNCTION__);
            } else {
                _debug($info, '$info 3 '.$key, __FUNCTION__);
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
                       if ($obligatoire && !testLongeurChaine($valeur) )
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];
                        }
                        break;
                }
                // Construction de la requettes
               if ($key != 'rapel' && $key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
            }
    }
   if ($erreur){  // le pseudo n'existe pas en BD donc on peut lancer l'inscription
        $msg .= '<br />'.$_trad['erreur']['uneErreurEstSurvenue'];
    } else {
        // verifions si dans la requete lancee, si le pseudo s'il existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne creee donc un pseudo existe
        // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
       if ($session = usersSelectConnexion($sql_Where))
        {
            _debug($session, '$session', __FUNCTION__);
           if (isset($crypte)){
               $_formulaire[$crypte]['sql'] = $session[$crypte];
               _debug($_formulaire[$crypte], '$_formulaire[$crypte]', __FUNCTION__);
               if (hashDeCrypt($_formulaire[$crypte])){
                   // overture d'une session Membre
                   ouvrirSession($session, $control);
                   $msg = 'OK';
                   // on reinitialise les tentatives de connexion
                   unset($_SESSION['connexion']);
                   header('Location:index.php');
                   exit();
                } else {
                    $_SESSION['connexion'] -= 1;
                }
            }
        } else {
            $msg .= '<br/ >'. $_trad['erreur']['erreurConnexion'];
            $_SESSION['connexion'] -= 1;
        }
    }
    usersConnexionForm($msg);
}

/**
 * @return string
 */
function usersChangerMotPasse(){

    global $minLen;

    $_trad = siteSelectTrad();
    include PARAM . 'changermotpasse.param.php';
    $message = '';
    $sql_Where = '';

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

       if ('valide' != $key)
            if (isset($info['maxlength']) && (strlen($valeur) < $minLen  || strlen($valeur) > $info['maxlength']))
            {

                $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                    ': doit avoir un nombre de caracter compris entre ' . $minLen .
                    ' et ' . $info['maxlength'] . ' </p></div>';

            } else {

                switch($key){
                    case 'mdp':
                        $crypte = $key;
                        break;

                    case 'pseudo':
                        $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
                        if (!$verif_caractere  && !empty($valeur))
                        {
                            $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                                ', Caractere acceptés: A à Z et 0 à 9 </p></div>';
                            // un message sans ecresser les messages existant avant. On place dans $msg des chaines de caracteres
                        }
                        break;
                }
                // Construction de la requettes
               if ($key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
            }
    }

   if (empty($message)) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {
        if ($membre = usersSelectChangerMotPasse($sql_Where))
        {
           if (isset($crypte) && hashDeCrypt($_formulaire[$crypte])){
                $_formulaire[$crypte]['sql'] = $membre[$crypte];
               if (hashDeCrypt($_formulaire[$crypte])){
                    // overture d'une session Membre
                    ouvrirSession($membre);
                    $message = 'OK';
                }
            }
        } else {
            $message .= '<div class="bg-danger message"> <p>Une erreur est survenue ! </p>';
        }
    }

    return $message;
}

/**
 * @return string
 */
function usersConnexion()
{

    global $_formulaire, $minLen;

    $_trad = siteSelectTrad();

    $message = '';
    $sql_Where = '';

    foreach ($_formulaire as $key => $info){

        $label = $_trad[$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

       if ('valide' != $key)
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
               if ($key != 'mdp') {
                    $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
                }
            }
    }

   if (empty($message)) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {

       // verifions si dans la requete lancee, si le pseudo existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne cree donc un pseudo existe
       if ($session = usersSelectUser($sql_Where)) // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
        {
           if (isset($crypte) && hashDeCrypt($_formulaire[$crypte])){
                $_formulaire[$crypte]['sql'] = $session[$crypte];
               if (hashDeCrypt($_formulaire[$crypte])){
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

/**
 * @return bool|string
 */
function usersProfilModifier(){

    global $_formulaire, $minLen;

    $_trad = siteSelectTrad();

    // control d'intrusion du membre
   if ($_formulaire['id_membre']['sql'] != $_formulaire['id_membre']['defaut']){
        //_debug($_formulaire, 'SQL');
        return '<div class="alert">'.$_trad['erreur']['NULL'].'!!!!!</div>';
    }
    $msg = 	$erreur = false;
    $sql_set = '';
    // active le controle pour les champs telephone et gsm
    $controlTelephone = true;

    $id_membre = $_formulaire['id_membre']['sql'];

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

       if ('valide' != $key && 'id_membre' != $key){

            if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']) && !empty($valeur))
            {

                $erreur = true;
                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                    ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                    ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

            }

            if ('vide' != testObligatoire($info) && !testObligatoire($info) && empty($valeur)){

                $erreur = true;
                $_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];

            } else {

                switch($key){

                    case 'pseudo':
                    case 'id_membre':
                        // je ne fait riens
                        break;

                    case 'mdp':
                        $valeur = (!empty($valeur))? hashCrypt($valeur) : '';
                        break;

                    case 'email': // il est obligatoire

                        if (testFormatMail($valeur)) {

                            // si la requete retourne un enregisterme, c'est que 'email' est deja utilisé en BD.
                           if (usersTestMail($_formulaire['id_membre']['sql'], $valeur))
                            {
                                $erreur = true;
                                $msg .= '<br/>' . $_trad['erreur']['emailexistant'];
                            }

                        } else {

                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'];

                        }

                        break;

                    case 'sexe':

                       if (empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
                        }

                        break;

                    case 'nom': // est obligatoire
                    case 'prenom': // il est obligatoire
                       if (!testLongeurChaine($valeur) )
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['nonVide'];

                        } else if (!testAlphaNumerique($valeur)){

                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'] ;

                        }


                        break;

                    case 'telephone':
                    case 'gsm':

                       if (!empty($valeur)){

                            // un des deux doit être renseigné
                            $controlTelephone = false;
                            $valeur = str_replace(' ', '', $valeur);

                            if (isset($info['length']) && (strlen($valeur) < $info['length'] || strlen($valeur)> $info['length']+4))
                            {

                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                                    ': ' . $_trad['erreur']['doitContenir'] . $info['length'] . $_trad['erreur']['caracteres'];
                            }

                           if (testNumerique($valeur))
                            {
                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                    ': '.$_trad['erreur']['queDesChiffres'];
                            }

                        }

                        break;

                    case 'statut':

                       if (testADMunique($valeur, $id_membre)){
                            $erreur = true;
                            $msg .= '<br/>' . $_trad['numAdmInsufisant'];
                            $_formulaire['statut']['valide'] = 'ADM';
                        }

                        break;

                    default:
                       if (!empty($valeur) && !testLongeurChaine($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];

                        }

                }
            }
            // Construction de la requettes
            // le mot de passe doit être traité differement

            $sql_set .= ((!empty($sql_set) && !empty($valeur))? ", " : "") . ((!empty($valeur))? "$key = '$valeur'" : '');

        }
    }

    // control sur les numero de telephones
    // au moins un doit être sonseigné
   if ($controlTelephone) {
        $erreur = true;
        $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
    }

    // si une erreur c'est produite
   if ($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{

        if (!empty($sql_set)){
            usersUpdateProfil($sql_set, $_formulaire['id_membre']['sql']);
        } else {
            echo 'ATTENTION';
        }
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}

/**
 * @return bool|string
 */
function userInscritionFormulaire(){

    global $_formulaire, $minLen;

    $_trad = siteSelectTrad();

    $msg = 	$erreur = false;
    $sql_champs = $sql_Value = '';
    // active le controle pour les champs telephone et gsm
    $controlTelephone = true;

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

       if ('valide' != $key)
            if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength'])  && !empty($valeur))
            {

                $erreur = true;
                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                    ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                    ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

            } else if (testObligatoire($info) && empty($valeur)){

                $erreur = true;
                $_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];

            } else {

                switch($key){
                    case 'mdp': // il est obligatoire
                        $valeur = hashCrypt($valeur);
                        break;

                    case 'pseudo': // il est obligatoire

                        if (!testAlphaNumerique($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'];

                        } else {

                            // si la requete tourne un enregistreme, c'est que 'pseudo' est déjà utilisé en BDD.
                           if (usersTestPseudo($valeur))
                            {
                                $erreur = true;
                                $msg .= '<br/>' . $_trad['erreur']['pseudoIndisponble'];
                            }
                        }

                        break;

                    case 'email': // il est obligatoire

                        if (testFormatMail($valeur)) {

                            // si la requete retourne un enregisterme, c'est que 'email' est deja utilisé en BD.
                           if (userExistMail($valeur))
                            {
                                $erreur = true;
                                $msg .= '<br/>' . $_trad['erreur']['emailexistant'];
                            }

                        } else {

                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'];

                        }

                        break;

                    case 'sexe':

                       if (empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
                        }

                        break;

                    case 'nom': // est obligatoire
                    case 'prenom': // il est obligatoire
                       if (!testLongeurChaine($valeur) )
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['nonVide'];

                        } else if (!testAlphaNumerique($valeur)){

                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'] ;

                        }


                        break;

                    case 'telephone':
                    case 'gsm':

                       if (!empty($valeur)){

                            // un des deux doit être renseigné
                            $controlTelephone = false;
                            $valeur = str_replace(' ', '', $valeur);

                            if (isset($info['length']) && (strlen($valeur) < $info['length'] || strlen($valeur)> $info['length']+4))
                            {

                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                                    ': ' . $_trad['erreur']['doitContenir'] . $info['length'] . $_trad['erreur']['caracteres'];
                            }

                           if (testNumerique($valeur))
                            {
                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                    ': '.$_trad['erreur']['queDesChiffres'];

                            }

                        }

                        break;

                    default:
                       if (!empty($valeur) && !testLongeurChaine($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];

                        }

                }

                // Construction de la requettes
               if (!empty($valeur)){
                    $sql_champs .= ((!empty($sql_champs))? ", " : "") . $key;
                    $sql_Value .= ((!empty($sql_Value))? ", " : "") . (($key != 'cp')? "'$valeur'" : $valeur) ;
                }
            }
    }

    // control sur les numero de telephones
    // au moins un doit être sonseigné
   if ($controlTelephone) {
        $erreur = true;
        $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
    }

    // si une erreur c'est produite
   if ($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{

        if (usersInsert($sql_champs, $sql_Value)) {

            $email = $_formulaire['email']['valide'];
            $checkinscription = hashCrypt($email);

            if (usersInsertCheckInscription($email, $checkinscription)) {
                $msg = (envoiMail($checkinscription, $email)) ? "OK" : $msg;
            }
        }
    }

    return $msg;
}

/**
 * @return NULL
 */
function usersControlSession(&$_formulaire)
{
    $_trad = siteSelectTrad();
    // définition des cookis
    setcookie('Lokisalle[lang]', $_SESSION['lang'], time() + 360000);
    // Déconnexion de l'utilisateur par tentative d'intrusion
    // comportement de déconnexion sur le site
    if (isset($_GET['nav']) && $_GET['nav'] == 'out' && isset($_SESSION['user'])) {
        // destruction de la navigation
        $lng = $_SESSION['lang'];
        unset($_GET['nav']);
        // destruction de la session
        unset($_SESSION['user']);
        session_destroy();
        // on relance la session avec le choix de la langue
        session_start();
        $_SESSION['lang'] = $lng;
    } else {
        if (isset($_GET['nav']) && $_GET['nav'] == 'actif' && isset($_SESSION['user'])) {
            // control pour eviter d'afficher le formulaire de connexion
            // si l'utilisateur tente de le faire
            header('Location:index.php?nav=backoffice');
            exit();
        }
    }

}

/**
 *
 */
function usersConnexionValider(&$_formulaire)
{
    $_trad = siteSelectTrad();
    $msg = '';
    if (!isset($_SESSION['user'])){
        // recuperation du pseudo
        if (empty($_POST) && isset($_COOKIE['Lokisalle']['pseudo'])) {
            $_POST['valide'] = 'cookie';
            $_POST['mdp'] = '';
            $_POST['pseudo'] = $_COOKIE['Lokisalle']['pseudo'];
            $_POST['rapel'] = 'on';
        }
        // inclusion des sources requises pour executer la connexion
        include PARAM . 'connexion.php';
        // traitement du formulaire
        if (isset($_POST['valide'])){
            $msg = postValide($_formulaire, true);
        }
    }
    return $msg;
}

/**
 * @param $_formulaire
 * @return array
 */
function usersInscription()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];

    include PARAM . 'inscription.param.php';
    // traitement du formulaire
    $msg = postCheck($_formulaire);

    // affichage des messages d'erreur
    if ('OK' == $msg){
        // on renvoi ver connexion
        $msg = $_trad['validerInscription'];
        $form = '<a href="?nav=home">SUITE</a>';
    }else {
        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
    }

    include TEMPLATE . 'inscription.html.php';
}

/**
 * @return array
 */
function usersConnexionForm($msg = '')
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    $form = '';
    if (isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
        // affichage
        $msg = $_trad['erreur']['acces'];
    } else {
        // RECUPERATION du formulaire
        include PARAM . 'connexion.php';
        $form = formulaireAfficher($_formulaire);
    }
    include TEMPLATE . 'connexion.html.php';
}


/**
 * @param $nomFormulaire
 * @param $_id
 * @param $table
 * @return bool
 */

function usersModCheck(&$_formulaire, $_id)
{
    $form = $_formulaire;
     if (!($user = usersSelectModCheck($_id))){
        return false;
    }
    foreach($form as $key => $info){
        if ($key != 'valide' && key_exists ( $key , $user )){
            $_formulaire[$key]['valide'] = $user[$key];
            $_formulaire[$key]['sql'] = $user[$key];
        }
    }
    return true;
}

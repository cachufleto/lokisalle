<?php
function inscription()
{
    $nav = 'inscription';
    $_trad = setTrad();


    include PARAM . 'inscription.param.php';
    include FUNC . 'form.func.php';

    // traitement POST du formulaire
    $msg = $_trad['erreur']['inconueConnexion'];
    if (isset($_POST['valide']) && postCheck($_formulaire, true)) {
        $msg = ($_POST['valide'] == 'cookie') ? 'cookie' : inscriptionValider($_formulaire);
    }

    // affichage des messages d'erreur
    if('OK' == $msg){
        // on renvoi ver connection
        $form = '<a href="?index.php">SUITE</a>';

    }else{
        // RECUPERATION du formulaire
        $form = '
				<form action="#" method="POST">
				' . formulaireAfficher($_formulaire) . '
				</form>';
    }
    include VUE . 'users/inscription.php';
}

# Fonction inscriptionValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function inscriptionValider($_formulaire)
{

    global $minLen;

    $_trad = setTrad();

    $msg = 	$erreur = false;
    $sql_champs = $sql_Value = '';
    // active le controle pour les champs telephone et gsm
    $controlTelephone = true;

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

        if('valide' != $key)
            if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength'])  && !empty($valeur))
            {

                $erreur = true;
                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                    ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                    ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

            } elseif (testObligatoire($info) && empty($valeur)){

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

                            $sql = "SELECT pseudo FROM membres WHERE pseudo='$valeur'";
                            $membre = executeRequete ($sql);

                            // si la requete tourne un enregistreme, c'est que 'pseudo' est déjà utilisé en BDD.
                            if($membre->num_rows > 0)
                            {
                                $erreur = true;
                                $msg .= '<br/>' . $_trad['erreur']['pseudoIndisponble'];
                            }
                        }

                        break;

                    case 'email': // il est obligatoire

                        if (testFormatMail($valeur)) {

                            $sql = "SELECT email FROM membres WHERE email='$valeur'";
                            $membre = executeRequete($sql);

                            // si la requete retourne un enregisterme, c'est que 'email' est deja utilisé en BD.
                            if($membre->num_rows > 0)
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

                        if(empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
                        }

                        break;

                    case 'nom': // est obligatoire
                    case 'prenom': // il est obligatoire
                        if(!testLongeurChaine($valeur) )
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['nonVide'];

                        } elseif (!testAlphaNumerique($valeur)){

                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'] ;

                        }


                        break;

                    case 'telephone':
                    case 'gsm':

                        if(!empty($valeur)){

                            // un des deux doit être renseigné
                            $controlTelephone = false;
                            $valeur = str_replace(' ', '', $valeur);

                            if (isset($info['length']) && (strlen($valeur) < $info['length'] || strlen($valeur)> $info['length']+4))
                            {

                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                                    ': ' . $_trad['erreur']['doitContenir'] . $info['length'] . $_trad['erreur']['caracteres'];
                            }

                            if(testNumerique($valeur))
                            {
                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                    ': '.$_trad['erreur']['queDesChiffres'];

                            }

                        }

                        break;

                    default:
                        if(!empty($valeur) && !testLongeurChaine($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];

                        }

                }

                // Construction de la requettes
                if(!empty($valeur)){
                    $sql_champs .= ((!empty($sql_champs))? ", " : "") . $key;
                    $sql_Value .= ((!empty($sql_Value))? ", " : "") . (($key != 'cp')? "'$valeur'" : $valeur) ;
                }
            }
    }

    // control sur les numero de telephones
    // au moins un doit être sonseigné
    if($controlTelephone) {
        $erreur = true;
        $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
    }

    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{
        // insertion en BDD
        $sql = "INSERT INTO membres ($sql_champs) VALUES ($sql_Value) ";
        executeRequete ($sql);

        $email = $_formulaire['email']['valide'];
        $checkinscription = hashCrypt($email);

        $sql = "INSERT INTO checkinscription (id_membre, checkinscription)
			VALUES ( (SELECT id_membre FROM membres WHERE email = '$email'), '$checkinscription')";

        if(executeRequete($sql)){
            $msg = (envoiMail($checkinscription, $email))? "OK" : $msg;
        }

    }

    return $msg;
}

function actifUser($_formulaire)
{
    $_trad = setTrad();

    include FUNC . 'form.func.php';
    // recuperation du pseudo
    if (empty($_POST) && isset($_COOKIE['Lokisalle']['pseudo'])) {
        $_POST['valide'] = 'cookie';
        $_POST['mdp'] = '';
        $_POST['pseudo'] = $_COOKIE['Lokisalle']['pseudo'];
        $_POST['rapel'] = 'on';
    }

    // traitement du formulaire
    $msg = $_trad['erreur']['inconueConnexion'];
    if (isset($_POST['valide']) && postCheck($_formulaire)) {
        $msg = ($_POST['valide'] == 'cookie') ? 'cookie' : connectionValider($_formulaire);
    }

    $form = '';
    // affichage des messages d'erreur
    if ('OK' == $msg) {
        // l'utilisateur est automatiquement connécté
        // et re-dirigé ver l'accueil
        $_nav = 'index.php';
        if (utilisateurEstAdmin()){
            $_nav = '../BacOff/index.php?nav=backoffice';
        }
        header('Location:'.$_nav);
        exit();
    }
}

function connection()
{
    $nav = 'connection';
    $msg = '';
    $_trad = setTrad();

    include PARAM . 'connection.param.php';

    actifUser($_formulaire);

    /////////////////////////////////////
    if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
        // affichage
        $msg = $_trad['erreur']['acces'];

    } else {

        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
    }

    include VUE . 'users/connection.php';
}


# Fonction valideForm()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg
function valideForm()
{

    global $_formulaire, $minLen;

    $_trad = setTrad();

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

function connectionValider($_formulaire)
{

    global $minLen;
    $_trad = setTrad();

    $msg = '';
    $erreur = false;
    $sql_Where = '';
    $control = true;
    $message ='';

    if(!isset($_SESSION['connexion'])) $_SESSION['connexion'] = 3;

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

        // lançons une requete nommee membre dans la BD pour voir si un pseudo est bien saisi.
        $sql = "SELECT mdp, id_membre, pseudo, statut, nom, prenom FROM membres WHERE $sql_Where ";
        $membre = executeRequete ($sql); // la variable $pseudo existe grace a l'extract fait prealablemrent.
        // verifions si dans la requete lancee, si le pseudo s'il existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne creee donc un pseudo existe

        if($membre->num_rows === 1) // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
        {
            $session = $membre->fetch_assoc();
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

function changermotpasse()
{
    $_trad = setTrad();
    include PARAM . 'changermotpasse.param.php';

    include FUNC . 'form.func.php';

    $msg = usersChangerMotPasse();

    $form = formulaireAfficher($_formulaire);

    include VUE . 'users/changermotpasse.html.php';
}

/**
 * @return string
 */
function usersChangerMotPasse(){

    global $minLen;

    $_trad = setTrad();
    include PARAM . 'changermotpasse.param.php';
    $message = '';
    $sql_Where = '';

    postCheck($_formulaire);

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
 * @param $sql_Where
 * @return bool
 */
function usersSelectChangerMotPasse($sql_Where)
{
    $sql = "SELECT id_membre FROM membres WHERE $sql_Where;";
    return executeRequete($sql);
}

function profil()
{
    $nav = 'profil';
    $_trad = setTrad();
    include PARAM . 'profil.param.php';
    if(utilisateurEstAdmin()) {
        include PARAM . 'backOff_profil.param.php';
    }
    include FUNC . 'form.func.php';
    if (!isset($_SESSION['user'])) {
        header('Location:index.php');
        exit();
    }
    // extraction des données SQL
    $msg = '';
    if (modCheck($_formulaire, $_id, 'membres')) {
        // traitement POST du formulaire
        if ($_valider){
            $msg = $_trad['erreur']['inconueConnexion'];
            if(postCheck($_formulaire, TRUE)) {
                $msg = ($_POST['valide'] == 'cookie') ? 'cookie' : profilValider($_formulaire);
            }
        }
        if ('OK' == $msg) {
            // on renvoi ver connection
            $msg = $_trad['lesModificationOntEteEffectues'];
            // on évite d'afficher les info du mot de passe
            unset($_formulaire['mdp']);
            $form = formulaireAfficherInfo($_formulaire);
        } else {
            if (!empty($msg) || $_modifier) {
                $_formulaire['valide']['defaut'] = $_trad['defaut']['MiseAJ'];
                $form = formulaireAfficherMod($_formulaire);
            } elseif (
                !empty($_POST['valide']) &&
                $_POST['valide'] == $_trad['Out'] &&
                $_POST['origin'] != $_trad['defaut']['MiseAJ']
            ){
                header('Location:?nav=home');
                exit();
            } else {
                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);
            }
        }
    } else {
        $form = 'Erreur 500: ' . $_trad['erreur']['NULL'];
    }
    include VUE . 'users/profil.php';
}

# Fonction profilValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function profilValider($_formulaire)
{
    global $minLen;

    $_trad = setTrad();

    // control d'intrusion du membre
    if($_formulaire['id_membre']['sql'] != $_formulaire['id_membre']['defaut']){
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

        if('valide' != $key && 'id_membre' != $key){

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

                            $sql = "SELECT email FROM membres WHERE id_membre != ". $_formulaire['id_membre']['sql'] ." and email='$valeur'";
                            $membre = executeRequete($sql);

                            // si la requete retourne un enregisterme, c'est que 'email' est deja utilisé en BD.
                            if($membre->num_rows > 0)
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

                        if(empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
                        }

                        break;

                    case 'nom': // est obligatoire
                    case 'prenom': // il est obligatoire
                        if(!testLongeurChaine($valeur) )
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['nonVide'];

                        } elseif (!testAlphaNumerique($valeur)){

                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
                                '", ' . $_trad['erreur']['aphanumeriqueSansSpace'] ;

                        }


                        break;

                    case 'telephone':
                    case 'gsm':

                        if(!empty($valeur)){

                            // un des deux doit être renseigné
                            $controlTelephone = false;
                            $valeur = str_replace(' ', '', $valeur);

                            if (isset($info['length']) && (strlen($valeur) < $info['length'] || strlen($valeur)> $info['length']+4))
                            {

                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                                    ': ' . $_trad['erreur']['doitContenir'] . $info['length'] . $_trad['erreur']['caracteres'];
                            }

                            if(testNumerique($valeur))
                            {
                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                    ': '.$_trad['erreur']['queDesChiffres'];
                            }

                        }

                        break;

                    case 'statut':

                        if(testADMunique($valeur, $id_membre)){
                            $erreur = true;
                            $msg .= '<br/>' . $_trad['numAdmInsufisant'];
                            $_formulaire['statut']['valide'] = 'ADM';
                        }

                        break;

                    default:
                        if(!empty($valeur) && !testLongeurChaine($valeur))
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
    if($controlTelephone) {
        $erreur = true;
        $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
    }

    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{

        // mise à jour de la base des données
        $sql = 'UPDATE membres SET '.$sql_set.'  WHERE id_membre = '.$_formulaire['id_membre']['sql'];

        if (!empty($sql_set))
            executeRequete ($sql);
        else echo 'ATTENTION';
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}

function validerInscription(){
    header("refresh:5;url=index.php?nav=actif");

    $_trad = setTrad();
    echo $_trad['redirigeVerConnection'];
    /**
     * Created by PhpStorm.
     * User: Domoquick
     * Date: 22/02/2016
     * Time: 00:01
     */
    if (isset($_GET['jeton']) && !empty($_GET['jeton'])) {

        $sql = "SELECT * FROM membres, checkinscription WHERE membres.id_membre = checkinscription.id_membre
            AND checkinscription.checkinscription = '" . $_GET['jeton'] . "'";
        $incription = executeRequete($sql);

        if ($incription->fetch_row()) {

            $membre = $incription->fetch_row();

            $sql = "UPDATE membres SET active = 1 WHERE id_membre = " . $membre['id_membre'] . ";";
            $sql .= "DELETE FROM `checkinscription` WHERE id_membre = " . $membre['id_membre'] . ";";
            executeMultiRequete($sql);

        }
    }
    exit();
}

function backOff_users()
{
    $msg = '';
    $nav = 'users';
    $_trad = setTrad();
    include PARAM . 'profil.param.php';



    if (!utilisateurEstAdmin()) {
        header('Location:index.php');
        exit();
    }

    if (isset($_GET)) {
        if (!empty($_GET['delete']) && $_GET['delete'] != 1) {

            $sql = "UPDATE membres SET active = 0 WHERE id_membre = " . $_GET['delete'];
            if ($_GET['delete'] != $_SESSION['user']['id']) {
                executeRequete($sql);
            } else {
                $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];
            }

        } elseif (!empty($_GET['active'])) {

            $sql = "UPDATE membres SET active = 1 WHERE id_membre = " . $_GET['active'];
            executeRequete($sql);

        }

    }

    $table = '';

    $table .= "<tr><th>" . $_trad['champ']['pseudo'] . "</th><th>" . $_trad['champ']['nom'] . "</th><th>" . $_trad['champ']['prenom'] . "</th>
          <th>" . $_trad['champ']['email'] . "</th><th>" . $_trad['champ']['statut'] . "</th>";
    $table .= "<th>" . $_trad['champ']['active'];

    $table .= "</th></tr>";

    if (isSuperAdmin()) {

// selection de tout les users sauffe le super-ADMIN
        $sql = "SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.statut, m.active
        FROM membres m, checkinscription c
        WHERE m.active = 2 AND m.id_membre = c.id_membre
        ORDER BY m.nom, m.prenom";
        $membres = executeRequete($sql);

        if ($membres->num_rows > 0) {
            while ($data = $membres->fetch_assoc()) {

                $table .= "<tr><td>" . $data['pseudo'] . "</td><td>" . $data['nom'] . "</td><td>" . $data['prenom'] . "</td>
              <td><a href='mailto:" . $data['email'] . "'>" . $data['email'] . "</a></td><td>" . $_trad['value'][$data['statut']] . "</td>";
                $table .= "<td><a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>";
                $table .= "NEW :: <a href='" . LINKADMIN . '?nav=users&active=' . $data['id_membre'] . "'>" . $_trad['champ']['active'] . "</a>";

                $table .= "</td></tr>";
                # code...
            }
        }
    }

    $sql = "SELECT id_membre, pseudo, nom, prenom, email, statut, active
        FROM membres  WHERE " . (!isSuperAdmin() ? "id_membre != 1 AND active == 1 " : "active != 2") . "
        ORDER BY nom, prenom";
    $membres = executeRequete($sql);

    while ($data = $membres->fetch_assoc()) {

        $table .= "<tr><td>" . $data['pseudo'] . "</td><td>" . $data['nom'] . "</td><td>" . $data['prenom'] . "</td>
            <td><a href='mailto:" . $data['email'] . "'>" . $data['email'] . "</a></td><td>" . $_trad['value'][$data['statut']] . "</td>";
        $table .= "<td><a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>";
        $table .= ($data['active'] == 1) ? " <a href='" . LINKADMIN . '?nav=users&delete=' . $data['id_membre'] . "'>" . $_trad['delete'] . "</a>" :
            " <a href='" . LINKADMIN . '?nav=users&active=' . $data['id_membre'] . "'>" . $_trad['champ']['active'] . "</a>";

        $table .= "</td></tr>";
        # code...
    }

    include VUE . 'users/users.php';
}
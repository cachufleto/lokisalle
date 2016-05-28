<?php
include_once FUNC . 'users.func.php';
include_once MODEL . 'users.php';

function inscription()
{
    $_trad = setTrad();
    include PARAM . 'inscription.param.php';
    include FUNC . 'form.func.php';

    // traitement POST du formulaire
    $msg = '';
    if (isset($_POST['valide']) && postCheck($_formulaire, true)) {
        //$msg = ($_POST['valide'] == 'cookie') ? 'cookie' : inscriptionValider($_formulaire);
        $msg = inscriptionValider($_formulaire);
    }

    $form = ('OK' != $msg)? formulaireAfficher($_formulaire) : '';
    // affichage des messages d'erreur
    include VUE . 'users/inscription.tpl.php';
}

function connection()
{
    $nav = 'connection';
    $_trad = setTrad();

    include PARAM . 'connection.param.php';

    $msg = actifUser($_formulaire);

    /////////////////////////////////////
    if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
        // affichage
        $msg = $_trad['erreur']['acces'];

    } else {

        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
    }

    include VUE . 'users/connection.tpl.php';
}

function backOff_users()
{
    $msg = '';
    $nav = 'users';
    $_trad = setTrad();
    //include PARAM . 'profil.param.php';

    if (!utilisateurEstAdmin()) {
        header('Location:index.php');
        exit();
    }

    if (isset($_GET)) {
        if (!empty($_GET['delete']) && $_GET['delete'] != 1) {

            if ($_GET['delete'] != $_SESSION['user']['id']) {
                setUserActive($_GET['delete'], 0);
            } else {
                $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];
            }

        } elseif (!empty($_GET['active'])) {

            setUserActive($_GET['active']);

        } else if(!empty($_GET['delete']) && $_GET['delete'] == 1) {

            $msg = $_trad['numAdmInsufisant'];

        }

    }

    $table = array();
    $table['champs'] = array();
    $table['champs']['pseudo'] = $_trad['champ']['pseudo'];
    $table['champs']['nom'] = $_trad['champ']['nom'];
    $table['champs']['prenom'] = $_trad['champ']['prenom'];
    $table['champs']['email'] = $_trad['champ']['email'];
    $table['champs']['statut'] = $_trad['champ']['statut'];
    $table['champs']['active'] = $_trad['champ']['active'];

    $table['info'] = array();
    if (isSuperAdmin()) {

        $membres = usersMoinsAdmin();

        if ($membres->num_rows > 0) {
            while ($data = $membres->fetch_assoc()) {
                $table['info'][] = array(
                    $data['pseudo'],
                    $data['nom'],
                    $data['prenom'],
                    '<a href="mailto:'. $data['email'] . '">' . $data['email'] . '</a>',
                    $_trad['value'][$data['statut']],
                    "<a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>" . (($info == 2) ? "NEW" : ""),
                    (($data['active'] == 1) ?
                        " <a href='" . LINKADMIN . '?nav=users&delete=' . $data['id_membre'] . "'>" . $_trad['delete'] . "</a>" :
                        " <a href='" . LINKADMIN . '?nav=users&active=' . $data['id_membre'] . "'>" . $_trad['champ'][$data['active']] . "</a>")

                );
            }
        }
    }

    $membres = selectUsersActive();

    while ($data = $membres->fetch_assoc()) {
        $table['info'][] = array(
            $data['pseudo'],
            $data['nom'],
            $data['prenom'],
            '<a href="mailto:'. $data['email'] . '">' . $data['email'] . '</a>',
            $_trad['value'][$data['statut']],
            "<a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>",
            (($data['active'] == 1) ?
                " <a href='" . LINKADMIN . '?nav=users&delete=' . $data['id_membre'] . "'>" . $_trad['delete'] . "</a>" :
                " <a href='" . LINKADMIN . '?nav=users&active=' . $data['id_membre'] . "'>" . $_trad['champ']['active'] . "</a>")

        );
    }

    include VUE . 'users/users.tpl.php';
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
    if (modCheckMembres($_formulaire, $_id, 'membres')) {
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
                header('Location:?nav=users');
                exit();
            } else {
                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);
            }
        }
    } else {
        $form = 'Erreur 500: ' . $_trad['erreur']['NULL'];
    }
    include VUE . 'users/profil.tpl.php';
}

# Fonction profilValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function profilValider(&$_formulaire)
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

                            $membre = selectMailUser($_formulaire['id_membre']['sql'], $valeur);

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

        if (!empty($sql_set)){
            userUpdate($sql_set, $_formulaire['id_membre']['sql']);
        }
        else {
            $msg = $_trad['erreur']['inconueConnexion'];
        }
        // ouverture d'une session
        $msg = "OK";
    }

    return $msg;
}

function identifians()
{
    $_trad = setTrad();
    include PARAM . 'identifians.param.php';

    include FUNC . 'form.func.php';

    $msg = usersIdentifians();

    $form = formulaireAfficher($_formulaire);

    include VUE . 'users/identifians.tpl.php';
}

function changermotpasse()
{

    $_trad = setTrad();
    include FUNC . 'form.func.php';
    include PARAM . 'changermotpasse.param.php';

    $msg = '';
    if (isset($_POST['valide']) && postCheck($_formulaire, true)) {

    if(!($msg = changerMotPasseValider($_formulaire))) {

            $membre = getUserMail($_formulaire);
            $checkinscription = hashCrypt("CHANGE" . date('m:D:d:s:Y:M'));
            if(userChangerMDPInsert($checkinscription, $_formulaire)){
                $msg = envoiMailChangeMDP($checkinscription, $membre);
            } else {
                $msg = $_trad['erreur']['inconueConnexion'];
            }

        }
    }

    /////////////////////////////////////
    if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
        // affichage
        $msg = $_trad['erreur']['acces'];

    } else {

        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
    }

    include VUE . 'users/changermotpasse.tpl.php';
}

function validerInscription()
{
    if (isset($_GET['jeton']) && !empty($_GET['jeton'])) {
        userMDP($_GET['jeton']);
    }
}

function validerChangementMDP()
{
    if (isset($_GET['jeton']) && !empty($_GET['jeton'])) {
        userMDP($_GET['jeton']);
    }
}

function newMDP()
{
    if (isset($_GET['jeton']) && !empty($_GET['jeton'])) {
        userMDP($_GET['jeton']);
    }
}

function expiration()
{
    include VUE . 'users/expiration.tpl.php';
}


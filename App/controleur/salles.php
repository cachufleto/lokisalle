<?php
function salles()
{
    $nav = 'salles';
    $msg = '';
    $_trad = setTrad();

    if (isset($_GET)) {
        if (!empty($_GET['reserver'])) {
            $_SESSION['panier'][$_GET['reserver']] = true;
        } elseif (!empty($_GET['enlever'])) {
            $_SESSION['panier'][$_GET['enlever']] = false;
        }
    }

    // selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT id_salle, titre, capacite, categorie, photo, active
            FROM salles " . (!isSuperAdmin()? " WHERE active != 0 " : "") . "
            ORDER BY cp, titre";
    $membres = executeRequete($sql);
    $table = '';

    $table .= '<tr><th>' . $_trad['champ']['id_salle'] . '</th>
            <th>' . $_trad['champ']['titre'] . '</th>
            <th>' . $_trad['champ']['capacite'] . '</th>
            <th>' . $_trad['champ']['categorie'] . '</th>
            <th>' . $_trad['champ']['photo'] . '</th>';
    $table .= '<th>' . $_trad['select'];

    $table .= '</th></tr>';

    $position = 1;
    while ($data = $membres->fetch_assoc()) {

        $table .= '
      <tr><td>' . $data['id_salle'] . '</td><td>' . $data['titre'] . '</td><td>' . $data['capacite'] . '</td><td>' .
            $_trad['value'][$data['categorie']] . '</td><td><a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] .
            '&pos=' . $position . '" id="P-' . $position . '" ><img class="trombi" src="' . LINK . 'photo/' . $data['photo'] .
            '" ></a></td><td>';

        $table .= (isset($_SESSION['panier'][$data['id_salle']]) && $_SESSION['panier'][$data['id_salle']] === true) ?
            '<a href="' . LINK . '?nav=salles&enlever=' . $data['id_salle'] . '#P-' . $position . '" >' . $_trad['enlever'] . '</a>' :
            ' <a href="' . LINK . '?nav=salles&reserver=' . $data['id_salle'] . '#P-' . $position . '">' . $_trad['reserver'] . '</a>';

        $table .= '</td></tr>';
        $position++;
    }

    include VUE . 'salles/salles.php';
}

function reservation()
{
    if(isset($_SESSION['panier'])){

        foreach ($_SESSION["panier"] as $key => $value) {
            echo '<br>Salle id ', $key; # code...
        }

    }
}

function listeDistinc($champ, $table, $info)
{

    $_trad = setTrad();

    $sql = "SELECT DISTINCT $champ FROM $table ORDER BY $champ ASC";
    $result = executeRequete($sql);

    $balise = '<select class=" " id="' . $champ . '" name="' . $champ . '">';

    while($data = $result->fetch_assoc()){
        $value = $data[$champ];
        $libelle = (isset($_trad['value'][$value]))? $_trad['value'][$value] : $value;
        $check = selectCheck($info, $value);
        $balise .= '<option value="' .  $value . '" ' . $check . ' >'.$libelle.'</option>';
    }
    // Balise par defaut
    $balise .= '</select>';

    return $balise;
}

function recherche()
{
    include FUNC . 'form.func.php';
    $echoville = listeDistinc('ville', 'salles', array('valide'=>'tokyo'));
    $echocategorie = listeDistinc('categorie', 'salles', array('valide'=>'R'));
    $echocapacite = listeDistinc('capacite', 'salles', array('valide'=>'56'));

    include VUE . 'salles/recherche.php';
}

function ficheSalles()
{
    $nav = 'ficheSalles';
    $_trad = setTrad();
    $msg = '';

    include PARAM . 'ficheSalles.param.php';
    // on cherche la fiche dans la BDD
    // extraction des données SQL
    include FUNC . 'form.func.php';
    if (modCheck($_formulaire, $_id, 'salles')) {
        // traitement POST du formulaire
        $form = formulaireAfficherInfo($_formulaire);
        $form .= '<a href="?nav=salles#P-' . $position . '">' . $_trad['revenir'] . '</a>';
    } else {
        header('Location:index.php');
        exit();
    }

    include VUE . 'salles/ficheSalles.php';
}

function backOff_gestionSalles()
{
    $nav = 'gestionSalles';
    $msg = '';
    $_trad = setTrad();


    if(!utilisateurEstAdmin()){
        header('Location:index.php');
        exit();
    }

    if (isset($_GET)) {
        if (!empty($_GET['delete'])) {

            $sql = "UPDATE salles SET active = 0 WHERE id_salle = " . $_GET['delete'];
            if ($_GET['delete'] != $_SESSION['user']['id']) {
                executeRequete($sql);
            } else {
                $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];
            }

        } elseif (!empty($_GET['active'])) {
            $sql = "UPDATE salles SET active = 1 WHERE id_salle = " . $_GET['active'];
            executeRequete($sql);
        }

    }

// selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT id_salle, titre, capacite, categorie, photo, active FROM salles " . (!isSuperAdmin() ? " WHERE active != 0 " : "") . " ORDER BY cp, titre";
    $membres = executeRequete($sql);
    $table = '';

    $table .= "<tr><th>" . $_trad['champ']['id_salle'] . "</th><th>" . $_trad['champ']['titre'] . "</th><th>" . $_trad['champ']['capacite'] . "</th>
          <th>" . $_trad['champ']['categorie'] . "</th><th>" . $_trad['champ']['photo'] . "</th>";
    $table .= "<th>" . $_trad['champ']['active'];

    $table .= "</th></tr>";

    $position = 1;
    while ($data = $membres->fetch_assoc()) {

        $table .= '
    <tr><td>' . $data['id_salle'] . '</td><td>' . $data['titre'] . '</td><td>' . $data['capacite'] . '</td><td>' .
            $_trad['value'][$data['categorie']] . '</td><td><a href="' . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] .
            '&pos=' . $position . '" id="P-' . $position . '" ><img class="trombi" src="' . LINK . 'photo/' . $data['photo'] .
            '" ></a></td><td>';

        $table .= "<td><a href='" . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] . "'>" . $_trad['modifier'] . "</a>";
        $table .= ($data['active'] == 1) ? " <a href='" . LINKADMIN . '?nav=gestionSalles&delete=' . $data['id_salle'] . "'>" . $_trad['delete'] . "</a>" :
            " <a href='" . LINKADMIN . '?nav=gestionSalles&active=' . $data['id_salle'] . "'>" . $_trad['activer'] . "</a>";

        $table .= "</td></tr>";
        # code...
    }

    include VUE . 'salles/gestionSalles.php';
}

function backOff_editerSalles()
{
    // Variables
    $extension = '';
    $message = '';
    $nomImage = '';

    $nav = 'editerSalles';
    $_trad = setTrad();

    // traitement du formulaire
    include PARAM . 'backOff_editerSalles.param.php';
    include FUNC . 'form.func.php';

    $msg = '';

    if (postCheck($_formulaire)) {
        if(isset($_POST['valide'])){
            $msg = ($_POST['valide'] == 'cookie') ? 'cookie' : editerSallesValider($_formulaire);
        }
    }

// affichage des messages d'erreur
    if ('OK' == $msg) {
        // on renvoi ver connection
        header('Location:' . REPADMIN . 'index.php?nav=gestionSalles&pos='.$_formulaire['position']['value']);
        exit();
    } else {
        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
        include VUE . 'salles/editerSalles.php';
    }
}

# Fonction editerSallesValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function editerSallesValider($_formulaire)
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

        if('valide' != $key && 'photo' != $key)
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

                    case 'capacite':
                        break;
                    /*
                                        case 'photo':

                                        break;
                    */
                    case 'categorie':

                        if(empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
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
                    $sql_Value .= ((!empty($sql_Value))? ", " : "") . (($key != 'cp')? "'$valeur'" : "$valeur") ;
                }
            }
    }

    // control sur les numero de telephones
    // au moins un doit être sonseigné
    /*	if($controlTelephone) {
            $erreur = true;
            $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
        }
    */
    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{

        $erreur = (controlImageUpload('photo', $_formulaire['photo']))? true : $erreur;
//	  $_formulaire['photo']['message'] = isset($info['message'])? $info['message'] : '' ;
        $valeur = $_formulaire['photo']['valide'];

        if(!$erreur){
            $sql_champs .= ", photo";
            $sql_Value .= ",'$valeur'";
        }

    }

    if(!$erreur) {

        // insertion en BDD
        $sql = " INSERT INTO salles ($sql_champs) VALUES ($sql_Value) ";
//		echo $sql;
        executeRequete ($sql);
        // ouverture d'une session
        $msg = "";//"OK";

    }

    return $msg;
}

function backOff_ficheSalles()
{
    $nav = 'ficheSalles';
    $msg = '';
    $_trad = setTrad();

    include PARAM . 'backOff_ficheSalles.param.php';

    include FUNC . 'form.func.php';
    if (!isset($_SESSION['user'])) {
        header('Location:index.php');
        exit();
    }

    // extraction des données SQL
    $form = $msg = '';
    if (modCheck($_formulaire, $_id, 'salles')) {

        // traitement POST du formulaire
        if ($_valider){
            $msg = $_trad['erreur']['inconueConnexion'];
            if(postCheck($_formulaire, TRUE)) {
                $msg = ($_POST['valide'] == 'cookie') ? 'cookie' : ficheSallesValider($_formulaire);
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
                header('Location:?nav=gestionSalles&pos=P-' . $position . '');
                exit();

            } else {

                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);

            }

        }

    } else {

        $form = 'Error 500: ' . $_trad['erreur']['NULL'];

    }

    include VUE . 'salles/ficheSalles.php';
}

# Fonction ficheSallesValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function ficheSallesValider($_formulaire)
{

    global $minLen;
    $_trad = setTrad();

    $msg = 	$erreur = false;
    $sql_set = '';
    // active le controle pour les champs telephone et gsm
    $controlTelephone = true;

    $id_salle = $_formulaire['id_salle']['sql'];

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

        if('pos' != $key && 'valide' != $key && 'id_salle' != $key && 'photo' != $key && $info['valide'] != $info['sql'])
        {

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

                    case 'capacite':
                        break;
                    /*
                                        case 'photo':

                                          $erreur = (controlImageUpload($key, $info))? true : $erreur;
                                          $_formulaire[$key]['message'] = isset($info['message'])? $info['message'] : '' ;
                                          $valeur = $info['valide'];

                                        break;
                    */
                    case 'categorie':

                        if(empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
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
    /*
        if($controlTelephone) {
            $erreur = true;
            $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
        }
    */
    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }elseif(!empty($_FILES['photo'])){

        $nomImage  = $_formulaire['pays']['value'];
        $nomImage .= '_' . $_formulaire['ville']['value'];
        $nomImage .= '_' . $_formulaire['titre']['value'];
        $nomImage .= str_replace(' ', '_', $nomImage);

        $erreur = (controlImageUpload('photo', $_formulaire['photo'] , $nomImage))? true : $erreur;
//	  $_formulaire['photo']['message'] = isset($info['message'])? $info['message'] : '' ;
        $valeur = $_formulaire['photo']['valide'];

        if(!$erreur){
            $sql_champs .= ", photo";
            $sql_Value .= ",'$valeur'";
        }

    }elseif(empty($_FILES['photo'])){
        $_formulaire['photo']['valide'] = $_formulaire['photo']['sql'];
    }

    if(!$erreur) {

        // mise à jour de la base des données
        $sql = 'UPDATE salles SET '.$sql_set.'  WHERE id_salle = '.$id_salle;

        if (!empty($sql_set))
            executeRequete ($sql);
        else {
            header('Location:?nav=gestionSalles&pos=P-'.$position.'');
            exit();
        }
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}

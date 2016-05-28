<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 13/05/2016
 * Time: 23:54
 */
function ListeDistinc($champ, $table, $info)
{
    $_trad = setTrad();
    $balise = '<select class=" " id="' . $champ . '" name="' . $champ . '">';
    $result = selectListeDistinc($champ, $table);
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

# Fonction modCheck()
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function modCheckSalles(&$_formulaire, $_id)
{
    $form = $_formulaire;

    $sql = "SELECT * FROM salles WHERE id_salle = ". $_id . ( !isSuperAdmin()? " AND active != 0" : "" );

    $data = executeRequete($sql) or die ($sql);
    $user = $data->fetch_assoc();

    if($data->num_rows < 1) return false;

    foreach($form as $key => $info){
        if($key != 'valide' && key_exists ( $key , $user )){
            $_formulaire[$key]['valide'] = $user[$key];
            $_formulaire[$key]['sql'] = $user[$key];
        }
    }

    return true;
}

# Fonction modCheck()
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function getSalles($_formulaire, $_id)
{
    $sql = "SELECT * FROM salles WHERE id_salle = ". $_id . ( !isSuperAdmin()? " AND active != 0" : "" );

    $data = executeRequete($sql) or die ($sql);

    if($data->num_rows < 1) return false;
    $salle = $data->fetch_assoc();
    $fiche = array();
    foreach($salle as $key=>$info){
        $fiche[$key] = html_entity_decode($info);
    }
    return $fiche;
}

function remplaceAccents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

    return $str;
}

function nomImage($_formulaire){

    $pays = (!empty($_formulaire['pays']['value']))? $_formulaire['pays']['value'] : $_formulaire['pays']['sql'];
    $ville = (!empty($_formulaire['ville']['value']))? $_formulaire['ville']['value'] : $_formulaire['ville']['sql'];
    $titre = (!empty($_formulaire['titre']['value']))? $_formulaire['titre']['value'] : $_formulaire['titre']['sql'];
    $nomImage = str_replace(' ', '_', remplaceAccents($pays.'_'.$ville.'_'.$titre, $charset='utf-8'));

    return $nomImage;
}

# Fonction ficheSallesValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function ficheSallesValider(&$_formulaire)
{

    global $minLen;
    $_trad = setTrad();
    $msg = 	$erreur = false;
    $sql_set = '';
    // active le controle pour les champs telephone et gsm
    $controlTelephone = true;

    $id_salle = $_formulaire['id_salle']['sql'];
    $exclure = array('pos','valide','id_salle','photo');
    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;
        if(!in_array($key,$exclure))
        {
            if($info['valide'] != $info['sql'])
            {
                if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']) && !empty($valeur))
                {

                    $erreur = true;
                    $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                        ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                        ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

                }

                //if ('vide' != testObligatoire($info) && !testObligatoire($info) && empty($valeur)){
                else if (testObligatoire($info) && empty($valeur)){

                    $erreur = true;
                    $_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];

                } else {

                    switch($key){

                        case 'capacite':
                            break;

                        case 'photo':

                          $erreur = (controlImageUpload($key, $info))? true : $erreur;
                          $_formulaire[$key]['message'] = isset($info['message'])? $info['message'] : '' ;
                          $valeur = $info['valide'];

                        break;

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
                $sql_set .= ((!empty($sql_set) && !empty($valeur))? ", " : "") . ((!empty($valeur))? "$key = '$valeur'" : '');

            }
        }
    }

    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }elseif(!empty($_FILES['photo'])){

        $erreur = controlImageUpload('photo', $_formulaire['photo'], nomImage($_formulaire))? true : $erreur;
        $valeur = $_formulaire['photo']['valide'];

        if(!$erreur){
            $sql_set .= ((!empty($sql_set))? ", " : "")."photo = '$valeur'";
        }

    }elseif(empty($_FILES['photo'])){
        $_formulaire['photo']['valide'] = $_formulaire['photo']['sql'];
    }

    if(!$erreur) {

        // mise à jour de la base des données
        if (!empty($sql_set)){
            sallesUpdate($sql_set, $id_salle);
        }
        else {
            header('Location:?nav=salles&pos=P-' . ($position -1) . '');
        }
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}

# Fonction editerSallesValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function editerSallesValider(&$_formulaire)
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

    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{

        $nomImage  = trim($_formulaire['pays']['value']);
        $nomImage .= '_' . trim($_formulaire['ville']['value']);
        $nomImage .= '_' . trim($_formulaire['titre']['value']);
        $nomImage .= str_replace(' ', '_', $nomImage);

        $erreur = controlImageUpload('photo', $_formulaire['photo'], $nomImage)? true : $erreur;
        $valeur = $_formulaire['photo']['valide'];

        if(!$erreur){
            $sql_champs .= ", photo";
            $sql_Value .= ",'$valeur'";
        }

    }

    if(!$erreur) {

        $msg = setSalle($sql_champs, $sql_Value);//"OK";

    }

    return $msg;
}

function orderSallesValide()
{
    if(isset($_SESSION['orderSalles']['orderActive'])){
        if(isset($_POST['ord']) AND $_POST['ord'] == 'active'){
            $_SESSION['orderSalles']['orderActive'] = !($_SESSION['orderSalles']['orderActive']);
        }
    } else {
        $_SESSION['orderSalles']['orderActive'] = false;
    }

    return ($_SESSION['orderSalles']['orderActive'])? "active ASC, " : '';
}

function listeSalles()
{
    $_trad = setTrad();

    $table = array();
    $table['champs'] = array();
    $table['champs']['id_salle'] = '#';
    $table['champs']['titre'] = $_trad['champ']['titre'];
    $table['champs']['capacite'] = $_trad['champ']['capacite'];
    $table['champs']['categorie'] = $_trad['champ']['categorie'];
    $table['champs']['photo'] = $_trad['champ']['photo'];
    $table['champs']['select'] = $_trad['select'];

    $position = 1;

    $salles = selectSallesOrder(orderSalles());

    while ($data = $salles->fetch_assoc()) {
        $table['info'][] = array(
            $data['id_salle'],
            $data['titre'],
            $data['capacite'],
            $_trad['value'][$data['categorie']],
            '<a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . $position . '" id="P-' . $position . '" >
                <img class="trombi" src="' . imageExiste($data['photo']) . '" ></a>',
            (isset($_SESSION['panier'][$data['id_salle']]) && $_SESSION['panier'][$data['id_salle']] === true) ?
                '<a href="' . LINK . '?nav=salles&enlever=' . $data['id_salle'] . '#P-' . ($position - 1) . '" >' . $_trad['enlever'] . '</a>' :
                ' <a href="' . LINK . '?nav=salles&reserver=' . $data['id_salle'] . '#P-' . ($position - 1) . '">' . $_trad['reserver'] . '</a>'
        );
        $position++;
    }

    return $table;
}

function listeSallesBO()
{
    $_trad = setTrad();

    $table = array();

    $table['champs']['id_salle'] = $_trad['champ']['id_salle'];
    $table['champs']['titre'] = $_trad['champ']['titre'];
    $table['champs']['capacite'] = $_trad['champ']['capacite'];
    $table['champs']['categorie'] = $_trad['champ']['categorie'];
    $table['champs']['photo'] = $_trad['champ']['photo'];
    $table['champs']['active'] = $_trad['champ']['active'];


    $position = 1;
    $salles = selectSallesUsers(orderSallesValide() . orderSalles());

    while ($data = $salles->fetch_assoc()) {
        $table['info'][] = array(
            $data['id_salle'],
            $data['titre'],
            $data['capacite'],
            $_trad['value'][$data['categorie']],
                '<a href="' . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . $position . '" id="P-' . $position . '" >
            <img class="trombi" src="' . imageExiste($data['photo']) . '" ></a>',
            '<a href="' . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] . '#P-' . ($position - 1) . '" >' . $_trad['modifier'] . '</a>',
            ($data['active'] == 1) ? ' <a href="' . LINKADMIN . '?nav=salles&delete=' . $data['id_salle'] . '#P-' . ($position - 1) . '">' . $_trad['delete'] . '</a>' :
                ' <a href="' . LINKADMIN . '?nav=salles&active=' . $data['id_salle'] . '#P-' . ($position - 1) . '">' . $_trad['activer'] . '</a>'
        );
        $position++;
    }

    return $table;
}

function orderSalles()
{
    if(isset($_SESSION['orderSalles']['champ'])){
        if(isset($_POST['ord'])){
            $ord = $_POST['ord'];
            switch($ord){
                case 'id_salle':
                case 'categorie':
                case 'capacite':
                case 'titre':
                    $_SESSION['orderSalles']['order'] = ($_SESSION['orderSalles']['champ'] != $ord)?
                        "ASC" : (($_SESSION['orderSalles']['order'] == "ASC")? "DESC" : "ASC" );

                    $_SESSION['orderSalles']['champ'] = $ord;
                break;
            }
        }
    } else {
        $_SESSION['orderSalles'] = array();
        $_SESSION['orderSalles']['champ'] = 'categorie';
        $_SESSION['orderSalles']['order'] = 'ASC';
    }

    return $_SESSION['orderSalles']['champ'] . " " . $_SESSION['orderSalles']['order'];
}
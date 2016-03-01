<?php
include MODEL . 'Salles.php';
/**
 * @return string
 */
function sallesListe()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];

    /**
     * Control des variables externes
     */
    if (isset($_GET)) {
        if (!empty($_GET['reserver'])) {
            $_SESSION['panier'][$_GET['reserver']] = true;
        } else if (!empty($_GET['enlever'])) {
            $_SESSION['panier'][$_GET['enlever']] = false;
        }
    }

// selection de tout les salles
    $infoBDD = sallesSelectAll();
    $msg = $table = '';

    $position = 1;

    /**
     * Traitement de la BDD salles
     */
    while ($data = $infoBDD->fetch_assoc()) {

        $table .= "\t" . '<tr>' . "\r\n";
        $table .= "\t\t" . '<td>' . $data['id_salle'] . '</td>' . "\r\n";
        $table .= "\t\t" . '<td>' . $data['titre'] . '</td>' . "\r\n";
        $table .= "\t\t" . '<td>' . $data['capacite'] . '</td>' . "\r\n";
        $table .= "\t\t" . '<td>' . $_trad['value'][$data['categorie']] . '</td>' . "\r\n";
        $table .= "\t\t" . '<td><a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] .
            '&pos=' . $position . '" id="P-' . $position . '" ><img class="trombi" src="' . LINK . 'photo/' . $data['photo'] .
            '" ></a></td>' . "\r\n";
        $table .= "\t\t" . '<td>' . "\r\n";

        $table .= (isset($_SESSION['panier'][$data['id_salle']]) && $_SESSION['panier'][$data['id_salle']] === true) ?
            '<a href="' . LINK . '?nav=salles&enlever=' . $data['id_salle'] . '#P-' . $position . '" >' . $_trad['enlever'] . '</a>' :
            ' <a href="' . LINK . '?nav=salles&reserver=' . $data['id_salle'] . '#P-' . $position . '">' . $_trad['reserver'] . '</a>';

        $table .= "\t\t" . '</td>' . "\r\n";
        $table .= "\t" . '</tr>' . "\r\n";
        $position++;
    }
    include TEMPLATE . 'salles.html.php';
}

/**
 * @return bool|string
 */
function sallesFiche()
{

    global $_formulaire, $minLen, $position;

    $_trad = siteSelectTrad();

    $msg = 	$erreur = false;
    $sql_champs = $sql_Value = $sql_set = '';

    // active le controle pour les champs telephone et gsm
    $id_salle = $_formulaire['id_salle']['sql'];

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

       if ('pos' != $key && 'valide' != $key && 'id_salle' != $key && 'photo' != $key && $info['valide'] != $info['sql'])
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

                       if (empty($valeur))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
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

    // si une erreur c'est produite
   if ($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    } else if (!empty($_FILES['photo'])){

        $erreur = (controlImageUpload('photo', $_formulaire['photo']))? true : $erreur;
//	  $_formulaire['photo']['message'] = isset($info['message'])? $info['message'] : '' ;
        $valeur = $_formulaire['photo']['valide'];

       if (!$erreur){
            $sql_champs .= ", photo";
            $sql_Value .= ",'$valeur'";
        }

    } else if (empty($_FILES['photo'])){
        $_formulaire['photo']['valide'] = $_formulaire['photo']['sql'];
    }

   if (!$erreur) {

        if (!sallesUpdate($sql_set, $id_salle)){
            header('Location:?nav=gestionSalles&pos=P-'.$position.'');
            exit();
        }
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}

/**
 * @return bool|string
 */
function sallesFicheInsert()
{
    global $minLen;
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    include PARAM . REPADMIN . 'editerSalles.param.php';
    $msg = 	$erreur = false;
    $sql_champs = $sql_Value = '';
    // active le controle pour les champs telephone et gsm
    foreach ($_formulaire as $key => $info){
        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;
       if ('valide' != $key && 'photo' != $key){
           if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength'])  && !empty($valeur)){
               $erreur = true;
               $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
                   ': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
                   ' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];
           } else if (testObligatoire($info) && empty($valeur)){
               $erreur = true;
               $_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];
           } else {
               switch($key){
                   case 'capacite':
                       break;
                   case 'categorie':
                       if (empty($valeur))
                       {
                           $erreur = true;
                           $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                               ': '.$_trad['erreur']['vousDevezChoisireUneOption'];
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
                   $sql_Value .= ((!empty($sql_Value))? ", " : "") . (($key != 'cp')? "'$valeur'" : "$valeur") ;
               }
           }
       }
    }
    // si une erreur c'est produite
   if ($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';
    }else{
        $erreur = (controlImageUpload('photo', $_formulaire['photo']))? true : $erreur;
        $valeur = $_formulaire['photo']['valide'];
       if (!$erreur){
            $sql_champs .= ", photo";
            $sql_Value .= ",'$valeur'";
        }
   }
    if (!$erreur) {
        $msg = (sallesInsert($sql_champs, $sql_Value))? "" : 'ERROR';
   }
    include TEMPLATE . 'editersalles.html.php';
}

/**
 * @param $_formulaire
 * @return array
 */
function sallesFicheModifier($_formulaire)
{
    if (!isset($_SESSION['user'])){
        header('Location:index.php');
        exit();
    }
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    include PARAM . REPADMIN . 'ficheSalles.param.php';
    // extraction des données SQL
    if (sallesModCheck($_formulaire, $_id) ){
        // traitement POST du formulaire
        $msg = ($_valider)? postCheck($_formulaire, TRUE) : '';
        if ('OK' == $msg){
            // on renvoi ver connexion
            $msg = $_trad['lesModificationOntEteEffectues'];
            // on évite d'afficher les info du mot de passe
            unset($_formulaire['mdp']);
            $form = formulaireAfficherInfo($_formulaire);
        } else {
            if (!empty($msg) || $_modifier) {
                $_formulaire['valide']['defaut'] = $_trad['defaut']['MiseAJ'];
                $form = formulaireAfficherMod($_formulaire);
            } if (!empty($_POST['valide']) && $_POST['valide'] == 'Annuler'){
                header('Location:?nav=gestionSalles&pos=P-'.$position.'');
                exit();
            } else {
                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);
            }
        }
    } else {
        $form = 'Erreur 500: '.$_trad['erreur']['NULL'];
    }
    include TEMPLATE . 'formulaire.html.php';
}

/**
 * @return array
 */
function sallesEditer()
{
    include PARAM . 'editSalles.param.php';
    // traitement du formulaire
    $msg = postCheck($_formulaire);
    // affichage des messages d'erreur
    if ('OK' == $msg){
        // on renvoi ver connexion
        //header('Location:index.php?nav=actif&qui='.$_formulaire['pseudo']['valide'].
        //	'&mp='.$_formulaire['mdp']['valide'].'');
        return ['msg'=>'', 'form' => ''];
    }
    // RECUPERATION du formulaire
    $form = '
			<form action="#" method="POST" enctype="multipart/form-data">
			' . formulaireAfficher($_formulaire) . '
			</form>';
    return ['msg'=>$msg, 'form' => $form];
}

/**
 *
 */
function sallesHome()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    $salles = sallesHomeDeniersOffres();
    $dernieresOffres = '';
    while ($salle = $salles->fetch_assoc()) {
        $dernieresOffres .= dernieresOffres($salle);
    }
    include TEMPLATE . 'home.html.php';
}

/**
 *
 */
function sallesPanier()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    $info = '';
    if (isset($_SESSION['panier'])){
        foreach ($_SESSION["panier"] as $key => $value) {
            $info .= '<br>Salle id ' . $key;
        }
    }
    include TEMPLATE . 'reservation.html.php';
}

/**
 *
 */
function sallesRecherche(){
    // afficher un formulaire de recherche
    // menu déroulant pour la ville
    // date-piqquer pour la date d'entreé
    // date-pikquer pour la date de sortie
    // date de sortie doit étre égale ou supperieur à la date d'entrée
    // formulaire inscrit dans la liste de sales disponibles
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    $villes = listeDistinc('ville', 'salles', array('valide'=>'tokyo'));
    $categories = listeDistinc('categorie', 'salles', array('valide'=>'R'));
    $capacite = listeDistinc('capacite', 'salles', array('valide'=>'56'));
    $resultat = 'RESULTAT';
    include TEMPLATE . 'recherche.html.php';
}

/**
 * @param $nomFormulaire
 * @param $_id
 * @param $table
 * @return bool
 */
function sallesModCheck(&$_formulaire, $_id)
{
    $formulaire = $_formulaire;
    $message = '';
    if (!($salle = sallesSelectModCheck($_id))){
        return false;
    }
    foreach($formulaire as $key => $info){
        if ($key != 'valide' && key_exists ( $key , $salle )){
            $_formulaire[$key]['valide'] = $salle[$key];
            $_formulaire[$key]['sql'] = $salle[$key];
        }
    }
    return true;
}

/**
 *
 */
function sallesFicheEditer(){
    // on cherche la fiche dans la BDD
    // extraction des données SQL
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    include PARAM . 'ficheSalles.param.php';
    if (sallesModCheck($_formulaire, $_id) ){
        $position = (isset($_GET['pos']))? $_GET['pos'] : 1;
        // traitement POST du formulaire
        $form = formulaireAfficherInfo($_formulaire);
        $form .=  "<a href=\"?nav=salles#P-$position\">" . $_trad['revenir'] . "</a>";
        include TEMPLATE . 'ficheSalles.html.php';
    } else {
        header('Location:index.php');
        exit();
    }
}
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
function modCheckProduits(&$_formulaire, $_id)
{

    $sql = "SELECT id_plagehoraire FROM produits WHERE id_salle = ". $_id ;

    $data = executeRequete($sql) or die ($sql);
    if($data->num_rows < 1) return false;

    while ($produit = $data->fetch_assoc()){
        $_formulaire['plagehoraire']['valide'][] = $produit['id_plagehoraire'];
        $_formulaire['plagehoraire']['sql'][] = $produit['id_plagehoraire'];
    }

    return true;
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
function getSalles($_id)
{
    $data = selectSalleId($_id) or die ($sql);
    if($data->num_rows < 1) {
        return false;
    }
    $salle = $data->fetch_assoc();
    $fiche = array();
    foreach($salle as $key=>$info){
        $fiche[$key] = html_entity_decode($info);
    }
    $fiche['produits'] = listeProduitsReservation($fiche);

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


function produitsValider(&$_formulaire)
{
    global $minLen;
    $_trad = setTrad();

    $msg = '';
    $erreur = false;
    $sql_Where = '';
    $control = true;
    $message ='';

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;
        if(testObligatoire($info) && empty($valeur)) {
            $erreur = true;
            $_formulaire[$key]['message'] = inputMessage(
                $_formulaire[$key], $label . $_trad['erreur']['obligatoire']);
        }

        switch ($key){
            case 'plagehoraire':
                break;
        }
    }

    if($erreur) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {  // le pseudo n'existe pas en BD donc on peut lancer l'inscription

        $msg .= '<br />'.$_trad['erreur']['uneErreurEstSurvenue'];

    }

    return $msg;
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
                        case 'cap_min':
                        case 'tranche':
                            if(empty($valeur = intval($valeur)))
                            {
                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                    ': '.$_trad['erreur']['minimumNumerique'];
                            }
                            $_formulaire[$key]['valide'] = $valeur;
                            break;

                        case 'prix_personne':
                            if(($valeur = doubleval(str_replace(',', '.', $valeur))) < PRIX)
                            {
                                $erreur = true;
                                $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                    ': '.$_trad['erreur']['prixPersonne'];
                                $valeur = PRIX;
                            }
                            $_formulaire[$key]['valide'] = $valeur;
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
                            $long = (isset($info['maxlength']))? $info['maxlength'] : 250;
                            if(!empty($valeur) && !testLongeurChaine($valeur, $long))
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

    if(!$erreur && intval($_formulaire['capacite']['valide']*.9) < $_formulaire['cap_min']['valide']){

        $erreur = true;
        $_formulaire['cap_min']['message'] = $_trad['erreur']['surLe'] . $_trad['champ']['cap_min'] .
            ': '.$_trad['erreur']['capaciteMinSuperieur'];
        $_formulaire['cap_min']['valide'] = intval($_formulaire['capacite']['valide']*.9);
    }

    if(controlTranche($_formulaire)){
        $erreur = true;
        $_formulaire['tranche']['message'] = $_trad['erreur']['surLe'] . $_trad['champ']['tranche'] .
            ': '.$_trad['erreur']['repartitionTranche'];
    }

    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }elseif(!empty($_FILES['photo']) && $_FILES['photo']['error'] != 4){

        $erreur = controlImageUpload('photo', $_formulaire['photo'], nomImage($_formulaire))? true : $erreur;
        $valeur = $_formulaire['photo']['valide'];

        if(!$erreur){
            $sql_set .= ((!empty($sql_set))? ", " : "")."photo = '$valeur'";
        }

    }elseif(!empty($_FILES['photo']) && $_FILES['photo']['error'] == 4){
        $_formulaire['photo']['valide'] = $_formulaire['photo']['sql'];
    }

    if(!$erreur) {

        // mise à jour de la base des données
        if (!empty($sql_set)){
            sallesUpdate($sql_set, $id_salle);
        }
        else {
            header('Location:' . LINK . '?nav=salles&pos=P-' . ($position -1) . '');
        }
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}

function controlTranche(&$_formulaire)
{
    $max = $_formulaire['capacite']['valide'];
    $min = $_formulaire['cap_min']['valide'];
    $tranche = $_formulaire['tranche']['valide'];

    if($max == $min AND $tranche != 1) {
        $_formulaire['tranche']['valide'] = 1;
        return true;
    }

    if( ($max - $min) < ($max*0.1) AND $tranche != 1) {
        $_formulaire['tranche']['valide'] = 1;
        return true;
    }

    if( ($max - $min) < ($max*0.2) AND $tranche > 2) {
        $_formulaire['tranche']['valide'] = 2;
        return true;
    }

    if( ($max - $min) < ($max*0.35) AND $tranche > 3) {
        $_formulaire['tranche']['valide'] = 3;
        return true;
    }

    if($tranche > 4) {
        $_formulaire['tranche']['valide'] = 4;
        return true;
    }

    return false;

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
        _debug($info, $key);
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
                    case 'cap_min':
                    case 'tranche':
                        if(empty($valeur = intval($valeur)))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumNumerique'];
                        }

                        $_formulaire[$key]['valide'] = $valeur;
                        break;

                    case 'prix_personne':
                        if(($valeur = doubleval(str_replace(',', '.', $valeur))) < PRIX)
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['prixPersonne'];
                            $valeur = PRIX;
                        }
                        $_formulaire[$key]['valide'] = $valeur;
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
                        $long = (isset($info['maxlength']))? $info['maxlength'] : 250;
                        if(!empty($valeur) && !testLongeurChaine($valeur, $long))
                        {
                            $erreur = true;
                            $_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
                                ': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];

                        }

                }

                // Construction de la requettes
                if(!empty($valeur)){
                    $sql_champs .= ((!empty($sql_champs))? ", " : "") . $key;
                    $sql_Value .= ((!empty($sql_Value))? ", " : "") .
                                  (($info['content'] != 'int' AND $info['content'] != 'float')? "'$valeur'" : "$valeur") ;
                }
            }
    }

    // si une erreur c'est produite
    if($erreur)
    {
        $msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

    }else{

        $nomImage  = trim($_formulaire['pays']['valide']);
        $nomImage .= '_' . trim($_formulaire['ville']['valide']);
        $nomImage .= '_' . trim($_formulaire['titre']['valide']);
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

function selectSallesReservations()
{
    $liste = '';

    if(isset($_SESSION['panier']) && !empty($_SESSION['panier'])){

        foreach ($_SESSION["panier"] as $key => $value) {
            $liste .= ((empty($liste))? '':',') . $key;
        }

        $liste =  " id_salle in ($liste) ";
    } else {

        $liste =  " id_salle = -1 ";
    }

    return listeSalles($liste);
}

function listeSalles($reservation = false)
{
    $_trad = setTrad();

    $table = array();
    $position = 1;
    $nav = ($reservation)? 'reservation' : 'salles';
    $salles = selectSallesOrder(orderSalles(), $reservation);

    while ($data = $salles->fetch_assoc()) {
        $table['info'][] = array(
            'ref'=>$data['id_salle'],
            'nom'=>html_entity_decode($data['titre']),
            'capacite'=>$data['capacite'],
            'categorie'=>$_trad['value'][$data['categorie']],
            'photo'=>'<a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . $position . '" " >
                <img class="trombi" src="' . imageExiste($data['photo']) . '" ></a>',
            'reservation'=>(isset($_SESSION['panier'][$data['id_salle']])) ?
                '<a href="' . LINK . '?nav=' . $nav . '&enlever=' . $data['id_salle'] . '&pos=' . $position . '" >' . $_trad['enlever'] . '</a>' :
                ' <a href="' . LINK . '?nav=' . $nav . '&reserver=' . $data['id_salle'] . '&pos=' . $position . '">' . $_trad['reserver'] . '</a>',
            'total' => (isset($_SESSION['panier'][$data['id_salle']]['total'])? "[ Total:" . number_format($_SESSION['panier'][$data['id_salle']]['total'], 2) . "€ ]" : ""),
            'position'=>'<a id="P-' . $position . '"></a>'

        );
        $position++;
    }

    return $table;
}

function listeSallesBO()
{
    $_trad = setTrad();

    $table = array();

    $table['champs']['id_salle'] = 'REF';
    $table['champs']['titre'] = $_trad['champ']['titre'];
    $table['champs']['capacite'] = $_trad['champ']['capacite'];
    $table['champs']['categorie'] = $_trad['champ']['categorie'];
    $table['champs']['produit'] = $_trad['champ']['produit'];
    $table['champs']['photo'] = $_trad['champ']['photo'];
    $table['champs']['active'] = $_trad['champ']['active'];

    $position = 1;
    $salles = selectSallesUsers(orderSallesValide() . orderSalles());

    while ($data = $salles->fetch_assoc()) {
        $table['info'][] = array(
            $data['id_salle'],
            html_entity_decode($data['titre']),
            "MIN. {$data['cap_min']}, MAX. : {$data['capacite']}<br> prix ref: {$data['prix_personne']}",
            $_trad['value'][$data['categorie']],
            listeProduits($data),
                '<a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . $position . '" id="P-' . $position . '" >
            <img class="trombi" src="' . imageExiste($data['photo']) . '" ></a>',
            '<a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . ($position - 1) . '" ><img width="25px" src="img/modifier.png"></a>',
            ($data['active'] == 1) ? ' <a href="' . LINK . '?nav=salles&delete=' . $data['id_salle'] . '#P-' . ($position - 1) . '"><img width="25px" src="img/activerOk.png"></a>' :
                ' <a href="' . LINK . '?nav=salles&active=' . $data['id_salle'] . '#P-' . ($position - 1) . '"><img width="25px" src="img/activerKo.png"></a>'
        );
        $position++;
    }

    return $table;
}

function listeCapacites($data, $info){

    $_trad = setTrad();
    $_prixPlage = setPrixPlage();
    $_tranches = setPrixTranches();

    $prixSalle = [];
    $max = $data['capacite'];
    $min = $data['cap_min'];
    $dif = $max - $min;
    $it = intval(str_replace('T', '', $data['tranche']));
    $delta = intval($dif/$it);

    for ($i=1; $i<=$it; $i++){
        $per = ($i != $it)? $min + $i*$delta : $max;
        $prix = $data['prix_personne'] * $_prixPlage[$info['id_plagehoraire']]['taux'] * $_tranches[$data['tranche']][$i];
        $prixSalle[$i]['id'] = $info['id'];
        $prixSalle[$i]['num'] = $per;
        $prixSalle[$i]['valeur'] = $prix *$per;
        $prixSalle[$i]['prix'] = number_format ($prix *$per , 2);
        $prixSalle[$i]['prix_personne'] = number_format ($prix , 2);
        $prixSalle[$i]['libelle'] = utf8_encode($_prixPlage[$info['id_plagehoraire']]['libelle']);
        $prixSalle[$i]['description'] = utf8_encode($info['description']);
    }

    return $prixSalle;
}

function listeProduits(array $data)
{
    $prix_salle = $ref ='';
    $affiche = [];
    if($prix = selectProduitsSalle($data['id_salle'])){
        while($info = $prix->fetch_assoc() ){
            $prixSalle= listeCapacites($data, $info);
            $ref = '';
            foreach($prixSalle as $key =>$produit){
                $ref .=  "<td>" . $produit['prix'] . "€</td>";
                $affiche[$key] = $produit['num'];
            }
            $prix_salle .= "<tr><td class='tableauprix'>{$produit['libelle']}</td>$ref</tr>";
        }
    }
    $ref = '';
    foreach($affiche as $col){
        $ref .=  "<td class='tableauprix'>$col pers.</td>";
    }
    $prix_salle = "<tr><td class='tableauprix' width='90'>Max. </td>$ref</tr>" . $prix_salle;
    $_trad['produitNonDispoble'] = "Produits non disponibles";
    return (empty($affiche))? $_trad['produitNonDispoble'] : "<table border='1' cellspacing='1' BGCOLOR='#ccc'>$prix_salle</table>";
}

function listeProduitsReservation(array $data)
{
    $prix_salle = $ref = $_listeReservation = '';
    $affiche = [];
    $i = $_total = 0;

    if($prix = selectProduitsSalle($data['id_salle'])){
        while($info = $prix->fetch_assoc() ){
            $prixSalle= listeCapacites($data, $info);
            $ref = '';
            $i++;

            $reservation = isset($_SESSION['panier'][$data['id_salle']])? $_SESSION['panier'][$data['id_salle']] : [];
            foreach($prixSalle as $key =>$produit){
                $checked = '';
                if(isset($reservation[$i]) && $reservation[$i] == $key){
                    $checked = 'checked';
                    $_listeReservation .= "<div class='tronche'>{$produit['libelle']} :</div>
                                            <div class='personne'>{$produit['num']} pers.</div>
                                            <div class='prix'>{$produit['prix']}€</div>";
                    $_total = $_total +  $produit['valeur'];
                }

                $ref .=  "<td>" . $produit['prix'] . "€ <input type='radio' name='prix[".$i."]' value='$key' $checked></checkbox></td>";
                $affiche[$key] = $produit['num'];
            }
            $prix_salle .= "<tr><td class='tableauprix'>{$produit['libelle']}</td>$ref</tr>";

        }
    }
    $ref = '';
    foreach($affiche as $col){
        $ref .=  "<td class='tableauprix'>$col pers.</td>";
    }
    $prix_salle = "<tr><td class='tableauprix' width='90'>Max. </td>$ref</tr>" . $prix_salle;
    $_trad['produitNonDispoble'] = "Produits non disponibles";

    $tableau = "<table border='1' cellspacing='1' BGCOLOR='#ccc'>$prix_salle</table>";
    $reserve = ($_total)? $_listeReservation .
                            "<div class='tronche total'>TOTAL :</div>
                            <div class='personne total'>&nbsp;</div>
                            <div class='prix total'>" . number_format ($_total, 2) . "€</div>"
                            : "";
    if(isset($_SESSION['panier'][$data['id_salle']])) {
        $_SESSION['panier'][$data['id_salle']]['total'] = $_total;
    }


    if(empty($affiche)){
        return ['tableau'=>$_trad['produitNonDispoble'], 'reserve'=>''];
    }
    return ['tableau'=>$tableau, 'reserve'=>$reserve];
}

function treeProduitsSalle($_formulaire, $_id){

    $existProduits = selectProduitsSalle($_id);

    $produit = array();
    while($exist = $existProduits->fetch_assoc()){
        if(!isset($_POST['plagehoraire'][$exist['id_plagehoraire']])){
            deleteProduit($exist['id']);
        } else {
            unset($_formulaire['plagehoraire']['valide'][$exist['id_plagehoraire']]);
        }
    }

    foreach($_formulaire['plagehoraire']['valide'] as $plage_horaire => $info){
        setProduit($_id, $plage_horaire);
    }

    return true;
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

function reservationSalles()
{
    if (!empty($_POST)) {
        if (isset($_POST['reserver'])) {
            if(isset($_POST['prix'])) {
                $_SESSION['panier'][$_POST['id']] = isset($_POST['prix']) ? $_POST['prix'] : [];
            } else {
                return false;
            }
        } else if (isset($_POST['enlever'])) {
            unset($_SESSION['panier'][$_POST['id']]);
        }
    } else if (!empty($_GET)) {
        if (isset($_GET['reserver'])) {
            if(!(utilisateurConnecte())){
                return false;
            }
            header('location:?nav=ficheSalles&id='.$_GET['reserver'].'&pos='.$_GET['pos']);

        } else if (isset($_GET['enlever'])) {
            unset($_SESSION['panier'][$_GET['enlever']]);
        }
    }

    return true;
}

function activeSalles()
{
    if (isset($_GET)) {
        if (!empty($_GET['delete'])) {

            setSallesActive($_GET['delete'], 0);

        } elseif (!empty($_GET['active'])) {

            if(!empty(selectProduitsSalle($_GET['active'])->num_rows)){
                setSallesActive($_GET['active'], 1);
            } else {
                return false;
            }
        }

    }
    return true;
}

function urlReservation(){

    if(isset($_GET['reserver']) OR isset($_POST['reserver'])){

        $_trad = setTrad();
        if(utilisateurConnecte()){
         return (reservationSalles())? '' : $_trad['erreur']['produitChoix'];
        }

        $_SESSION['urlReservation'] = $_GET;
        header('refresh:0;url=index.php?nav=actif');
        echo "<html>{$_trad['erreur']['produitConnexion']}</html>";
    }

}

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
    var_dump($_formulaire);

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

        if (!empty($sql_set)){
            sallesUpdate($sql_set, $id_salle);
        }
        else {
            header('Location:?nav=salles&pos=P-'.$position.'');
            exit();
        }
        // ouverture d'une session
        $msg = "OK";

    }

    return $msg;
}



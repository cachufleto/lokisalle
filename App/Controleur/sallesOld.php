<?php
include_once MODEL . 'salles.php';
include_once FUNC . 'salles.func.php';


function reservation()
{
    if(isset($_SESSION['panier'])){

        foreach ($_SESSION["panier"] as $key => $value) {
            echo '<br>Salle id ', $key; # code...
        }

    }
}

function recherche()
{
    include FUNC . 'form.func.php';
    $echoville = ListeDistinc('ville', 'salles', array('valide'=>'tokyo'));
    $echocategorie = ListeDistinc('categorie', 'salles', array('valide'=>'R'));
    $echocapacite = ListeDistinc('capacite', 'salles', array('valide'=>'56'));

    include VUE . 'salles/recherche.tpl.php';
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
        header('Location:' . REPADMIN . 'index.php?nav=salles&pos='.$_formulaire['position']['value']);
        exit();
    } else {
        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
        include VUE . 'salles/editerSalles.tpl.php';
    }
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

        $erreur = controlImageUpload('photo', $_formulaire['photo'], $_formulaire['titre']['valide'])? true : $erreur;
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


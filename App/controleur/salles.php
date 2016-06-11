<?php
include_once MODEL . 'salles.php';
include_once FUNC . 'salles.func.php';

function salles()
{
    $nav = 'salles';
    $msg = '';
    $_trad = setTrad();
    reservationSalles();

    $table = listeSalles();
    include VUE . 'salles/salles.tpl.php';
}

function ficheSalles()
{
    $nav = 'ficheSalles';
    $_trad = setTrad();
    $msg = '';
    $_id = (int)(isset($_GET['id'])? $_GET['id'] : false);
    $position = (int)(isset($_GET['pos'])? $_GET['pos'] : 1);
    reservationSalles();

    include PARAM . 'ficheSalles.param.php';
    // on cherche la fiche dans la BDD
    // extraction des données SQL
    include FUNC . 'form.func.php';
    if ($salle = getSalles($_formulaire, $_id)) {
        // traitement POST du formulaire
        include VUE . 'salles/ficheSalles.tpl.php';
    } else {
        header('Location:index.php');
        exit();
    }

}

function backOff_salles()
{
    $nav = 'gestionSalles';
    $msg = '';
    $_trad = setTrad();

    if (isset($_GET)) {
        if (!empty($_GET['delete'])) {

            setSallesActive($_GET['delete'], 0);

        } elseif (!empty($_GET['active'])) {

            setSallesActive($_GET['active'], 1);
        }

    }

    $table = listeSallesBO();

    include VUE . 'salles/gestionSalles.tpl.php';
}

function backOff_editProduits($id)
{
    //$table = selectProduitsSalle($id);
    //include VUE . 'salles/gestionProduits.tpl.php';
}

function backOff_gestionProduits()
{
    backOff_ficheSalles();
    echo __FUNCTION__;
}

function backOff_ficheSalles()
{
    $nav = 'ficheSalles';
    $msg = '';
    $_trad = setTrad();

    include PARAM . 'backOff_ficheSalles.param.php';
    include FUNC . 'form.func.php';

    // extraction des données SQL
    $form = $msg = '';
    if (modCheckSalles($_formulaire, $_id, 'salles')) {
        // traitement POST du formulaire dans les parametres
        if ($_valider){

            $msg = $_trad['erreur']['inconueConnexion'];
            if(postCheck($_formulaire, TRUE)) {
                $msg = ficheSallesValider($_formulaire);
            }
        }

        if ('OK' == $msg) {
            // on renvoi ver connection
            $msg = $_trad['lesModificationOntEteEffectues'];
            // on évite d'afficher les info du mot de passe
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
                header('Location:' . LINK . '?nav=salles&pos=P-' . $position . '');
                exit();

            } else {

                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);

            }

        }

    } else {

        $form = 'Error 500: ' . $_trad['erreur']['NULL'];

    }

    include VUE . 'salles/backOff_ficheSalles.tpl.php';
    backOff_editProduits($_formulaire['id_salle']['valide']);
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
        // on renvoi ver la liste des salles
        header('Location:index.php?nav=salles&pos='.$_formulaire['position']['value']);
        exit();
    } else {
        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
        include VUE . 'salles/editerSalles.tpl.php';
    }
}


function reservation()
{
    $_trad = setTrad();
    reservationSalles();

    $nav = 'reservation';
    $table = selectSallesReservations();
    $msg = (!empty($table))? $_trad['reservationOk'] : $_trad['erreur']['reservationVide'];

    include VUE . "salles/salles.tpl.php";
}

<?php
include_once MODEL . 'salles.php';
include_once FUNC . 'salles.func.php';

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

    include VUE . 'salles/salles.tpl.php';
}

function ficheSalles()
{
    $nav = 'ficheSalles';
    $_trad = setTrad();
    $msg = '';
    $_id = (int)(isset($_GET['id'])? $_GET['id'] : false);
    $position = (int)(isset($_GET['pos'])? $_GET['pos'] : 1);

    include PARAM . 'ficheSalles.param.php';
    // on cherche la fiche dans la BDD
    // extraction des données SQL
    include FUNC . 'form.func.php';
    if (modCheckSalles($_formulaire, $_id, 'salles')) {
        // traitement POST du formulaire
        $form = formulaireAfficherInfo($_formulaire);
        $form .= '<a href="?nav=salles#P-' . $position . '">' . $_trad['revenir'] . '</a>';
    } else {
        header('Location:index.php');
        exit();
    }

    include VUE . 'salles/ficheSalles.tpl.php';
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
            '<a href="' . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] . '">' . $_trad['modifier'] . '</a>',
            ($data['active'] == 1) ? ' <a href="' . LINKADMIN . '?nav=salles&delete=' . $data['id_salle'] . '">' . $_trad['delete'] . '</a>' :
            ' <a href="' . LINKADMIN . '?nav=salles&active=' . $data['id_salle'] . '">' . $_trad['activer'] . '</a>'
        );
    }

    include VUE . 'salles/gestionSalles.tpl.php';
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
            echo 'TRAITEENT **************<br><br><br><br><br>**************';
            $msg = $_trad['erreur']['inconueConnexion'];
            if(postCheck($_formulaire, TRUE)) {
                //exit(var_dump($_formulaire));
                $msg = ficheSallesValider($_formulaire);
                exit('traitement');
            }
            exit('tratement');
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
                header('Location:?nav=salles&pos=P-' . $position . '');
                exit();

            } else {

                unset($_formulaire['mdp']);
                $form = formulaireAfficherInfo($_formulaire);

            }

        }

    } else {

        $form = 'Error 500: ' . $_trad['erreur']['NULL'];

    }

    include VUE . 'salles/ficheSalles.tpl.php';
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
        header('Location:index.php?nav=salles&pos='.$_formulaire['position']['value']);
        exit();
    } else {
        // RECUPERATION du formulaire
        $form = formulaireAfficher($_formulaire);
        include VUE . 'salles/editerSalles.tpl.php';
    }
}


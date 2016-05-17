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
    $table['champs']['id_salle'] = $_trad['champ']['id_salle'];
    $table['champs']['titre'] = $_trad['champ']['titre'];
    $table['champs']['capacite'] = $_trad['champ']['capacite'];
    $table['champs']['categorie'] = $_trad['champ']['categorie'];
    $table['champs']['photo'] = $_trad['champ']['photo'];
    $table['champs']['select'] = $_trad['select'];

    $position = 1;
    $salles = selectSalles();
    while ($data = $salles->fetch_assoc()) {
        $table['info'][] = array(
            $data['id_salle'],
            $data['titre'],
            $data['capacite'],
            $_trad['value'][$data['categorie']],
            '<a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . $position . '" id="P-' . $position . '" >
                <img class="trombi" src="' . LINK . 'photo/' . $data['photo'] . '" ></a>',
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
    $salles = selectSallesUsers();
    while ($data = $salles->fetch_assoc()) {
        $table['info'][] = array(
            $data['id_salle'],
            $data['titre'],
            $data['capacite'],
            $_trad['value'][$data['categorie']],
            '<a href="' . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] . '&pos=' . $position . '" id="P-' . $position . '" >
                <img class="trombi" src="' . LINK . 'photo/' . $data['photo'] . '" ></a>',
            '<a href="' . LINKADMIN . '?nav=ficheSalles&id=' . $data['id_salle'] . '">' . $_trad['modifier'] . '</a>',
            ($data['active'] == 1) ? ' <a href="' . LINKADMIN . '?nav=gestionSalles&delete=' . $data['id_salle'] . '">' . $_trad['delete'] . '</a>' :
            ' <a href="' . LINKADMIN . '?nav=gestionSalles&active=' . $data['id_salle'] . '">' . $_trad['activer'] . '</a>'
        );
    }

    include VUE . 'salles/gestionSalles.tpl.php';
}


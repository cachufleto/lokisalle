<?php


gestionSalles();

function gestionSalles()
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

    include TEMPLATE . 'gestionSalles.php';
}


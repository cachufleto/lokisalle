<?php

users($_trad, $titre, $msg);

function users($_trad, $titre, $msg)
{
  if (!utilisateurEstAdmin()) {
    header('Location:index.php');
    exit();
  }

  if (isset($_GET)) {
    if (!empty($_GET['delete']) && $_GET['delete'] != 1) {

      $sql = "UPDATE membres SET active = 0 WHERE id_membre = " . $_GET['delete'];
      if ($_GET['delete'] != $_SESSION['user']['id']) {
        executeRequete($sql);
      } else {
        $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];
      }

    } elseif (!empty($_GET['active'])) {

      $sql = "UPDATE membres SET active = 1 WHERE id_membre = " . $_GET['active'];
      executeRequete($sql);

    }

  }

  $table = '';

  $table .= "<tr><th>" . $_trad['champ']['pseudo'] . "</th><th>" . $_trad['champ']['nom'] . "</th><th>" . $_trad['champ']['prenom'] . "</th>
          <th>" . $_trad['champ']['email'] . "</th><th>" . $_trad['champ']['statut'] . "</th>";
  $table .= "<th>" . $_trad['champ']['active'];

  $table .= "</th></tr>";

  if (isSuperAdmin()) {

// selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.statut, m.active
        FROM membres m, checkinscription c
        WHERE m.active = 2 AND m.id_membre = c.id_membre
        ORDER BY m.nom, m.prenom";
    $membres = executeRequete($sql);

    if ($membres->num_rows > 0) {
      while ($data = $membres->fetch_assoc()) {

        $table .= "<tr><td>" . $data['pseudo'] . "</td><td>" . $data['nom'] . "</td><td>" . $data['prenom'] . "</td>
              <td><a href='mailto:" . $data['email'] . "'>" . $data['email'] . "</a></td><td>" . $_trad['value'][$data['statut']] . "</td>";
        $table .= "<td><a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>";
        $table .= "NEW :: <a href='" . LINKADMIN . '?nav=users&active=' . $data['id_membre'] . "'>" . $_trad['champ']['active'] . "</a>";

        $table .= "</td></tr>";
        # code...
      }
    }
  }

  $sql = "SELECT id_membre, pseudo, nom, prenom, email, statut, active
        FROM membres  WHERE " . (!isSuperAdmin() ? "id_membre != 1 AND active == 1 " : "active != 2") . "
        ORDER BY nom, prenom";
  $membres = executeRequete($sql);

  while ($data = $membres->fetch_assoc()) {

    $table .= "<tr><td>" . $data['pseudo'] . "</td><td>" . $data['nom'] . "</td><td>" . $data['prenom'] . "</td>
            <td><a href='mailto:" . $data['email'] . "'>" . $data['email'] . "</a></td><td>" . $_trad['value'][$data['statut']] . "</td>";
    $table .= "<td><a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>";
    $table .= ($data['active'] == 1) ? " <a href='" . LINKADMIN . '?nav=users&delete=' . $data['id_membre'] . "'>" . $_trad['delete'] . "</a>" :
        " <a href='" . LINKADMIN . '?nav=users&active=' . $data['id_membre'] . "'>" . $_trad['champ']['active'] . "</a>";

    $table .= "</td></tr>";
    # code...
  }

  include TEMPLATE . 'users.php';
}
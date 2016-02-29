<?php

if (!utilisateurEstAdmin()){
  header('Location:index.php');
  exit();
}

include MODEL . 'Users.php';

if (isset($_GET)){
 if (!empty($_GET['delete']) && $_GET['delete'] != 1){

   if ($_GET['delete'] != $_SESSION['user']['id']) {
      usersUpdateDelete($_GET['delete']);
    }
   else $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];

  } else if (!empty($_GET['active'])){
    usersUpdateActive($_GET['active']);
  }

}

$table = '';


if (isSuperAdmin()) {

// selection de tout les users sauffe le super-ADMIN

  $membres = usersSelectCheckInscription();

 if ($membres->num_rows > 0 )
    while ($data = $membres->fetch_assoc()) {

      $table .= "<tr>\r\n";
      $table .= "<td>" . $data['pseudo'] . "</td>\r\n";
      $table .= "<td>" . $data['nom'] . "</td>\r\n";
      $table .= "<td>" . $data['prenom'] . "</td>\r\n";
      $table .= "<td><a href='mailto:" . $data['email'] . "'>" . $data['email'] . "</a></td>\r\n";
      $table .= "<td>" . $_trad['value'][$data['statut']] . "</td>\r\n";
      $table .= "<td><a href='" . LINKADMIN . '?nav=profil&id=' . $data['id_membre'] . "'>" . $_trad['modifier'] . "</a>\r\n";
      $table .= "NEW :: <a href='" . LINKADMIN . "'?nav=users&active='" . $data['id_membre'] . "'>" . $_trad['champ']['active'] . "</a>";
      $table .= "</td>\r\n";
      $table .= "</tr>\r\n";
    }
}

$membres = usersSelectAll();

while ($data = $membres->fetch_assoc()) {

  $table .= "<tr>\r\n";
  $table .= "<td>". $data['pseudo'] . "</td>\r\n";
  $table .= "<td>". $data['nom'] . "</td>\r\n";
  $table .= "<td>". $data['prenom'] . "</td>\r\n";
  $table .= "<td><a href='mailto:". $data['email'] . "'>". $data['email'] . "</a></td>\r\n";
  $table .= "<td>". $_trad['value'][$data['statut']] . "</td>";
  $table .= "<td><a href='". LINKADMIN.'?nav=profil&id='.$data['id_membre'] . "'>".$_trad['modifier']."</a>";
  $table .= ($data['active'] == 1 )?
            " <a href='". LINKADMIN.'?nav=users&delete='.$data['id_membre'] . "'>".$_trad['delete']."</a>" :
            " <a href='". LINKADMIN.'?nav=users&active='.$data['id_membre'] . "'>".$_trad['champ']['active']."</a>";
  $table .= "</td>\r\n";
      $table .= "</tr>\r\n";
}

include TEMPLATE . 'users.html.php';
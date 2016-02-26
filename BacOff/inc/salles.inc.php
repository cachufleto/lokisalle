<?php

if(!utilisateurEstAdmin()){
  header('Location:index.php');
  exit();
}


if (isset($_GET)){
  if (!empty($_GET['delete']) && $_GET['delete'] != 1){

    if ($_GET['delete'] != $_SESSION['user']['id']){
      sallesUpdateDelete($_GET['delete']);
    }
    else $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];

  } else if (!empty($_GET['active'])){
    sallesUpdateActive($_GET['active']);
  }
}

// selection de tout les Salles
$membres = sallesSelectAll();

$table = '';

while ($data = $membres->fetch_assoc()) {

  $table .= "<tr>\r\n";
  $table .= "<td>" . $data['id_salle'] . "</td>\r\n";
  $table .= "<td>" . $data['titre'] . "</td>\r\n";
  $table .= "<td>" . $data['capacite'] . "</td>\r\n";
  $table .= "<td>" . $_trad['value'][$data['categorie']] . "</td>\r\n";
//  $table .= "<td><a href='". LINKADMIN.'?nav=profil&id='.$data['id_salle'] . "'>".$_trad['modifier']."</a>";
//  $table .= ($data['active'] == 1 )?" <a href='". LINKADMIN.'?nav=users&delete='.$data['id_salle'] . "'>".$_trad['delete']."</a>" :
//           " <a href='". LINKADMIN.'?nav=users&active='.$data['id_salle'] . "'>".$_trad['activer']."</a>";

  $table .= "</td>\r\n";
  $table .= "</tr>\r\n";
}

include(TEMPLATE . 'salles.html.php');
<?php
include MODEL . 'Salles.php';

if (!utilisateurEstAdmin()){
  header('Location:index.php');
  exit();
}


if (isset($_GET)){
 if (!empty($_GET['delete'])){
    sallesUpdateDelete($_GET['delete']);
  } else if (!empty($_GET['active'])){
    sallesUpdateActive($_GET['active']);
  }
}


// selection de tout les salles
$salles = sallesSelectAll();
$table = '';

$position = 1;
while ($data = $salles->fetch_assoc()) {

  $table .= "<tr>\r\n";
  $table .= "<td>" . $data['id_salle'] . "</td>\r\n";
  $table .= "<td>" . $data['titre'] . "</td>\r\n";
  $table .= "<td>" . $data['capacite'] . "</td>\r\n";
  $table .= "<td>" . $_trad['value'][$data['categorie']] . "</td>\r\n";
  $table .= "<td><a href=\"" . LINKADMIN . "?nav=ficheSalles&id=" . $data['id_salle'] . "&pos=$position\" id=\"P-$position\" >";
  $table .= "<img class=\"trombi\" src=\"" . LINK . "photo/" . $data['photo'] . "\" ></a></td>\r\n";
  $table .= "<td><a href='". LINKADMIN.'?nav=ficheSalles&id='.$data['id_salle'] . "'>".$_trad['modifier']."</a>\r\n";
  $table .= ($data['active'] == 1 )?
                "<a href='". LINKADMIN.'?nav=gestionSalles&delete='.$data['id_salle'] . "'>".$_trad['delete']."</a>" :
                "<a href='". LINKADMIN.'?nav=gestionSalles&active='.$data['id_salle'] . "'>".$_trad['activer']."</a>";
  $table .= "</td>\r\n";
  $table .= "</tr>\r\n";
}

include TEMPLATE . 'gestionsalles.html.php';
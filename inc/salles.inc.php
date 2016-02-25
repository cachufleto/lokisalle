<?php
/**
 * Control des variables externes
 */
if(isset($_GET)){
  if(!empty($_GET['reserver'])){
    $_SESSION['panier'][$_GET['reserver']] = true;
  } elseif(!empty($_GET['enlever'])){
    $_SESSION['panier'][$_GET['enlever']] = false;
  }
}

// selection de tout les users sauffe le super-ADMIN
$sql = "SELECT id_salle, titre, capacite, categorie, photo, active FROM salles " . (  !isSuperAdmin()? " WHERE active != 0 " : "" ). " ORDER BY cp, titre";
$membres = executeRequete($sql);
$table = '';


$position = 1;

/**
 * Traitement de la BDD table salles
 */
while ($data = $membres->fetch_assoc()) {

  $table .= "\t" . '<tr>' . "\r\n";
  $table .= "\t\t" . '<td>'. $data['id_salle'] . '</td>' . "\r\n";
  $table .= "\t\t" . '<td>'. $data['titre'] . '</td>' . "\r\n";
  $table .= "\t\t" . '<td>'. $data['capacite'] . '</td>' . "\r\n";
  $table .= "\t\t" . '<td>'. $_trad['value'][$data['categorie']] . '</td>' . "\r\n";
  $table .= "\t\t" . '<td><a href="'. LINK.'?nav=ficheSalles&id='.$data['id_salle'] .
    '&pos='.$position.'" id="P-'. $position.'" ><img class="trombi" src="'. LINK.'photo/'.$data['photo'] . 
    '" ></a></td>' . "\r\n";
  $table .= "\t\t" . '<td>' . "\r\n";

  $table .= (isset($_SESSION['panier'][$data['id_salle']]) && $_SESSION['panier'][$data['id_salle']] === true)?
        '<a href="'. LINK.'?nav=salles&enlever='.$data['id_salle'] . '#P-'.$position.'" >'.$_trad['enlever'].'</a>' :
        ' <a href="'. LINK.'?nav=salles&reserver='.$data['id_salle'] . '#P-'.$position.'">'.$_trad['reserver'].'</a>';

  $table .= "\t\t" . '</td>' . "\r\n";
  $table .= "\t" . '</tr>' . "\r\n";
  $position++;
}

include (TEMPLATE . 'salles.html.php');
<?php

if(!utilisateurEstAdmin()){
  header('Location:index.php');
  exit();
}

if(isset($_GET)){
  if(!empty($_GET['delete']) && $_GET['delete'] != 1){

    $sql = "UPDATE salles SET active = 0 WHERE id_salle = ".$_GET['delete'];
    if($_GET['delete'] != $_SESSION['user']['id']) executeRequete($sql);
    else $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];

  } elseif(!empty($_GET['active'])){

    $sql = "UPDATE salless SET active = 1 WHERE id_salle = ".$_GET['active'];
    executeRequete($sql);

  }

}

$_trad['id_salle'] = 'Identifiant';
$_trad['titre'] = 'Nom';
$_trad['capacite'] = 'Capacité';
$_trad['categorie'] = 'Categorie';
$_trad['R'] = 'Reunions';
$_trad['F'] = 'Fêtes';
$_trad['C'] = 'Conferences';

// selection de tout les users sauffe le super-ADMIN
$sql = "SELECT id_salle, titre, capacite, categorie FROM salles " . (  !isSuperAdmin()? " WHERE active != 0 " : "" ). " ORDER BY cp, titre";
$membres = executeRequete($sql);
$table = '';

$table .= "<tr><th>". $_trad['id_salle'] . "</th><th>". $_trad['titre'] . "</th><th>". $_trad['capacite'] . "</th>
          <th>". $_trad['categorie'] . "</th>";
//$table .= "<th>".$_trad['activer'];

$table .= "</th></tr>";


while ($data = $membres->fetch_assoc()) {

  $table .= "<tr><td>". $data['id_salle'] . "</td><td>". $data['titre'] . "</td><td>". $data['capacite'] . "</td>
            <td>". $_trad[$data['categorie']] . "</td>";
//  $table .= "<td><a href='". LINK.LINKADM.'?nav=profil&id='.$data['id_salle'] . "'>".$_trad['modifier']."</a>";
 // $table .= ($data['active'] == 1 )?" <a href='". LINK.LINKADM.'?nav=users&delete='.$data['id_salle'] . "'>".$_trad['delete']."</a>" :
 //           " <a href='". LINK.LINKADM.'?nav=users&active='.$data['id_salle'] . "'>".$_trad['activer']."</a>";

  $table .= "</td></tr>";
  # code...
}

$table = "<table>$table</table>";

?>



    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-pencil "></span><?php echo $titre; ?></h1>
		<hr />
      </div>
      <div class="">
        <?php echo $msg, $table; ?>
		<hr />
      </div>
    </div><!-- /.container -->

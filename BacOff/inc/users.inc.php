<?php

if(!utilisateurEstAdmin()){
  header('Location:index.php');
  exit();
}

if(isset($_GET)){
  if(!empty($_GET['delete']) && $_GET['delete'] != 1){

    $sql = "UPDATE membres SET active = 0 WHERE id_membre = ".$_GET['delete'];
    if($_GET['delete'] != $_SESSION['user']['id']) executeRequete($sql);
    else $msg = $_trad['vousNePouvezPasVousSupprimerVousMeme'];

  } elseif(!empty($_GET['active'])){

    $sql = "UPDATE membres SET active = 1 WHERE id_membre = ".$_GET['active'];
    executeRequete($sql);

  }

}

// selection de tout les users sauffe le super-ADMIN
$sql = "SELECT id_membre, pseudo, nom, prenom, email, statut, active FROM membres " . (  !isSuperAdmin()? " WHERE id_membre != 1 AND active != 0 " : "" ). " ORDER BY nom, prenom";
$membres = executeRequete($sql);
$table = '';

$table .= "<tr><th>". $_trad['champ']['pseudo'] . "</th><th>". $_trad['champ']['nom'] . "</th><th>". $_trad['champ']['prenom'] . "</th>
          <th>". $_trad['champ']['email'] . "</th><th>". $_trad['champ']['statut'] . "</th>";
$table .= "<th>".$_trad['champ']['active'];

$table .= "</th></tr>";


while ($data = $membres->fetch_assoc()) {

  $table .= "<tr><td>". $data['pseudo'] . "</td><td>". $data['nom'] . "</td><td>". $data['prenom'] . "</td>
            <td><a href='mailto:". $data['email'] . "'>". $data['email'] . "</a></td><td>". $_trad['value'][$data['statut']] . "</td>";
  $table .= "<td><a href='". LINK.LINKADM.'?nav=profil&id='.$data['id_membre'] . "'>".$_trad['modifier']."</a>";
  $table .= ($data['active'] == 1 )?" <a href='". LINK.LINKADM.'?nav=users&delete='.$data['id_membre'] . "'>".$_trad['delete']."</a>" :
            " <a href='". LINK.LINKADM.'?nav=users&active='.$data['id_membre'] . "'>".$_trad['champ']['active']."</a>";

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

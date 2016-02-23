<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 22/02/2016
 * Time: 00:01
 */

var_dump($_GET);

$sql = "SELECT * FROM membres, checkinscription WHERE membres.id_membre = checkinscription.id_membre
AND checkinscription.checkinscription = '".$_GET['jeton']."'";
echo "<p>$sql</p>";
$incription = executeRequete($sql);
if($incription->fetch_row()) {
    $membre = $incription->fetch_row();
    var_dump($membre);
} else {

    echo "<p>PAS DE JETON</p>";

}

$sql = "UPDATE membres SET active = 1 WHERE id_membre = ( SELECT id_membre FROM checkinscription WHERE checkinscription = '".$_GET['jeton']."')";

echo "<p>$sql</p>";

executeRequete($sql);

$sql = "DELETE FROM `checkinscription` WHERE checkinscription = '".$_GET['jeton']."'";
echo "<p>$sql</p>";

executeRequete($sql);


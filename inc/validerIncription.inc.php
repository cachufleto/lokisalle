<?php
header( "refresh:5;url=index.php?nav=actif" );
echo $_trad['redirigeVerConnection'];
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 22/02/2016
 * Time: 00:01
 */
if(isset($_GET['jeton']) && !empty($_GET['jeton'])) {

    $sql = "SELECT * FROM membres, checkinscription WHERE membres.id_membre = checkinscription.id_membre
            AND checkinscription.checkinscription = '" . $_GET['jeton'] . "'";
    $incription = executeRequete($sql);

    if ($incription->fetch_row()) {

        $membre = $incription->fetch_row();

        $sql = "UPDATE membres SET active = 1 WHERE id_membre = " . $membre['id_membre'] . ";";
        $sql .= "DELETE FROM `checkinscription` WHERE id_membre = " . $membre['id_membre'] . ";";
        executeMultiRequete($sql);

    }
}
exit();
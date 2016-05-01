<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 09/03/2016
 * Time: 13:34
 */
function selecMembreJeton($jeton)
{
    $sql = "SELECT membres.id_membre
          FROM membres, checkinscription
          WHERE membres.id_membre = checkinscription.id_membre
              AND checkinscription.checkinscription = '$jeton'";

    $incription = executeRequete($sql);

    if ($incription->fetch_row()) {

        $membre = $incription->fetch_row();

        return $membre['id_membre'];
    }

    return false;


}

function updateMembreJeton($id)
{
    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $id;";
    $sql .= "DELETE FROM `checkinscription` WHERE id_membre = $id;";

    return executeMultiRequete($sql);

}
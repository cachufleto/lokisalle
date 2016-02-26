<?php
function inscriptionSelectMembreJeton($_id){

    $sql = "SELECT * FROM membres, checkinscription WHERE membres.id_membre = checkinscription.id_membre
            AND checkinscription.checkinscription = '" .  . "'";
    return executeRequete($sql);

}

function inscriptionValideJeton($_id){

    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $_id;";
    $sql .= "DELETE FROM `checkinscription` WHERE id_membre = $_id;";

    executeMultiRequete($sql);

}
<?php
function usersUpdateDelete($_id){

    $sql = "UPDATE membres SET active = 0 WHERE id_membre = $_id";

    executeRequete($sql);

}

function usersUpdateActive($_id){

    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $_id";

    executeRequete($sql);

}

function usersSelectCheckInscription(){

    $sql = "SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.statut, m.active
          FROM membres m, checkinscription c
          WHERE m.active = 2 AND m.id_membre = c.id_membre
          ORDER BY m.nom, m.prenom";

    return executeRequete($sql);

}

function usersSelectAll(){

    $sql = "SELECT id_membre, pseudo, nom, prenom, email, statut, active
            FROM membres  WHERE " . (  !isSuperAdmin()? "id_membre != 1 AND active == 1 " : "active != 2" ). "
            ORDER BY nom, prenom";

    return executeRequete($sql);

}

function usersSelectMembreJeton($jeton){

    $sql = "SELECT * FROM membres, checkinscription WHERE membres.id_membre = checkinscription.id_membre
            AND checkinscription.checkinscription = '$jeton'";
    return executeRequete($sql);

}

function usersValideJeton($_id){

    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $_id;";
    $sql .= "DELETE FROM `checkinscription` WHERE id_membre = $_id;";

    return executeMultiRequete($sql);

}
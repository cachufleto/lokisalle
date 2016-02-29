<?php

function sallesHomeDeniersOffres()
{
    $sql = "SELECT * FROM salles";
    return executeRequete   ($sql);
}

function sallesUpdate($sql_set, $id_salle)
{
    if (!empty($sql_set)) {
        // mise à jour de la base des données
        $sql = "UPDATE salles SET $sql_set  WHERE id_salle = $id_salle";
        return executeRequete($sql);
    }
    return false;
}

function sallesUpdateDelete($_id)
{
    $sql = "UPDATE salles SET active = 0 WHERE id_salle = $_id";
    executeRequete($sql);
}

function sallesUpdateActive($_id)
{
    $sql = "UPDATE salles SET active = 1 WHERE id_salle = $_id";
    executeRequete($sql);
}

function sallesSelectAll()
{
    $sql = "SELECT *
            FROM salles " . (  !isSuperAdmin()? " WHERE active != 0 " : "" ). "
            ORDER BY cp, titre";
    return executeRequete($sql);
}

function sallesInsert($sql_champs, $sql_Value){
    $sql = " INSERT INTO salles ($sql_champs) VALUES ($sql_Value) ";
    return executeRequete ($sql);
}
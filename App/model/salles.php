<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 09/03/2016
 * Time: 13:35
 */

function setSallesActive($id, $active){

    $sql = "UPDATE salles SET active = $active WHERE id_salle = $id";
    executeRequete($sql);
}

function selectSalles()
{
    $sql = "SELECT * FROM salles";
    return executeRequete($sql);
}

function selectSallesUsers()
{
// selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT id_salle, titre, capacite, categorie, photo, active
            FROM salles " . (!isSuperAdmin() ? " WHERE active != 0 " : "") . "
            ORDER BY cp, titre";
    return executeRequete($sql);
}


function selectListeDistinc($champ, $table)
{

    $sql = "SELECT DISTINCT $champ FROM $table ORDER BY $champ ASC";
    return   executeRequete($sql);

}

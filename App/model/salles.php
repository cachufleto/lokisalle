<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 09/03/2016
 * Time: 13:35
 */

function sallesUpdate($sql_set, $id_salle){

    $sql = 'UPDATE salles SET '.$sql_set.'  WHERE id_salle = '.$id_salle;
    executeRequete($sql);
}

function setSallesActive($id, $active){

    $sql = "UPDATE salles SET active = $active WHERE id_salle = $id";
    executeRequete($sql);
}

function selectSalles()
{
    $sql = "SELECT * FROM salles where active = 1";
    return executeRequete($sql);
}

function selectSallesOrder($order, $listeId)
{
    $sql = "SELECT id_salle, titre, capacite, categorie, photo
            FROM salles WHERE active = 1 " . ((!empty($listeId))? " AND $listeId " : "") .
            " ORDER BY $order";
    return executeRequete($sql);
}

function selectSallesUsers($order)
{
// selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT id_salle, titre, capacite, categorie, photo, active
            FROM salles " . (!isSuperAdmin() ? " WHERE active != 0 " : "") . "
            ORDER BY $order";
    return executeRequete($sql);
}


function selectListeDistinc($champ, $table)
{

    $sql = "SELECT DISTINCT $champ FROM $table ORDER BY $champ ASC";
    return   executeRequete($sql);

}


function setSalle($sql_champs, $sql_value)
{
    // insertion en BDD
    $sql = " INSERT INTO salles ($sql_champs) VALUES ($sql_value) ";
    return executeRequete ($sql);
    // ouverture d'une session
}
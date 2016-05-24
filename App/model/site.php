<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 09/03/2016
 * Time: 13:35
 */
/**
 * @return bool|mysqli_result
 */
function userSelectContactAll()
{
    $sql = "SELECT * FROM membres WHERE statut != 'MEM';";
    return executeRequete($sql);
}

function selectSallesActive()
{
    // selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT id_salle, titre, capacite, categorie, photo, active
            FROM salles
            WHERE active != 0
            ORDER BY titre
            LIMIT 0,5;";
    return executeRequete($sql);
}


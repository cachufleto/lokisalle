<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 09/03/2016
 * Time: 13:35
 */


function selectSalles()
{
    $sql = "SELECT * FROM salles";
    return executeRequete($sql);
}

# Fonction modCheck()
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function modCheckSalles(&$_formulaire, $_id)
{
    $form = $_formulaire;

    $sql = "SELECT * FROM salles WHERE id_salle = ". $_id . ( !isSuperAdmin()? " AND active != 0" : "" );

    $data = executeRequete($sql) or die ($sql);
    $user = $data->fetch_assoc();

    if($data->num_rows < 1) return false;

    foreach($form as $key => $info){
        if($key != 'valide' && key_exists ( $key , $user )){
            $_formulaire[$key]['valide'] = $user[$key];
            $_formulaire[$key]['sql'] = $user[$key];
        }
    }

    return true;
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

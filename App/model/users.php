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

    $inscription = executeRequete($sql);
    if ($inscription->field_count == 1) {

        $membre = $inscription->fetch_row();

        return $membre[0];
    }

    return false;


}

function updateMembreJeton($id)
{
    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $id;";
    $sql .= "DELETE FROM `checkinscription` WHERE id_membre = $id;";

    return executeMultiRequete($sql);

}

/**
 * @param $sql_Where
 * @return bool
 */
function usersSelectWhere($sql_Where)
{
    $sql = "SELECT id_membre FROM membres WHERE $sql_Where;";
    return executeRequete($sql);
}

function usersMoinsAdmin()
{
    // selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.statut, m.active
        FROM membres m, checkinscription c
        WHERE m.active = 2 AND m.id_membre = c.id_membre
        ORDER BY m.nom, m.prenom";
    return executeRequete($sql);
}

function selectMailUser($id, $valeur)
{
    $sql = "SELECT email FROM membres WHERE id_membre != ". $id ." and email='$valeur'";
    return executeRequete($sql);
}


function slectUsersActive()
{
    $sql = "SELECT id_membre, pseudo, nom, prenom, email, statut, active
        FROM membres  WHERE " . (!isSuperAdmin() ? "id_membre != 1 AND active == 1 " : "active != 2") . "
        ORDER BY nom, prenom";
    return executeRequete($sql);
}

# Fonction testADMunique()
# Control des informations Postées
# $info tableau des items validées du formulaire
# RETURN boolean

function testADMunique($statut, $id_membre)
{


    if(utilisateurEstAdmin() && $id_membre == $_SESSION['user']['id'] && $statut != 'ADM')
    {

        // interdiction de modifier le statut pour le super administrateur
        if($id_membre == 1) return true;

        // interdiccion de modifier le statut pour un admin si il est le seule;
        // Le super administrateur peut inhabiliter tout le monde
        $sql = "SELECT COUNT(statut) as 'ADM' FROM membres WHERE statut = 'ADM' ". (!isSuperAdmin()? " AND id_membre != 1 " : "" );
        $ADM = executeRequete($sql);
        $num = $ADM->fetch_assoc();

        // si la requete retourne un enregistrement, c'est qu'il est el seul admin.
        if($num['ADM'] == 1)  return true;
    }
    return false;

}

# Fonction modCheck()
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function modCheckMembres(&$_formulaire, $_id)
{
    $form = $_formulaire;

    $sql = "SELECT * FROM membres WHERE id_membre = ". $_id . ( !isSuperAdmin()? " AND active != 0" : "" );

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


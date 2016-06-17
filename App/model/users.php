<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 09/03/2016
 * Time: 13:34
 */
function selecMembreJeton($jeton)
{
    $sql = "SELECT membres.id
          FROM membres, checkinscription
          WHERE membres.id = checkinscription.id_membre
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
    $sql = "UPDATE membres SET active = 1 WHERE id = $id;";
    $sql .= "DELETE FROM `checkinscription` WHERE id_membre = $id;";

    return executeMultiRequete($sql);

}

/**
 * @param $sql_Where
 * @return bool
 */
function usersSelectWhere($sql_Where)
{
    $sql = "SELECT id FROM membres WHERE $sql_Where;";
    return executeRequete($sql);
}

function usersMoinsAdmin()
{
    // selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT m.id, m.pseudo, m.nom, m.prenom, m.email, m.statut, m.active
        FROM membres m, checkinscription c
        WHERE m.active = 2 AND m.id = c.id_membre
        ORDER BY m.nom, m.prenom";
    return executeRequete($sql);
}

function selectMailUser($id, $valeur)
{
    $sql = "SELECT email FROM membres WHERE id != ". $id ." and email='$valeur'";
    return executeRequete($sql);
}


function userPseudoExist($pseudo){
    $sql = "SELECT pseudo FROM membres WHERE pseudo='$pseudo'";
    $membre = executeRequete ($sql);
    if($membre->num_rows > 0){
        return true;
    }
    return false;
}

function setUserActive($id, $active = 1){

    $sql = "UPDATE membres SET active = $active WHERE id = $id";
    executeRequete($sql);
}

function userUpdate($sql_set, $id)
{
    // mise à jour de la base des données
    $sql = "UPDATE membres SET $sql_set  WHERE id = $id";
    executeRequete ($sql);
}

function getUserMail($info)
{
    $sql = "SELECT id, nom, prenom, email FROM membres WHERE email = '" . $info['email']['valide'] . "'";
    $membre = executeRequete($sql);
    if($membre->num_rows > 0){
        $num = $membre->fetch_assoc();
        return $num;
    }
    return false;
}

function getUserConnexion($sql_Where)
{
    $sql = "SELECT mdp, id, email, pseudo, statut, nom, prenom, active FROM membres WHERE $sql_Where ";
    $membre = executeRequete ($sql); // la variable $pseudo existe grace a l'extract fait prealablemrent.

    if($membre->num_rows === 1){
        return $membre->fetch_assoc();
    }
    return false;
}

function userChangerMDPInsert($checkinscription, $info)
{
    $sql = "INSERT INTO checkinscription (id_membre, checkinscription)
        VALUES ( (SELECT id FROM membres WHERE email = '" . $info['email']['valide'] . "'), '$checkinscription');";

    return executeRequete($sql);
}

function userInscriptionInsert($sql_champs, $sql_Value, $checkinscription, $info)
{

    $sql = "INSERT INTO membres ($sql_champs, mdp) VALUES ($sql_Value, '$checkinscription');";
    $sql .= "INSERT INTO checkinscription (id_membre, checkinscription)
        VALUES ( (SELECT id FROM membres WHERE email = '" . $info['email']['valide'] . "'), '$checkinscription');";

    return executeMultiRequete($sql);
}

function userMailExist($email)
{
    $sql = "SELECT email FROM membres WHERE email='$email'";
    $membre = executeRequete($sql);
    if($membre->num_rows > 0){
        return true;
    }
    return false;
}

function selectUsersActive()
{
    $sql = "SELECT id, pseudo, nom, prenom, email, statut, active
        FROM membres  WHERE " . (!isSuperAdmin() ? "id != 1 AND active == 1 " : "active != 2") . "
        ORDER BY nom, prenom";
    return executeRequete($sql);
}

function getUser($id)
{

    $sql = "SELECT id, prenom, nom, pseudo, email, telephone, gsm, sexe, ville, cp, adresse, statut
            FROM membres WHERE id = $id " . ( !isSuperAdmin()? " AND active != 0" : "" );

    $data = executeRequete($sql);

    if($data->num_rows < 1) {
        return false;
    }

    return $data->fetch_assoc();

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
        $sql = "SELECT COUNT(statut) as 'ADM' FROM membres WHERE statut = 'ADM' ". (!isSuperAdmin()? " AND id != 1 " : "" );
        $ADM = executeRequete($sql);
        $num = $ADM->fetch_assoc();

        // si la requete retourne un enregistrement, c'est qu'il est el seul admin.
        if($num['ADM'] == 1)  return true;
    }
    return false;

}

function userUpdateMDP($mdp, $id)
{
    $sql = "UPDATE membres SET mdp = '" . hashCrypt($mdp) . "' WHERE id = $id";
    executeRequete($sql);
}


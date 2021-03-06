<?php
/**
 * @param $_id
 */
function usersUpdateDelete($_id)
{
    $sql = "UPDATE membres SET active = 0 WHERE id_membre = $_id;";
    executeRequete($sql);
}

/**
 * @param $_id
 */
function usersUpdateActive($_id)
{
    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $_id;";
    executeRequete($sql);
}

/**
 * @return bool|mysqli_result
 */
function usersSelectCheckInscription()
{
    $sql = "SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.statut, m.active
          FROM membres m, checkinscription c
          WHERE m.active = 2 AND m.id_membre = c.id_membre
          ORDER BY m.nom, m.prenom;";
    return executeRequete($sql);
}

/**
 * @param $sql_Where
 * @return array|bool
 */
function usersSelectConnexion($sql_Where)
{
    // lançons une requete nommee membre dans la BD pour voir si un pseudo est bien saisi.
    $sql = "SELECT mdp, id_membre, pseudo, statut, nom, prenom FROM membres WHERE $sql_Where;";
    return executeRequeteAssoc($sql);
    // la variable $pseudo existe grace a l'extract fait prealablemrent.
}

/**
 * @param $email
 * @param $checkinscription
 * @return bool|mysqli_result
 */
function usersInsertCheckInscription($email, $checkinscription)
{
    $sql = "INSERT INTO checkinscription (id_membre, checkinscription)
			VALUES (
			  (SELECT id_membre
			    FROM membres
			    WHERE email = '$email'),
			  '$checkinscription');";
    return executeRequete($sql);
}

/**
 * @param $sql_champs
 * @param $sql_Value
 * @return bool|mysqli_result
 */
function usersInsert($sql_champs, $sql_Value)
{
    $sql = "INSERT INTO membres ($sql_champs) VALUES ($sql_Value);";
    return executeRequete ($sql);
}

/**
 * @param $sql_set
 * @param $_id
 */
function usersUpdateProfil($sql_set, $_id)
{
    // mise à jour de la base des données
    $sql = "UPDATE membres SET $sql_set WHERE id_membre = $_id;";
    executeRequete ($sql);
}

/**
 * @param $pseudo
 * @return bool
 */
function usersTestPseudo($pseudo)
{
    $sql = "SELECT pseudo FROM membres WHERE pseudo='$pseudo';";
    return executeRequeteExist($sql);
}

/**
 * @param $_id
 * @param $_mail
 * @return bool
 */
function usersTestMail($_id, $_mail)
{
    $sql = "SELECT email FROM membres WHERE id_membre != $_id and email='$_mail';";
    return executeRequeteExist($sql);
}

/**
 * @param $sql_Where
 * @return bool
 */
function usersSelectChangerMotPasse($sql_Where)
{
    $sql = "SELECT id_membre FROM membres WHERE $sql_Where;";
    return executeRequete($sql);
}

/**
 * @param $mail
 * @return bool|mysqli_result
 */
function userExistMail($mail)
{
    $sql = "SELECT email FROM membres WHERE email='$mail';";
    return executeRequete($sql);
}

/**
 * @return bool|mysqli_result
 */
function usersSelectAll()
{
    $sql = "SELECT id_membre, pseudo, nom, prenom, email, statut, active
            FROM membres
            WHERE " . (  !isSuperAdmin()? "id_membre != 1 AND active == 1 " : "active != 2" ). "
            ORDER BY nom, prenom;";
    return executeRequete($sql);
}

/**
 * @param $jeton
 * @return bool|mysqli_result
 */
function usersSelectMembreJeton($jeton)
{
    $sql = "SELECT *
            FROM membres, checkinscription
            WHERE membres.id_membre = checkinscription.id_membre
            AND checkinscription.checkinscription = '$jeton';";
    return executeRequete($sql);
}

/**
 * @param $_id
 * @return bool
 */
function usersValideJeton($_id)
{
    $sql = "UPDATE membres SET active = 1 WHERE id_membre = $_id;";
    $sql .= "DELETE FROM `checkinscription` WHERE id_membre = $_id;";
    return executeMultiRequete($sql);
}

/**
 * @param $sql_Where
 * @return array|bool
 */
function usersSelectUser($sql_Where)
{
    $sql = "SELECT * FROM membre WHERE $sql_Where;";
    return executeRequeteAssoc($sql);
}

/**
 * @return bool
 */
function usersSelectUniqueADM()
{
    $sql = "SELECT id_membre FROM membres WHERE statut = 'ADM' ". (!isSuperAdmin()? " AND id_membre != 1; " : ";" );
    $ADM = executeRequete($sql);
    return ($ADM->num_rows === 1)? true : false;
}

/**
 * @return bool|mysqli_result
 */
function userSelectContactAll()
{
    $sql = "SELECT * FROM membres WHERE statut != 'MEM';";
    return executeRequete($sql);
}

function usersSelectModCheck($_id){
    $sql = "SELECT * FROM membres WHERE id_membre = $_id " . ( !isSuperAdmin()? " AND active != 0" : "" );
    return executeRequeteAssoc($sql);

}
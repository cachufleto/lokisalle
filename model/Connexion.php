<?php

function usersSelectConnexion($sql_Where)
{
    // lançons une requete nommee membre dans la BD pour voir si un pseudo est bien saisi.
    $sql = "SELECT mdp, id_membre, pseudo, statut, nom, prenom FROM membres WHERE $sql_Where ";
    return executeRequeteAssoc($sql);
    // la variable $pseudo existe grace a l'extract fait prealablemrent.
}


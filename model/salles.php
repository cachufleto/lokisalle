<?php
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
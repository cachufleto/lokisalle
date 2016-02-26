<?php

function contactSelectAll()
{
    $sql = "SELECT * FROM membres WHERE statut != 'MEM'";

    return executeRequete($sql);

}


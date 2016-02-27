<?php

function install($install = $_GET)
{

    if(isset($install['install']) && $install['install'] == 'BDD')
    {

        $data = isset($install['data'])? true : false;

        if(installBDD()){
            if(installData($data)) {
                installUpdateMotPasseCrypte();
            }
        }

        exit();

    }

}

function installBDD() {

    // initialisation des tables
    echo "chargement du fichier lokisalle.sql";
    $sql = file_get_contents(APP.'SQL' . DIRECTORY_SEPARATOR . 'lokisalle.sql');

    return executeMultiRequete($sql);

}

function installData($data) {

    // remplisage des tables
    echo "<br>chargement du fichier data.sql";
    $sql = file_get_contents(APP.'SQL' . DIRECTORY_SEPARATOR . 'data.sql');

    if($data){
        return executeMultiRequete($sql);
    }
    return true;

}

function installUpdateMotPasseCrypte(){

    $membres = executeRequete("SELECT id_membre, mdp FROM membres");

    while($membre = $membres->fetch_assoc()){
        $sql = "UPDATE membres SET mdp = '". hashCrypt($membre['mdp']) ."' WHERE id_membre = ".$membre['id_membre'];
        executeRequete($sql);
    }
}
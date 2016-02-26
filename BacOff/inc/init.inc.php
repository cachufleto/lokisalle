<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 25/02/2016
 * Time: 16:45
 */

$_menu = 'navAdmin';

// ajout du css admin
$_linksFiles['Css'][] = REPADMIN . 'css/admin.css';
$_linksFiles['Css'][] = REPADMIN . 'css/' . $nav . '.css';
$_linksFiles['Js'][] = REPADMIN . 'js/' . $nav . '.js';
$_linksFiles['JsFooter'][] = REPADMIN . 'js/' . $nav . '.footer.js';

#########################################################
## reetablire les tables de la base pour DEMO
#########################################################

if(isset($_GET['install']) && $_GET['install'] == 'BDD')
{

    // initialisation des tables
    echo "chargement du fichier lokisalle.sql";
    $sql = file_get_contents(APP.'/SQL/lokisalle.sql');

    if(	isset($_GET['data'])){
        // remplisage des tables
        echo "<br>chargement du fichier data.sql";
        $sql .= file_get_contents(APP.'/SQL/data.sql');
    }

    if(executeMultiRequete($sql)){

        $membres = executeRequete("SELECT id_membre, mdp FROM membres");
        var_dump($membres);
        while($membre = $membres->fetch_assoc()){
            $sql = "UPDATE membres SET mdp = '". hashCrypt($membre['mdp']) ."' WHERE id_membre = ".$membre['id_membre'];
            executeRequete($sql);
        }
    }
    exit();

}

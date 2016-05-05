<?php
//if(utilisateurEstAdmin() && isset($_GET['install']) && $_GET['install'] == 'BDD')
function install()
{
    if (isset($_GET['install']) && $_GET['install'] == 'BDD') {

        // initialisation des tables
        header("refresh:2;url=index.php");
        echo "<p>chargement du fichier shema.sql.....</p>";
        $sql = file_get_contents(INC . '/SQL/shema.sql');

        if (isset($_GET['data'])) {
            // remplisage des tables
            echo "<p>Chargement du fichier data.sql....</p>";
            $sql .= file_get_contents(INC . '/SQL/data.sql');
        }
        // echo "<pre>$sql</pre>";
        if (executeMultiRequete($sql)) {

            $membres = executeRequete("SELECT id_membre, mdp FROM membres");
            while ($membre = $membres->fetch_assoc()) {
                $sql = "UPDATE membres SET mdp = '" . hashCrypt($membre['mdp']) . "' WHERE id_membre = " . $membre['id_membre'];
                executeRequete($sql);
            }
        }
        exit();
    }
}

install();
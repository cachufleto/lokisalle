Espace ADMIN<br>
Trouver une solution pour pointer directement dans me BO<br>
le fichier bacoffice.inc.php peut exister seulement sur l'un des deux repertoires:<br>
/inc<br>
/BocOffice/inc<br>

<?php
// Définit les cookies
setcookie("cookie[three]", "cookiethree");
setcookie("cookie[two]", "cookietwo");
setcookie("cookie[one]", "cookieone");

// Après le rechargemet de la page, nous les affichons
if (isset($_COOKIE['cookie'])) {
    foreach ($_COOKIE['cookie'] as $name => $value) {
        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);        
        echo "$name : $value <br />\n";
    }
}
?>
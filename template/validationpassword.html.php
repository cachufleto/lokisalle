<?php
$message = "<html>" . "\r\n";
$message .= "<head>" . "\r\n";
$message .= "<title> </title>" . "\r\n";
$message .= "</head>" . "\r\n";
$message .= "<body>" . "\r\n";
$message .= "<p>" . $_trad['validerMail'];
$message .= " <a href=\"" . LINK . "?nav=validerIncription&jeton=$key\">";
$message .= $_trad['valide'] . "</a></p>" . "\r\n";
$message .= "</body>" . "\r\n";
$message .= "</html>" . "\r\n";


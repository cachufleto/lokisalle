<?php
function envoiMail($to = 'carlos.paz@free.fr', $subject = 'Lokisalle', $message = 'Bonjour !')
{
	session_start();
	$headers = 'From: webmaster@lokisalle.domoquick.fr' . "\r\n" .
		'Reply-To: carlos.dupriez@gmail.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	//var_dump($_SESSION);
	return mail($to, $subject, $message, $headers);
}

if(envoiMail()) echo 'OK Vous pouvez envoyer des Mails';
else echo '<br><br><p>La function mail n\'est pas opperationelle</p>';

//var_dump($_SESSION);

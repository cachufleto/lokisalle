<?php
function envoiMail($to = 'carlos.paz@free.fr', $subject = 'Lokisalle', $message = 'Bonjour !')
{
	session_start();
	$headers = 'From: webmaster@lokisalle.domoquick.fr' . "\r\n" .
		'Reply-To: carlos.dupriez@gmail.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	var_dump($_SESSION);
	// return mail($to, $subject, $message, $headers);
}

if(envoiMail()) echo 'OK';
else echo '-------------------';

var_dump($_SESSION);

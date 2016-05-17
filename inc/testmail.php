<?php
function testEnvoiMail($to = WEBMAIL, $subject = 'Lokisalle', $message = 'Bonjour !')
{
	session_start();
	$headers = 'From: ' . SITEMAIL . "\r\n" .
		'Reply-To: carlos.dupriez@gmail.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	//var_dump($_SESSION);
	return mail($to, $subject, $message, $headers);
}

if(testEnvoiMail()) echo 'OK Vous pouvez envoyer des Mails';
else echo '<br><br><p>La function mail n\'est pas opperationelle</p>';

//var_dump($_SESSION);

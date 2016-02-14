<?php
# FORMULAIRE D'INSCRIPTION

// Items du formulaire
$_formulaire = array();

$_formulaire['pseudo'] = array(
	'type' => 'text',
	'maxlength' => 14,
	'defaut' => $_trad['pseudo'],
	'obligatoire' => true);
	
$_formulaire['mdp'] = array(
	'type' => 'password',
	'maxlength' => 14,
	'defaut' => $_trad['defaut']['MotPasse'],
	'obligatoire' => true,
	'rectification' => true);

$_formulaire['nom'] = array(
	'type' => 'text',
	'defaut' => $_trad['defaut']['Monnom'],
	'obligatoire' => true);
	
$_formulaire['prenom'] = array(
	'type' => 'text',
	'defaut' => $_trad['defaut']['Monprenom'],
	'obligatoire' => true);
	
$_formulaire['email'] = array(
	'type' => 'email',
	'defaut' => "e.mail@webmail.net",
	'obligatoire' => true,
	'rectification' => true);

$_formulaire['telephone'] = array(
	'type' => 'text',
	'defaut' => "0125845678");

$_formulaire['gsm'] = array(
	'type' => 'text',
	'defaut' => "0660123456",
	'obligatoire' => true);

$_formulaire['sexe'] = array(
	'type' => 'radio',
	'option' => array($_trad['defaut']['Homme']=>'m', $_trad['defaut']['Femme']=>'f'),
	'defaut' => "",
	'obligatoire' => true);

$_formulaire['ville'] = array(
	'type' => 'text',
	'defaut' => "Paris");

$_formulaire['cp'] = array(
	'type' => 'text',
	'defaut' => "75001");

$_formulaire['adresse'] = array(
	'type' => 'textarea',
	'defaut' => $_trad['defaut']["Ouhabite"]);

// ############## SUBMIT ############
$_formulaire['valide'] = array(
	'type' => 'submit',
	'defaut' => $_trad['defaut']["Inscription"]);
	
// traitement du formulaire
$msg = postCheck('_formulaire');

// affichage des messages d'erreur
if('OK' == $msg){
	// on renvoi ver connection
	header('Location:index.php?nav=actif&qui='.$_formulaire['pseudo']['valide'].
		'&mp='.$_formulaire['mdp']['valide'].'');
	exit();
}else{
	// RECUPERATION du formulaire
	$form = '
			<form action="#" method="POST">
			' . afficheForm($_formulaire) . ' 
			</form>';
}
?>
    <principal>
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div>
			<?php  
			// affichage
			echo $msg, $form; 
			?>
		</div>
		<hr />
	</principal>
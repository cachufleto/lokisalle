<?php
# FORMULAIRE D'INSCRIPTION

// Déconnection de l'utilisateur par tentative d'intrusion
if(isset($_SESSION['user'])){
	// destruction de la session
	session_destroy();
	header('Location:index.php');
	exit();
}

// Items du formulaire
$_formulaire = array();

$_formulaire['pseudo'] = array(
	'type' => 'text',
	'maxlength' => 14,
	'defaut' => $_trad["pseudo"]);
	
$_formulaire['mdp'] = array(
	'type' => 'password',
	'maxlength' => 14,
	'defaut' => $_trad['defaut']["MotPasse"]);

// ############## SUBMIT ############
$_formulaire['valide'] = array(
	'type' => 'submit',
	'defaut' => $_trad['defaut']['SeConnecter']);
	
/////////////////////////////////////

// Connection automatique suite au remplissage du formulaire d'inscription
// traitement info get
if(	isset($_GET['qui']) && 	!empty($_GET['qui']) && 
	isset($_GET['mp']) && 	!empty($_GET['mp']))
{
	$_POST['pseudo'] =  $_GET['qui'];
	$_POST['mdp'] =  $_GET['mp'];
	$_POST['valide'] = 'valide';
}

// traitement du formulaire
$msg = postCheck('_formulaire');

// affichage des messages d'erreur
if('OK' == $msg){
	// l'utilisateur est automatiquement connécté
	// et dirigé ver l'accueil
	header('Location:index.php');
	exit();

} else {
// RECUPERATION du formulaire
$form = '
			<form action="#" method="POST">
			' . afficheForm($_formulaire) . ' 
			</form>';

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
<?php } ?>
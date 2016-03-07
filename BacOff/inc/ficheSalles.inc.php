<?php
ficheSalles();

function ficheSalles()
{
	$nav = 'ficheSalles';
	$msg = '';
	$_trad = setTrad();

	include PARAMADM . 'ficheSalles.param.php';

	include FUNC . 'form.func.php';
	if (!isset($_SESSION['user'])) {
		header('Location:index.php');
		exit();
	}

	// extraction des données SQL
	$form = $msg = '';
	if (modCheck($_formulaire, $_id, 'salles')) {

		// traitement POST du formulaire
		if ($_valider){
			$msg = $_trad['erreur']['inconueConnexion'];
			if(postCheck($_formulaire, TRUE)) {
				$msg = ($_POST['valide'] == 'cookie') ? 'cookie' : ficheSallesValider($_formulaire);
			}
		}

		if ('OK' == $msg) {
			// on renvoi ver connection
			$msg = $_trad['lesModificationOntEteEffectues'];
			// on évite d'afficher les info du mot de passe
			unset($_formulaire['mdp']);
			$form = formulaireAfficherInfo($_formulaire);

		} else {

			if (!empty($msg) || $_modifier) {

				$_formulaire['valide']['defaut'] = $_trad['defaut']['MiseAJ'];

				$form = formulaireAfficherMod($_formulaire);

			} elseif (
				!empty($_POST['valide']) &&
				$_POST['valide'] == $_trad['Out'] &&
				$_POST['origin'] != $_trad['defaut']['MiseAJ']
			){
				header('Location:?nav=gestionSalles&pos=P-' . $position . '');
				exit();

			} else {

				unset($_formulaire['mdp']);
				$form = formulaireAfficherInfo($_formulaire);

			}

		}

	} else {

		$form = 'Error 500: ' . $_trad['erreur']['NULL'];

	}

	include TEMPLATE . 'fichesalles.php';
}

# Fonction ficheSallesValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function ficheSallesValider($_formulaire)
{

	global $minLen;
	$_trad = setTrad();

	$msg = 	$erreur = false;
	$sql_set = '';
	// active le controle pour les champs telephone et gsm
	$controlTelephone = true;

	$id_salle = $_formulaire['id_salle']['sql'];

	foreach ($_formulaire as $key => $info){

		$label = $_trad['champ'][$key];
		$valeur = (isset($info['valide']))? $info['valide'] : NULL;

		if('pos' != $key && 'valide' != $key && 'id_salle' != $key && 'photo' != $key && $info['valide'] != $info['sql'])
		{

			if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']) && !empty($valeur))
			{

				$erreur = true;
				$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label.
					': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
					' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

			}

			if ('vide' != testObligatoire($info) && !testObligatoire($info) && empty($valeur)){

				$erreur = true;
				$_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];

			} else {

				switch($key){

					case 'capacite':
						break;
					/*
                                        case 'photo':

                                          $erreur = (controlImageUpload($key, $info))? true : $erreur;
                                          $_formulaire[$key]['message'] = isset($info['message'])? $info['message'] : '' ;
                                          $valeur = $info['valide'];

                                        break;
                    */
					case 'categorie':

						if(empty($valeur))
						{
							$erreur = true;
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
								': '.$_trad['erreur']['vousDevezChoisireUneOption'];
						}

						break;

					default:
						if(!empty($valeur) && !testLongeurChaine($valeur))
						{
							$erreur = true;
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
								': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];

						}

				}
			}
			// Construction de la requettes
			// le mot de passe doit être traité differement

			$sql_set .= ((!empty($sql_set) && !empty($valeur))? ", " : "") . ((!empty($valeur))? "$key = '$valeur'" : '');

		}
	}

	// control sur les numero de telephones
	// au moins un doit être sonseigné
	/*
        if($controlTelephone) {
            $erreur = true;
            $_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
        }
    */
	// si une erreur c'est produite
	if($erreur)
	{
		$msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

	}elseif(!empty($_FILES['photo'])){

		$nomImage  = $_formulaire['pays']['value'];
		$nomImage .= '_' . $_formulaire['ville']['value'];
		$nomImage .= '_' . $_formulaire['titre']['value'];
		$nomImage .= str_replace(' ', '_', $nomImage);

		$erreur = (controlImageUpload('photo', $_formulaire['photo'] , $nomImage))? true : $erreur;
//	  $_formulaire['photo']['message'] = isset($info['message'])? $info['message'] : '' ;
		$valeur = $_formulaire['photo']['valide'];

		if(!$erreur){
			$sql_champs .= ", photo";
			$sql_Value .= ",'$valeur'";
		}

	}elseif(empty($_FILES['photo'])){
		$_formulaire['photo']['valide'] = $_formulaire['photo']['sql'];
	}

	if(!$erreur) {

		// mise à jour de la base des données
		$sql = 'UPDATE salles SET '.$sql_set.'  WHERE id_salle = '.$id_salle;

		if (!empty($sql_set))
			executeRequete ($sql);
		else {
			header('Location:?nav=gestionSalles&pos=P-'.$position.'');
			exit();
		}
		// ouverture d'une session
		$msg = "OK";

	}

	return $msg;
}
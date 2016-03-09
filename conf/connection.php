<?php

////////////////////////////
///// BDD //////////////////
////////////////////////////
$BDD = array();
switch($_SERVER["SERVER_NAME"]){

	case 'lokisalle.domoquick.fr':
		$BDD['SERVEUR_BDD'] = 'rdbms';
		$BDD['USER'] = 'U2407285';
		$BDD['PASS'] = '20Seajar!';
		$BDD['BDD'] = 'DB2407285';
		break;

	default:
		$BDD['SERVEUR_BDD'] = 'localhost';
		$BDD['USER'] = 'root';
		$BDD['PASS'] = '';
		$BDD['BDD'] = 'lokisalle';
}

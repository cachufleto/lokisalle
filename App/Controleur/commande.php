<?php
include_once FUNC . 'commande.func.php';
include_once MODEL . 'commande.php';
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 20/06/2016
 * Time: 01:02
 */
function commande()
{
    $nav = 'commande';
    $_trad = setTrad();
    include_once VUE . 'commande/commande.tpl.php';
}

function facture()
{

    $_trad = setTrad();
    $facture = generationProduitsFacture();

    $id = setReservations();
    $date_facturation = date('Y-m-d H:i:s');
    foreach($facture as $key=>$commande){
        $commande['id_reservation'] = $id;
        $commande['date_facturation'] = $date_facturation;
        $commande['prix_ttc'] = ($commande['prix'] - $commande['reduction'] ) * (1 + TVA);
        setComandes($commande);
    }
    unset($_SESSION['panier']);
    header('refresh:2;url=index.php');
    echo "<h4>{$_trad['factureOk']}</h4>";
}


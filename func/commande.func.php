<?php

/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 20/06/2016
 * Time: 22:51
 */
function listeProduitsFacture()
{
    $listePrix = [];
    if(isset($_SESSION['panier']) && !empty($_SESSION['panier'])){
        $listeOrdenee = sortIndice($_SESSION["panier"]);
        foreach ($listeOrdenee as $key => $date) {
            foreach($_SESSION['panier'][$date] as $id=>$reserv){
                $data = selectSalleId($id);
                $salle = $data->fetch_assoc();
                $listePrix[$date][] = listeProduitsPrixFacture($date, $salle);
            }
        }
    }
    return $listePrix;

}

function listeProduitsCommandes()
{
    $listePrix = [];
    $salles = selectProduitsCommandes();
    $Commandes = $salles->fetch_assoc();

    if(isset($Commandes) && !empty($Commandes)){
        while($Commandes = $salles->fetch_assoc()){
            $listePrix[] = $Commandes;
        }
    }

    return $listePrix;
}

function listeProduitsPrixFacture($date, $data)
{
    $_listeReservation = $_reserve = [];

    $i = $_total = 0;

    if($prix = selectProduitsSalle($data['id_salle'])){
        while($info = $prix->fetch_assoc() ){
            $prixSalle= listeCapacites($data, $info);
            $i++;
            $reservation = (isset($_SESSION['panier'][$date][$data['id_salle']]))?
                $_SESSION['panier'][$date][$data['id_salle']] : [];

            foreach($prixSalle as $key =>$produit){
                if(isset($reservation[$i]) && $reservation[$i] == $key){
                    $_reserve['date'] = $date;
                    $_reserve['titre'] = $data['titre'];
                    $_reserve['libelle'] = $produit['libelle'];
                    $_reserve['num'] = $produit['num'];
                    $_reserve['prix'] = $produit['prix'];
                    $_listeReservation[] = $_reserve;
                }
            }
        }
    }

    return $_listeReservation;
}

function listeProduitsPrixCommandes($date, $data)
{
    $_listeReservation = $_reserve = [];

    $i = $_total = 0;

    if($prix = selectProduitsSalle($data['id_salle'])){
        while($info = $prix->fetch_assoc() ){
            $prixSalle= listeCapacites($data, $info);
            $i++;
            $reservation = (isset($_SESSION['panier'][$date][$data['id_salle']]))?
                $_SESSION['panier'][$date][$data['id_salle']] : [];

            foreach($prixSalle as $key =>$produit){
                if(isset($reservation[$i]) && $reservation[$i] == $key){
                    $_reserve['date'] = $date;
                    $_reserve['titre'] = $data['titre'];
                    $_reserve['libelle'] = $produit['libelle'];
                    $_reserve['num'] = $produit['num'];
                    $_reserve['prix'] = $produit['prix'];
                    $_listeReservation[] = $_reserve;
                }
            }
        }
    }

    return $_listeReservation;
}

function generationProduitsFacture()
{
    $listePrix = [];
    if(isset($_SESSION['panier']) && !empty($_SESSION['panier'])){
        $listeOrdenee = sortIndice($_SESSION["panier"]);
        foreach ($listeOrdenee as $key => $date) {
            foreach($_SESSION['panier'][$date] as $id=>$reserv){
                $data = selectSalleId($id);
                $salle = $data->fetch_assoc();
                $listePrix[$date][] = generationProduitsPrixFacture($date, $salle);
            }
        }
    }

    $_liste = [];
    foreach($listePrix as $date=>$data){
        foreach($data as $key=>$info){
            if(!empty($info)){
                $commande['id_salle'] = $info['plages']['id_salle'];
                $commande['date'] = $date;
                $commande['tranche'] = $info['plages']['id_plagehoraire'];
                $commande['capacitee'] = $info['produit']['num'];
                $commande['prix'] = $info['produit']['prix'];
                $commande['reduction'] = 0;
                $_liste[] = $commande;
            }
        }
    }

    return $_liste;
}

function generationProduitsPrixFacture($date, $data)
{
    $liste = [];
    $i = $_total = 0;

    if($prix = selectProduitsSalle($data['id_salle'])){
        while($info = $prix->fetch_assoc() ){
            $prixSalle= listeCapacites($data, $info);
            $i++;
            $reservation = (isset($_SESSION['panier'][$date][$data['id_salle']]))?
                $_SESSION['panier'][$date][$data['id_salle']] : [];

            foreach($prixSalle as $key =>$produit){
                if(isset($reservation[$i]) && $reservation[$i] == $key){
                    $liste['plages'] = $info;
                    $liste['produit'] = $produit;
                }
            }
        }
    }
    return $liste;
}
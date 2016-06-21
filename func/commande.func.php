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
                $listePrix[$date][] = ['salle'=>$salle, 'reservation'=>listeProduitsPrixFacture($date, $salle)];
            }
        }
    }

    $_liste = '';
    $_total = 0;
    foreach($listePrix as $date=>$data){
        foreach($data as $key=>$info){
            $_liste .= $info['reservation']['reserve'];
            $_total = $_total + $info['reservation']['couts'];
        }
    }

    $TTC = round($_total*(1+TVA),2);
    $TVA = $TTC - $_total;
    return !empty($_total)? $_liste . "
                            <div class='ligne'>
                            <hr>
                                <div class='tronche total'>&nbsp;</div>
                                <div class='personne total'>TOTAL</div>
                                <div class='prix total'>" . number_format ($_total, 2) . "€</div>
                            </div>
                            <div class='ligne'>
                                <div class='tronche total'>&nbsp;</div>
                                <div class='personne total'>TVA 20%</div>
                                <div class='prix total'>" . number_format ($TVA, 2) . "€</div>
                            </div>
                            <div class='ligne'>
                                <div class='tronche total'>&nbsp;</div>
                                <div class='personne total'>TTC</div>
                                <div class='prix total'>" . number_format ($TTC, 2) . "€</div>
                            </div>
                            <div class='ligne'>
                            <hr>
                                <div class='valider'>
                                <form name='commande' method='POST' action='?nav=validerFacture'>
                                    <a href='?nav=validerFacture'>
                                        <input type='submit name='facture' value='FACTURATION'>
                                </form>
                                </div>
                            </div>
                            " : "";
}

function listeProduitsPrixFacture($date, $data)
{
    $_listeReservation = '';
    $i = $_total = 0;

    if($prix = selectProduitsSalle($data['id_salle'])){
        while($info = $prix->fetch_assoc() ){
            $prixSalle= listeCapacites($data, $info);
            $i++;
            $reservation = (isset($_SESSION['panier'][$date][$data['id_salle']]))?
                $_SESSION['panier'][$date][$data['id_salle']] : [];

            foreach($prixSalle as $key =>$produit){
                if(isset($reservation[$i]) && $reservation[$i] == $key){
                    $_listeReservation .= "<div class='tronche'>$date : {$data['titre']} :</div>
                                            <div class='personne'>{$produit['libelle']} {$produit['num']} pers.</div>
                                            <div class='prix'>{$produit['prix']}€</div>";
                    $_total = $_total +  $produit['valeur'];
                }
            }
        }
    }

    $reserve = ($_total)? $_listeReservation : "";
    return ['reserve'=>$reserve, 'couts'=>$_total];
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
                $commande['prix'] = $info['produit']['valeur'];
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
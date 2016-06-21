<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 20/06/2016
 * Time: 01:14
 */
function selectSalleId($_id)
{
    $sql = "SELECT * FROM salles WHERE id_salle = ". $_id .
        ( !isSuperAdmin()? " AND active != 0" : "" ) .
        recherchePernonnes();
    return executeRequete($sql);
}

function selectProduitsSalle($id)
{
    $sql = "SELECT p.*, h.description
            FROM produits p, plagehoraires h
            WHERE id_salle = $id
              AND p.id_plagehoraire = h.id
            ORDER BY id_plagehoraire ASC";
    return executeRequete($sql);
}

function setReservations()
{
    $sql = "INSERT INTO `reservations` (`id`, `id_membre`, `date_facturacion`) VALUES (NULL, '{$_SESSION['user']['id']}', CURRENT_TIMESTAMP)";
    return executeRequeteInsert($sql);
}

function setComandes($commande)
{
    $sql = "INSERT INTO `commandes` (`id`, `id_reservation`, `id_salle`, `date_facturacion`, `date_reserve`,
                                      `tranche`, `capacitee`, `prix`, `reduction`, `prix_ttc`)
                              VALUES (NULL, '{$commande['id_reservation']}', '{$commande['id_salle']}',
                                      '{$commande['date_facturation']}', '{$commande['date']}',
                                      '{$commande['tranche']}', '{$commande['capacitee']}', '{$commande['prix']}',
                                      '{$commande['reduction']}', '{$commande['prix_ttc']}');";
    return executeRequeteInsert($sql);
}
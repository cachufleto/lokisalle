<?php
function salles($_trad, $titre, $msg)
{
  //
    if (isset($_GET)) {
      if (!empty($_GET['reserver'])) {
        $_SESSION['panier'][$_GET['reserver']] = true;
      } elseif (!empty($_GET['enlever'])) {
        $_SESSION['panier'][$_GET['enlever']] = false;
      }
    }

  // selection de tout les users sauffe le super-ADMIN
    $sql = "SELECT id_salle, titre, capacite, categorie, photo, active
            FROM salles " . (!isSuperAdmin()? " WHERE active != 0 " : "") . "
            ORDER BY cp, titre";
    $membres = executeRequete($sql);
    $table = '';

    $table .= '<tr><th>' . $_trad['champ']['id_salle'] . '</th>
            <th>' . $_trad['champ']['titre'] . '</th>
            <th>' . $_trad['champ']['capacite'] . '</th>
            <th>' . $_trad['champ']['categorie'] . '</th>
            <th>' . $_trad['champ']['photo'] . '</th>';
    $table .= '<th>' . $_trad['select'];

    $table .= '</th></tr>';

    $position = 1;
    while ($data = $membres->fetch_assoc()) {

      $table .= '
      <tr><td>' . $data['id_salle'] . '</td><td>' . $data['titre'] . '</td><td>' . $data['capacite'] . '</td><td>' .
          $_trad['value'][$data['categorie']] . '</td><td><a href="' . LINK . '?nav=ficheSalles&id=' . $data['id_salle'] .
          '&pos=' . $position . '" id="P-' . $position . '" ><img class="trombi" src="' . LINK . 'photo/' . $data['photo'] .
          '" ></a></td><td>';

      $table .= (isset($_SESSION['panier'][$data['id_salle']]) && $_SESSION['panier'][$data['id_salle']] === true) ?
          '<a href="' . LINK . '?nav=salles&enlever=' . $data['id_salle'] . '#P-' . $position . '" >' . $_trad['enlever'] . '</a>' :
          ' <a href="' . LINK . '?nav=salles&reserver=' . $data['id_salle'] . '#P-' . $position . '">' . $_trad['reserver'] . '</a>';

      $table .= '</td></tr>';
      $position++;
    }

    $table = '<table>' . $table . '
  </table>';

  include TEMPLATE . 'salles.php';
}

salles($_trad, $titre, $msg);
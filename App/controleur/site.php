<?php
include_once MODEL . 'site.php';
include_once FUNC . 'site.func.php';

function home()
{

    $nav = 'home';
    $_trad = setTrad();

    $salles = selectSallesActive();

    $dernieresOffres = '<div>';
    while($salle = $salles->fetch_assoc()){
        $dernieresOffres .= dernieresOffres($salle);
    }
    $dernieresOffres .= '</div>';

    include VUE . 'site/home.tpl.php';
}

function dernieresOffres($salle)
{
    $_trad = setTrad();

    $offre = '
	<div class="offre">
	<div>' . $salle['titre'] . '</div>
  	<figure>
	  <img class="ingOffre" src="' . LINK . 'photo/' . $salle['photo'] . '" alt="" />
  		<figcaption>Légende associée</figcaption>
	</figure>
  	<div>' . $salle['capacite'] . ' / ' . $_trad['value'][$salle['categorie']] .'</div>
  	<hr/>
	</div>
	';

    return $offre;
}

function backoffice()
{
    $nav = 'backoffice';
    $_trad = setTrad();


    $activite = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' : 'Activité';
    $dernieresOffres = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' :  'Derniéres Offres';

    include VUE . 'site/backoffice.tpl.php';

}

function contact()
{
    $_trad = setTrad();
    $listConctact = array();

    $membres = userSelectContactAll();

    if ($membres->num_rows > 0) {
        while ($membre = $membres->fetch_assoc()) {
            $listConctact[] = ficheContactTemplate($membre);
        }
    }

    include VUE . 'site/contact.html.php';
}

function mentions()
{
    include VUE . 'site/mentions.tpl.php';
}

function cgv()
{
    include VUE . 'site/activite.xhtml';
}

function erreur404($nav)
{
    $_trad = setTrad();

    $msg = ($nav=='erreur404')?
        $_trad['erreur']['erreur404'] :
        $_trad['enConstruccion'];

    include VUE . 'site/erreur404.tpl.php';
}



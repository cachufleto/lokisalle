<?php
$_trad = setTrad();
$href = imageExiste($salle['photo']);
$titre = strtoupper($salle['titre']);
$lien = LINK . "?nav=salles&pos=$position";
$active = "";
$reserver = 'reserver';
$modifier = '';
$date = disponibilite();

if(isset($_SESSION['panier'][$salle['id_salle']])){
    $active = "active";
    $reserver = 'enlever';
    $modifier = '<input type="submit" name="reserver" value="'.$_trad['modifier'].'">';
}


echo <<<EOL
 <div class="ligne">
    <h1>{$_trad['titre']['ficheSalles']}</h1>
</div>
<div class="ligne">
    <form name="" method="POST" action="?nav=ficheSalles&id={$salle['id_salle']}&pos=$position">
    <div id="fiche" class="salle">
        <div class="ligne">
            <div class="ville">{$salle['ville']} ({$salle['pays']})</div>
            <div>{$msg}</div>
        </div>
        <div class="ligne">
            <div class="photo">
                <div><img src="$href"></div>
                $date
               {$salle['produits']['tableau']}
            </div>
            <div class="info">
                <div class="titre">$titre</div>
                <div class="fiche">{$salle['adresse']}<br>
                    {$salle['cp']} {$salle['ville']}<br>
                    {$salle['telephone']}<br>
                    {$salle['gsm']}
                </div>
                    <input type="hidden" name="id" value="{$salle['id_salle']}">
                    <input type="hidden" name="pos" value="$position">
                    <div class="categorie">
                        Cat. {$_trad['value'][$salle['categorie']]} :: {$salle['capacite']}{$_trad['personnes']}
                    </div>
                    <div>
                        {$salle['description']}
                    </div>
                    <div class="reserve">
                        {$_trad['votreReservation']}
                        {$salle['produits']['reserve']}
                    </div>
            </div>
        </div>
        <div class="ligne description">
             <div class="ligne">
            </div>
            <div class="reserver $active">
                <input type="submit" name="$reserver" value="{$_trad[$reserver]}">
                $modifier
            </div>
            <div class="reserver lien">
                <a href="$lien"><button type="button">{$_trad['revenir']}</button></a> :
                <a href="?nav=reservation"><button type="button">{$_trad['nav']['reservation']}</button></a>
            </div>
        </div>
    </div>
    </form>
</div>
$alert
EOL;

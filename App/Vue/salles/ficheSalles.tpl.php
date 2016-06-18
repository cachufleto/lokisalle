<?php
$_trad = setTrad();
$href = imageExiste($salle['photo']);
$titre = strtoupper($salle['titre']);
$lien = LINK . "?nav=salles&pos=$position";
$active = "";
$reserver = 'reserver';

if(isset($_SESSION['panier'][$salle['id_salle']])){
    $active = "active";
    $reserver = 'enlever';
}

echo <<<EOL
 <div class="ligne">
    <h1>{$_trad['titre']['ficheSalles']}</h1>
</div>
<div class="ligne">
    <div id="fiche" class="salle">
        <div class="ligne">
            <div class="ville">{$salle['ville']} ({$salle['pays']})</div>
            <div>{$msg}</div>
        </div>
        <div class="ligne">
            <div class="photo">
                <div><img src="$href"></div>
            </div>
            <div class="info">
                <div class="titre">$titre</div>
                <div class="fiche">{$salle['adresse']}<br>
                    {$salle['cp']} {$salle['ville']}<br>
                    {$salle['telephone']}<br>
                    {$salle['gsm']}
                </div>
                <form name="" method="POST" action="?nav=ficheSalles&id={$salle['id_salle']}&pos=$position">
                    <input type="hidden" name="id" value="{$salle['id_salle']}">
                    <input type="hidden" name="pos" value="$position">
                    <div class="categorie">
                        Cat. {$_trad['value'][$salle['categorie']]} :: {$salle['capacite']}{$_trad['personnes']}
                        {$salle['produits']}
                    </div>
                    <div class="reserver $active">
                        <input type="submit" name="$reserver" value="{$_trad[$reserver]}">
                    </div>
                    <div class="reserver lien">
                        <a href="$lien">{$_trad['revenir']}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="ligne description">
            <div>
                {$salle['description']}
            </div>
        </div>
    </div>
</div>
$alert
EOL;

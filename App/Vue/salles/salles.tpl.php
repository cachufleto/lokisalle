<?php
$lien = LINK . '?nav=' . $nav;

echo <<<EOL
<div class="ligne">
    <h1>{$_trad['nav'][$nav]}</h1>
</div>
<div class="ligne">
    <p>{$msg}</p>
    <div class="trier"><div>Trier par: </div>
            <div>
                <form action="$lien" method="POST">
                    <input type="hidden" name="ord" value="id_salle">
                    <input type="submit" name="" value="REF">
                </form>
            </div><div>
                <form action="$lien" method="POST">
                    <input type="hidden" name="ord" value="titre">
                    <input type="submit" name="" value="{$_trad['champ']['titre']}">
                </form>
            </div><div>
                <form action="$lien" method="POST">
                    <input type="hidden" name="ord" value="capacite">
                    <input type="submit" name="" value="{$_trad['champ']['capacite']}">
                </form>
            </div><div>
                <form action="$lien" method="POST">
                    <input type="hidden" name="ord" value="categorie">
                    <input type="submit" name="" value="{$_trad['champ']['categorie']}">
                </form>
            </div>
        <div>&nbsp;&nbsp;</div>
    </div>
</div>
<div class="ligne">
EOL;

    if(!empty($table['info'])){
        foreach($table['info'] as $ligne=>$salle){
            $class = ($ligne%2 == 1)? 'lng1':'lng2' ;
            $nom = strtoupper($salle['nom']);
            $active = isset($_SESSION['panier'][$salle['ref']])? "active" : "";

            echo <<<EOL
            <div class="quart">
                {$salle['position']}
                <h3>$nom</h3>
                <div class="quart_photo">
                    {$salle['photo']}
                </div>
                <div class="ligne">

                        <h4 class="in_catalogue">{$salle['categorie']}</h4>
                        <p>Jusqu'à {$salle['capacite']} personnes<br>
                            REF:{$salle['ref']}
                        </p>
                </div>
                <div class="ligne">
                </div>
                <div class="reserver $active">{$salle['reservation']}</div>
            </div>
EOL;
        }
    }
echo '</div>' , $alert;
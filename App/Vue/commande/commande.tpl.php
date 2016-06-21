<?php
$lien = LINK . '?nav=' . $nav;
$disponibilite = disponibilite();
echo <<<EOL
<div class="ligne">
    <h1>{$_trad['nav'][$nav]}</h1>
</div>
<div class="ligne">
    <div class="facture">
EOL;
    echo listeProduitsFacture();

    echo <<<EOL
    </div>
</div>
EOL;

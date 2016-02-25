<?php
# FORMULAIRE D'INSCRIPTION
# FUNCTIONS formulaires
include_once FUNC . 'form.func.php';

// RECUPERATION du formulaire
    $form = '
			<form action="#" method="POST">
			' . formulaireAfficher($_formulaire) . '
			</form>';
?>

<principal clas="<?php echo $nav; ?>">
    <h1><?php echo $titre; ?></h1>
    <hr />
    <div id="formulaire">
        <?php
        // affichage
        echo $msg;
        echo $form;
        ?>
        <hr />
    </div>
</principal>

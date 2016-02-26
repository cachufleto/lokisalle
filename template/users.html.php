<div class="">
    <div class="">
        <h1><?php echo $titre; ?></h1>
        <hr />
    </div>
    <div class="">
        <?php echo $msg ?>
        <table>
            <tr>
                <th><?php echo $_trad['champ']['pseudo'] ?></th>
                <th><?php echo $_trad['champ']['nom'] ?></th>
                <th><?php echo $_trad['champ']['prenom'] ?></th>
                <th><?php echo $_trad['champ']['email'] ?></th>
                <th><?php echo $_trad['champ']['statut'] ?></th>
                <th><?php echo $_trad['champ']['active'] ?></th>
                </tr>
            <?php echo $table; ?>
        </table>";
        <hr />
    </div>
</div>

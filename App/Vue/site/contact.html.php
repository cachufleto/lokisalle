<principal id="contact">
    <h1><?php echo $_trad['titre']['contact']; ?></h1>
    <hr />
    <div class="contact">
        <?php
        foreach($listConctact as $fiche){
            echo $fiche;
        }
        ?>
    </div>
    <hr />
</principal>
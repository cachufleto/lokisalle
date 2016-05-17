<principal clas="changermotpasse">
    <h1><?php echo $_trad['titre']['validerMDP']; ?></h1>
    <hr />
    <div id="formulaire">
        <?php if($msg == 'OK'){
            echo $_trad['priseEnCompteMDP'];
        } else {
            echo $msg; ?>
        <form action="#" method="POST">
            <?php echo $form; ?>
        </form>
        <?php } ?>
        <hr />
    </div>
</principal>

<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 11/06/2016
 * Time: 16:22
 */
echo __FILE__;
$_trad['value']['matinee'] = "Matinee";
$_trad['value']['journee'] = "Journee";
$_trad['value']['soiree'] = "Soiree";
$_trad['value']['nocturne'] = "Nocturne";
var_dump($table);
?>
<style>
    .ligne{

    }
    .ligne .option{
        float: left;
        background-color: #55787D;
        padding: 10px;
        color: #E7E5E5;
        width: 150px;
    }
</style>

<div class="ligne">
    <div class="option"><?php echo $_trad['value']['matinee']; ?></div>
    <div class="option"><?php echo $_trad['value']['journee']; ?></div>
    <div class="option"><?php echo $_trad['value']['soiree']; ?></div>
    <div class="option"><?php echo $_trad['value']['nocturne']; ?></div>
</div>

<div class="ligne">
    <div class="option">T1</div>
    <div class="option">T2</div>
    <div class="option">T3</div>
</div>


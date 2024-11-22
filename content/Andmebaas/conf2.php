<?php
//zone kasutaja jaoks conf fail
$kasutaja="d132030_daria";
$parool="Jessica809205";
$andmebaas="d132030_bassphp";
$serverinimi="d132030.mysql.zonevs.eu";

$yhendus=new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);
$yhendus->set_charset("utf8");



<?php
$kasutaja="daria";
$parool="123456";
$andmebaas="daria";
$serverinimi="localhost";

$yhendus=new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);
$yhendus->set_charset("utf8");



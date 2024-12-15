<?php
if (isset($_POST["submit"]))
{
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once 'functions.inc.php';
    require_once '../content/Andmebaas/konkurss/conf2.php';
    global $yhendus;

    if(emptyInputLogin($username, $pwd) )
    {
        header("location: ../content/Andmebaas/konkurss/Sisselogimisvorm/login.php?error=emptyinput");
        exit();
    }

    loginUser($yhendus, $username, $pwd);
}
else
{
    header("location: ../content/Andmebaas/konkurss/Sisselogimisvorm/login.php");
    exit();
}
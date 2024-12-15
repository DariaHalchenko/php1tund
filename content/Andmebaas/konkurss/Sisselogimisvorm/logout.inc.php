<?php
session_start();
session_unset();
session_destroy();
header("location: ../content/Andmebaas/konkurss/Sisselogimisvorm/login.php");
exit();
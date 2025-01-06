<?php
//session_start();
require ('conf.php');
global $yhendus;
//if (!isset($_SESSION['rolli'])) {
 //  echo "Kasutaja pole sisse logitud";
 //  exit();
//}
// Lõpetatud
if(isset($_REQUEST["lopetatud_id"])) {
    $paring = $yhendus->prepare("Update lend SET lopetatud=0 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["lopetatud_id"]);
    $paring->execute();
}
// Aktiivne
if(isset($_REQUEST["aktiivne_id"])) {
    $paring = $yhendus->prepare("Update lend SET lopetatud=1 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["aktiivne_id"]);
    $paring->execute();
}
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM lend WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//tabeli andmete lisamine
if(isset($_REQUEST["lennu_nr"]) && !empty($_REQUEST["lennu_nr"])){
    global $yhendus;
    $paring=$yhendus->prepare("INSERT INTO lend(lennu_nr, kohtade_arv, ots, siht, siht_pilt, valjumisaeg)
VALUES (?, ?, ?, ?, ?, ?)");
    //i- integer, s- string
    $paring->bind_param("ssssss", $_REQUEST["lennu_nr"], $_REQUEST["kohtade_arv"], $_REQUEST["ots"], $_REQUEST["siht"],
        $_REQUEST["siht_pilt"], $_REQUEST["valjumisaeg"]);
    $paring->execute();
}
//tabeli sisu kuvamine
$paring=$yhendus->prepare("SELECT id, lennu_nr, kohtade_arv, ots, siht, siht_pilt, valjumisaeg, lopetatud  FROM lend");
$paring->bind_result($id, $lennu_nr, $kohtade_arv, $ots, $siht, $siht_pilt, $valjumisaeg, $lopetatud);
$paring->execute();
?>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
    <title>Lennujaam</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Lennujaam</h1>
</header>
<nav>
    <ul>
        <?php
        if (isset($_SESSION['useruid']) && isset($_SESSION['rolli'])) {
            if ($_SESSION['rolli'] == 1) {
                echo '<li><a href="lendude_lisamiseks.php">Lennujaam</a></li>';
                echo '<li><a href="reisijate_lisandumine.php">Reisijad</a></li>';
            } else if ($_SESSION['rolli'] == 0) {
                echo '<li><a href="koiki_lopetatud.php">Tavakasutaja</a></li>';
            }
            echo '<li><a href="Sisselogimisvorm/logout.inc.php">Logi välja (' . htmlspecialchars($_SESSION['useruid']) . ')</a></li>';
        } else {
            echo '<li><a href="login.php">Sisse loogimine</a></li>';
            echo '<li><a href="signup.php">Registreerimine</a></li>';
        }
        ?>
    </ul>
</nav>
<table  border="2">
    <tr>
        <th></th>
        <th>Id</th>
        <th>Lennu_nr</th>
        <th>Kohtade_arv</th>
        <th>Ots</th>
        <th>Siht</th>
        <th>Siht pilt</th>
        <th>Väljumisaeg</th>
        <th colspan="2">Staatus</th>
    </tr>
    <?php

    while($paring->fetch()) {
        echo "<tr>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        echo "<td>$id</td>";
        echo "<td>$lennu_nr</td>";
        echo "<td>$kohtade_arv</td>";
        echo "<td>$ots</td>";
        echo "<td>$siht</td>";
        echo "<td><img src='$siht_pilt' alt='pilt' width='100px'></td>";
        echo "<td>$valjumisaeg</td>";
        //ava
        $avamistekst="Ava";
        $avamisparam="aktiivne_id";
        $avamisseisund="Lõpetatud";
        if($lopetatud===1){
            $avamistekst="Lõpetatud";
            $avamisparam="lopetatud_id";
            $avamisseisund="Aktiivne";
        }
        echo "<td><a href='?$avamisparam=$id'>$avamistekst</a></td>";
        echo "<td>$avamisseisund</td>";
        echo "</tr>";
    }
        ?>
</table>
<table>
    <h2>Uue lennu lisamine</h2>
    <!--tabeli lisamisVorm-->
    <form action="?" method="post">
        <label for="lennu_nr">Lennu_nr</label>
        <input type="text" id="lennu_nr" name="lennu_nr">
        <br>
        <label for="kohtade_arv">Kohtade_arv</label>
        <input type="number" id="kohtade_arv" name="kohtade_arv">
        <br>
        <label for="ots">Ots</label>
        <input type="text" id="ots" name="ots">
        <br>
        <label for="siht">Siht</label>
        <input type="text" id="siht" name="siht">
        <br>
        <label for="siht_pilt">Pilt</label><br>
        <textarea id="siht_pilt" name="siht_pilt" cols="30" rows="10">Sisesta pildi link</textarea><br>
        <br>
        <label for="valjumisaeg">Väljumisaeg</label>
        <input type="datetime-local" id="valjumisaeg" name="valjumisaeg">
        <br>
        <input type="submit" value="OK">
    </form>
</table>
</body>
</html>
<?php
$yhendus->close();
?>
<footer>
    <?php
    echo "Daria Halchenko &copy;";
    echo date('Y');
    ?>
</footer>

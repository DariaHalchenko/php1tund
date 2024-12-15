<?php
session_start();
require ('conf.php');
global $yhendus;
// laulu peitmine
if(isset($_REQUEST["peitmine_id"])) {
    $paring = $yhendus->prepare("Update konkurss SET avalik=0 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["peitmine_id"]);
    $paring->execute();
}
// laulu kuvamine/näitmine
if(isset($_REQUEST["naitmine_id"])) {
    $paring = $yhendus->prepare("Update konkurss SET avalik=1 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["naitmine_id"]);
    $paring->execute();
}
//konkurssi lisamine
if(!empty($_REQUEST["uusKonkurss"])){
    $paring=$yhendus->prepare("INSERT INTO konkurss (konkursiNimi, lisamisaeg) 
VALUES (?, NOW())");
    $paring->bind_param("s", $_REQUEST["uusKonkurss"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM konkurss WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//kustutamine komment
if(isset($_REQUEST["kustutakomment"])){
    $kask=$yhendus->prepare("UPDATE konkurss SET kommentaarid='' WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustutakomment"]);
    $kask->execute();
}
// tabeli uuendamine 0 punktid
if(isset($_REQUEST["nullidakonkurss_id"])) {
    $paring = $yhendus->prepare("Update konkurss SET punktid=0 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["nullidakonkurss_id"]);
    $paring->execute();
}
?>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
    <title>TARpv23 jõulu konkursid</title>
    <link rel="stylesheet" href="konkurssStyle.css">
</head>
<body>
<header>
    <h1>TARpv23 jõulu konkursid</h1>
</header>
<nav>
    <ul>
        <?php
        if (isset($_SESSION['useruid'])) {
            echo '<li><a href="KonkurssAdmin.php">Admin</a></li>';
        }
        ?>
        <li><a href="KonkurssKasutaja.php">Kasutaja</a></li>
        <li><a href="konkurss1kaupa.php">Konkurss 1 kaupa</a></li>
        <?php
        if (!isset($_SESSION['useruid'])) {
            echo '<li><a href="login.php">Sisse loogimine</a></li>';
            echo '<li><a href="signup.php">Registreerimine</a></li>';
        }
        else {
            echo '<li><a href="Sisselogimisvorm/logout.inc.php">Logi välja</a></li>';
        }
        ?>
    </ul>
</nav>
<form action="?">
    <label for="uusKonkurss">Lisa konkurssi nimi</label>
    <input type="text" name="uusKonkurss" id="uusKonkurss">
    <input type="submit" value="OK">
</form>
<br><br><br><br>
<table border="1">
    <tr>
        <th>KonkursiNimi</th>
        <th>Lisamisaeg</th>
        <th>Punktid</th>
        <th>Avalik</th>
        <th colspan="2">Kommentaarid</th>
        <th colspan="4">Haldus</th>
    </tr>
    <?php
    //tabeli sisu kuvamine
    $paring=$yhendus->prepare("SELECT id, konkursiNimi, lisamisaeg, punktid, kommentaarid, avalik FROM konkurss");
    $paring->bind_result($id, $konkurssnimi, $lisamisaeg, $punktid, $kommentaarid, $avalik);
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        $konkurssnimi = htmlspecialchars($konkurssnimi);
        $kommentaarid = nl2br(htmlspecialchars($kommentaarid));
        echo "<td>$konkurssnimi</td>";
        echo "<td>$lisamisaeg</td>";
        echo "<td>$punktid</td>";
        echo "<td>$avalik</td>";
        echo "<td>$kommentaarid</td>";
        ?>
        <td>
            <form action="?">
                <input type="hidden" name="kustutakomment" value="<?=$id?>">
                <input type="submit" value="Kustuta kommentaar">
            </form>
        </td>
        <?php
        echo "<td><a href='?nullidakonkurss_id=$id' class='link-button'>Nullida</a></td>";
        echo "<td><a href='?kustuta=$id' class='link-button'>Kustuta</a></td>";
        //ava/peida nupud
        $avamistekst="Ava";
        $avamisparam="naitmine_id";
        $avamisseisund="Peidetud";
        if($avalik===1){
            $avamistekst="Peida";
            $avamisparam="peitmine_id";
            $avamisseisund="Näidetud";
        }
        echo "<td><a href='?$avamisparam=$id'>$avamistekst</a></td>";
        echo "<td>$avamisseisund</td>";
        echo "</tr>";
    }
    ?>
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

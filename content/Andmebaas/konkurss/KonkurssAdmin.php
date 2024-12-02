<?php
require ('conf.php');
global $yhendus;
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
// 	avalik = 1 - näita
if(isset($_REQUEST["näita"])) {
    $paring = $yhendus->prepare("Update konkurss SET avalik=1 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["näita"]);
    $paring->execute();
}
// 	avalik = 0 - peida
if(isset($_REQUEST["peida"])) {
    $paring = $yhendus->prepare("Update konkurss SET avalik=0 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["peida"]);
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
<h1>TARpv23 jõulu konkursid</h1>
<nav>
    <ul>
        <li><a href="KonkurssAdmin.php">Admin</a></li>
        <li><a href="KonkurssKasutaja.php">Kasutaja</a></li>
    </ul>
</nav>
<form action="?">
    <label for="uusKonkurss">Lisa konkurssi nimi</label>
    <input type="text" name="uusKonkurss" id="uusKonkurss">
    <input type="submit" value="OK">
</form>
<br><br><br>
<table border="1">
    <tr>
        <th>KonkursiNimi</th>
        <th>Lisamisaeg</th>
        <th>Punktid</th>
        <th>Avalik</th>
        <th colspan="2">Kommentaarid</th>
        <th colspan="3">Haldus</th>
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
        echo "<td><a href='?nullidakonkurss_id=$id'>Nullida</a></td>";
        echo "<td><a href='?näita=$id'>Näita</a></td>";
        echo "<td><a href='?peida=$id'>Peida</a></td>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
<?php
$yhendus->close();

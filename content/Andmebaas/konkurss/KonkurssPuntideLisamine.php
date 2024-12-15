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
//kommeentaaride lisamine
if(isset($_REQUEST["uusKomment"])){
    $paring=$yhendus->prepare("UPDATE konkurss SET kommentaarid=CONCAT(kommentaarid, ?) WHERE id=?");
    $kommentLisa="\n".$_REQUEST["komment"];
    $paring->bind_param("si", $kommentLisa, $_REQUEST["uusKomment"]);
    $paring->execute();
}
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM konkurss WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
// tabeli uuendamine +1 punktid
if(isset($_REQUEST["heakonkurss_id"])) {
    $paring = $yhendus->prepare("Update konkurss SET punktid=punktid+1 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["heakonkurss_id"]);
    $paring->execute();
}
// tabeli uuendamine -1 punktid
if(isset($_REQUEST["halvastikonkurss_id"])) {
    $paring = $yhendus->prepare("Update konkurss SET punktid=punktid-1 WHERE id=?");
    $paring->bind_param('i', $_REQUEST["halvastikonkurss_id"]);
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
        <th colspan="2">Kommentaarid</th>
        <th colspan="3">Haldus</th>
    </tr>
    <?php
    //tabeli sisu kuvamine
    $paring=$yhendus->prepare("SELECT id, konkursiNimi, lisamisaeg, punktid, kommentaarid FROM konkurss");
    $paring->bind_result($id, $konkurssnimi, $lisamisaeg, $punktid, $kommentaarid);
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        $konkurssnimi = htmlspecialchars($konkurssnimi);
        $kommentaarid = nl2br(htmlspecialchars($kommentaarid));
        echo "<td>$konkurssnimi</td>";
        echo "<td>$lisamisaeg</td>";
        echo "<td>$punktid</td>";
        echo "<td>$kommentaarid</td>";
        ?>
        <td>
            <form action="?">
                <input type="hidden" name="uusKomment" value="<?=$id?>">
                <input type="text" name="komment" id="komment">
                <input type="submit" value="Lisa kommentaar">
            </form>
        </td>
        <?php
        echo "<td><a href='?heakonkurss_id=$id' class='link-button'>Lisa +1 punkt</a></td>";
        echo "<td><a href='?halvastikonkurss_id=$id' class='link-button'>-1 punkt</a></td>";
        echo "<td><a href='?kustuta=$id' class='link-button'>Kustuta</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
<?php
$yhendus->close();

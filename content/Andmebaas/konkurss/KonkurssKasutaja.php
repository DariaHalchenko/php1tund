<?php
session_start();
require ('conf.php');
global $yhendus;
if (!isset($_SESSION['rolli'])) {
    echo "Kasutaja pole sisse logitud";
    exit();
}
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
<header>
    <h1>TARpv23 jõulu konkursid</h1>
</header>
<nav>
    <ul>
        <?php
        if (isset($_SESSION['useruid']) && isset($_SESSION['rolli'])) {
            if ($_SESSION['rolli'] == 1) {
                echo '<li><a href="KonkurssAdmin.php">Admin</a></li>';
            } else if ($_SESSION['rolli'] == 0) {
                echo '<li><a href="KonkurssKasutaja.php">Kasutaja</a></li>';
                echo '<li><a href="konkurss1kaupa.php">Konkurss 1 kaupa</a></li>';
            }
            echo '<li><a href="Sisselogimisvorm/logout.inc.php">Logi välja (' . htmlspecialchars($_SESSION['useruid']) . ')</a></li>';
        } else {
            echo '<li><a href="login.php">Sisse loogimine</a></li>';
            echo '<li><a href="signup.php">Registreerimine</a></li>';
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
        <th colspan="2">Kommentaarid</th>
        <th colspan="2">Haldus</th>
    </tr>
    <?php
    //tabeli sisu kuvamine
    $paring = $yhendus->prepare("SELECT id, konkursiNimi, lisamisaeg, punktid, kommentaarid, avalik FROM konkurss WHERE avalik = 1");
    $paring->bind_result($id, $konkurssnimi, $lisamisaeg, $punktid, $kommentaarid, $avalik);
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

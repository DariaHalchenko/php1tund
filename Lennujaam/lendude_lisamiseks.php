<?php
session_start();
require ('conf.php');
global $yhendus;
if (!isset($_SESSION['rolli'])) {
    echo "Kasutaja pole sisse logitud";
    exit();
}
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
    $paring=$yhendus->prepare("INSERT INTO lend(lennu_nr, kohtade_arv, ots, siht, siht_pilt, valjumisaeg, kestvus)
VALUES (?, ?, ?, ?, ?, ?, ?)");
    //i- integer, s- string
    $paring->bind_param("ssssss", $_REQUEST["lennu_nr"], $_REQUEST["kohtade_arv"], $_REQUEST["ots"], $_REQUEST["siht"],
        $_REQUEST["siht_pilt"], $_REQUEST["valjumisaeg"], $_REQUEST["kestvus"]);
    $paring->execute();
}
//tabeli sisu kuvamine
$paring=$yhendus->prepare("SELECT id, lennu_nr, kohtade_arv, ots, siht, siht_pilt, valjumisaeg, lopetatud, kestvus  FROM lend");
$paring->bind_result($id, $lennu_nr, $kohtade_arv, $ots, $siht, $siht_pilt, $valjumisaeg, $lopetatud, $kestvus);
$paring->execute();
?>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
    <title>Lennujaam</title>
    <link rel="stylesheet" href="LennujaamStyle.css">
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
<table>
    <tr>
        <th></th>
        <th>Id</th>
        <th>Lennu_nr</th>
        <th>Kohtade_arv</th>
        <th>Ots</th>
        <th>Siht</th>
        <th>Siht pilt</th>
        <th>Väljumisaeg</th>
        <th>Kestvus</th>
        <th colspan="2">Staatus</th>
    </tr>
    <?php
    while($paring->fetch()) {
        echo "<tr>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        //htmlspecialchars - ei käivita sisestatud koodi <>
        echo "<td>".htmlspecialchars($id)."</td>";
        echo "<td>".htmlspecialchars($lennu_nr)."</td>";
        echo "<td>".htmlspecialchars($kohtade_arv)."</td>";
        echo "<td>".htmlspecialchars($ots)."</td>";
        echo "<td>".htmlspecialchars($siht)."</td>";
        echo "<td><img src='$siht_pilt' alt='pilt' width='100px'></td>";
        echo "<td>".htmlspecialchars($valjumisaeg)."</td>";
        echo "<td>".htmlspecialchars($kestvus)."</td>";
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
    <section class="lisamine">
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
            <label for="">Kestvus</label>
            <input type="number" id="kestvus" name="kestvus">
            <br>
            <input type="submit" value="OK">
        </form>
    </section>
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

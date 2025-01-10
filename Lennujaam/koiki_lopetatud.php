<?php if(isset($_GET['code'])){die(highlight_file(__FILE__, 1));} ?>
<?php
session_start();
require ('conf.php');
global $yhendus;
//kontrollige, kas on olemas teave kasutaja rolli kohta
if (!isset($_SESSION['rolli'])) {
    echo "Kasutaja pole sisse logitud";
    exit();
}
// valmislendude valiku taotlus koos kestuse arvutamisega
$paring = $yhendus->prepare("SELECT id, lennu_nr, ots, siht, siht_pilt, lopetatud, kestvus
    FROM lend WHERE lopetatud = 0");
$paring->bind_result($id, $lennu_nr, $ots, $siht, $siht_pilt, $lopetatud, $kestvus);
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
    <h1>Kõik lõpetatud lennud</h1>
</header>
<nav>
    <ul>
        <?php
        //kontrollida, kas kasutaja on volitatud, kuvada menüü sõltuvalt kasutaja rollist
        if (isset($_SESSION['useruid']) && isset($_SESSION['rolli'])) {
            if ($_SESSION['rolli'] == 1) {
                echo '<li><a href="lendude_lisamiseks.php">Lennujaam</a></li>';
                echo '<li><a href="reisijate_lisandumine.php">Reisijad</a></li>';
            } else if ($_SESSION['rolli'] == 0) {
                echo '<li><a href="koiki_lopetatud.php">Kõik lõpetatud lennud</a></li>';
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
        <th>Lennu_nr</th>
        <th>Ots</th>
        <th>Siht</th>
        <th>Siht pilt</th>
        <th>Kestvus</th>
    </tr>
    <?php
    //lennuandmete kuvamine
    while($paring->fetch()) {
        echo "<tr>";
        //htmlspecialchars - ei käivita sisestatud koodi <>
        echo "<td>".htmlspecialchars($lennu_nr)."</td>";
        echo "<td>".htmlspecialchars($ots)."</td>";
        echo "<td>".htmlspecialchars($siht)."</td>";
        echo "<td><img src='$siht_pilt' alt='pilt' width='100px'></td>";
        echo "<td>".htmlspecialchars($kestvus)."</td>";
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


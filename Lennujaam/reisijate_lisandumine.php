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
//tabeli kirje kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM reisijad WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//uue reisija lisamine
if (isset($_REQUEST["reisija_nimi"]) && isset($_REQUEST["reisija_perekonanimi"]) && isset($_REQUEST["reisija_foto"])) {
    if (!empty($_REQUEST["reisija_nimi"]) && !empty($_REQUEST["reisija_perekonanimi"]) && !empty($_REQUEST["reisija_foto"]) && !empty($_REQUEST["lend_id"])) {
        $paring = $yhendus->prepare("INSERT INTO reisijad(lend_id, reisija_nimi, reisija_perekonanimi, reisija_foto)
        VALUES (?, ?, ?, ?)");
        //i- integer, s- string
        $paring->bind_param("isss", $_REQUEST["lend_id"], $_REQUEST["reisija_nimi"], $_REQUEST["reisija_perekonanimi"],
            $_REQUEST["reisija_foto"]);
        $paring->execute();
    } else {
        $error_message = "Palun täitke kõik väljad.";
    }
}
//tabeli sisu kuvamine
$paring=$yhendus->prepare("SELECT id, lend_id, reisija_nimi, reisija_perekonanimi, reisija_foto  FROM reisijad");
$paring->bind_result($id, $lend_id, $reisija_nimi, $reisija_perekonanimi, $reisija_foto);
$paring->execute();
?>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
    <title>Reisijad</title>
    <link rel="stylesheet" href="LennujaamStyle.css">
</head>
<body>
<header>
    <h1>Reisijad</h1>
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
        <th>Lend_id</th>
        <th>reisija_nimi</th>
        <th>reisija_perekonanimi</th>
        <th>reisija_foto</th>
    </tr>
    <?php
    //kõigi reisijate kuvamine
    while($paring->fetch()) {
        echo "<tr>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        echo "<td>".htmlspecialchars($id)."</td>";
        echo "<td>".htmlspecialchars($lend_id)."</td>";
        echo "<td>".htmlspecialchars($reisija_nimi)."</td>";
        echo "<td>".htmlspecialchars($reisija_perekonanimi)."</td>";
        echo "<td><img src='$reisija_foto' alt='pilt' width='100px'></td>";
    }
    ?>
</table>
<div class="lisamine">
    <!-- veateade -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
        <h2>Uue reisija lisamine</h2>
        <!--uue reisija lisamise vorm-->
        <form method="post" action="">
            <label for="lend_id">Lennu_nr</label>
            <select id="lend_id" name="lend_id" required>
                <option value="">Vali lend</option>
                <?php
                // kõikide lendude laadimine rippmenüüde jaoks
                $paring_lennud = $yhendus->prepare("SELECT id, lennu_nr FROM lend WHERE lopetatud=1");
                $paring_lennud->bind_result($id, $lennu_nr);
                $paring_lennud->execute();
                while ($paring_lennud->fetch()) {
                    echo "<option value='$id'>$lennu_nr</option>";
                }
                ?>
            </select>
            <br>
            <label for="reisija_nimi">reisija_nimi</label>
            <input type="text" id="reisija_nimi" name="reisija_nimi">
            <br>
            <label for="reisija_perekonanimi">reisija_perekonanimi</label>
            <input type="text" id="reisija_perekonanimi" name="reisija_perekonanimi">
            <br>
            <label for="reisija_foto">reisija_foto</label><br>
            <textarea id="reisija_foto" name="reisija_foto" cols="30" rows="10">Sisesta pildi link</textarea><br>
            <br>
            <input type="submit" value="OK">
        </form>
</div>
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

<?php
//session_start();
// Получаем текущую дату и время
require ('conf.php');
global $yhendus;
//if (!isset($_SESSION['rolli'])) {
//  echo "Kasutaja pole sisse logitud";
//  exit();
//}
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM reisijad WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//tabeli andmete lisamine
if (isset($_REQUEST["lend_id"]) && isset($_REQUEST["reisija_nimi"]) && isset($_REQUEST["reisija_perekonanimi"]) && isset($_REQUEST["reisija_foto"])){
    $paring=$yhendus->prepare("INSERT INTO reisijad(lend_id, reisija_nimi, reisija_perekonanimi, reisija_foto)
VALUES (?, ?, ?, ?)");
    //i- integer, s- string
    $paring->bind_param("isss", $_REQUEST["lend_id"], $_REQUEST["reisija_nimi"], $_REQUEST["reisija_perekonanimi"],
        $_REQUEST["reisija_foto"]);
    $paring->execute();
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Reisijad</h1>
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
        <th>Lend_id</th>
        <th>reisija_nimi</th>
        <th>reisija_perekonanimi</th>
        <th>reisija_foto</th>
    </tr>
    <?php

    while($paring->fetch()) {
        echo "<tr>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        echo "<td>$id</td>";
        echo "<td>$lend_id</td>";
        echo "<td>$reisija_nimi</td>";
        echo "<td>$reisija_perekonanimi</td>";
        echo "<td><img src='$reisija_foto' alt='pilt' width='100px'></td>";
    }
    ?>
</table>
<table>
    <h2>Uue lennu lisamine</h2>
    <!--tabeli lisamisVorm-->
    <form method="post" action="">
        <label for="lend_id">Lennu_nr</label>
        <select id="lend_id" name="lend_id" required>

            <option value="">Vali lend</option>


            <?php
            // Загрузка всех рейсов для выпадающего списка
            $paring_lennud = $yhendus->prepare("SELECT id, lennu_nr FROM lend WHERE valjumisaeg > ?");
            $paring_lennud->bind_result($id, $lennu_nr);
            $today=new DateTime();
            $paring_lennud->bind_param("s", $today);
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

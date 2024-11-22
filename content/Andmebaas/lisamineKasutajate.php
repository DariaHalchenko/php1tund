<?php
require ('conf2.php');
global $yhendus;
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM osalejad WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//tabeli andmete lisamine
if(isset($_REQUEST["nimi"]) && !empty($_REQUEST["nimi"])){
    global $yhendus;
    $paring=$yhendus->prepare("INSERT INTO osalejad(nimi, telefon, pilt, synniaeg)
VALUES (?, ?, ?, ?)");
    //i- integer, s- string
    $paring->bind_param("ssss", $_REQUEST["nimi"], $_REQUEST["telefon"], $_REQUEST["pilt"], $_REQUEST["synniaeg"]);
    $paring->execute();
}
//tabeli sisu kuvamine
global $yhendus;
$paring=$yhendus->prepare("SELECT id, nimi, telefon, pilt, synniaeg FROM osalejad");
$paring->bind_result($id, $nimi, $telefon, $pilt, $synniaeg);
$paring->execute();
// Funktsioon vanuse arvutamiseks
function kalkulaator($birthDate) {
    $birthDate = new DateTime($birthDate);
    $praeguneKyypaev = new DateTime();
    $intervall = $birthDate->diff($praeguneKyypaev);
    return $intervall->y;
}
?>
    <style>
        footer{
            color: Blue;
            background-color: PaleTurquoise;
            left: 0;
            width: 45%;
            text-align: center;
            border: solid 4pt DodgerBlue;
            border-radius: 80px;
            padding: 1%;
            margin-top: 10px;
        }
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {background-color: Cornsilk;}
    </style>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
    <title>Tabeli sisu, mida võetakse andmebaasist</title>
</head>
<body>
<h2>Uue osaleja lisamine</h2>
<table  border="2">
    <tr>
        <th></th>
        <th>id</th>
        <th>nimi</th>
        <th>telefon</th>
        <th>pilt</th>
        <th>synniaeg</th>
        <th>vanus</th>
    </tr>
<?php
while($paring->fetch()){
    $vanus = kalkulaator($synniaeg);
    echo "<tr>";
    echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
    echo "<td>".$id."</td>";
    //htmlspecialchars - ei käivita sisestatud koodi <>
    echo "<td>".htmlspecialchars($nimi)."</td>";
    echo "<td>".htmlspecialchars($telefon)."</td>";
    echo "<td><img src='$pilt' alt='pilt' width='100px'></td>";
    echo "<td>".htmlspecialchars($synniaeg)."</td>";
    echo "<td>".$vanus." aastat</td>";
    echo "</tr>";
}
?>
</table>
<table>
    <h2>Matkale registreerimine</h2>
    <!--tabeli lisamisVorm-->
    <form action="?" method="post">
        <label for="nimi">Nimi</label>
        <input type="text" id="nimi" name="nimi">
        <br>
        <label for="telefon">Telefon</label>
        <input type="text" id="telefon" name="telefon">
        <br>
        <label for="pilt">Loomapilt</label>
        <textarea id="pilt" name="pilt" cols="30" rows="10">Sisesta pildi link</textarea>
        <br>
        <label for="synniaeg">Synniaeg</label>
        <input type="date" id="synniaeg" name="synniaeg">
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
    <a href="https://dariahalchenko23.thkit.ee/wp/andmebaas-php/">Wordpress</a>
</footer>

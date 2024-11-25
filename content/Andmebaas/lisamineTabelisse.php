<?php
require ('conf.php');
global $yhendus;
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM loomad WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//tabeli andmete lisamine
if(isset($_REQUEST["loomanimi"]) && !empty($_REQUEST["loomanimi"])){
    global $yhendus;
    $paring=$yhendus->prepare("INSERT INTO loomad(loomanimi, omanik, varv, pilt)
VALUES (?, ?, ?, ?)");
    //i- integer, s- string
    $paring->bind_param("ssss", $_REQUEST["loomanimi"], $_REQUEST["omanik"], $_REQUEST["varv"], $_REQUEST["pilt"]);
    $paring->execute();
}
//tabeli sisu kuvamine
global $yhendus;
$paring=$yhendus->prepare("SELECT id, loomanimi, omanik, varv, pilt FROM loomad");
$paring->bind_result($id, $loomanimi, $omanik, $varv, $pilt);
$paring->execute();
?>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {background-color: crimson;}
    </style>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
    <title>Tabeli sisu, mida võetakse andmebaasist</title>
</head>
<body>
<h2>Uue looma lisamine</h2>
<table  border="2">
    <tr>
        <th></th>
        <th>id</th>
        <th>loomanimi</th>
        <th>omanik</th>
        <th>varv</th>
        <th>loomapilt</th>
    </tr>
<?php
while($paring->fetch()){
    echo "<tr>";
    echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
    echo "<td>".$id."</td>";
    //htmlspecialchars - ei käivita sisestatud koodi <>
    echo "<td style='color:white; background-color: $varv;'>".htmlspecialchars($loomanimi)."</td>";
    echo "<td>".htmlspecialchars($omanik)."</td>";
    echo "<td>".htmlspecialchars($varv)."</td>";
    echo "<td><img src='$pilt' alt='pilt' width='100px'></td>";
    echo "</tr>";
}
?>
</table>
<table>
    <!--tabeli lisamisVorm-->
    <form action="?" method="post">
        <label for="loomanimi">Loomanimi</label>
        <input type="text" id="loomanimi" name="loomanimi">
        <br>
        <label for="omanik">Omanik</label>
        <input type="text" id="omanik" name="omanik">
        <br>
        <label for="varv">Värv</label>
        <input type="color" id="varv" name="varv">
        <br>
        <label for="pilt">Loomapilt</label>
        <textarea id="pilt" name="pilt" cols="30" rows="10">Sisesta pildi link</textarea>
        <input type="submit" value="OK">
    </form>
</table>
</body>
</html>
<?php
$yhendus->close();
?>
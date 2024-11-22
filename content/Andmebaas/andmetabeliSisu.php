<?php
require ('conf.php');

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
<h1>Loomad andmebaasist</h1>
<table  border="2">
    <tr>
        <th>id</th>
        <th>loomanimi</th>
        <th>omanik</th>
        <th>varv</th>
        <th>loomapilt</th>
    </tr>
<?php
while($paring->fetch()){
    echo "<tr>";
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
</body>
</html>
<?php
$yhendus->close();
?>
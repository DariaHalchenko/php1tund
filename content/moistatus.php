<?php
echo "<h2>Mõistatus. Euroopa riik</h2>";
$riik='Slovakkia';
echo "<div id='euroopa'>";
echo "<ol>";
echo "<li>Viimane täht riigis - ".substr($riik,8,1)."</li>";
echo "<br>";
echo "<li>Riigi nimi koosneb  - ".strlen($riik)."</li>";
echo "<br>";
$kiri="vak";
echo "<li>Kolm tähte - ".substr($riik,0,strpos($riik,$kiri))."</li>";
echo "<br>";
$kiri='i';
echo "<li>i on sõnas - ".strpos($riik, $kiri)."</li>";
echo "<br>";
echo "<li>3.tähte - " . substr($riik, 3, 3) . "</li>";
echo "<br>";
//Tähtede vahetus (substr_replace)
$asendus = 'a';
$otsitav_algus = 1; //Alustab asendamist 2 positsioonilt
$otsitav_pikkus = 5; //Asendab 5 sümbolit sõnas
echo "<li>Tähtede vahetus - ". substr_replace($riik, $asendus, $otsitav_algus, $otsitav_pikkus)."</li>";
echo "</ol>";
echo "<br>";
echo "<br>";
echo "</div>";
?>
<h2>Vastus</h2>
<form method="post" action="">
    Sisesta oma vastus:
    <input type="text" name="vastus">
    <input type="submit" value="OK">
</form>
<?php
if (isset($_POST["vastus"])) {
    $kasutajavastus = $_POST["vastus"];
    if (strtolower($kasutajavastus) === strtolower($riik)) {
        echo "Õige vastus. Hästi tehtud.";
    }
    else {
        echo "Vale vastus.";
    }
}
echo "<br>";
echo "<br>";
highlight_file('moistatus.php');
?>




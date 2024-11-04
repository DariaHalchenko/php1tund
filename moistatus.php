<?php
echo "<h2>Mõistatus. Euroopa riik</h2>";
$riik='Slovakkia';
$kiri="vak";
echo "<ol>";
echo "<li>Esimene täht riigis on - ".substr($riik,0,1)."</li>";
echo "</ol>";
echo "<ol>";
echo "<li>Riigi nimi koosneb  - ".strlen($riik)."</li>";
echo "</ol>";
echo "<ol>";
echo "<li>Kolm tähte - ".substr($riik,0,strpos($riik,$kiri))."</li>";
echo "</ol>";
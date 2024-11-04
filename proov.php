<?php
echo "Tere hommikust!";
echo "<br>";
$muutuja='PHP on skriptikeel';
echo "<strong>";
echo $muutuja;
echo "</strong>";
echo "<br>";
// Tekstifunktsioonid
echo "<h2>Tekstifunktsioonid!</h2>";
$tekst ='Esmaspäev on 4.november';
echo $tekst;
//Kõik tähed on suured
echo "<br>";
echo strtoupper($tekst); // ei tunne ä täht
echo "<br>";
echo mb_strtoupper($tekst); // tunneb ä
//Kõik tähed on väiksed
echo "<br>";
echo strtolower($tekst);
//Iga sõna algab suure täheda
echo "<br>";
echo ucwords($tekst);
//Teksti pikkus
echo "<br>";
echo "Teksti pikkus - ".strlen($tekst);
//eraldame esimesed 5 tähte
echo "<br>";
echo "Esimesed 5 tähte - ".substr($tekst,0,5);
echo "<br>";
$otsing="on";
echo "On asukoht lauses on ".strpos($tekst,$otsing);
// eralda esimene sõna kuni $otsing
echo "<br>";
echo substr($tekst,0,strpos($tekst,$otsing));
//eralda peale esimest sõna, alates 'on'
echo "<br>";
echo substr($tekst,strpos($tekst,$otsing));
echo "<br>";
echo "<h2>Kasutame veebis kasutavaid näidised</h2>";
//Sõnade arv lauses
echo "<br>";
echo "Sõnade arv lauses - ".str_word_count($tekst);
//Iseseisvalt - teksti kärpimine
$tekst2='Põhitoetus võitakse ära 11.11 kui võlgnevused ei ole parandatud';
echo "<br>";
echo "<pre>$tekst2</pre>";
echo "<br>";
echo ltrim($tekst2);
echo "<br>";
echo trim($tekst2, "P, a.. k..n, w");
//Iseseisvalt - tekst kui massiiv
<?php
// XML-faili andmete üleslaadimine
$opilased = simplexml_load_file("TARpv23ruhmaleht.xml");
// Juuksevärvi otsingufunktsioon
function otsingJuuksevarv($paring){
    global $opilased;
    $paringVastus = array(); // Massiiv salvestab otsingu tulemused
    // Loendab kõik õpilased XML-failist
    foreach ($opilased->opilane as $opilane) {
        $juuksevarv = trim($opilane->juuksevarv); // Juuksevärvi saamine
        // Õpilaste lisamine tulemusele
        if (stripos($juuksevarv, $paring) !== false) {
            array_push($paringVastus, $opilane);
        }
    }
    return $paringVastus; // Tulemuste tagastamine
}

// Uue õpilase lisamine
if (isset($_POST['submit'])) {
    // XML-faili üleslaadimine muutmiseks
    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    // Olemasoleva XML-struktuuri laadimine
    $xmlDoc->load("TARpv23ruhmaleht.xml");
    $xmlDoc->formatOutput = true;
    // Hangi juurelement
    $xml_root = $xmlDoc->documentElement;
    // Loo uue õpilase jaoks uus <opilane> element
    $xml_opilane = $xmlDoc->createElement("opilane");
    // Lisage juurelemendile uus <opilane> element
    $xml_root->appendChild($xml_opilane);
    // Uute õpilaste andmed
    foreach ($_POST as $key => $value) {
        if ($key !== 'submit') {
            // Loo iga vormivälja jaoks uus lapselement
            $element = $xmlDoc->createElement($key, htmlspecialchars($value));
            $xml_opilane->appendChild($element);
        }
    }
    //  Salvesta uuendatud XML faili
    $xmlDoc->save("TARpv23ruhmaleht.xml");
    // Kuva lehel
    $opilased = simplexml_load_file("TARpv23ruhmaleht.xml");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styleTARpv23ruhm.css">
    <title>TARpv23 rühmaleht</title>
</head>
<body>
<h1>TARpv23 rühmaleht</h1>
<!-- Otsi õpilasi juuste värvi järgi -->
<div id="juuste_otsimine">
    <h2>Otsi juuste värvi järgi</h2>
    <form method="post" action="?">
        <label for="otsing">Juuksevärv: (must, punane, blond, tumepruun, helepruun, oranz)</label>
        <br>
        <input type="text" id="otsing" name="otsing" placeholder="juuksevarv">
        <input type="submit" value="OK">
    </form>
</div>

<?php
// Otsingutulemused juuksevärvi järgi
$color = '';
if (!empty($_POST['otsing'])) {
    $color = strtolower(trim($_POST['otsing']));
    $paringVastus = otsingJuuksevarv($color);
    if (count($paringVastus) > 0) {
        // Näitame leitud õpilasi
        foreach ($paringVastus as $opilane) {
            $nimi = $opilane->nimi;
            $kodulehed = "https://" . $opilane->kodulehed;
            $juuksevarv = strtolower(trim($opilane->juuksevarv));
            echo "<a class='opilased' href='$kodulehed' target='_blank' data-haircolor='$juuksevarv'>$nimi</a>";
        }
    }
} else {
    // Kõigi õpilaste kuvamine
    foreach ($opilased->opilane as $opilane) {
        $nimi = $opilane->nimi;
        $kodulehed = "https://" . $opilane->kodulehed;
        $juuksevarv = strtolower(trim($opilane->juuksevarv));  // Получаем цвет волос
        echo "<a class='opilased' href='$kodulehed' target='_blank' data-haircolor='$juuksevarv'>$nimi</a>";
    }
}
?>
<!-- Uue õpilase lisamine -->
<div id="lisamine">
    <h2>Uue õpilase lisamine</h2>
    <form action="" method="post" name="andmete_lisamine">
        <table>
            <tr>
                <td><label for="nimi">Nimi:</label></td>
                <td><input type="text" name="nimi" id="nimi" autofocus></td>
            </tr>
            <tr>
                <td><label for="perekonnanimi">Perekonnanimi:</label></td>
                <td><input type="text" name="perekonnanimi" id="perekonnanimi"></td>
            </tr>
            <tr>
                <td><label for="kodulehed">Kodulehed:</label></td>
                <td><input type="text" name="kodulehed" id="kodulehed"></td>
            </tr>
            <tr>
                <td><label for="juuksevarv">Juuksevarv:</label></td>
                <td><input type="text" name="juuksevarv" id="juuksevarv"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" id="submit" value="Salvestada"></td>
                <td></td>
            </tr>
        </table>
    </form>
</div>
<!-- Faili avamised (PHP, XML, CSS) -->
<div id="open">
    <h2>Failide avamine</h2>
    <form action="TARpv23opilased.php" method="post" target="_blank">
        <button type="submit" name="action" value="phpfaili">PHP faili avamine</button>
    </form>
    <form action="TARpv23ruhmaleht.xml" method="get" target="_blank">
        <button type="submit">XML-faili avamine</button>
    </form>
    <form action="styleTARpv23ruhm.css" method="get" target="_blank">
        <button type="submit">CSS-faili avamine</button>
    </form>
    <!-- Allikad -->
    <h2>Allikad</h2>
    <a href="https://www.metshein.com/unit/xml-xml-andmete-salvestamine-php-abil/">XML andmete salvestamine PHP abil</a>
    <br>
    <br>
    <a href="https://www.w3schools.com/cssref/tryit.php?filename=trycss_anim_box-shadow">Varju loomine plokkide ümber</a>
    <br>
    <br>
    <a href="https://www.w3schools.com/cssref/tryit.php?filename=trycss_anim_text-shadow">Animatsioon h2</a>
    <br>
    <br>
    <a href="https://www.w3schools.com/cssref/tryit.php?filename=trycss_anim_clip">Ploki suuruse muutmise animatsioon</a>
</div>
<?php
if (isset($_POST['action']) && $_POST['action'] == 'phpfaili') {
    // PHP-faili koodi kuvamine
    highlight_file('TARpv23opilased.php');
}
?>
<script>
    // Bloki värv muutub sõltuvalt juuste värvusest
    let otsi = "<?php echo $color; ?>".toLowerCase(); // PHP otsingupäring
    let opilased_ring = document.querySelectorAll('.opilased'); // Kõik elemendid klassist .opilased
    opilased_ring.forEach(function(ring) {
        let varv = ring.getAttribute('data-haircolor'); // Õpilase juuste värvi saamine
        if (otsi && varv === otsi) {
            // Muutke taustavärvi sõltuvalt juuste värvusest
            if (varv === "must") {
                ring.style.backgroundColor = "black";
                ring.style.color="white";
            } else if (varv === "punane") {
                ring.style.backgroundColor = "red";
                ring.style.color="white";
            } else if (varv === "blond") {
                ring.style.backgroundColor = "#FBF6D9";
            } else if (varv === "tumepruun") {
                ring.style.backgroundColor = "brown";
                ring.style.color="white";
            } else if (varv === "helepruun") {
                ring.style.backgroundColor = "SandyBrown";
                ring.style.color="white";
            } else if (varv === "oranz") {
                ring.style.backgroundColor = "OrangeRed";
                ring.style.color="white";
            }else {
                ring.style.backgroundColor = "PeachPuff";
            }
        }
    });
</script>
</body>
</html>

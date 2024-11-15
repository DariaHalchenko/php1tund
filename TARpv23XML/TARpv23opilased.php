<?php
$opilased=simplexml_load_file("TARpv23ruhmaleht.xml");
// Функция поиска по цвету волос
function otsingJuuksevarv($paring){
    global $opilased;
    $paringVastus = array();
    $paring = trim($paring);
    foreach ($opilased->opilane as $opilane) {
        $juuksevarv = trim($opilane->juuksevarv);
        if (stripos($juuksevarv, $paring) !== false) {
            array_push($paringVastus, $opilane);
        }
    }
    return $paringVastus;
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <link rel="stylesheet" href="styleTARpv23ruhm.css">
    <title>TARpv23 rühmaleht</title>
</head>
<body>
<h2>TARpv23 rühmaleht</h2>
<div id="tarpv23">
<form method="post" action="?">
    <label for="otsing">Juuksevärv:</label>
    <input type="text" id="otsing" name="otsing" placeholder="juuksevarv">
    <input type="submit" value="OK">
</form>
    <?php
    if (!empty($_POST['otsing'])) {
        $paringVastus = otsingJuuksevarv($_POST['otsing']);
        if (count($paringVastus) > 0) {

            foreach ($paringVastus as $opilane) {
                $nimi = $opilane->nimi;
                $kodulehed = "https://" . $opilane->kodulehed;

                echo "<a class='opilased' href='$kodulehed' target='_blank'>$nimi</a>";

            }
        }
    } else {
        foreach ($opilased->opilane as $opilane) {
            $nimi = $opilane->nimi;
            $kodulehed = "https://" . $opilane->kodulehed;
            echo "<a class='opilased' href='$kodulehed' target='_blank'>$nimi</a>";
        }
    }
    ?>
</div>
</body>
</html>

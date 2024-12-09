<?php
require("abifunktsioonid.php");

// Muutujad sorteerimiseks ja otsimiseks
$sorttulp = "nimetus";
$otsisona = "";
$veateade = ""; // Muutuja vigade jaoks

// Sorteerimis- ja otsinguparameetrite olemasolu kontrollimine
if (isset($_REQUEST["sort"])) {
    $sorttulp = $_REQUEST["sort"];
}
if (isset($_REQUEST["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}

// Saame kauba kohta andmeid, arvestades sorteerimist ja otsimist
$kaubad = kysiKaupadeAndmed($sorttulp, $otsisona);

// Funktsioon kontrollib, kas grupp juba eksisteerib
function kysiGrupp($grupinimi) {
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id FROM kaubagrupid WHERE grupinimi = ?");
    $kask->bind_param("s", $grupinimi);
    $kask->execute();
    $tulemus = $kask->get_result();
    return $tulemus->num_rows > 0;
}

// Uue tootegrupi lisamise töötlemine
if (isset($_REQUEST["grupilisamine"])) {
    $uuegrupinimi = trim($_REQUEST["uuegrupinimi"]);
    if (empty($uuegrupinimi)) {
        $veateade = "Grupi nimi ei saa olla tühi.";
    } elseif (kysiGrupp($uuegrupinimi)) {
        $veateade = "Grupi nimi on juba olemas.";
    } else {
        lisaGrupp($uuegrupinimi);
        header("Location: index.php");
        exit();
    }
}

// Uue kauba lisamise töötlemine
if (isset($_REQUEST["kaubalisamine"])) {
    $nimetus = trim($_REQUEST["nimetus"]);
    $hind = trim($_REQUEST["hind"]);
    $kaubagrupi_id = $_REQUEST["kaubagrupi_id"];
    if (empty($nimetus) || empty($hind) || empty($kaubagrupi_id)) {
        $veateade = "Kauba nimetus, hind ja grupp ei saa olla tühjad.";
    } else {
        lisaKaup($nimetus, $kaubagrupi_id, $hind);
        header("Location: index.php");
        exit();
    }
}

// Kauba kustutamine
if (isset($_REQUEST["kustutusid"])) {
    kustutaKaup($_REQUEST["kustutusid"]);
}

// Kauba muutmine
if (isset($_REQUEST["muutmine"])) {
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
}

// Uuendatud andmete saamine
$kaubad = kysiKaupadeAndmed($sorttulp, $otsisona);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Kauba haldamine</h1>
</header>
<!-- Otsingu vorm -->
<form action="index.php" method="get">
    Otsi: <input type="text" name="otsisona" value="<?= htmlspecialchars($otsisona) ?>" />
    <input type="submit" value="Otsi" />
</form>

<!-- Veateade -->
<?php if (!empty($veateade)): ?>
    <p class="error"><?= htmlspecialchars($veateade) ?></p>
<?php endif; ?>

<!-- Uue kauba lisamise vorm -->
<form action="index.php" method="post">
    <h2>Kauba lisamine</h2>
    <dl>
        <dt>Nimetus:</dt>
        <dd><input type="text" name="nimetus" /></dd>
        <dt>Kaubagrupp:</dt>
        <dd>
            <?php echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupi_id"); ?>
        </dd>
        <dt>Hind:</dt>
        <dd><input type="text" name="hind" /></dd>
    </dl>
    <input type="submit" name="kaubalisamine" value="Lisa kaup" />

    <h2>Grupi lisamine</h2>
    <input type="text" name="uuegrupinimi" />
    <input type="submit" name="grupilisamine" value="Lisa grupp" />
</form>

<!-- Kaupade tabel -->
<h2>Kaupade loetelu</h2>
<form action="index.php" method="post">
    <table>
        <tr>
            <th>Haldus</th>
            <th><a href="index.php?sort=nimetus">Nimetus</a></th>
            <th><a href="index.php?sort=grupinimi">Kaubagrupp</a></th>
            <th><a href="index.php?sort=hind">Hind</a></th>
        </tr>
        <?php foreach ($kaubad as $kaup): ?>
            <tr>
                <?php if (isset($_REQUEST["muutmisid"]) && intval($_REQUEST["muutmisid"]) == $kaup->id): ?>
                    <td>
                        <input type="submit" name="muutmine" value="Muuda" />
                        <input type="submit" name="katkestus" value="Katkesta" />
                        <input type="hidden" name="muudetudid" value="<?= $kaup->id ?>" />
                    </td>
                    <td><input type="text" name="nimetus" value="<?= htmlspecialchars($kaup->nimetus) ?>" /></td>
                    <td>
                        <?php echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupi_id", $kaup->kaubagrupi_id); ?>
                    </td>
                    <td><input type="text" name="hind" value="<?= htmlspecialchars($kaup->hind) ?>" /></td>
                <?php else: ?>
                    <td>
                        <a href="index.php?kustutusid=<?= $kaup->id ?>" onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                        <a href="index.php?muutmisid=<?= $kaup->id ?>">m</a>
                    </td>
                    <td><?= htmlspecialchars($kaup->nimetus) ?></td>
                    <td><?= htmlspecialchars($kaup->grupinimi) ?></td>
                    <td><?= htmlspecialchars($kaup->hind) ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</form>
</body>
</html>

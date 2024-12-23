<?php
session_start();
require ('conf.php');
global $yhendus;
if (!isset($_SESSION['rolli'])) {
    echo "Kasutaja pole sisse logitud";
    exit();
}
// Konkurssi lisamine
if (!empty($_REQUEST["uusKonkurss"])) {
    $pilt = isset($_REQUEST["pilt"]) ? $_REQUEST["pilt"] : ''; // Default to empty string if no image is provided
    $paring = $yhendus->prepare("INSERT INTO konkurss (konkursiNimi, lisamisaeg, pilt) VALUES (?, NOW(), ?)");
    $paring->bind_param("ss", $_REQUEST["uusKonkurss"], $pilt);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]");
}

// Kommenteerimine
$error_message = "";
if(isset($_REQUEST["uusKomment"])) {
    $komment = trim($_REQUEST["komment"]);
    if (!empty($komment)) {
        $paring = $yhendus->prepare("UPDATE konkurss SET kommentaarid=CONCAT(kommentaarid, ?) WHERE id=?");
        $kommentLisa = "\n" . $komment;
        $paring->bind_param("si", $kommentLisa, $_REQUEST["uusKomment"]);
        $paring->execute();
    } else {
        $error_message = "Kommentaar ei saa olla tühi!";
    }
}

// Punktide lisamine +1
if (isset($_REQUEST["heakonkurss_id"])) {
    $paring = $yhendus->prepare("UPDATE konkurss SET punktid = punktid + 1 WHERE id = ?");
    $paring->bind_param('i', $_REQUEST["heakonkurss_id"]);
    $paring->execute();
}

// Punktide vähendamine -1
if(isset($_REQUEST["halvastikonkurss_id"])) {
    $paring = $yhendus->prepare("UPDATE konkurss SET punktid=GREATEST(0, punktid-1) WHERE id=?");
    $paring->bind_param('i', $_REQUEST["halvastikonkurss_id"]);
    $paring->execute();
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>TARpv23 jõulu konkursid</title>
    <link rel="stylesheet" href="konkurss1kaupaStyle.css">
</head>
<body>
<header>
    <h1>Konkurss 1 kaupa</h1>
</header>
<nav>
    <ul>
        <?php
        if (isset($_SESSION['useruid']) && isset($_SESSION['rolli'])) {
            if ($_SESSION['rolli'] == 1) {
                echo '<li><a href="KonkurssAdmin.php">Admin</a></li>';
            } else if ($_SESSION['rolli'] == 0) {
                echo '<li><a href="KonkurssKasutaja.php">Kasutaja</a></li>';
                echo '<li><a href="konkurss1kaupa.php">Konkurss 1 kaupa</a></li>';
            }
            echo '<li><a href="Sisselogimisvorm/logout.inc.php">Logi välja (' . htmlspecialchars($_SESSION['useruid']) . ')</a></li>';
        } else {
            echo '<li><a href="login.php">Sisse loogimine</a></li>';
            echo '<li><a href="signup.php">Registreerimine</a></li>';
        }
        ?>
    </ul>
</nav>
<br><br><br><br>
<main>
    <div id="konkurss">
        <h2>Konkurss:</h2>
        <ul>
            <?php
            $paring = $yhendus->prepare("SELECT id, konkursiNimi FROM konkurss WHERE avalik = 1");
            $paring->bind_result($id, $konkursiNimi);
            $paring->execute();
            while ($paring->fetch()) {
                echo "<li><a href='?konkurss_id=$id'>$konkursiNimi</a></li>";
            }
            ?>
            <a href='?lisamine=jah'>LISA...</a>
        </ul>
    </div>

    <div id="info">
        <?php
        if (isset($_REQUEST["konkurss_id"])) {
            $konkurss_id = $_REQUEST["konkurss_id"];
            $paring = $yhendus->prepare("SELECT id, konkursiNimi, lisamisaeg, punktid, kommentaarid, avalik, pilt FROM konkurss WHERE id = ?");
            $paring->bind_param("i", $konkurss_id);
            $paring->bind_result($id, $konkurssnimi, $lisamisaeg, $punktid, $kommentaarid, $avalik, $pilt);
            $paring->execute();
            if ($paring->fetch()) {
                echo "<p><strong>KonkursiNimi:</strong> $konkurssnimi</p>";
                echo "<p><strong>Lisamisaeg:</strong> $lisamisaeg</p>";
                echo "<p><strong>Punktid:</strong> $punktid</p>";
                echo "<p><strong>Kommentaarid:</strong> $kommentaarid</p>";
                echo "<p><img src='$pilt' alt='pilt' width='100px'></p>";
                ?>
                <form action="?" method="post">
                    <input type="hidden" name="uusKomment" value="<?=$id?>">
                    <input type="text" name="komment" id="komment">
                    <input type="submit" value="Lisa kommentaar">
                </form>
                <?php if ($error_message != ""): ?>
                    <p class="error-message"><?= $error_message ?></p>
                <?php endif; ?>
                <a href='?heakonkurss_id=<?=$id?>'>Lisa +1 punkt</a><br>
                <a href='?halvastikonkurss_id=<?=$id?>'>-1 punkt</a>
                <?php
            }
        }
        ?>
    </div>
    <div id="lisa">
        <?php
        // Lisamisvorm, mis avatakse kui vajutatud "LISA..."
        if (isset($_REQUEST["lisamine"])) {
            ?>
            <form action="?" method="post">
                <label for="uusKonkurss">Lisa konkurssi nimi</label>
                <input type="text" name="uusKonkurss" id="uusKonkurss"><br>
                <label for="pilt">Pilt</label><br>
                <textarea id="pilt" name="pilt" cols="30" rows="10">Sisesta pildi link</textarea><br>
                <input type="submit" value="OK">
            </form>
            <?php
        }
        ?>
    </div>
</main>
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

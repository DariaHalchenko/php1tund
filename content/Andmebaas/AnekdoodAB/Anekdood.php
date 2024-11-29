<?php
require('confAnekdood.php');
global $yhendus;
//Anekdoodi eemaldamine
if (isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("DELETE FROM anekdoot WHERE id=?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();
}
// Uue anekdoodi lisamine
if (isset($_REQUEST["nimetus"]) && !empty($_REQUEST["nimetus"])) {
    $paring = $yhendus->prepare("INSERT INTO anekdoot(nimetus, kuupaev, kirjeldus) VALUES (?, ?, ?)");
    $paring->bind_param("sss", $_REQUEST["nimetus"], $_REQUEST["kuupaev"], $_REQUEST["kirjeldus"]);
    $paring->execute();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>Anekdoot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="AnekdoodStyle.css">
</head>
<body>
<div class="w3-container w3-green">
    <?php
    include('header.php');
    ?>
</div>
<main>
    <div class="content-wrapper">
        <h2>Anekdoot:</h2>
        <ul>
            <?php
            // Получаем все анекдоты из базы данных и выводим их как ссылки
            $paring = $yhendus->prepare("SELECT id, nimetus FROM anekdoot");
            $paring->bind_result($id, $nimetus);
            $paring->execute();
            while ($paring->fetch()) {
                echo "<li><a href='?anekdood_id=$id'>$nimetus</a></li>";
            }
            ?>
        </ul>
        <div class="main-content">
            <h2>Anekdoodid</h2>
            <div class="anecdood-content">
                <?php
                if (isset($_REQUEST["anekdood_id"])) {
                    $anekdood_id = $_REQUEST["anekdood_id"];
                    $paring = $yhendus->prepare("SELECT nimetus, kuupaev, kirjeldus FROM anekdoot WHERE id = ?");
                    $paring->bind_result($nimetus, $kuupaev, $kirjeldus);
                    $paring->bind_param("i", $anekdood_id);
                    $paring->execute();
                    if ($paring->fetch()) {
                        echo "<p><strong>Kuupaev:</strong> $kuupaev</p>";
                        echo "<p><strong>Kirjeldus:</strong> $kirjeldus</p>";
                        echo "<br><a href='?kustuta=$anekdood_id'>Kustuta</a>";
                    }
                }
                ?>
            </div>
            <div>
                <a href='?lisamine=jah'>LISA...</a>
            </div>
        </div>
    </div>
    <!--Uue anekdoodi lisamise vorm-->
    <?php
    if (isset($_REQUEST["lisamine"])) {
        ?>
        <form action="?" method="post">
            <label for="nimetus">Nimetus:</label>
            <input type="text" id="nimetus" name="nimetus" required><br><br>
            <label for="kuupaev">Kuupaev:</label>
            <input type="date" id="kuupaev" name="kuupaev" required><br><br>
            <label for="kirjeldus">Kirjeldus:</label><br>
            <textarea id="kirjeldus" name="kirjeldus" rows="5" required></textarea><br><br>
            <input type="submit" value="OK">
        </form>
        <?php
    }
    ?>
</main>
</body>
</html>
<?php
$yhendus->close();
?>
<div class="w3-container w3-green">
    <?php
    include('footer.php');
    ?>
</div>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>TARpv23 jõulu konkursid</title>
    <link rel="stylesheet" href="SisselogimisvormStyle.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="KonkurssAdmin.php">Admin</a></li>
        <li><a href="KonkurssKasutaja.php">Kasutaja</a></li>
        <li><a href="Konkurss1kaupa.php">Konkurss 1 kaupa</a></li>
        <li><a href="login.php">Sisse loogimine</a></li>
        <li><a href="signup.php">Registreerimine</a></li>
    </ul>
</nav>
<main>
    <div class="overlay">
        <form action="Sisselogimisvorm/login.inc.php" method="post">
            <div class="con">
                <header class="head-form">
                    <h2>Sisse loogimine</h2>
                    <p>Logi siia oma kasutajanime ja parooli abil</p>
                </header>
                <div class="field-set">
                    <span class="input-item"><i class="fa fa-user-circle"></i></span>
                    <input class="form-input" id="txt-input" type="text" name="uid" placeholder="Kasutaja nimi" required>
                </div>
                <div class="field-set">
                    <span class="input-item"><i class="fa fa-key"></i></span>
                    <input class="form-input" type="password" placeholder="Parool" id="pwd" name="pwd" required>
                </div>
                <div class="field-set">
                    <button class="log-in" type="submit" name="submit">Loogi sisse</button>
                </div>
                <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "emptyinput") {
                        echo "<p class='error-message'>Täida kõik väljad</p>";
                    }
                    if ($_GET["error"] == "wronglogin") {
                        echo "<p class='error-message'>Vale andmed</p>";
                    }
                }
                ?>
            </div>
        </form>
        <footer>
            <?php
            echo "Daria Halchenko &copy;";
            echo date('Y');
            ?>
        </footer>
    </div>
</main>
</body>
</html>

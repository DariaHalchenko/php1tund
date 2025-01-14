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
<main>
    <div class="login-container">
        <div class="login-frame">
            <nav>
                <ul>
                    <?php
                    if (isset($_SESSION['useruid']) && isset($_SESSION['rolli'])) {
                        if ($_SESSION['rolli'] == 1) {
                            echo '<li><a href="lendude_lisamiseks.php">Admin</a></li>';
                        } else if ($_SESSION['rolli'] == 0) {
                            echo '<li><a href="koiki_lopetatud.php">Tavakasutaja</a></li>';
                        }
                        echo '<li><a href="Sisselogimisvorm/logout.inc.php">Logi välja (' . htmlspecialchars($_SESSION['useruid']) . ')</a></li>';
                    } else {
                        echo '<li><a href="login.php">Sisse loogimine</a></li>';
                        echo '<li><a href="signup.php">Registreerimine</a></li>';
                    }
                    ?>
                </ul>
            </nav>
            <form action="Sisselogimisvorm/login.inc.php" method="post">
                <div class="login-content">
                    <header class="login-header">
                        <h2>Sisse loogimine</h2>
                        <p>Logi siia oma kasutajanime ja parooli abil</p>
                    </header>
                    <div class="login-field">
                        <span class="input-icon"><i class="fa fa-user-circle"></i></span>
                        <input class="form-input" id="txt-input" type="text" name="uid" placeholder="Kasutaja nimi" required>
                    </div>
                    <div class="login-field">
                        <span class="input-icon"><i class="fa fa-key"></i></span>
                        <input class="form-input" type="password" placeholder="Parool" id="pwd" name="pwd" required>
                    </div>
                    <div class="login-action">
                        <button class="log-in-button" type="submit" name="submit">Loogi sisse</button><br><br>
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
                <footer class="login-footer">
                    <?php
                    echo "Daria Halchenko &copy; ";
                    echo date('Y');
                    ?>
                </footer>
            </form>
        </div>
    </div>
</main>
</body>
</html>

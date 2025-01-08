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
    <div class="signup-overlay">
        <section class="signup-form">
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
            <form action="Sisselogimisvorm/signup.inc.php" method="post">
                <div class="signup-container">
                    <header class="signup-header">
                        <h2>Registreerimine</h2>
                        <p>Palun täitke allolevad väljad, et registreeruda</p>
                    </header>
                    <div class="signup-field">
                        <span class="input-icon"><i class="fa fa-user-circle"></i></span>
                        <input class="form-input" type="text" name="name" placeholder="Nimi" required>
                    </div>
                    <div class="signup-field">
                        <span class="input-icon"><i class="fa fa-envelope"></i></span>
                        <input class="form-input" type="email" name="email" placeholder="E-Post" required>
                    </div>
                    <div class="signup-field">
                        <span class="input-icon"><i class="fa fa-user"></i></span>
                        <input class="form-input" type="text" name="uid" placeholder="Kasutaja nimi" required>
                    </div>
                    <div class="signup-field">
                        <span class="input-icon"><i class="fa fa-key"></i></span>
                        <input class="form-input" type="password" name="pwd" placeholder="Parool" required>
                    </div>
                    <div class="signup-field">
                        <span class="input-icon"><i class="fa fa-key"></i></span>
                        <input class="form-input" type="password" name="pwdrepeat" placeholder="Korda parool" required>
                    </div>
                    <div class="signup-action">
                        <button class="signup-button" type="submit" name="submit">Registreeri</button><br><br>
                    </div>
                    <?php
                    if (isset($_GET["error"])) {
                        if ($_GET["error"] == "emptyinput") {
                            echo "<p class='error-message'>Ei ole täidetud kõik väljad</p>";
                        }
                        if ($_GET["error"] == "invalidusername") {
                            echo "<p class='error-message'>Kasutajanimi omab lubamatuid sümboleid</p>";
                        }
                        if ($_GET["error"] == "invalidemail") {
                            echo "<p class='error-message'>E-posti aadressi vale formaat</p>";
                        }
                        if ($_GET["error"] == "passwordmismatch") {
                            echo "<p class='error-message'>Paroolid ei klapi</p>";
                        }
                        if ($_GET["error"] == "usernametaken") {
                            echo "<p class='error-message'>Kasutajanimi on juba kasutusel</p>";
                        }
                        if ($_GET["error"] == "stmtfailed") {
                            echo "<p class='error-message'>Tekkis viga, proovige hiljem</p>";
                        }
                        if ($_GET["error"] == "emailregistered") {
                            echo "<p class='error-message'>Email on juba registreeritud</p>";
                        }
                        if ($_GET["error"] == "none") {
                            echo "<p class='success-message'>Teid registreeriti. Tere tulemast!</p>";
                        }
                    }
                    ?>
                    <footer class="signup-footer"><br>
                        <?php
                        echo "Daria Halchenko &copy; ";
                        echo date('Y');
                        ?>
                    </footer>
                </div>
            </form>
        </section>
    </div>
</main>
</body>
</html>

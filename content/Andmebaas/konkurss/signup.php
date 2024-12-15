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
        <section class="signup-form">
            <div class="con">
                <header class="head-form">
                    <h2>Registreerimine</h2>
                    <p>Palun täitke allolevad väljad, et registreeruda</p>
                </header>
                <form action="user_handler/signup.inc.php" method="post">
                    <div class="field-set">
                        <span class="input-item"><i class="fa fa-user-circle"></i></span>
                        <input class="form-input" type="text" name="name" placeholder="Nimi" required>
                    </div>
                    <div class="field-set">
                        <span class="input-item"><i class="fa fa-envelope"></i></span>
                        <input class="form-input" type="email" name="email" placeholder="E-Post" required>
                    </div>
                    <div class="field-set">
                        <span class="input-item"><i class="fa fa-user"></i></span>
                        <input class="form-input" type="text" name="uid" placeholder="Kasutaja nimi" required>
                    </div>
                    <div class="field-set">
                        <span class="input-item"><i class="fa fa-key"></i></span>
                        <input class="form-input" type="password" name="pwd" placeholder="Parool" required>
                    </div>
                    <div class="field-set">
                        <span class="input-item"><i class="fa fa-key"></i></span>
                        <input class="form-input" type="password" name="pwdrepeat" placeholder="Korda parool" required>
                    </div>
                    <div class="field-set">
                        <button class="log-in" type="submit" name="submit">Registreeri</button>
                    </div>
                    <?php
                    if (isset($_GET["error"])) {
                        if ($_GET["error"] == "emptyinput") {
                            echo "<p>Palun täitke kõik väljad!</p>";
                        }
                        if ($_GET["error"] == "invalidusername") {
                            echo "<p>Kasutajanimi sisaldab kehtetuid märke.</p>";
                        }
                        if ($_GET["error"] == "invalidemail") {
                            echo "<p>Kehtetu e-posti aadress.</p>";
                        }
                        if ($_GET["error"] == "passwordmismatch") {
                            echo "<p>Te sisestasite kaks erinevat parooli.</p>";
                        }
                        if ($_GET["error"] == "usernametaken") {
                            echo "<p>Vabandust, see kasutajanimi on juba hõivatud.</p>";
                        }
                        if ($_GET["error"] == "stmtfailed") {
                            echo "<p>Midagi läks valesti, proovige hiljem uuesti.</p>";
                        }
                        if ($_GET["error"] == "emailregistered") {
                            echo "<p>Te olete selle e-posti aadressiga juba registreerunud.</p>";
                        }
                        if ($_GET["error"] == "none") {
                            echo "<p>Teid registreeriti. Tere tulemast!</p>";
                        }
                    }
                    ?>
                </form>
            </div>
        </section>
    </div>
</main>
</body>
</html>

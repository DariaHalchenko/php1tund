<?php
include 'header.php';
?>

    <section class="signup-form">
        <div>
            <h2>Registreerimine</h2>
            <form action="user_handler/signup.inc.php" method="post">
                <p><input type="text" name="name" placeholder="Nimi"></p>
                <p><input type="text" name="email" placeholder="E-Post"></p>
                <p><input type="text" name="uid" placeholder="Kasutaja nimi"></p>
                <p><input type="password" name="pwd" placeholder="Parool"></p>
                <p><input type="password" name="pwdrepeat" placeholder="Korda parool"></p>
                <p><button type="submit" name="submit">Registreeri</button></p>
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

<?php
include 'footer.php';
?>
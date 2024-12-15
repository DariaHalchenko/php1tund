<?php
include 'header.php';
?>
    <div>
        <section class="signup-form">
            <h2>Sisse loogimine</h2>
            <form action="user_handler/login.inc.php" method="post">
                <p><input type="text" name="uid" placeholder="Kasutaja nimi"></p>
                <p><input type="password" name="pwd" placeholder="Parool"></p>
                <p><button type="submit" name="submit">Loogi sisse</button></p>
                <?php
                if( isset($_GET["error"]) )
                {
                    if( $_GET["error"] == "emptyinput" )
                    {
                        echo "<p>Täida kõik väljad</p>";
                    }
                    if( $_GET["error"] == "wronglogin" )
                    {
                        echo "<p>Vale andmed</p>";
                    }
                }
                ?>
            </form>
        </section>

    </div>
<?php
include 'footer.php';
?>
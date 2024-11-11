<h2>HP - Tõõ pildifailidega</h2>
<div id="pilt">
    <a href="https://www.metshein.com/unit/php-pildifailidega-ulesanne-14/">Tõõ pildifailidega</a>
    <form method="post" action="">
        <select name="pildid">
            <option value="">Vali pilt</option>
            <?php
            $kataloog = 'content/img';
            $asukoht = opendir($kataloog);
            while ($rida = readdir($asukoht)) {
                if ($rida != '.' && $rida != '..') {
                    echo "<option value='$rida'>$rida</option>\n";
                }
            }
            closedir($asukoht);
            ?>
        </select>
        <input type="submit" value="Vaata">
        <input name="random" type="submit" value="Random picture">
    </form>
    <?php
    $pildimassiv = array(
        "kevad.jpg", "sugis.jpg", "suvi.jpg", "talv.jpg"
    ); //massiv kus pildid asuvad

    if (!empty($_POST['pildid']) || isset($_POST['random'])) {
        if (isset($_POST['random'])) {
            $pilt = $pildimassiv[array_rand($pildimassiv)]; //Valime suvalise massiivi elemendi
        } else {
            $pilt = $_POST['pildid']; //kasutame 'pildid' välja väärtust
        }

        $pildi_aadress = 'content/img/' . $pilt;
        $pildi_andmed = getimagesize($pildi_aadress);

        if ($pildi_andmed) {
            $laius = $pildi_andmed[0];
            $korgus = $pildi_andmed[1];
            $formaat = $pildi_andmed[2];

            $max_laius = 120;
            $max_korgus = 90;

            //suhtearvu leidmine
            if($laius <= $max_laius && $korgus <= $max_korgus){
                $ratio = 1;
            } elseif($laius > $korgus){
                $ratio = $max_laius / $laius;
            } else {
                $ratio = $max_korgus / $korgus;
            }
            //uute mõõtmete leidmine
            $pisi_laius = round($laius * $ratio);
            $pisi_korgus = round($korgus * $ratio);
            echo '<h3>Originaal pildi andmed</h3>';
            echo "Laius: $laius<br>";
            echo "Kõrgus: $korgus<br>";
            echo "Formaat: $formaat<br>";
            echo '<h3>Uue pildi andmed</h3>';
            echo "Arvutamise suhe: $ratio <br>";
            echo "Laius: $pisi_laius<br>";
            echo "Kõrgus: $pisi_korgus<br>";
            echo "<img width='$pisi_laius' src='$pildi_aadress'><br>";
        }
    }
    ?>
</div>
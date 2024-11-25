<?php
require ('conf.php');
global $yhendus;
//kustutamine
if(isset($_REQUEST["kustuta"])){
    $kask=$yhendus->prepare("DELETE FROM osalejad WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}
//tabeli andmete lisamine
if(isset($_REQUEST["nimi"]) && !empty($_REQUEST["nimi"])){
    global $yhendus;
    $paring=$yhendus->prepare("INSERT INTO osalejad(nimi, telefon, pilt, synniaeg)
VALUES (?, ?, ?, ?)");
    //i- integer, s- string
    $paring->bind_param("ssss", $_REQUEST["nimi"], $_REQUEST["telefon"], $_REQUEST["pilt"], $_REQUEST["synniaeg"]);
    $paring->execute();
}
// Funktsioon vanuse arvutamiseks
function kalkulaator($birthDate) {
    $birthDate = new DateTime($birthDate);
    $praeguneKyypaev = new DateTime();
    $intervall = $birthDate->diff($praeguneKyypaev);
    return $intervall->y;
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>Matkajad 1 kaupa</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<header>
    <h1>Matkajad</h1>
</header>
<main>
    <div id="matkajad">
        <table>
                <?php
                $rida = 0; //Строка
                //tabeli sisu kuvamine
                global $yhendus;
                $paring=$yhendus->prepare("SELECT id, nimi, telefon, pilt, synniaeg FROM osalejad");
                $paring->bind_result($id, $nimi, $telefon, $pilt, $synniaeg);
                $paring->execute();
                echo "<tr>";
                while($paring->fetch()){
                    echo "<td><a href='?kasutaja_id=$id'> <img src='$pilt' alt='kasutaja' width='100' height='100'></a></td>";
                    $rida++;
                    if ($rida % 2 == 0) {
                        echo "</tr><tr>";
                    }
                }
                echo "</tr>";
                ?>
        </table>
        <?php
        echo "<a href='?lisamine=jah'>LISA...</a>";
        ?>
    </div>
    <div id="info">
        <?php
        //kui klik looma nimele, siis näitame looma info
        if(isset($_REQUEST["kasutaja_id"])) {
            $paring = $yhendus->prepare("SELECT id, nimi, telefon, pilt, synniaeg From osalejad WHERE id = ?");
            $paring->bind_result($id, $nimi, $telefon, $pilt, $synniaeg);
            $paring->bind_param("i", $_REQUEST["kasutaja_id"]);
            $paring->execute();
            //näitame ühe kaupa
            if ($paring->fetch()) {
                echo "<br>Nimi: ".$nimi;
                echo "<br>Telefon: ".$telefon;
                echo "<br><img src='$pilt' width='100px' alt='pilt'>";
                echo "<br>Sünniaeg: ".$synniaeg;
                echo "<br><a href='?kustuta=$id'>Kustuta</a>";
            }
        }
        ?>
    </div>
    <?php
    //lisamisvorm, mis avatakse kui vajutatud lisa...
    if(isset($_REQUEST["lisamine"])){
        ?>
        <!--tabeli lisamisVorm-->
        <form action="?" method="post">
            <label for="nimi">Nimi</label>
            <input type="text" id="nimi" name="nimi">
            <br>
            <label for="telefon">Telefon</label>
            <input type="text" id="telefon" name="telefon">
            <br>
            <label for="pilt">Loomapilt</label>
            <textarea id="pilt" name="pilt" cols="30" rows="10">Sisesta pildi link</textarea>
            <br>
            <label for="synniaeg">Synniaeg</label>
            <input type="date" id="synniaeg" name="synniaeg">
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
<footer>
    <?php
    echo "Daria Halchenko &copy;";
    echo date('Y');
    ?>
</footer>

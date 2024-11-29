<!DOCTYPE html>
<html lang="et">
<head>
    <title>Anekdoodid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="anekdood.css">
</head>
<body>
<div class="w3-container w3-green">
    <?php include('header.php'); ?>
</div>

<div class="content-wrapper">
    <?php
    include('nav.php');
    ?>
    <div class="main-content">
        <h2>Anekdoodid</h2>
        <div class="anecdood-content">
            <?php
            if (isset($_GET['anekdood'])) {
                $anecdood_arv = intval($_GET['anekdood']);
                $faili = "anekdood{$anecdood_arv}.php";
                if (file_exists($faili)) {
                    include($faili);
                }
            }
            ?>
        </div>
    </div>
</div>
<div class="w3-container w3-green">
    <?php include('footer.php'); ?>
</div>
</body>
</html>
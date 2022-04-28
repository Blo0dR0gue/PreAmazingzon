<?php
error_reporting(-1);
ini_set('display_errors', 'On');
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Amazinzon</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="script.js" defer type="module"/>

    <link rel="stylesheet" href="styles.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossOrigin="anonymous">
</head>
<body>
<header class="jumbotron">
    <div class="container">
        <h1>Willkommen auf Amazinzon</h1>
    </div>
</header>
    <section class="container" id="products">
        <div class="row">
            <div class="col">
                <?php include "include/itemCard.php";?>
            </div>
        </div>
    </section>
</body>
</html>

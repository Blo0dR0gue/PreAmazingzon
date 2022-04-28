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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossOrigin="anonymous">
</head>
<body>

<header>
<div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            <strong>Amazinzon</strong>
        </a>
    </div>
</div>
</header>


    <section class="container" id="products">
        <div class="row">
            <div class="col">
                <?php include "include/itemCard.php";?>
            </div>
            <div class="col">
                <?php include "include/itemCard.php";?>
            </div>
            <div class="col">
                <?php include "include/itemCard.php";?>
            </div>
            <div class="col">
                <?php include "include/itemCard.php";?>
            </div>
        </div>
    </section>
</body>
</html>

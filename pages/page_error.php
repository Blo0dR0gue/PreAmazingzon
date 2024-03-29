<?php require_once "../include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - ERROR</title>
</head>

<body class="d-flex flex-column h-100">

<?php
if (!isset($_GET["errorCode"])) {
    $_GET["errorCode"] = "503";
}

switch ($_GET["errorCode"]) {
    case "500":
        $errorCode = "500";
        $errorHead = "An error has occurred.";
        $errorMsg = "Internal server error!";
        break;
    case "503":
    default:
        $errorCode = "503";
        $errorHead = "An error has occurred.";
        $errorMsg = "Service unavailable!";
}
?>

<!-- main body -->
<main class="flex-shrink-0" id="content">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold"><?= $errorCode ?></h1>
            <p class="fs-3"><span class="text-danger">Opps!</span> <?= $errorHead ?></p>
            <p class="lead"><?= $errorMsg ?></p>
            <a href="<?= ROOT_DIR ?>" class="btn btn-warning">Try again</a>
        </div>
    </div>
</main>

</body>
</html>
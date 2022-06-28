<!-- TODO COMMENT -->
<?php require_once "../include/site_php_head.inc.php" ?>

<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <?php require_once INCLUDE_DIR . "site_html_head.inc.php"; ?>
    <title><?= PAGE_NAME ?> - About</title>
</head>

<body class="d-flex flex-column h-100">
<!-- header -->
<?php require_once INCLUDE_DIR . "site_header.inc.php"; ?>

<!-- main body -->
<main class="flex-shrink-0" id="content">
    <div class="container mt-5">
        <h1 class="frame mb-4">About us</h1>

        <div class="border-bottom pb-4">
            <h3 class="mb-3">Information according to § 5 TMG</h3>
            <p><strong>Amazingzon Inc.</strong></p>
            <p>Glockengießerwall 8-10<br>20095 Hamburg</p>
            <p class="mb-1"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp &nbsp02973 974430</p>
            <i class="fa fa-envelope" aria-hidden="true">&nbsp &nbsp</i><a href="mailto:info@amazingzon.com">info@amazingzon.com</a>
        </div>

        <div>
            <h3 class="mt-3">Disclaimer</h3>
            <div class="ce-bodytext">
                <p><strong>Liability for contents</strong><br>
                    As a service provider, we are responsible for our own content on these pages in accordance with
                    general legislation pursuant to Section 7 (1) of the German Telemedia Act (TMG). According to §§ 8
                    to 10 TMG, however, we are not obligated as a service provider to monitor transmitted or stored
                    third-party information or to investigate circumstances that indicate illegal activity. Obligations
                    to remove or block the use of information under the general laws remain unaffected. However,
                    liability in this regard is only possible from the point in time at which a concrete infringement of
                    the law becomes known. If we become aware of such infringements, we will remove this content
                    immediately.
                </p>
                <p><strong>Liability for links</strong><br>
                    Our offer contains links to external websites of third parties, on whose contents we have no
                    influence. Therefore, we cannot assume any liability for these external contents. The respective
                    provider or operator of the pages is always responsible for the content of the linked pages. The
                    linked pages were checked for possible legal violations at the time of linking. Illegal contents
                    were not recognizable at the time of linking. However, a permanent control of the contents of the
                    linked pages is not reasonable without concrete evidence of a violation of the law. If we become
                    aware of any infringements, we will remove such links immediately.
                </p>
                <p><strong>Copyright</strong><br>
                    The content and works created by the site operators on these pages are subject to German copyright
                    law. The reproduction, editing, distribution and any kind of exploitation outside the limits of
                    copyright require the written consent of the respective author or creator. Downloads and copies of
                    this site are only permitted for private, non-commercial use. Insofar as the content on this site
                    was not created by the operator, the copyrights of third parties are respected. In particular,
                    third-party content is identified as such. Should you nevertheless become aware of a copyright
                    infringement, please inform us accordingly. If we become aware of any infringements, we will remove
                    such content immediately.
                </p>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
</main>

<!-- footer -->
<?php require_once INCLUDE_DIR . "site_footer.inc.php"; ?>

</body>
</html>

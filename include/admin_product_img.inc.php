<!--Represents an uploaded file in the edit or add product page-->
<?php
//Ad the php header, so ajax calls work.
require_once "./site_php_head.inc.php";
?>
<div class="img-box">
    <img src="<?= IMAGE_DIR . DS . 'products' . DS . 'notfound.jpg' ?>"
         class="tbl-img" alt="product_img">
    <button type="button" class="btn btn-warning btn-sm" onclick="deleteImg(this)" data-id="<?= $imgID ?? -1 ?>">
        Delete
    </button>
    <button type="button" name="setMainBtn" class="btn btn-danger btn-sm" onclick="setMainImg(this)"
            data-id="<?= $imgID ?? -1 ?>">
        Set Main
    </button>
</div>
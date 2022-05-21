<!--Represents an uploaded file in the edit or add product page //TODO Comments-->
<div class="img-box">
    <!-- set the image source, if it is present (edit mode) -->
    <img src="<?= $img ?? "" ?>"
         class="tbl-img" alt="product_img">
    <!-- delete image, pass true to the deleteImg function, if it is a new image which is not uploaded yet. -->
    <button type="button" class="btn btn-warning btn-sm" onclick="deleteImg(this, <?php echo isset($isNewImg) ? 'false' : 'true' ?>)" data-id="<?= $imgID ?? -1 ?>">
        Delete
    </button>
    <!-- In edit mode, if we add the main image, change the set main image button to is main image button -->
    <?php if(isset($isMainImg) && $isMainImg): ?>
    <button type="button" name="setMainBtn" class="btn btn-success btn-sm" onclick="setMainImg(this)"
            data-id="<?= $imgID ?? -1 ?>">
        Main
    </button>
    <?php else:?>
    <button type="button" name="setMainBtn" class="btn btn-danger btn-sm" onclick="setMainImg(this)"
            data-id="<?= $imgID ?? -1 ?>">
        Set Main
    </button>
    <?php endif; ?>
</div>
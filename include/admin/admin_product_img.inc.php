<!--Represents an uploaded file in the edit or add product page //TODO Comments-->
<div class="img-box">
    <img src="<?= $img ?? "" ?>"
         class="tbl-img" alt="product_img">
    <button type="button" class="btn btn-warning btn-sm" onclick="deleteImg(this, <?php echo isset($isNewImg) ? 'false' : 'true' ?>)" data-id="<?= $imgID ?? -1 ?>">
        Delete
    </button>
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
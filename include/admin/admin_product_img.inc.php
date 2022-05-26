<!-- Represents an uploaded file in the edit or add product page -->
<div class="img-box mb-3 col-xl-4 col-lg-6">
    <!-- set the image source, if it is present (edit mode) -->
    <div class="border rounded d-flex justify-content-center align-items-center overflow-hidden mb-1"
         style="height: 200px;">
        <img src="<?= $img ?? "" ?>" class="mh-100 mw-100" alt="product_img">
    </div>

    <!-- delete image, pass true to deleteImg function, if it is a new image which is not uploaded yet. -->
    <button type="button" class="btn btn-warning btn-sm" data-id="<?= $imgID ?? -1 ?>"
            onclick="deleteImg(this, <?php echo isset($isNewImg) ? 'false' : 'true' ?>)">
        <i class="fa fa-trash "></i>
    </button>

    <!-- in edit mode, if we add the main image, change the set main image button to is main image button -->
    <?php if (isset($isMainImg) && $isMainImg): ?>
        <button type="button" name="setMainBtn" class="btn btn-success btn-sm" onclick="setMainImg(this)"
                data-id="<?= $imgID ?? -1 ?>">
            Main
        </button>
    <?php else: ?>
        <!-- add the set main image button, if we do not add them via php, and it's not the main image. (Gets updated via javascript) -->
        <button type="button" name="setMainBtn" class="btn btn-secondary btn-sm" onclick="setMainImg(this)"
                data-id="<?= $imgID ?? -1 ?>">
            Main
        </button>
    <?php endif; ?>
</div>
<?php

//Add models
require_once MODEL_DIR . "model_product.php";
require_once MODEL_DIR . "model_category.php";

class ProductController
{

    /**
     * Search for {@link Product}s an only show a specific amount.
     * @param string $search The search string.
     * @param bool $onlyActiveProducts true, if only active products should be selected.
     * @param int $offset The offset from where to select from the database
     * @param int $amount The amount of products, which should be selected.
     * @return array An array with all found {@link Product}s in range.
     */
    public static function searchProductsInRange(string $search, bool $onlyActiveProducts, int $offset = 0, int $amount = 8): array
    {
        return Product::searchProductsInRange($search, $onlyActiveProducts, $offset, $amount);
    }

    /**
     * Gets all {@link Product}s.
     * @return array An array with all {@link Product}s.
     */
    public static function getAllProducts(): array
    {
        return Product::getAllProducts();
    }

    /**
     * Gets all {@link Product} in range
     * @param bool $onlyActiveProducts true, if only active {@link Product}s should be selected.
     * @param int $offset The offset from where to select from the database
     * @param int $amount The amount of products, which should be selected.
     * @return array An array with all found {@link Product}s in range.
     */
    public static function getProductsInRange(bool $onlyActiveProducts, int $offset = 0, int $amount = 8): array
    {
        return Product::getProductsInRange($offset, $amount, $onlyActiveProducts);
    }

    /**
     * Gets all {@link Product} for a category in a specific range.
     * @param int $categoryId The id of the category
     * @param int $offset The offset from where to select from the database
     * @param int $amount The amount of products, which should be selected.
     * @return array An array with all found {@link Product}s in range.
     */
    public static function getProductsByCategoryIDInRange(int $categoryId, int $offset = 0, int $amount = 8): array
    {
        if ($categoryId != -1) {
            return Product::getProductsByCategoryIDInRange($categoryId, $offset, $amount);
        } else {
            return Product::getProductsByCategoryIDInRange(null, $offset, $amount);
        }
    }

    /**
     * Gets random {@link Product}s.
     * @param int $amount The amount of how many should be selected.
     * @return array An array random {@link Product}s.
     */
    public static function getRandomProducts(int $amount = 4): array
    {
        return Product::getRandomProducts($amount);
    }

    /**
     * Gets a {@link Product} by its id.
     * @param int $productID The id of the product.
     * @return Product|null The {@link Product} or null, if not found.
     */
    public static function getByID(int $productID): ?Product
    {
        if ($productID == null || $productID == 0) {
            return null;
        }

        return Product::getByID($productID);
    }

    /**
     * Decreases the stock amount for a {@link Product}.
     * @param int $amount The decrease amount.
     * @param Product $product The {@link Product}.
     * @return Product|null The updated {@link Product} or null, if an error occurred.
     */
    public static function decreaseStockAmount(int $amount, Product $product): ?Product
    {
        $currentStock = $product->getStock();

        if ($currentStock < $amount) {
            header("LOCATION: " . PAGES_DIR . 'page_error.php?errorCode=500');
            return null;
        } else {
            $product->setStock($product->getStock() - $amount);
            return $product->update();
        }
    }

    /**
     * Updates an {@link Product}.
     * @param Product $product The {@link Product}.
     * @param string $title The new title.
     * @param int $categoryID The new category id.
     * @param string $description The new description.
     * @param float $price The new price.
     * @param float $shippingCost The new shipping cost.
     * @param int $stock The new stock amount.
     * @param bool $active Is the product active?
     * @return Product|null The updated {@link Product} or null, if an error occurred.
     */
    public static function update(Product $product, string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock, bool $active): ?Product
    {
        $product->setCategoryID($categoryID == -1 ? null : $categoryID);
        $product->setTitle(htmlspecialchars($title, ENT_QUOTES, 'UTF-8'));
        $product->setDescription(htmlspecialchars($description, ENT_QUOTES, 'UTF-8'));
        $product->setPrice($price);
        $product->setShippingCost($shippingCost);
        $product->setStock($stock);
        $product->setActive($active);

        return $product->update();
    }

    /**
     * Creates an new {@link Product}.
     * @param string $title The title.
     * @param int $categoryID The category id.
     * @param string $description The description.
     * @param float $price The price.
     * @param float $shippingCost The shipping cost.
     * @param int $stock The stock amount.
     * @param bool $active Is the prompt active?
     * @return Product|null
     */
    public static function insert(string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock, bool $active): ?Product
    {
        // TODO validation
        $product = new Product(
            0,
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $price,
            $stock,
            $shippingCost,
            $categoryID == -1 ? null : $categoryID,
            $active
        );

        $product = $product->insert();

        if (!$product) {
            return null;
        }

        return $product;
    }

    /**
     * Deletes a product from the database and all its images.
     * @param Product $product The product, which should be deleted
     * @return bool true, if the product got deleted.
     */
    public static function delete(Product $product): bool
    {
        $productDeleted = $product->delete();

        if ($productDeleted) {
            self::deleteAllImages($product->getId());
            return true;
        }
        return false;
    }

    /**
     * Deletes all images of the passed product id.
     * @param int $productID The product id
     * @return void
     */
    private static function deleteAllImages(int $productID): void
    {
        $targetDir = IMAGE_PRODUCT_DIR . $productID;
        if (file_exists($targetDir) && is_dir($targetDir)) {
            self::removeDirectoryRec($targetDir);
        }
    }

    /**
     * Deletes a directory with all sub-folders and files.
     * @param string $path
     * @return void
     */
    private static function removeDirectoryRec(string $path): void
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? self::removeDirectoryRec($file) : unlink($file);
        }
        rmdir($path);
    }

    /**
     * Returns the amounts of active of products stored in the database using a filter, if it is defined.
     * @param string|null $searchFilter A filter, which is used to test, if the passed string is either in the description, the title or in the name of the category of a product.
     * @return int  The amount of found products
     */
    public static function getAmountOfActiveProducts(?string $searchFilter): int
    {
        return Product::getAmountOfProducts($searchFilter, true);
    }

    /**
     * Returns the amounts of products stored in the database using a filter, if it is defined.
     * @param string|null $searchFilter A filter, which is used to test, if the passed string is either in the description, the title or in the name of the category of a product.
     * @return int  The amount of found products
     */
    public static function getAmountOfProducts(?string $searchFilter): int
    {
        return Product::getAmountOfProducts($searchFilter, false);
    }

    /**
     * Returns the amounts of products stored in a given category.
     * @param int $categoryId CategoryId in which to count the products
     * @return int  The amount of found products
     */
    public static function getAmountOfActiveProductsInCategory(int $categoryId): int
    {
        if ($categoryId != -1) {
            return Product::getAmountOfProductsInCategory($categoryId, true);
        } else {
            return Product::getAmountOfProductsInCategory(null, true);
        }

    }

    // region image related

    /**
     * Deletes images from the filesystem.
     * @param int|null $productID The id of the product, from which the images should be deleted.
     * @param array|null $fileNames The names of the images, which should be deleted.
     * @return bool true, if an error occurred.
     */
    public static function deleteSelectedImages(?int $productID, ?array $fileNames): bool
    {
        if (!isset($fileNames) || !count($fileNames) > 0 || $fileNames[0] == "" || !isset($productID)) {
            return false;
        }

        $errors = false;

        $targetDir = IMAGE_PRODUCT_DIR . $productID;
        foreach ($fileNames as $fileName) {
            $targetFile = $targetDir . DS . $fileName;
            if (file_exists($targetFile)) {
                //Delete the file.
                unlink($targetFile);
            } else {
                $errors = true;
            }
        }

        return $errors;
    }

    /**
     * Sets/Updates the maint image for a product
     * @param int|null $productID The id of the product.
     * @param string|null $newMainImgFileName The image name of the new main image.
     * @return bool true, if an error occurred.
     */
    public static function updateMainImg(?int $productID, ?string $newMainImgFileName): bool
    {
        if (!isset($productID) || !isset($newMainImgFileName) || $newMainImgFileName == "") {
            return false;
        }    //No error

        $targetDirWithSep = IMAGE_PRODUCT_DIR . $productID . DS;
        $targetFile = $targetDirWithSep . $newMainImgFileName;
        $imgNameParts = explode(".", $newMainImgFileName);

        if (sizeof($imgNameParts) < 2) {
            return false;
        }   //It's a new image, wait until its uploaded.

        self::removeAllMainImgTags($productID);

        if (file_exists($targetFile)) {
            if (sizeof($imgNameParts) == 2) {
                rename($targetFile, $targetDirWithSep . $imgNameParts[0] . 'main' . "." . $imgNameParts[1]);
                return false;   //No error
            }
        }
        return true;     //Error
    }

    /**
     * Removes all main image tags from each image for a product.
     * @param int $productID The if of the product.
     * @return void
     */
    private static function removeAllMainImgTags(int $productID): void
    {
        $mainImages = glob(IMAGE_PRODUCT_DIR . $productID . DS . "*main.*");
        if (count($mainImages) > 0) {
            foreach ($mainImages as $mainImage) {
                $newName = str_replace("main", "", $mainImages);
                //just override the file, even if it exists, because this should never happen. There should never be two files named e.g. 4.png and 4main.png at the same time.
                rename($mainImage, $newName[0]);
            }
        }
    }

    /**
     * Uploads images for a product.
     * @param int|null $productID The id of the product.
     * @param array|null $files The images, which should be uploaded.
     * @param string|null $mainImgID The id of the main image.
     * @return bool true, if an error occurred.
     */
    public static function uploadImages(?int $productID, ?array $files, ?string $mainImgID): bool
    {
        if (!isset($files) || !count($files) > 0 || !isset($productID)) {
            return false;
        }

        $targetUploadDir = IMAGE_PRODUCT_DIR . $productID;

        $errors = false;

        for ($i = 0; $i < count($files["tmp_name"]); $i++) {
            $suc = self::uploadImage($files["tmp_name"][$i], $targetUploadDir, $productID, is_numeric($mainImgID) && $i == intval($mainImgID));
            if (!$suc && !$errors) {
                $errors = true;
            }
        }
        return $errors;
    }

    /**
     * Uploads an image.
     * @param string $tmpFile The name of the temporary file inside the cache.
     * @param string $targetUploadDir The upload dir.
     * @param int $productID The id of the product.
     * @param bool $isMainImg true, if this image should be the main image.
     * @return bool true, if successfully.
     */
    private static function uploadImage(string $tmpFile, string $targetUploadDir, int $productID, bool $isMainImg): bool
    {
        //Get the size of the image.
        $fileSize = filesize($tmpFile);

        //Get all infos for the image file.
        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($fInfo, $tmpFile);

        //Error, if the filesize is 0.
        if ($fileSize === 0) {
            return false;
        }

        $allowed = [
            "image/png" => "png",
            "image/jpg" => "jpg",
            "image/jpeg" => "jpg"
        ];

        //Is the datatype allowed?
        if (!in_array($type, array_keys($allowed))) {
            return false;
        }

        /**
         * Create the upload dir, if it doesn't exist.
         */
        if (!file_exists($targetUploadDir)) {
            mkdir($targetUploadDir, 0777, true);
        }

        //Get the ending for a file.
        $expand = $allowed[$type];

        //Get the amount of images in the dir for the product.
        $imageCounter = count(glob(IMAGE_PRODUCT_DIR . $productID . DS . "*"));

        //Is the max image amount reached?
        if ($imageCounter >= MAX_IMAGE_PER_PRODUCT) {
            return false;
        }

        //Define the name of the new image.
        if ($isMainImg) {
            self::removeAllMainImgTags($productID);
            $pictureID = ($imageCounter + 1) . "_" . $productID . "_" . self::generateRandomImageName() . "_main";
        } else {
            $pictureID = ($imageCounter + 1) . "_" . $productID . "_" . self::generateRandomImageName();
        }

        //Define the path to the file.
        $filePath = $targetUploadDir . DS . $pictureID . '.' . $expand;

        //The to create the file.
        if (!copy($tmpFile, $filePath)) {
            return false;
        }

        //Delete the cached file.
        unlink($tmpFile);

        return true;
    }

    /**
     * Generates a random name for an image.
     * @param $length The length of the name.
     * @return string The random name.
     */
    private static function generateRandomImageName($length = 10): string
    {
        $characters = '0123456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    // endregion
}
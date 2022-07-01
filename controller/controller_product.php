<?php
//TODO Comments + introduce regions

require_once MODEL_DIR . "model_product.php";
require_once MODEL_DIR . "model_category.php";

class ProductController
{

    public static function searchProductsInRange(string $search, bool $onlyActiveProducts, int $offset = 0, int $amount = 8): array
    {
        return Product::searchProductsInRange($search, $onlyActiveProducts, $offset, $amount);
    }

    public static function getAllProducts(): array
    {
        return Product::getAllProducts();
    }

    public static function getProductsInRange(bool $onlyActiveProducts, int $offset = 0, int $amount = 8): array
    {
        return Product::getProductsInRange($offset, $amount, $onlyActiveProducts);
    }

    public static function getRandomProducts(int $amount = 4): array
    {
        return Product::getRandomProducts($amount);
    }

    public static function getByID(int $productID): ?Product
    {
        if ($productID == null || $productID == 0){ return null; }

        return Product::getByID($productID);
    }

    public static function decreaseStockAmount(int $amount, Product $product): ?Product
    {
        $currentStock = $product->getStock();

        if ($currentStock < $amount) {
            //TODO error
            return null;
        } else {
            $product->setStock($product->getStock() - $amount);
            return $product->update();
        }
    }

    public static function update(Product $product, string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock): ?Product
    {
        $product->setCategoryID($categoryID);
        $product->setTitle(htmlspecialchars($title, ENT_QUOTES, 'UTF-8'));
        $product->setDescription(htmlspecialchars($description, ENT_QUOTES, 'UTF-8'));
        $product->setPrice($price);
        $product->setShippingCost($shippingCost);
        $product->setStock($stock);

        return $product->update();
    }

    public static function insert(string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock): ?Product
    {
        // TODO validation
        $product = new Product(
            0,
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $price,
            $stock,
            $shippingCost,
            $categoryID
        );

        $product = $product->insert();

        if (!$product) { return null; }

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
     * Returns the amounts of products stored in the database using a filter, if it is defined.
     * @param string|null $searchFilter A filter, which is used to test, if the passed string is either in the description, the title or in the name of the category of a product.
     * @return int  The amount of found products
     */
    public static function getAmountOfProducts(?string $searchFilter): int
    {
        return Product::getAmountOfProducts($searchFilter, false);
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

    public static function deleteSelectedImages(?int $productID, ?array $fileNames): bool
    {
        // TODO validation
        if (!isset($fileNames) || !count($fileNames) > 0 || $fileNames[0] == "" || !isset($productID)) { return false; }

        $errors = false;

        $targetDir = IMAGE_PRODUCT_DIR . $productID;
        foreach ($fileNames as $fileName) {
            $targetFile = $targetDir . DS . $fileName;
            if (file_exists($targetFile)) {
                unlink($targetFile);
            } else {
                $errors = true;
            }
        }

        return $errors;
    }

    public static function updateMainImg(?int $productID, ?string $newMainImgFileName): bool
    {
        if (!isset($productID) || !isset($newMainImgFileName) || $newMainImgFileName == "") { return false; }    //No error

        $targetDirWithSep = IMAGE_PRODUCT_DIR . $productID . DS;
        $targetFile = $targetDirWithSep . $newMainImgFileName;
        $imgNameParts = explode(".", $newMainImgFileName);

        if (sizeof($imgNameParts) < 2) { return false; }   //It's a new image, wait until its uploaded.

        self::removeAllMainImgTags($productID);

        if (file_exists($targetFile)) {
            if (sizeof($imgNameParts) == 2) {
                rename($targetFile, $targetDirWithSep . $imgNameParts[0] . 'main' . "." . $imgNameParts[1]);
                return false;   //No error
            }
        }
        return true;     //Error
    }

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

    public static function uploadImages(?int $productID, ?array $files, ?string $mainImgID): bool
    {
        // TODO validation
        if (!isset($files) || !count($files) > 0 || !isset($productID)) { return false; }

        $targetUploadDir = IMAGE_PRODUCT_DIR . $productID;

        $errors = false;

        for ($i = 0; $i < count($files["tmp_name"]); $i++) {
            $suc = self::uploadImage($files["tmp_name"][$i], $targetUploadDir, $productID, is_numeric($mainImgID) && $i == intval($mainImgID));
            if (!$suc && !$errors) { $errors = true; }
        }
        return $errors;
    }

    // TODO cleanup methods?
    private static function uploadImage(string $tmpFile, string $targetUploadDir, int $productID, bool $isMainImg): bool
    {// TODO validation
        $fileSize = filesize($tmpFile);

        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($fInfo, $tmpFile);

        if ($fileSize === 0) { return false; }

        $allowed = [
            "image/png" => "png",
            "image/jpg" => "jpg",
            "image/jpeg" => "jpg"
        ];

        if (!in_array($type, array_keys($allowed))) { return false; }

        if (!file_exists($targetUploadDir)) {
            mkdir($targetUploadDir, 0777, true);
        }

        $expand = $allowed[$type];

        $imageCounter = count(glob(IMAGE_PRODUCT_DIR . $productID . DS . "*"));

        if ($imageCounter >= MAX_IMAGE_PER_PRODUCT) { return false; }

        $pictureID = "";    // TODO unused?
        if ($isMainImg) {
            self::removeAllMainImgTags($productID);
            $pictureID = ($imageCounter + 1) . "_" . $productID . "_" . self::generateRandomImageName() . "_main";
        } else {
            $pictureID = ($imageCounter + 1) . "_" . $productID . "_" . self::generateRandomImageName();
        }

        $filePath = $targetUploadDir . DS . $pictureID . '.' . $expand;

        if (!copy($tmpFile, $filePath)) { return false; }

        unlink($tmpFile);

        return true;
    }

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
}
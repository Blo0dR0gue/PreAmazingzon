<!--TODO Comments -->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_product.php";
require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_category.php";

class ProductController
{
    public static function addNew(string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock): ?Product
    {
        // TODO validation
        $product = new Product(0, $title, $description, $price, $stock, $shippingCost, $categoryID);

        $product = $product->insert();

        if (!$product) return null;

        return $product;
    }

    public static function uploadImages(?int $productID, ?array $files, ?int $mainImgID): bool
    {
        // TODO validation
        if (!isset($files) || !count($files) > 0 || !isset($productID)) return true;

        $targetUploadDir = IMAGE_PRODUCT_DIR . DIRECTORY_SEPARATOR . $productID;

        $errors = false;

        for ($i = 0; $i < count($files["tmp_name"]); $i++) {
            $suc = self::uploadImage($files["tmp_name"][$i], $targetUploadDir, $productID, $i == $mainImgID);
            if (!$suc && !$errors) $errors = true;
        }
        return $errors;
    }

    private static function uploadImage(string $tmpFile, string $targetUploadDir, int $productID, bool $isMainImg): bool
    {// TODO validation
        $fileSize = filesize($tmpFile);

        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($fInfo, $tmpFile);

        if ($fileSize === 0) return false;

        $allowed = [
            "image/png" => "png",
            "image/jpg" => "jpg"
        ];

        if (!in_array($type, array_keys($allowed))) return false;

        if (!file_exists($targetUploadDir)) {
            mkdir($targetUploadDir, 0777, true);
        }

        $expand = $allowed[$type];

        $imageCounter = count(glob(IMAGE_PRODUCT_DIR . DIRECTORY_SEPARATOR . $productID . DIRECTORY_SEPARATOR . "*"));

        if ($imageCounter >= MAX_IMAGE_PER_PRODUCT) return false;

        $mainImages = glob(IMAGE_PRODUCT_DIR . DIRECTORY_SEPARATOR . $productID . DIRECTORY_SEPARATOR . "*main.*");

        $pictureID = "";
        if ($isMainImg)
        {
            if (count($mainImages) > 0)
            {
                foreach ($mainImages as $mainImage)
                {
                    $newName = str_replace("main", "", $mainImages);
                    //just override the file, even if it exists, because this should never happen. There should never be two files named e.g. 4.png and 4main.png at the same time.
                    rename($mainImage, $newName[0]);
                }
            }
            $pictureID = ($imageCounter + 1) . "main";
        } else
        {
            $pictureID = $imageCounter + 1;
        }

        $filePath = $targetUploadDir . DIRECTORY_SEPARATOR . $pictureID . '.' . $expand;

        if (!copy($tmpFile, $filePath)) return false;

        unlink($tmpFile);

        return true;
    }

    public static function searchProducts(string $search): array
    {// TODO validation
        return Product::searchProducts($search);
    }

    public static function getAllProducts(): array
    {
        return Product::getAllProducts();
    }

    public static function getProductsInRange(int $offset = 0, int $amount = 8): array
    {
        return Product::getProductsInRange($offset, $amount);
    }

    public static function getRandomProducts(int $amount = 4): array
    {
        return Product::getRandomProducts($amount);
    }

    public static function getByID(int $productID): ?Product
    {
        if ($productID == null || $productID == 0)
            return null;

        return Product::getByID($productID);
    }
}
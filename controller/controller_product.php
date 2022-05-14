<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_product.php";
require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_category.php";

class ProductController
{

    public static function addNew(string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock): ?Product
    {
        $product = new Product(0, $title, $description, $price, $stock, $shippingCost, $categoryID);

        $product = $product->insert();

        if (!$product) return null;

        return $product;
    }

    public static function uploadImages(?int $productID, ?array $files): bool{

        if(!isset($files) || !count($files) > 0 || !isset($productID)) return true;

        $targetUploadDir = IMAGE_DIR . DIRECTORY_SEPARATOR . $productID;

        $errors = false;

        foreach ($files as $file){
            $suc = self::uploadImage($files, $targetUploadDir);
            if(!$suc && !$errors) $errors = true;
        }
        return $errors;
    }

    private static function uploadImage($file, $targetUploadDir): bool {
        $tmpFile = $file["tmp_name"];;
        $fileSize = filesize($tmpFile);

        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($fInfo, $tmpFile);

        if($fileSize === 0) return false;

        $allowed = [
            "image/png" => "png",
            "image/jpg" => "jpg"
        ];

        if(!in_array($type, array_keys($allowed))) return false;

        if (!file_exists($targetUploadDir)) {
            mkdir($targetUploadDir, 0777, true);
        }

        $expand = $allowed[$type];

        $filePath = $targetUploadDir . DIRECTORY_SEPARATOR . time() . '.' . $expand;

        if(!copy($tmpFile, $filePath)) return false;

        unlink($tmpFile);

        return true;
    }

    public static function searchProducts(string $search): array
    {
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
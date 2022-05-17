<!--TODO Comments -->

<?php

require_once MODEL_DIR . DS . "model_product.php";
require_once MODEL_DIR . DS . "model_category.php";

class ProductController
{

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

    private static function generateRandomImageName($length = 10): string {
        $characters = '0123456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function addNew(string $title, int $categoryID, string $description, float $price, float $shippingCost, int $stock): ?Product
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

        if (!$product) return null;

        return $product;
    }

    public static function deleteSelectedImages(?int $productID, ?array $fileNames): bool
    {
        // TODO validation
        if (!isset($fileNames) || !count($fileNames) > 0 || $fileNames[0] == "" || !isset($productID)) return false;

        $errors = false;

        $targetDir = IMAGE_PRODUCT_DIR . DS . $productID;
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
        if (!isset($productID) || !isset($newMainImgFileName) || $newMainImgFileName == "") return false;    //No error

        $targetDirWithSep = IMAGE_PRODUCT_DIR . DS . $productID . DS;
        $targetFile = $targetDirWithSep . $newMainImgFileName;
        $imgNameParts = explode(".", $newMainImgFileName);

        if (sizeof($imgNameParts) < 2) return false;   //It's a new image, wait until its uploaded.

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
        $mainImages = glob(IMAGE_PRODUCT_DIR . DS . $productID . DS . "*main.*");
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
        if (!isset($files) || !count($files) > 0 || !isset($productID)) return false;

        $targetUploadDir = IMAGE_PRODUCT_DIR . DS . $productID;

        $errors = false;

        for ($i = 0; $i < count($files["tmp_name"]); $i++) {
            $suc = self::uploadImage($files["tmp_name"][$i], $targetUploadDir, $productID, is_numeric($mainImgID) && $i == intval($mainImgID));
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

        $imageCounter = count(glob(IMAGE_PRODUCT_DIR . DS . $productID . DS . "*"));

        if ($imageCounter >= MAX_IMAGE_PER_PRODUCT) return false;

        $pictureID = "";
        if ($isMainImg) {
            self::removeAllMainImgTags($productID);
            $pictureID = ($imageCounter + 1) . "_" . self::generateRandomImageName() . "main";
        } else {
            $pictureID = ($imageCounter + 1) . "_" . self::generateRandomImageName();
        }

        $filePath = $targetUploadDir . DS . $pictureID . '.' . $expand;

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
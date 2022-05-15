<!--TODO Comments -->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_cart_product.php";
require_once CONTROLLER_DIR . DIRECTORY_SEPARATOR . "controller_product.php";

class CartProductController
{

    public static function getAllByUser(int $userId): ?array
    {   // TODO validate?
        return CartProduct::getAllByUser($userId);
    }

    public static function getById(int $userId, int $productId): ?CartProduct
    {   // TODO validate?
        return CartProduct::getById($productId, $userId);
    }

    public static function getCountByUser(int $userId): int
    {   // TODO validate?
        return CartProduct::getCountByUser($userId);
    }

    public static function delete(CartProduct $cartProduct): bool
    {   // TODO validate?
        return $cartProduct->delete();
    }

    public static function add(int $userId, int $productId, int $amount): ?CartProduct
    {   // TODO validate?
        $cartProduct = CartProductController::getById($userId, $productId);     // is there already an entry?
        if ($cartProduct)
        {
            return CartProductController::incAmount($cartProduct, $amount);     // increase amount by selected amount
        } else
        {
            $cartProduct = new CartProduct($userId, $productId, $amount);       // insert new entry
            return $cartProduct->insert();
        }
    }

    public static function incAmount(CartProduct $cartProduct, int $by = 1): ?CartProduct
    {   // TODO validate?
        $product = ProductController::getByID($cartProduct->getProdId());

        if($product->getStock() >= $cartProduct->getAmount() + $by)   // can not sell more than in stock
        {
            $cartProduct->setAmount($cartProduct->getAmount() + $by);
            return $cartProduct->update();
        }elseif ($product->getStock() > $cartProduct->getAmount())   // fill up to stock
        {
            $cartProduct->setAmount($product->getStock());
            return $cartProduct->update();
        }
        return null;
    }

    public static function decAmount(CartProduct $cartProduct, int $by = 1): ?CartProduct
    {   // TODO validate?
        $product = ProductController::getByID($cartProduct->getProdId());

        if($cartProduct->getAmount() - $by > 1)     // can not sell less than one
        {
            $cartProduct->setAmount($cartProduct->getAmount() - $by);
            return $cartProduct->update();
        }
        return null;
    }
}
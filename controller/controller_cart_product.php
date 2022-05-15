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

    public static function incAmount(CartProduct $cartProduct): void
    {   // TODO validate?
        $product = ProductController::getByID($cartProduct->getProdId());

        if($product->getStock() > $cartProduct->getAmount())   // can not sell more than in stock
        {
            $cartProduct->setAmount($cartProduct->getAmount() + 1);
            $cartProduct->update();
        }
    }

    public static function decAmount(CartProduct $cartProduct): void
    {   // TODO validate?
        $product = ProductController::getByID($cartProduct->getProdId());

        if($cartProduct->getAmount() > 1)     // can not sell less than one
        {
            $cartProduct->setAmount($cartProduct->getAmount() - 1);
            $cartProduct->update();
        }
    }
}
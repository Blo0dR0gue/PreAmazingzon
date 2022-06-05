<?php
//TODO Comments

require_once MODEL_DIR . "model_cart_product.php";

class CartProductController
{
    public static function getAllByUser(int $userId): ?array
    {
        return CartProduct::getAllByUser($userId);
    }

    public static function getCountByUser(int $userId): int
    {
        return CartProduct::getCountByUser($userId);
    }

    public static function add(int $userId, int $productId, int $amount): ?CartProduct
    {
        $cartProduct = CartProductController::getById($userId, $productId);     // is there already an entry?
        if ($cartProduct) {
            return CartProductController::incAmount($cartProduct, $amount);     // increase amount by selected amount
        } else {
            $cartProduct = new CartProduct($userId, $productId, $amount);       // insert new entry
            return $cartProduct->insert();
        }
    }

    public static function getById(int $userId, int $productId): ?CartProduct
    {
        return CartProduct::getById($productId, $userId);
    }

    public static function incAmount(CartProduct $cartProduct, int $by = 1): ?CartProduct
    {
        $product = ProductController::getByID($cartProduct->getProdId());   // get product related to cartProduct

        if ($product->getStock() >= $cartProduct->getAmount() + $by) {  // can not sell more than in stock
            $cartProduct->setAmount($cartProduct->getAmount() + $by);
            return $cartProduct->update();
        } elseif ($product->getStock() > $cartProduct->getAmount()) {   // else fill up to stock
            $cartProduct->setAmount($product->getStock());
            return $cartProduct->update();
        }
        return null;
    }

    /**
     * Decreases the amount of products in cart to the max in stock, if we have more items in cart than it is in the stock. If the stock is 0 delete the item from the cart.
     * @param CartProduct $cartProduct The cartproduct-object
     * @return bool true, if the product got deleted from the cart
     */
    public static function handleOtherUserBoughtItemInCart(CartProduct $cartProduct): bool
    {
        require_once INCLUDE_DIR . "modal_popup.inc.php";
        $product = ProductController::getByID($cartProduct->getProdId());

        if (!isset($product)) {
            //Product does not exist
            $cartProduct->delete();
            show_popup(
                "Product Changed",
                "Product: '" . $product->getTitle() . "' got removed from your cart, because it does not exist anymore."
            );
            return true;
        }

        if ($cartProduct->getAmount() > $product->getStock()) {

            if ($product->getStock() <= 0) {
                //Remove it from the cart and show popup
                $cartProduct->delete();
                show_popup(
                    "Product Changed",
                    "Product: '" . $product->getTitle() . "' got removed from your cart, because the Stock is 0."
                );
                return true;
            } else {
                $newAmount = $product->getStock();
                $popupString = "The amount for the product: '" . $product->getTitle() . "' got decreased from " . $cartProduct->getAmount() . " to " . $newAmount . ", because there a no more items in stock!";
                self::decAmount($cartProduct, $cartProduct->getAmount() - $newAmount);

                show_popup(
                    "Product Changed",
                    $popupString
                );
            }

        }
        return false;
    }

    public static function delete(CartProduct $cartProduct): bool
    {
        return $cartProduct->delete();
    }

    public static function decAmount(CartProduct $cartProduct, int $by = 1): ?CartProduct
    {
        if ($cartProduct->getAmount() - $by >= 1) {  // can not sell less than one
            $cartProduct->setAmount($cartProduct->getAmount() - $by);
            return $cartProduct->update();
        }
        return null;
    }
}
<?php
// Add cart model.
require_once MODEL_DIR . "model_cart_product.php";

class CartProductController
{

    /**
     * Get all elements in the card for a user.
     * @param int $userId The id of the user.
     * @return array|null An array of shoppingcart_product or null, if an error occurred.
     */
    public static function getAllByUser(int $userId): ?array
    {
        return CartProduct::getAllByUser($userId);
    }

    /**
     * Gets the amount of items in the card for a user.
     * @param int $userId The id of the user.
     * @return int The amount of items in the card.
     */
    public static function getCountByUser(int $userId): int
    {
        return CartProduct::getCountByUser($userId);
    }

    /**
     * Adds a product to the card of a user. <br>
     * If the item is already in the card, the amount is getting increased.
     * @param int $userId The id of the user.
     * @param int $productId The id of the product
     * @param int $amount The number of products to add of the selected product.
     * @return CartProduct|null A {@link CartProduct} or null, if an error occurred.
     */
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

    /**
     * Gets an {@link CartProduct} by its id.
     * @param int $userId The user which has the element.
     * @param int $productId The id of the product the {@link CartProduct} is for.
     * @return CartProduct|null The required {@link CartProduct} or null, if not found.
     */
    public static function getById(int $userId, int $productId): ?CartProduct
    {
        return CartProduct::getById($productId, $userId);
    }

    /**
     * Increases the amount of a product in the cart.
     * @param CartProduct $cartProduct The {@link CartProduct} in which the amount should be increased.
     * @param int $by The number of how much to increment.
     * @return CartProduct|null The updated {@link CartProduct} or null, if an error occurred.
     */
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
     * Reduces the amount of a specific product in a cart.
     * @param CartProduct $cartProduct The {@link CartProduct} in which the amount should be decreased.
     * @param int $by The amount by how much it should be decreased.
     * @return CartProduct|null The updated {@link CartProduct} or null, if an error occurred.
     */
    public static function decAmount(CartProduct $cartProduct, int $by = 1): ?CartProduct
    {
        if ($cartProduct->getAmount() - $by >= 1) {     // can not sell less than one
            $cartProduct->setAmount($cartProduct->getAmount() - $by);
            return $cartProduct->update();
        }
        return null;
    }

    /**
     * Decreases the amount of products in cart to the max in stock, if we have more items in cart than it is in the stock. If the stock is 0 delete the item from the cart.
     * @param CartProduct $cartProduct The cart-product-object
     * @return bool true, if the product got deleted from the cart
     */
    public static function handleOtherUserBoughtItemInCart(CartProduct $cartProduct): bool
    {
        require_once INCLUDE_MODAL_DIR . "modal_popup.inc.php";
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

    /**
     * Deletes an entry out of a cart.
     * @param CartProduct $cartProduct
     * @return bool
     */
    public static function delete(CartProduct $cartProduct): bool
    {
        return $cartProduct->delete();
    }
}
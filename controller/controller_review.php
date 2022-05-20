<!-- TODO Comment -->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_review.php";

class ReviewController
{

    public static function insert(string $title, string $text, int $stars, int $userId, int $productId): ?Review {
        $review = new Review(0, $title, $text, $stars, $userId, $productId);
        return $review->insert();
    }

    /**
     * Returns the amount of reviews for a product
     * @param int $productId
     * @return int
     */
    public static function getNumberOfReviewsForProduct(int $productId): int
    {
        $number = Review::getNumberOfReviewsForProduct($productId);

        if (!$number) return 0;
        return $number;
    }

    /**
     * Calculate and set the average star rating using full and half stars.
     * @param int $productId The ProductID, for which the Stars should be included.
     * @return void HTML-Tags
     */
    public static function calcAndIncAvgProductStars(int $productId): void
    {
        self::createStarsRating(self::getAvgRating($productId));
    }

    /**
     * Calculate and set the star rating using full and half stars.
     * @param Review $review The review object, for which the Stars should be created.
     * @return void HTML-Tags
     */
    public static function calcAndIncProductStars(Review $review): void
    {
        self::createStarsRating($review->getStars());
    }

    /**
     * Creates the stars rating html based on the passed rating
     * @param int $rating
     * @return void
     */
    private static function createStarsRating(int $rating): void {
        for ($i = 1; $i <= 5; $i++) {
            $difference = $rating - $i;
            if ($difference >= 0) {
                echo "<i class='fa fa-star rating-color ms-1'></i>";     // full star
            } elseif (-0.25 > $difference && $difference > -0.75) {
                echo "<i class='fa fa-star-half-full rating-color ms-1'></i>";   // half star
            } else {
                echo "<i class='fa fa-star-o rating-color ms-1'></i>";      // empty star
            }
        }
    }

    public static function getAvgRating(int $productId): float
    {
        $avgRating = Review::getAvgRating($productId);

        if (!$avgRating) return 0;
        return $avgRating;
    }

    /**
     * Selects a specified amount of reviews of a product starting at an offset.
     * @param int $productId The product id of the product from which the reviews should be selected
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An array with the found reviews or null, if an error occurred.
     */
    public static function getReviewsForProductInRange(int $productId, int $offset, int $amount): ?array {
        return Review::getReviewsForProductInRange($productId, $offset, $amount);
    }

    /**
     * Returns the amounts of reviews for a product stored in the database.
     * @param int $productId The product id of the product from which the reviews should be counted
     * @return int The amount of found reviews
     */
    public static function getAmountOfReviewsForProduct(int $productId): int
    {
        return Review::getAmountOfReviewsForProduct($productId);
    }

    /**
     * Count the amount of reviews for each star (0-5) and calculates the distribution of percentages among the 5 stars for a product.
     * @param int $productId The id of the product.
     * @return array An array with all this information. [0 => ["star"=0, "amount"=x, "percentage"=x, 1 => ...]
     */
    public static function getStatsForEachStarForAProduct(int $productId): array {
        return Review::getStatsForEachStarForAProduct($productId);
    }

}
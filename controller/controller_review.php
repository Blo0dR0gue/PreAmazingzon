<!-- TODO Comment -->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_review.php";

class ReviewController
{
    public static function getNumberOfReviews(int $productId): int
    {
        $number = Review::getNumberOfReviews($productId);

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
     * @param int $productId The ProductID, for which the Stars should be included.
     * @return void HTML-Tags
     */
    public static function calcAndIncProductStars(Review $review): void
    {
        self::createStarsRating($review->getStars());
    }

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

}
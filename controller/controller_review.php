<?php
//Add review model.
require_once MODEL_DIR . "model_review.php";

class ReviewController
{

    /**
     * Creates a new {@link Review}
     * @param string $title The title.
     * @param string $text The text.
     * @param int $stars The amount of stars.
     * @param int $userId The id of the user, who created the review.
     * @param int $productId The id of the product.
     * @return Review|null A new {@link Review} object or null, if an error occurred.
     */
    public static function insert(string $title, string $text, int $stars, int $userId, int $productId): ?Review
    {
        $review = new Review(0, $title, $text, $stars, $userId, $productId);
        return $review->insert();
    }

    /**
     * Calculate and set the average star rating using full and half stars.
     * @param int $productId The ProductID, for which the Stars should be included.
     * @return string HTML-Tags
     */
    public static function calcAndIncAvgProductStars(int $productId): string
    {
        return self::createStarsRating(self::getAvgRating($productId));
    }

    /**
     * Creates the stars rating html based on the passed rating
     * @param float $rating
     * @return string Generated HTML Code for echoing
     */
    private static function createStarsRating(float $rating): string
    {
        $html = "";
        for ($i = 1; $i <= 5; $i++) {
            $difference = $rating - $i;
            if ($difference >= 0) {
                $html .= "<i class='fa fa-star rating-color ms-1'></i>";     // full star
            } elseif (-0.25 > $difference && $difference > -0.75) {
                $html .= "<i class='fa fa-star-half-full rating-color ms-1'></i>";   // half star
            } else {
                $html .= "<i class='fa fa-star-o rating-color ms-1'></i>";      // empty star
            }
        }
        return $html;
    }

    /**
     * Calculates the average rating for a product.
     * @param int $productId The id of the product
     * @return float The avg.
     */
    public static function getAvgRating(int $productId): float
    {
        $avgRating = Review::getAvgRating($productId);

        if (!$avgRating) {
            return 0;
        }
        return $avgRating;
    }

    /**
     * Calculate and set the star rating using full and half stars.
     * @param Review $review The review object, for which the Stars should be created.
     * @return string HTML-Tags
     */
    public static function calcAndIncProductStars(Review $review): string
    {
        return self::createStarsRating($review->getStars());
    }

    /**
     * Selects a specified amount of reviews of a product starting at an offset.
     * @param int $productId The product id of the product from which the reviews should be selected
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An array with the found reviews or null, if an error occurred.
     */
    public static function getReviewsForProductInRange(int $productId, int $offset, int $amount): ?array
    {
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
    public static function getStatsForEachStarForAProduct(int $productId): array
    {
        return Review::getStatsForEachStarForAProduct($productId);
    }

    /**
     * Gets an {@link Review} by its id.
     * @param int $id The id.
     * @return Review|null The {@link Review} or null, if not found.
     */
    public static function getById(int $id): ?Review
    {
        return Review::getById($id);
    }

    /**
     * Deletes an {@link Review}.
     * @param Review $review The {@link Review}.
     * @return bool true, if it was successfully
     */
    public static function delete(Review $review): bool
    {
        return $review->delete();
    }
}
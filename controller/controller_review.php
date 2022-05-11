<!-- TODO Comment-->

<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_review.php";

class ReviewController
{

    public static function getNumberOfReviews(int $productId): int
    {
        $number = Review::getNumberOfReviews($productId);

        if (!$number)
        {
            //TODO Error Handling
            return 0;
        }

        return $number;

    }

    /**
     * Calculate and set the star rating using full and half stars.
     * @param int $productID The ProductID, for which the Stars should be included.
     * @return void HTML-Tags
     */
    public static function calcAndIncAvgProductStars(int $productID): void
    {
        $avgRating = self::getAvgRating($productID);

        for ($i = 1; $i <= 5; $i++)
        {
            $difference = $avgRating - $i;
            if ($difference >= 0)
            {
                echo "<i class='fa fa-star rating-color ms-1'></i>";     // full star
            } elseif (-0.25 > $difference && $difference > -0.75)
            {
                echo "<i class='fa fa-star-half-full rating-color ms-1'></i>";   // half star
            } else {
                echo "<i class='fa fa-star-o rating-color ms-1'></i>";      // empty star
            }
        }
    }

    public static function getAvgRating(int $productId): float
    {
        $avgRating = Review::getAvgRating($productId);

        if (!$avgRating) {
            //TODO Error Handling
            return 0;
        }

        return $avgRating;

    }

}
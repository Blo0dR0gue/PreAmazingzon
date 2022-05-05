<?php

require_once MODEL_DIR . DIRECTORY_SEPARATOR . "model_review.php";

class ReviewController
{

    public static function getAvgRating(int $productId): float
    {
        $avgRating = Review::getAvgRating($productId);

        if (!$avgRating)
        {
            //TODO Error Handling
            return 0;
        }

        return $avgRating;

    }

}
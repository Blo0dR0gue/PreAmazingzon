<?php
//TODO Comments

// load required files
require_once(INCLUDE_DIR . DS . "database.inc.php");

class Review
{
    // region fields
    private int $id;
    private string $title;
    private string $text;
    private int $stars;
    private int $userId;
    private int $productId;
    // endregion

    /**
     * @param int $id
     * @param string $title
     * @param string $text
     * @param int $stars
     * @param int $userId
     * @param int $productId
     */
    public function __construct(int $id, string $title, string $text, int $stars, int $userId, int $productId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->stars = $stars;
        $this->userId = $userId;
        $this->productId = $productId;
    }

    public static function getAvgRating(int $productId): ?float
    {
        $sql = "SELECT ROUND(AVG(stars), 1) as rating
                FROM review
                WHERE product = ?
                GROUP BY product;";
        $stmt = getDb()->prepare($sql);
        $stmt->bind_param("i", $productId);
        if (!$stmt->execute()) return null;    //TODO Error Handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["rating"];
    }

    public static function getNumberOfReviewsForProduct(int $productId): ?int
    {
        $sql = "SELECT COUNT(*) as reviews
                FROM review
                WHERE product = ?
                GROUP BY product";
        $stmt = getDb()->prepare($sql);
        $stmt->bind_param("i", $productId);
        if (!$stmt->execute()) return null;    //TODO Error Handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["reviews"];
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
        $reviews = [];

        $stmt = getDB()->prepare("SELECT id FROM review WHERE product = ? ORDER BY id LIMIT ? OFFSET ?;");
        $stmt->bind_param("iii", $productId, $amount, $offset);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        foreach ($stmt->get_result() as $review) {
            $reviews[] = self::getByID($review["id"]);
        }
        $stmt->close();
        return $reviews;
    }

    /**
     * Get an existing review by its id.
     *
     * @param int $id ID of a review
     * @return Review|null corresponding review
     */
    public static function getById(int $id): ?Review
    {
        $stmt = getDB()->prepare("SELECT * FROM review WHERE id = ?;");

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Review($id, $res["title"], $res["text"], $res["stars"], $res["user"], $res["product"]);
    }

    public static function getAmountOfReviewsForProduct(int $productId): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM review WHERE product = ?;");
        $stmt->bind_param("i", $productId);

        if (!$stmt->execute()) return 0;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Count the amount of reviews for each star (0-5) and calculates the distribution of percentages among the 5 stars for a product.
     * @param int $productId The id of the product.
     * @return array An array with all this information. [0 => ["star"=0, "amount"=x, "percentage"=x, 1 => ...]
     */
    public static function getStatsForEachStarForAProduct(int $productId): array
    {
        $stmt = getDB()->prepare("SELECT stars AS star, COUNT(*) AS amount, ROUND(COUNT(*)/(SELECT COUNT(DISTINCT id) FROM review WHERE product = ?)*100, 2) AS percentage FROM review WHERE product = ? GROUP BY stars;");
        $stmt->bind_param("ii", $productId, $productId);

        if (!$stmt->execute()) return [];     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        $inner = ["star" => 0, "amount" => 0, "percentage" => 0];
        $result = array(0 => $inner, 1 => $inner, 2 => $inner, 3 => $inner, 4 => $inner, 5 => $inner);
        if ($res->num_rows === 0) return $result;
        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $result[$row['star']] = $row;
        }

        $stmt->close();

        return $result;
    }

    // region getter

    /**
     * @return int The id of this review.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string The title for this review.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string The description resp. text for this review.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int The amount of full stars given in this review.
     */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * @return int The user id of the user who created this review.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int The product id of the product this review is for.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    // endregion

    public function insert(): ?Review
    {
        $stmt = getDB()->prepare("INSERT INTO review(title, stars, text, user, product)
                                        VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisii",
            $this->title,
            $this->stars,
            $this->text,
            $this->userId,
            $this->productId);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    public function update(): void
    {
        // TODO
    }


    /**
     * Deletes itself from the database.
     * @return bool true, if the product got deleted.
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM review WHERE id = ?;");
        $stmt->bind_param("i",
            $this->id);
        if (!$stmt->execute()) return false;     // TODO ERROR handling

        $stmt->close();

        return self::getById($this->id) == null;
    }
}

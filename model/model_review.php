<!-- TODO COMMENT-->

<?php
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

    /**
     * Get an existing review by its id.
     *
     * @param int $id ID of a review
     * @return Review|null corresponding review
     */
    public static function getById(int $id): ?Review {
        $stmt = getDB()->prepare("SELECT * from review where id = ?;");

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return null;
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Review($id, $res["title"], $res["text"], $res["stars"], $res["user"], $res["product"]);
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
    public static function getReviewsForProductInRange(int $productId, int $offset, int $amount): ?array {
        $reviews = [];

        $stmt = getDB()->prepare("SELECT id from review WHERE product = ? ORDER BY id limit ? offset ?;");
        $stmt->bind_param("iii", $productId, $amount, $offset);
        if (!$stmt->execute()) return null;     // TODO ERROR handling

        // get result
        foreach ($stmt->get_result() as $review) {
            $reviews[] = self::getByID($review["id"]);
        }
        $stmt->close();
        return $reviews;
    }

    public static function getAmountOfProducts(int $productId): int {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) as count from review where product = ?;");
        $stmt->bind_param("i", $productId);

        if (!$stmt->execute()) return 0;     // TODO ERROR handling

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) return 0;
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
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

    public function delete(): void
    {
        // TODO
    }
}

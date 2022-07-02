<?php

//Add databse
require_once(INCLUDE_DIR . "database.inc.php");

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
     * Constructor for {@link Review}.
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

    //region getter

    /**
     * Gets the average rating for an {@link Product}
     * @param int $productId The {@link Product} id
     * @return float|null The average rating or null, if an error occurred.
     */
    public static function getAvgRating(int $productId): ?float
    {
        $stmt = getDb()->prepare("SELECT ROUND(AVG(stars), 1) as rating
                                        FROM review
                                        WHERE product = ?
                                        GROUP BY product;");
        $stmt->bind_param("i", $productId);
        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (get avg)", CRITICAL_LOG);

            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return 0; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["rating"];
    }

    /**
     * Gets the amount of {@link Review}s for a {@link Product}.
     * @param int $productId The id of the {@link Product}.
     * @return int The amount.
     */
    public static function getAmountOfReviewsForProduct(int $productId): int
    {
        $stmt = getDB()->prepare("SELECT COUNT(DISTINCT id) AS count FROM review WHERE product = ?;");
        $stmt->bind_param("i", $productId);

        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (get amount for product)", CRITICAL_LOG);
            return 0;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return 0; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return $res["count"];
    }

    /**
     * Selects a specified amount of {@link Review}s of a product starting at an offset.
     * @param int $productId The product id of the product from which the reviews should be selected
     * @param int $offset The first row, which should be selected.
     * @param int $amount The amount of rows, which should be selected.
     * @return array|null An array with the found {@link Review}s or null, if an error occurred.
     */
    public static function getReviewsForProductInRange(int $productId, int $offset, int $amount): ?array
    {
        $reviews = [];

        $stmt = getDB()->prepare("SELECT id FROM review WHERE product = ? ORDER BY id LIMIT ? OFFSET ?;");
        $stmt->bind_param("iii", $productId, $amount, $offset);
        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        foreach ($stmt->get_result() as $review) {
            $reviews[] = self::getByID($review["id"]);
        }
        $stmt->close();
        return $reviews;
    }

    /**
     * Get an existing {@link Review} by its id.
     *
     * @param int $id The id.
     * @return Review|null The {@link Review} or null, if not found.
     */
    public static function getById(int $id): ?Review
    {
        $stmt = getDB()->prepare("SELECT * FROM review WHERE id = ?;");

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (get)", CRITICAL_LOG);
            return null;
        }

        // get result
        $res = $stmt->get_result();
        if ($res->num_rows === 0) { return null; }
        $res = $res->fetch_assoc();
        $stmt->close();

        return new Review($id, $res["title"], $res["text"], $res["stars"], $res["user"], $res["product"]);
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

        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (get)", CRITICAL_LOG);
            return [];
        }

        // get result
        $res = $stmt->get_result();
        $inner = ["star" => 0, "amount" => 0, "percentage" => 0];
        $result = array(0 => $inner, 1 => $inner, 2 => $inner, 3 => $inner, 4 => $inner, 5 => $inner);
        if ($res->num_rows === 0) { return $result; }
        $rows = $res->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $result[$row['star']] = $row;
        }

        $stmt->close();

        return $result;
    }

    /**
     * Gets the id of this {@link Review}.
     * @return int The id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the title of this {@link Review}.
     * @return string The title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Gets the text of this {@link Review}
     * @return string The text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Gets the stars for this {@link Review}.
     * @return int The amount of full stars given in this review.
     */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * Gets the id of the {@link User} who created this {@link Review}.
     * @return int The user id.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Gets the {@link Product} id this {@link Review} is for.
     * @return int The product id.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    // endregion

    /**
     * Creates a new {@link Review} inside the database.
     * @return Review|null The created {@link Review} or null, if an error occurred.
     */
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
        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (insert)", CRITICAL_LOG);
            return null;
        }

        // get result
        $newId = $stmt->insert_id;
        $stmt->close();

        return self::getById($newId);
    }

    /**
     * Deletes the {@link Review} from the database.
     * @return bool true, if the {@link Review} got deleted.
     */
    public function delete(): bool
    {
        $stmt = getDB()->prepare("DELETE FROM review WHERE id = ?;");
        $stmt->bind_param("i",
            $this->id);
        if (!$stmt->execute()) {
            logData("Review Model", "Query execute error! (delete)", CRITICAL_LOG);
            return false;
        }

        $stmt->close();

        return self::getById($this->id) == null;
    }
}

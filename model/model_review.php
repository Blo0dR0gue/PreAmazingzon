<!-- TODO COMMENT-->

<?php
// load required files
require_once(INCLUDE_DIR . DIRECTORY_SEPARATOR . "database.inc.php");

// TODO implement
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
        $sql = "SELECT AVG(stars) as rating
                FROM Review
                WHERE product = ?
                GROUP BY product;";
        $stmt = getDb()->prepare($sql);
        $stmt->bind_param("s", $productId);

        if(!$stmt->execute()) return null;    //TODO Error Handling

        $res = $stmt->get_result();

        if($res->num_rows === 0) return 0;

        $stmt->bind_result($rating);
        $stmt->fetch();

        $stmt->close();

        return $rating;
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

    public function insert(): void
    {
        // TODO
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

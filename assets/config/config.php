<!-- config file for general stuff-->

<?php
/**
 * ROOT_PATH_OFFSET
 * offset of project related to server web-root.
 * e.g. if the projekt folder is located in the web-root this is "".
 */
const ROOT_PATH_OFFSET = "";

const CURRENCY_SYMBOL = "€";

// region ########## Central Numbers ##########
/**
 * Amount of random products which are displayed on the index.php.
 * Its recommender to set the value to multiples of four.
 */
const INDEX_PRODUCTS_AMOUNT = 4;

/**
 * Amount the highly complex algorithm can vary the original price to motivate the
 * user to buy the product for the current price.
 */
const DISCOUNT_VARIATION = 50;

/**
 * Max number of images per product shown on product detail page, inclusive the main image.
 */
const MAX_IMAGE_PER_PRODUCT = 6;
// endregion

// region ########## Central Strings ##########
/**
 * Name of the website.
 */
const PAGE_NAME = "Amazingzon";

/**
 * Date of copyright.
 */
const PAGE_COPYRIGHT = "2022";
// endregion
<!-- config file for general stuff -->

<?php
/**
 * ROOT_PATH_OFFSET
 * offset of project related to server web-root.
 * e.g. if the projekt folder is located in the web-root this is "".
 */
const ROOT_PATH_OFFSET = "";

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

/**
 * Defines, how many items are displayed on a page. For example, how many products are showed on one page on the all products page.
 * Its recommender to set the value to multiples of four.
 */
const LIMIT_OF_SHOWED_ITEMS = 8;

/**
 * Defines, how many pagination links are on the left and right side of the current page.
 */
const PAGINATION_RANGE = 2;

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

/**
 * Symbol placed after price.
 */
const CURRENCY_SYMBOL = "€";

/**
 * The timezone of this shop
 */
const DATE_TIME_ZONE = "Europe/Berlin";

//region invoice

const COMPANY_STREET = "Musterstraße";
const COMPANY_STREET_NR = "1";
const COMPANY_CITY = "Musterstadt";
const COMPANY_ZIP_CODE = "12345";
const COMPANY_COUNTRY = "Germany";
const INVOICE_FOOTER = "We ask that you settle the invoice within 14 days of receipt.";


//endregion

// endregion
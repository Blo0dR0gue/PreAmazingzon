<?php
// config file for general stuff

/**
 * Offset of project relative to server web-root.
 * e.g. if the projekt folder is located in the web-root this is "".
 */
const ROOT_PATH_OFFSET = "";

// region ########## Central Numbers ##########
/**
 * Amount of random products which are displayed on the index.php.
 * Its recommender to set the value to multiples of 4.
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
 * Max number of addresses a user can have.
 */
const MAX_AMOUNT_OF_ADDRESSES_PER_USER = 5;

/**
 * Defines, how many items are displayed on a page.<br>
 * For example, how many products are showed on the all products page.
 * It is recommended to set the value to multiples of four.
 */
const LIMIT_OF_SHOWED_ITEMS = 8;

/**
 * Defines, how many pagination links are on the left and right side of the current page.
 */
const PAGINATION_RANGE = 2;

/**
 * Defines the format of the displayed dates.<br>
 * @see https://www.php.net/manual/en/datetime.format.php
 */
const DATA_FORMAT = "d.m.Y H:i:s";

/**
 * Defines the short date format used for e.g. the delivery date.
 * @see https://www.php.net/manual/en/datetime.format.php
 */
const DATE_FORMAT_SHORT = "d.m.Y";

/**
 * The taxes used in this shop. (1.0 means 100%, 0.19 means 19%)
 */
const SHOP_TAX = 0.0;

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

// region invoice

/**
 * The street where company is located.
 */
const COMPANY_STREET = "Musterstraße";

/**
 * The street number of your company.
 */
const COMPANY_STREET_NR = "1";

/**
 * The city where your business is located.
 */
const COMPANY_CITY = "Musterstadt";

/**
 * The zip code of the city.
 */
const COMPANY_ZIP_CODE = "12345";

/**
 * The country in which your company is located.
 */
const COMPANY_COUNTRY = "Germany";

/**
 * The legal form of the company.
 */
const LEGAL_FORM = "inc.";

/**
 * The contact email address.
 */
const CONTACT_EMAIL = "info@amazingzon.com";

/**
 * The contact phone number.
 */
const CONTACT_PHONE = "02973 974430";

/**
 * The footer for each invoice
 */
const INVOICE_FOOTER = "We ask that you settle the invoice within 14 days of receipt.";

// endregion

// endregion
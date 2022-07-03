// script for the product detail page

// noinspection JSUnusedGlobalSymbols
/**
 * Function for changing the image placed in the big image space.
 * By default, there is the main image.
 * @param element to be set in big img slot
 */
function changeImage(element) {
    const main_product_image = document.getElementById('main_product_image');
    main_product_image.src = element.src;
}
// script for handling the cookie consent modal

// display modal
const cookieModal = new bootstrap.Modal(document.getElementById('modalCookie'), {
    backdrop: 'static',
    keyboard: false,
    focus:    true
});
cookieModal.show();

/**
 * Hide the modal with the remark of cookies
 */
// region button handler
/**
 * Button handler for "accept"-button.
 * Hides modal and saves consent.
 */
function acceptCookies() {
    document.cookie = "cookie_consent=1; max-age=" + 60*60*24*365 + "; path=/";
    cookieModal.hide();
}

/**
 * Button handler for "decline"-button.
 * Leaves the website due to no consent using cookies.
 */
function declineCookies() {
    window.location.href = 'https://www.amazon.de/';    // redirections user to competition
}
// endregion
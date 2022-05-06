// script for handling the cookie consent modal
// TODO COMMENT

// display modal
const cookieModal = new bootstrap.Modal(document.getElementById("modalCookie"), {
    backdrop: "static",
    keyboard: false,
    focus: true
});
cookieModal.show();

// region button handler
/**
 * Button handler for "accept"-button.
 * Hides modal and saves consent.
 */
function acceptCookies()
{
    document.cookie = "cookie_consent=1; max-age=" + 60 * 60 * 24 * 365 + "; path=/; SameSite=Lax";
    cookieModal.hide();
}

/**
 * Button handler for "decline"-button.
 * Leaves the website due to no consent using cookies.
 */
function declineCookies()
{
    window.location.href = "https://www.amazon.de/";    // redirections user to competition
}


$("#modalCookie").on("hidden.bs.modal", function ()
{
    // remove remaining back-drop
    $("#modalCookie").remove()
    $(document.body).removeClass("modal-open");
})
// endregion
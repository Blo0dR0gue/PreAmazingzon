<!-- modal loaded to obtain the user's consent to the use of cookies -->

<?php if (!isset($_COOKIE["cookie_consent"])) { ?>
<!-- cookie modal -->
<div class="modal fade d-block py-5" tabindex="-1" id="modalCookie">
    <div class="modal-dialog py-5" role="document">
        <div class="modal-content rounded-6 shadow">
            <div class="modal-header border-bottom-0">
                <h4 class="modal-title">Cookie Consent Request</h4>
            </div>
            <div class="modal-body py-0">
                <p>This website uses cookies to enable its extensive fantastic features. Those cookies are only used
                    to enable the website to function. No data is collected for marketing purposes. Agree to the use
                    of cookies to continue to use the services of the website.</p>
            </div>
            <div class="modal-footer flex-column border-top-0">
                <button type="button" class="btn btn-lg btn-primary w-100 mx-0 mb-2" onclick="acceptCookies()" >Accept</button>
                <button type="button" class="btn btn-lg btn-light w-100 mx-0" onclick="declineCookies()">Decline</button>
            </div>
        </div>
    </div>
</div>

<!-- load js managing modal -->
<script src="<?= SCRIPT_DIR . "/modal_cookie_consent.js" ?>"></script>

<?php } ?>
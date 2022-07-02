<!--Modal to add or edit an address-->

<div class="modal fade" id="addressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-6 shadow">
            <div class="modal-header border-bottom-0">
                <h4 class="modal-title" id="popupModalHead">New Address</h4>
            </div>
            <form action="<?= INCLUDE_HELPER_DIR . "helper_add_edit_address.inc.php" ?>" method="post">

                <!-- -1 means, create new one -->
                <input type="hidden" id="addressId" name="addressId" value="-1">

                <input type="hidden" id="userId" name="userId" value="<?php
                if(isset($user))
                    echo $user->getId();
                else
                    echo -1;
                ?>">

                <div class="modal-body py-0 text-start">

                    <!-- region address 1 row -->
                    <div class="form-row row">
                        <div class="col-md-4 mb-3 px-2 position-relative">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" required
                                   pattern="\d{5}">
                            <div class="invalid-tooltip opacity-75">Please enter a valid ZIP!</div>
                        </div>
                        <div class="col-md-8 mb-3 px-2 position-relative">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="City" required
                                   pattern="[a-zöäüA-ZÄÖÜ]+(?:[\s-][a-zöäüA-ZÖÄÜ]+)*">
                            <div class="invalid-tooltip opacity-75">Please enter a valid City!</div>
                        </div>
                    </div>
                    <!-- endregion -->

                    <!-- region address 2 row -->
                    <div class="form-row row">
                        <div class="col-md-8 mb-3 px-2 position-relative">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" id="street" name="street" placeholder="Street"
                                   required
                                   pattern="[a-zöäüA-ZÄÖÜß]+(?:[\s-][a-zöäüA-ZÖÄÜß]+)*">
                            <div class="invalid-tooltip opacity-75">Please enter a valid Street!</div>
                        </div>
                        <div class="col-md-4 mb-4 px-2 position-relative">
                            <label for="number">No.</label>
                            <input type="text" class="form-control" id="number" name="number" placeholder="Number"
                                   required
                                   pattern="[1-9]\d*(?:[ -]?(?:[a-zA-Z]+|[1-9]\d*))?">
                            <div class="invalid-tooltip opacity-75">Please enter a Number!</div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- load js managing modal -->
<script src="<?= SCRIPT_DIR . "modal_address.js" ?>"></script>
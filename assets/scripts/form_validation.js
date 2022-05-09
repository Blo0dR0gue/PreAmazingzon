/**
 * Script for disabling form submissions if there are invalid fields.
 * Used by custom boostrap forms.
 * based on https://getbootstrap.com/docs/4.0/components/forms/#custom-styles
 */
(function ()
{
    "use strict";
    window.addEventListener("load", function ()
    {
        // Fetch all the forms to apply custom Bootstrap validation styles to
        const forms = document.getElementsByClassName("needs-validation");
        // Loop over them and prevent submission
        Array.prototype.filter.call(forms, function (form)
        {
            form.addEventListener("submit", function (event)
            {
                if (form.checkValidity() === false)
                {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            }, false);
        });
    }, false);
})();
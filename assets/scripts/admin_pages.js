$(function () {
    /**
     * Add the click-event to the img drop zone that opens the file explorer
     */
    $("#dropZone").click(e => {
        if (e.target === $("#dropZone")[0] || e.target === $("#imgRow")[0])   //Only, if we click the div and not a sub element.
            $("#files").click();    //Open the file explorer
    });
});

/**
 * The drop handler for the img drop zone.
 * It uses the event object to get the transferred items.
 * @param ev The event object
 * @param maxFileAmount The maximum amount of images, which can be uploaded
 */
function dropHandler(ev, maxFileAmount) {
    ev.preventDefault();

    if (ev.dataTransfer.items) {
        // Use DataTransferItemList interface to access the file(s)
        for (let i = 0; i < ev.dataTransfer.items.length; i++) {
            // If dropped items aren't files, reject them
            if (ev.dataTransfer.items[i].kind === 'file') {
                let file = ev.dataTransfer.items[i].getAsFile();
                addImg(file, maxFileAmount);
            }
        }
    } else {
        // Use DataTransfer interface to access the file(s)
        for (let i = 0; i < ev.dataTransfer.files.length; i++) {
            addImg(ev.dataTransfer.files[i], maxFileAmount);
        }
    }
}

/**
 * Prevents the browser default drag event.
 * @param ev
 */
function dragOverHandler(ev) {
    ev.preventDefault();
}

/**
 * Gets a the admin page product image template using ajax
 * @param templateSelector The query selector for the item in the DOM.
 * @returns {string | DocumentFragment} The document fragment object of this template.
 */
function getTemplate(templateSelector) {
    let fragment = document.createDocumentFragment();;
    $.ajax({
        url: "../../include/admin/admin_product_img.inc.php",
        type: "GET",
        dataType: "html",
        async: false,
        success: function (data) {
            //console.log($(data));
            //The container is at position 10 in this jquery object //TODO dynamic search of this div
            fragment.appendChild($(data)[10]);
        }
    });
    return fragment;
}

/**
 * Contains the uploaded images
 * @type {Map<any, any>}
 */
const FILES = new Map();
/**
 * The dom-element of the current selected main image.
 */
let lastMainImgElem = null;
/**
 * The data-id if the current selected main image.
 * @type {number}
 */
let mainImgID = -1;
/**
 * The next data-id for the next uploaded image
 * @type {number}
 */
let nextImgID = 0;

/**
 * The img box template
 * @type {string|DocumentFragment}
 */
const main = getTemplate("#imgBoxTemplate");

/**
 * Adds the formdata event to the product form.
 * It adds the uploaded images to the form data before it is sent to the server.
 * We need to do this, because the input field "file" deletes the previous selected files out of the form, after the user selects other files.
 * JQuery does not have a formdata event :(
 */
document.getElementById("prodForm").addEventListener('formdata', (e) => {
    //The form data of the product form
    const formData = e.formData;

    //delete all uploaded files
    formData.delete('files[]');

    //if mainImgID is null, no main img is selected. set it to 0 which means the index 0 of the $_FILES array is chosen as main image.
    if (mainImgID == null) {
        formData.set("mainImgID", Number(0).toString());
    }

    //counter variable
    let index = 0;

    FILES.forEach(function (value, key) {
        //if the current file is the mainImg, set the mainImgID formdata input to the counter variable, which is the index of this files in the array $_FILES.
        if (mainImgID != null && key === parseInt(mainImgID)) {
            formData.set("mainImgID", index.toString());
        }
        //Add the file to the files array ($_FILES)
        formData.append("files[]", value, value.name);

        index++;
    });
})

/**
 * Add a image to the local FILES map.
 * @param file The file, which should be added
 * @param maxFileAmount The max amount of images, which can be uploaded.
 */
function addImg(file, maxFileAmount) {
    //If the file is not an image, skip
    if (!file || file['type'].split('/')[0] !== 'image') return;

    //If we reached the maxFilesAmount already, skip
    if (FILES.size >= maxFileAmount) return;
    //Disables the texts "click here or drop" text in the dropZone.
    $("#dropTexts")[0].style.display = "none";

    //Clone the template object
    let template = main.cloneNode(true);

    //Select the image inside the template and set the src to the tmp url of the image.
    template.querySelector('div img').src = URL.createObjectURL(file);

    //Select all buttons inside the template and set the data-id to the current nextImgID.
    template.querySelectorAll('div button').forEach(function (elem) {
        elem.dataset.id = nextImgID.toString();

        //If this is the first image, which is added to the drop zone (FILES array), set it to the main image.
        if (elem.name === "setMainBtn" && FILES.size <= 0) {
            setMainImg(elem);
        }
    });

    FILES.set(nextImgID, file);
    nextImgID++;

    //Add the template to the DOM.
    $("#imgRow")[0].appendChild(template)
}

/**
 * Event, which is called when a file is selected over the file explorer
 * @param fileElem The input field element
 * @param maxFileAmount The max amount of images, which can be uploaded
 */
function filesChanged(fileElem, maxFileAmount) {
    for (let i = 0; i < fileElem.files.length; i++) {
        let file = fileElem.files[i];
        addImg(file, maxFileAmount);
    }
}

/**
 * Removes an Image from the local FILES array and removed the image box from the DOM.
 * @param btnElem The delete button element of the image which should be deleted.
 */
function deleteImg(btnElem) {
    //Get the intern image if from the button element
    const _imgID = parseInt(btnElem.dataset.id);

    const imgBox = btnElem.parentElement;
    const imgContainer = imgBox.parentElement;

    //Remove the image box of this image from the DOM
    imgContainer.removeChild(imgBox);

    //Delete the image from the FILES map
    FILES.delete(_imgID);

    if (_imgID === parseInt(lastMainImgElem.dataset.id)) {
        if (FILES.size > 0) {
            //If the img, which should be deleted is the current main img, update the references to the first elem in the FILES map.
            setMainImg(document.getElementsByName("setMainBtn")[0]);
        } else {
            //If the FILES array is empty, reset all variables.
            document.getElementById("mainImgID").value = "";
            lastMainImgElem = null;
            mainImgID = -1;
        }
    }

    if (FILES.size === 0)
        $("#dropTexts")[0].style.display = "block";
}

/**
 * Sets the main-img
 * @param btnElem The button element, which is clicked.
 */
function setMainImg(btnElem) {
    if (lastMainImgElem === btnElem) return;

    //Change the button of the new main img
    btnElem.innerHTML = "Main";
    btnElem.classList.remove("btn-danger");
    btnElem.classList.add("btn-success");

    //Change old texts and css classes
    if (lastMainImgElem) {
        lastMainImgElem.classList.remove("btn-success");
        lastMainImgElem.classList.add("btn-danger");
        lastMainImgElem.innerHTML = "Set Main";
    }
    //Update references
    lastMainImgElem = btnElem;
    mainImgID = parseInt(btnElem.dataset.id);
}

/**
 * Event is called, if a category is selected.
 * Updates the input field to show the user which category is selected.
 * @param myRadio The radio input element.
 */
function handleRadioUpdate(myRadio) {
    document.getElementById("selectedRadio").value = myRadio.dataset.name;
}
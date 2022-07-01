/**
 * JQuery load function
 */
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
            if (ev.dataTransfer.items[i].kind === "file") {
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
 * Gets the admin page product image template using ajax
 * @param templateSelector The query selector for the item in the DOM.
 * @returns {string | DocumentFragment} The document fragment object of this template.
 */
function getTemplate(templateSelector) {
    let fragment = document.createDocumentFragment();
    $.ajax({
        url: "../../include/admin/admin_product_img.inc.php",
        type: "GET",
        dataType: "html",
        async: false,
        success: function (data) {
            //console.log($(data).filter("div"));
            //The container is at position 2 in this jquery object
            fragment.appendChild($(data).filter("div")[0]);
        }
    });
    return fragment;
}

/**
 * Contains the uploaded images
 * @type {Map<any, any>}
 */
const FILES = new Map();

const DELETED_IMAGES_IDS = [];

/**
 * The dom-element of the current selected main image.
 */
let lastMainImgElem = $(".btn.btn-success.btn-sm")[0];
/**
 * The data-id if the current selected main image.
 */
let mainImgID = null;
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
document.getElementById("prodForm").addEventListener("formdata", (e) => {
    //The form data of the product form
    const formData = e.formData;

    //delete all uploaded files
    formData.delete("files[]");

    //If Images, which has been uploaded are deleted. (Can happen, if we edit an image)
    if (DELETED_IMAGES_IDS.length > 0) {
        //reset the from-data variable.
        formData.delete("deletedImgIDs[]");
        //Add them all to the formdata
        DELETED_IMAGES_IDS.forEach(function (val) {
            formData.append("deletedImgIDs[]", val);
        })
    }

    //if mainImgID is null, no main img is selected. set it to 0 which means the index 0 of the $_FILES array is chosen as main image.
    if (mainImgID == null && lastMainImgElem == null && FILES.size > 0) {
        formData.set("mainImgID", Number(0).toString());
    } else if (mainImgID != null && FILES.size <= 0) {
        formData.set("mainImgID", mainImgID);
    }

    //counter variable
    let index = 0;

    FILES.forEach(function (value, key) {
        //if the current file is the mainImg, set the mainImgID formdata input to the counter variable, which is the index of this files in the array $_FILES.
        if (mainImgID != null && key === mainImgID) {
            formData.set("mainImgID", index.toString());
        }
        //Add the file to the files array ($_FILES)
        formData.append("files[]", value, value.name);

        index++;
    });

})

/**
 * Add a new image to the local FILES map.
 * @param file The file, which should be added
 * @param maxFileAmount The max amount of images, which can be uploaded.
 */
function addImg(file, maxFileAmount) {
    //If the file is not an image, skip
    if (!file || file["type"].split("/")[0] !== "image") return;

    //If we reached the maxFilesAmount already, skip
    if (FILES.size >= maxFileAmount) return;
    //Disables the texts "click here or drop" text in the dropZone.
    $("#dropTexts")[0].style.display = "none";

    //Clone the template object
    let template = main.cloneNode(true);

    //Select the image inside the template and set the src to the tmp url of the image.
    template.querySelector("div img").src = URL.createObjectURL(file);

    //Select all buttons inside the template and set the data-id to the current nextImgID.
    template.querySelectorAll("div button").forEach(function (elem) {
        elem.dataset.id = nextImgID.toString();

        //If this is the first image, which is added to the drop zone (FILES array), set it to the main image.
        //We check both, if the FILES map size is 0 and the container does not contain any imgBox,
        // because in editMode the already uploaded images will not be added to the FILES map.
        if (elem.name === "setMainBtn" && FILES.size <= 0 && $("#imgRow").children().length === 0) {
            setMainImg(elem);
        }
    });

    FILES.set(nextImgID.toString(), file);
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
 * @param isNewImg If this param is true, we delete an image, which is not uploaded yet.
 */
function deleteImg(btnElem, isNewImg) {
    //Get the intern image if from the button element
    const _imgID = btnElem.dataset.id;

    const imgBox = btnElem.parentElement;
    const imgContainer = imgBox.parentElement;

    //Remove the image box of this image from the DOM
    imgContainer.removeChild(imgBox);

    if (!isNewImg) {
        //Relevant path by editing products. (Image is already uploaded)

        DELETED_IMAGES_IDS.push(_imgID);

        //Get the setMain btn of the image, in which to delete btn was pressed.
        let deleteBtn = btnElem.nextElementSibling;
        //It was the main image but was selected as one inside this script. This happens, because the element was created by php and not javascript.
        if (deleteBtn.classList.contains("btn-success") && lastMainImgElem == null) {
            lastMainImgElem = btnElem;
        }
    } else {
        //Delete the image from the FILES map, if it is not uploaded yet.
        FILES.delete(_imgID);
    }

    if (lastMainImgElem != null && _imgID === lastMainImgElem.dataset.id) {
        if (FILES.size > 0 || $("#imgRow").children().length > 0) {
            //If the img, which should be deleted is the current main img, update the references to the first elem in the FILES map.
            setMainImg($("button[name='setMainBtn']")[0]);
        } else {
            //If no image is inside the dropArea, reset all variables.
            $("#mainImgID").value = null;
            lastMainImgElem = null;
            mainImgID = null;
        }
    }

    //If no image is added or no image is already uploaded.
    if (FILES.size === 0 && $("#imgRow").children().length === 0) $("#dropTexts")[0].style.display = "block";
}

/**
 * Sets the main-img
 * @param btnElem The button element, which is clicked.
 */
function setMainImg(btnElem) {
    if (lastMainImgElem === btnElem || btnElem.classList.contains("btn-success")) return;

    //Change the button of the new main img
    btnElem.innerHTML = "Main";
    btnElem.classList.remove("btn-danger");
    btnElem.classList.add("btn-success");

    //Change old texts and css classes
    if (lastMainImgElem) {
        lastMainImgElem.classList.remove("btn-success");
        lastMainImgElem.classList.add("btn-secondary");
        lastMainImgElem.innerHTML = "Main";
    }
    //Update references
    lastMainImgElem = btnElem;
    mainImgID = btnElem.dataset.id;
}
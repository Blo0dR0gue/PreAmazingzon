$(function () {
    $("#dropZone").click(e => {
        if (e.target === $("#dropZone")[0] || e.target === $("#imgRow")[0])   //Only, if we click the div and not a sub element.
            $("#files").click();    //Open the file explorer
    });
});

function dropHandler(ev, maxFileAmount) {
    ev.preventDefault();

    if (ev.dataTransfer.items) {
        // Use DataTransferItemList interface to access the file(s)
        for (let i = 0; i < ev.dataTransfer.items.length; i++) {
            // If dropped items aren't files, reject them
            if (ev.dataTransfer.items[i].kind === 'file') {
                var file = ev.dataTransfer.items[i].getAsFile();
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

function dragOverHandler(ev) {
    ev.preventDefault();
}

function getTemplate(templateSelector) {
    return document.querySelector(templateSelector).content;
}

const FILES = new Map();
let lastMainImgElem = null;
let mainImgID = 0;
let nextImgID = 0;
const main = getTemplate("#imgBoxTemplate");

//JQuery does not have a formdata event :(
document.getElementById("prodForm").addEventListener('formdata', (e) => {
    const formData = e.formData;

    formData.delete('files[]');

    if(mainImgID == null){
        formData.set("mainImgID", Number(0).toString());
    }
    let index = 0;
    FILES.forEach(function (value, key) {
        if(mainImgID != null && key === parseInt(mainImgID)){
            formData.set("mainImgID", index.toString());
        }
        formData.append("files[]", value, value.name);
        index++;
    });
})

function addImg(file, maxFileAmount) {
    if (!file || file['type'].split('/')[0] !== 'image') return;
    if(FILES.size >= maxFileAmount) return;
    $("#dropTexts")[0].style.display = "none";
    let template = main.cloneNode(true);

    template.querySelector('div img').src = URL.createObjectURL(file);
    template.querySelectorAll('div button').forEach(function (elem){
        elem.dataset.id = nextImgID.toString();
        if(elem.name === "setMainBtn" && FILES.size <= 0){
            setMainImg(elem);
        }
    });

    FILES.set(nextImgID, file);
    nextImgID++;

    $("#imgRow")[0].appendChild(template)
}

function filesChanged(fileElem, maxFileAmount) {
    for (let i = 0; i < fileElem.files.length; i++) {
        let file = fileElem.files[i];
        addImg(file, maxFileAmount);
    }
}

function deleteImg(btnElem) {
    const _imgID = parseInt(btnElem.dataset.id);

    const imgBox = btnElem.parentElement;
    const imgContainer = imgBox.parentElement;
    imgContainer.removeChild(imgBox);

    FILES.delete(_imgID);

    //If the img, which should be deleted is the current main img, update the references to the first elem in the FILES map.
    if(_imgID === parseInt(lastMainImgElem.dataset.id)){
        if(FILES.size > 0){
            setMainImg(document.getElementsByName("setMainBtn")[0]);
        }else{
            document.getElementById("mainImgID").value = "";
            lastMainImgElem = null;
            mainImgID = null;
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
    if(lastMainImgElem === btnElem) return;

    //Change the button of the new main img
    btnElem.innerHTML = "Main";
    btnElem.classList.remove("btn-danger");
    btnElem.classList.add("btn-success");

    //Change old texts and css classes
    if(lastMainImgElem){
        lastMainImgElem.classList.remove("btn-success");
        lastMainImgElem.classList.add("btn-danger");
        lastMainImgElem.innerHTML = "Set Main";
    }
    //Update references
    lastMainImgElem = btnElem;
    mainImgID = btnElem.dataset.id;
}

function handleRadioUpdate(myRadio) {
    document.getElementById("selectedRadio").value = myRadio.dataset.name;
}
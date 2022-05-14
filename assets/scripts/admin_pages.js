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
let imgID = 0;
const main = getTemplate("#imgBoxTemplate");

//JQuery does not have a formdata event :(
document.getElementById("prodForm").addEventListener('formdata', (e) => {
    const formData = e.formData;

    formData.delete('files[]');

    FILES.forEach(function (value) {
        formData.append("files[]", value, value.name);
    });
})

function addImg(file, maxFileAmount) {
    if (!file || file['type'].split('/')[0] !== 'image') return;
    if(FILES.size >= maxFileAmount) return;
    $("#dropTexts")[0].style.display = "none";
    let template = main.cloneNode(true);

    template.querySelector('div img').src = URL.createObjectURL(file);
    template.querySelector('div button').dataset.id = imgID.toString();

    FILES.set(imgID, file);
    imgID++;

    $("#imgRow")[0].appendChild(template)
}

function filesChanged(fileElem, maxFileAmount) {
    for (let i = 0; i < fileElem.files.length; i++) {
        let file = fileElem.files[i];
        addImg(file, maxFileAmount);
    }
}

function deleteImg(imgElem) {
    const _imgID = parseInt(imgElem.dataset.id);

    const imgBox = imgElem.parentElement;
    const imgContainer = imgBox.parentElement;
    imgContainer.removeChild(imgBox);

    FILES.delete(_imgID);

    if (FILES.size === 0)
        $("#dropTexts")[0].style.display = "block";
}

function handleRadioUpdate(myRadio) {
    document.getElementById("selectedRadio").value = myRadio.dataset.name;
}
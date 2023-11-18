var imageSource = document.getElementById('imageFile');
var alertContainer = document.getElementsByClassName('alert-container')[0];
var notifTitle = document.getElementsByClassName('title')[0];
var notifMsg = document.getElementsByClassName('message')[0];


var loadImage = function(event) {
var reader = new FileReader();
reader.onload = function() {
var output1 = document.getElementById('imgFrom');
var output2 = document.getElementById('imgTo');
var icon = document.getElementById('icon-arrow');
var imgfromsize = document.getElementById('imgFromSize');
var imageFileTextInput = document.getElementById('imageFileInputText');
var fileNameImgUploaded = event.target.files[0].name;
var imageFromFileSize = event.target.files[0].size;

var forKB = imageFromFileSize / 1024;
var forMB = forKB / 1024;
var formattedSize;
if (forMB >= 1) {
    formattedSize = forMB.toFixed(2) + " MB";
} else {
    formattedSize = forKB.toFixed(2) + " KB";
}
output1.src = reader.result;
imageFileTextInput.innerText = fileNameImgUploaded;
imgfromsize.innerText = "File size: " + formattedSize;
icon.hidden = false;
output1.hidden = false;
output2.hidden = false;

if (imageSource.files.length > 0) {
    var file = imageSource.files[0];
    var formData = new FormData();
    formData.append('imageFile', file);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'process.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var base64String = xhr.responseText;
            var binaryData = atob(base64String);
            var imageSize = binaryData.length;
            var forKB = imageSize / 1024;
            var forMB = forKB / 1024;
            var displaySize;
            if (forMB >= 1){ 
                displaySize = forMB.toFixed(2) + " MB"; 
            } else { 
                displaySize = forKB.toFixed(2) + " KB"; 
            }
            output2.src = "data:image/jpeg;base64," + xhr.responseText;
            var imgToFileSize = document.getElementById("imgToSize");
            imgToFileSize.innerText = "File size: " + displaySize;
        }
    };
    xhr.send(formData);
    }
};
reader.readAsDataURL(event.target.files[0]);


};
function showBase64Result() {
    if (imageSource.value != "")
    {
        var body = document.getElementsByClassName('main-body')[0];
        var resultContainer = document.getElementsByClassName('result-container')[0];
        var base64ResultInput = document.getElementById('result-input');

        body.classList.add("adjust");
        resultContainer.classList.add("show");
        resultContainer.hidden = false;

        if (imageSource.files.length > 0) {
            var file = imageSource.files[0];
            var formData = new FormData();
            formData.append('imageFile', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'process.php', true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    base64ResultInput.value = xhr.responseText;
                }
            };
            xhr.send(formData);
        }
    }
    else
    {
        // alert("Please select a file");
        notifTitle.innerText = "Error";
        notifMsg.innerText = "Please select a file";
        alertContainer.classList.add('showAlert');
        alertContainer.style.backgroundColor = "lightcoral";
        setTimeout(function () {
            alertContainer.classList.remove('showAlert');
        }, 3000);
    }
    
}
function copyToClipboard(){
    var inputToCopy = document.getElementById('result-input');
    inputToCopy.select();
    inputToCopy.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(inputToCopy.value);
    // alert('Text copied to clipboard ' + inputToCopy.value);
    notifTitle.innerText = "Success";
    notifMsg.innerText = "Copy to clipboard successfully!";
    alertContainer.classList.add('showAlert');
    setTimeout(function () {
        alertContainer.classList.remove('showAlert');
    }, 3000);
}
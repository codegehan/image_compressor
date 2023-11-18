<?php 
    include 'dbconn.php';
    include 'compress.php';
    if (isset($_POST["submit"])) {
        // $sourceImage, $destination, $quality, $width, $height
        $sourceImage = $_FILES["imageFile"]["tmp_name"];
        $filename = $_FILES["imageFile"]["name"];
        $rand = rand(000, 999);
        $newfilename = $rand.$filename;
        // $destination = "C:/Users/gelli/OneDrive/Desktop/$newfilename";
        $quality = 30;
        $width = 150;
        $height = 150;
        $compressedImg = Image::Compress($sourceImage,$quality,$width,$height);
        $encodeBase64 = base64_encode($compressedImg);
        //For the source image = data:{mime};base64, $compressedImg
        $query = "INSERT INTO tbl_image (img_data) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $encodeBase64);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/2ee3da4ec4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Image Compressor</title>
</head>
<body class="main-body">
    <div class="header">
        <h1>Image Compressor</h1>
    </div>
    <form method="post" enctype="multipart/form-data">
        <label for="imageFile" class="img-file-label" id="imageFileInputText">Select a file</label>
        <input type="file" name="imageFile" id="imageFile" accept="image/*" onchange="loadImage(event)" hidden>
        <button type="button" name="submit" class="submit-btn" onclick="showBase64Result()">Submit</button>
        <div class="result-container" hidden>
            <input type="text" id="result-input" disabled>
            <button type="button" id="copy" onclick="copyToClipboard()">Copy to clipboard</button>
        </div>
    </form>
    <div class="preview">
        <div class="img-container">
            <img src="#" id="imgFrom" hidden>
            <p class="image-size" id="imgFromSize"></p>
            <!-- <p class="image-type">image/jpg</p> -->
        </div>
        <div class="arrow" id="icon-arrow" hidden>
            <i class="fa-solid fa-arrow-down"></i>
        </div>
        <div class="img-container"">
            <img src="#" id="imgTo" hidden>
            <p class="image-size" id="imgToSize"></p>
            <!-- <p class="image-type">image/jpg</p> -->
        </div>
    </div>
    <div class="alert-container">
        <div class="alert-content">
            <h3>Success</h3>
            <p>Copy to clipboard successfully</p>
        </div>
    </div>
    <script>
         var imageSource = document.getElementById('imageFile');
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
                alert("Please select a file");
            }
            
        }
        function copyToClipboard(){
            var inputToCopy = document.getElementById('result-input');
            inputToCopy.select();
            inputToCopy.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(inputToCopy.value);
            alert('Text copied to clipboard ' + inputToCopy.value);
        }
    </script>
</body>
</html>

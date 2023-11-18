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
    <div class="alert-container">
        <div class="alert-content">
            <h3 class="title"></h3>
            <p class="message"></p>
        </div>
    </div>
    <div class="header">
        <h1>Image Compressor</h1>
        <span class="author">by codeGehan</span>
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
    <script src="main.js"></script>
</body>
</html>

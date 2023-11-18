<?php 
    include 'dbconn.php';
    // to rize image width and height
    function resizeImage($sourcePath, $width, $height) {
        $info = getimagesize($sourcePath);
        $mime = $info['mime'];
        switch ($mime) {
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                $image = imagecreatefromjpeg($sourcePath);
        }
        $result = imagescale($image, $width, $height);
        imagedestroy($image);
        return $result;
    }
    // to compress image size
    function compressImage($image, $destinationPath, $quality) 
    {
        $result = imagejpeg($image, $destinationPath, $quality);
        imagedestroy($image);
        return $result;
    }
    
    if (isset($_POST["submit"])) {
        $sourceImage = $_FILES["imageFile"]["tmp_name"];
        $filename = $_FILES["imageFile"]["name"];
        // set the heigth and width of the image
        $width = 150;
        $height = 150;
        $rand = rand(000, 999);
        $newfilename = $rand.$filename;
        $resizedImage = resizeImage($sourceImage, $width, $height);
        $destinationPath = "C:/Users/gelli/OneDrive/Desktop/$newfilename";
        // set the quality of the image from 0 - 100
        $quality = 30;
        compressImage($resizedImage, $destinationPath, $quality);

        //Convert the image to base64
        $compressedImg = file_get_contents($destinationPath);
        // For the source image = data:{mime};base64, $compressedImg
        $encodeBase64 = base64_encode($compressedImg);
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
    <title>Image Compressor</title>
</head>
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family:monospace;
    }
    body{
        position: absolute;
        top: 0;
        left: 0;
        transform: translate(50%, 50%);
        border: 2px solid grey;
        border-radius: 50px;
        text-align: center;
        width: 50%;
        height: 30%;
        box-shadow: 2px 6px 8px rgb(0,0,0,0.4);
    }
    .header{
        width: 100%;
        text-align: center;
        margin-top: 40px;
    }
    .header h1{
        color: red;
    }
    form{
        margin-top: 30px;
    }
    input{
        border: 1px solid black;
        border-radius: 10px;
        padding: 10px;
    }
    button{
        border: 1px solid black;
        border-radius: 10px;
        padding: 12px 30px;
        background-color: green;
        box-shadow: 0px 4px 8px rgb(0,0,0,0.4);
        cursor: pointer;
        color: #fff;
        transition:  scale 0.2s ease-in-out;
    }
    button:hover{
        background-color: darkgreen;
        scale: 1.03;
    }
    .preview {
        margin-top: 90px;
        position: relative;
    }
    .preview .img-container img{
        border-radius: 10px;
        box-shadow: 2px 2px 8px rgb(0,0,0,0.4);
        width: 20%;
    }

    .preview .img-container p:first-of-type{
        padding-top: 10px;
    }
    .preview .img-container:last-of-type{
        padding-top: 20px;
    }
    .preview i{
        position: relative;
        top: 30%;
        padding-top: 20px;
        font-size: 3rem;
        text-align: center;
    }
</style>
<body>
    <div class="header">
        <h1>Image Compressor</h1>
    </div>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="imageFile" id="imageFile" accept="image/*" onchange="loadImage(event)">
        <button type="submit" name="submit">Submit</button>
        
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

    <script>
        var loadImage = function(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output1 = document.getElementById('imgFrom');
                var output2 = document.getElementById('imgTo');
                var icon = document.getElementById('icon-arrow');
                var imgfromsize = document.getElementById('imgFromSize');
                output1.src = reader.result;
                icon.hidden = false;
                output1.hidden = false;
                output2.hidden = false;
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
</body>
</html>

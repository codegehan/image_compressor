<?php 

    include 'compress.php';

    if (isset($_FILES['imageFile'])) {
        $sourceImage = $_FILES['imageFile']['tmp_name'];
        $quality = 30;
        $width = 150;
        $height = 150;
        $compressedImage = Image::Compress($sourceImage,$quality,$width,$height);
        $convertedBase64Image = base64_encode($compressedImage);

        echo $convertedBase64Image;
    } else  {
        echo "Error: No image file received.";
    }
?>
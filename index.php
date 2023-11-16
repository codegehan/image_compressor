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
        $destinationPath = "C:/Users/codeGehan/Desktop/$newfilename";
        // set the quality of the image from 0 - 100
        $quality = 30;
        compressImage($resizedImage, $destinationPath, $quality);

        //Convert the image to base64
        $compressedImg = file_get_contents($destinationPath);
        // For the source image = data:{mime};base64, $compressedImg
        $encodeBase64 = base64_encode($compressedImg);
        $query = "INSERT INTO image (img_data) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $encodeBase64);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo $encodeBase64;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Compressor</title>
</head>
<body>
    <h1>Image Compressor</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="imageFile" id="imageFile" accept="image/*">
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>

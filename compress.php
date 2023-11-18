<?php 
    class Image {
        public static function Compress($sourceImage, $quality, $width, $height){
            $info = getimagesize($sourceImage);
            $mime = $info['mime'];
            switch ($mime) {
                case 'image/png':   $image = imagecreatefrompng($sourceImage);    break;
                case 'image/jpeg':  $image = imagecreatefromjpeg($sourceImage);   break;
                case 'image/gif':   $image = imagecreatefromgif($sourceImage);    break;
                default: $image = imagecreatefromjpeg($sourceImage);
            }
            $imageResize = imagescale($image, $width, $height);
            ob_start();
            imagejpeg($imageResize, null , $quality);
            $compressedImage = ob_get_clean();
            imagedestroy($image);
            return $compressedImage;
        }
    }

?>
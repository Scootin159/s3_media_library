<?php namespace s3ml;

class Media
{
    public static function ReadEXIFData(string $image_file)
    {
        return exif_read_data($image_file);
    }

    public static function CreateThumbnail(string $image_data) : string
    {
        // Load the image
        $image = imagecreatefromstring($image_data);

        // Get the current dimensions
        $original_x = imagesx($image);
        $original_y = imagesy($image);

        if($original_x > $original_y)
        {
            $new_x = SETTING_IMAGE_THUMBNAIL_SIZE;
            $new_y = (SETTING_IMAGE_THUMBNAIL_SIZE / $original_x) * $original_y;
        } else {
            $new_x = (SETTING_IMAGE_THUMBNAIL_SIZE / $original_y) * $original_x;
            $new_y = SETTING_IMAGE_THUMBNAIL_SIZE;
        }
        $thumbnail = imagescale($image, $new_x, $new_y);

        // Write the image in WEBP format to a byte array
        ob_start();
        imagewebp($thumbnail);
        $webp = ob_get_contents();
        ob_end_clean();     
        
        imagedestroy($image);
        imagedestroy($thumbnail);

        // Return the WEBP image
        return base64_encode($webp);
    }
}
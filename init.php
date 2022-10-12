<?php
declare(strict_types=1);

include('upload.php');

if (! function_exists('s3_upload_image')) {
    function s3_upload_image()
    {
        return upload();
    }
}

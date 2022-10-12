<?php

declare(strict_types=1);

use Apro\Upload\Storage;
use Apro\Upload\Storage\S3Storage;

if (! function_exists('storage')) {
    /**
     * @throws Exception
     */
    function storage(string $storage = 's3'): Storage
    {
        global $CFG;

        switch ($storage) {
            case 's3':
                return new S3Storage([
                    'url'                     => $CFG->S3_URL,
                    'endpoint'                => $CFG->S3_ENDPOINT,
                    'bucket'                  => $CFG->S3_BUCKET,
                    'region'                  => $CFG->S3_REGION,
                    'secret'                  => $CFG->S3_AWS_SECRET_KEY,
                    'key'                     => $CFG->S3_AWS_KEY,
                    'directory'               => $CFG->sessionname,
                    'use_path_style_endpoint' => $CFG->S3_USE_PATH_STYLE_ENDPOINT,
                ]);
            default:
                throw new Exception('找不到可用的儲存驅動');
        }
    }
}

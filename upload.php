<?php
use Aws\S3\S3Client;

// require the amazon sdk from your composer vendor dir
require __DIR__.'/vendor/autoload.php';


function create_connection() {
    global $CFG;
    // Instantiate the S3 class and point it at the desired host
    return new S3Client([
        'region' => '',
        'version' => '2022-10-12',
        'endpoint' => $CFG->endpoint,
        'credentials' => [
            'key' => $CFG->AWS_KEY,
            'secret' => $CFG->AWS_SECRET_KEY
        ],
        // Set the S3 class to use objects.dreamhost.com/bucket
        // instead of bucket.objects.dreamhost.com
        'use_path_style_endpoint' => true
    ]);
    
}

function upload() {
    $client = create_connection();
    $listResponse = $client->listBuckets();
    $buckets = $listResponse['Buckets'];
    foreach ($buckets as $bucket) {
        echo $bucket['Name'] . "\t" . $bucket['CreationDate'] . "\n";
    }
}
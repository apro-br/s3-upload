<?php

declare(strict_types=1);

namespace Apro\Upload\Storage;

use Apro\Upload\Storage;
use Aws\S3\S3Client;

class S3Storage implements Storage
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function url(string $path): string
    {
        return $this->config['url'] . '/' . $this->config['directory'] . '/' . $path;
    }

    public function put(string $path, $contents, array $options = []): string
    {
        $filepath = $this->config['directory'] . '/' . $path;
        $this->connection()->putObject([
            'Bucket' => $this->config['bucket'],
            'Key'    => $filepath,
            'Body'   => $contents,
            'ACL'    => $options['ACL'] ?? 'public-read',
        ]);

        return $filepath;
    }

    public function del(string $path, array $options = []): void
    {
        $this->connection()->deleteObject([
            'Bucket' => $this->config['bucket'],
            'Key'    => $path,
        ]);
    }

    public function list(string $path, array $options = []): array
    {
        $filepath = $this->config['directory'] . '/' . $path;
        $contents = $this->connection()->listObjects([
            'Bucket' => $this->config['bucket']
        ]);
        foreach ($contents['Contents'] as $content) {
            if (strpos($content['Key'], $filepath) !== false) {
                if (!empty($options['filter'])) {
                    if (strpos($content['Key'], $options['filter']) !== false) {
                        $image_list[] = $content['Key'];
                    }
                } else {
                    $image_list[] = $content['Key'];
                }
            }
        }

        return $image_list ?? [];
    }

    public function mkdir(string $path, array $options = []): void
    {
        $this->put($path, '', $options);
    }

    public function delDir(string $path, array $options = []): void
    {
        $arr_image = $this->list($path, $options);

        foreach ($arr_image as $value) {
            $this->del($value);
        }
    }

    protected function connection(): S3Client
    {
        return new S3Client([
            'version'     => 'latest',
            'region'      => $this->config['region'],
            'credentials' => [
                'key'    => $this->config['key'],
                'secret' => $this->config['secret'],
            ],
            'endpoint'                => $this->config['endpoint'],
            'use_path_style_endpoint' => $this->config['use_path_style_endpoint'],
        ]);
    }
}

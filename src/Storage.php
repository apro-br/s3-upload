<?php

declare(strict_types=1);

namespace Apro\Upload;

interface Storage
{
    public function put(string $path, string $contents, array $options = []): string;

    public function del(string $path, array $options = []): void;

    public function list(string $path, array $options = []): array;

    public function mkdir(string $path, array $options = []): void;

    public function delDir(string $path, array $options = []): void;

    public function url(string $path): string;
}

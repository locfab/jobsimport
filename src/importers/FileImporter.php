<?php

namespace JobMangement\Importers;

use JobMangement\Database\JobRepository;

interface FileImporter {
    public function import(string $file): int;
    public static function getRule(): string;
}
<?php

namespace JobMangement\Importers;

use JobMangement\Database\JobRepository;

abstract class FileImporter {

    protected JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository) {
        $this->jobRepository = $jobRepository;
    }
    abstract public function import(string $file): int;
}
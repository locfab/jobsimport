<?php

use JobMangement\Database\JobRepository;
use JobMangement\Importers\ImportJobteaser\ImportJobteaser;
use JobMangement\Importers\ImportRegionsJob\ImportRegionsJob;

class JobsImporter
{
    private array $files;

    private array $classes;

    protected JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository, array $files)
    {
        $this->files = $files;
        $this->jobRepository = $jobRepository;

        $this->classes = [
            ImportJobteaser::class,
            ImportRegionsJob::class,
        ];
    }


    /**
     * Retrieve the class name associated with the given file.
     *
     * @param string $filename The name of the file to search for an associated class.
     * @return string|null The corresponding class name if found, or null if no matching rule is found.
     */
    private function getImportClass(string $filename): ?string {
        foreach ($this->classes as $importClass) {
            if (fnmatch(call_user_func([$importClass, 'getRule']), $filename)) {
                return $importClass;
            }
        }
        return null; // if no rule matches
    }

    public function importJobs(): int
    {
        $count = 0;

        foreach ($this->files as $file) {
            $importClass = $this->getImportClass($file); // This function maps the file import class name to the given file.
            if ($importClass) {
                $importer = new $importClass($this->jobRepository); // Classes are in src/importers.
                $result = $importer->import($file);
                $count += $result !== null ? $result : 0;
            }
        }
        return $count;
    }
}

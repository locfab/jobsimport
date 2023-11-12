<?php

use JobMangement\Database\JobRepository;
use JobMangement\Importers\ImportJobteaser\ImportJobteaser;
use JobMangement\Importers\ImportRegionsJob\ImportRegionsJob;

class JobsImporter
{
    private array $files;

    private array $fileRules;

    protected JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository, array $files)
    {
        $this->files = $files;
        $this->jobRepository = $jobRepository;

        /**
         * Defines a class property $fileRules, which is an associative array.
         *
         * This property is used to establish rules that map file names to data import methods.
         * For example, the rule "*jobteaser*.json" => "importJobteaser::class" signifies that if a file name matches
         * the pattern "*jobteaser*.json", the importJobteaser class should be used to import data from that file.
         * This pattern matches files whose names start with any sequence of characters (* means any sequence)
         * followed by "jobteaser" and ending with ".json."
         *
         * In summary, $this->fileRules is a mechanism for associating files with data import classes
         * based on their names and locations. This enables dynamic management of data imports from various file types
         * using the appropriate classes.
         *
         * Additionally, if a new company arrives, you can simply add it to the $fileRules array
         * and create the corresponding import class. This ensures that the system can adapt to new data sources
         * without requiring extensive code changes, enhancing flexibility and scalability.
         *
         * Note: Files that don't match any of the defined fileRules will not be considered for processing.
         */


        $this->fileRules = [
            '*/jobteaser*.json' => ImportJobteaser::class,
            '*/regionsjob*.xml' => ImportRegionsJob::class,
            // Add other rules here
        ];
    }

    /**
     * Getter for the fileRules property.
     *
     * @return array The array of file rules used for data import.
     */
    private function getRules(): array {
        return $this->fileRules;
    }


    /**
     * Retrieve the class name associated with the given file.
     *
     * @param string $filename The name of the file to search for an associated class.
     * @return string|null The corresponding class name if found, or null if no matching rule is found.
     */

    private function getImportClass(string $filename): ?string {
        foreach ($this->getRules() as $pattern => $importClass) {
            if (fnmatch($pattern, $filename)) {
                return $importClass;
            }
        }
        return null; // if no rule matches
    }

    public function importJobs(): int
    {
        $ids = $this->jobRepository->selectJobIds();
        $count = 0;

        foreach ($this->files as $file) {
            $importClass = $this->getImportClass($file); // This function maps the file import class name to the given file.
            if ($importClass) {
                $importer = new $importClass($this->jobRepository); // Classes are in src/importers.
                $result = $importer->import($file);
                $count += $result !== null ? $result : 0;
            }
        }

        // After successfully importing all new data, we might consider deleting the old data.
        if ($count > 0) {
            $this->jobRepository->deleteJobsByIds($ids);
        }

        return $count;
    }
}

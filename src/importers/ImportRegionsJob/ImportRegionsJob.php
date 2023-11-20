<?php

namespace JobMangement\Importers\ImportRegionsJob;

use JobMangement\Database\JobRepository;
use JobMangement\Entity\Job;
use JobMangement\Importers\FileImporter;


class ImportRegionsJob implements FileImporter {

    protected JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository){
        $this->jobRepository = $jobRepository;
    }
    /**
     * Import job data from an XML file in the RegionsJob format.
     *
     * This function is used to import job data from an XML file in the RegionsJob format. It loads the XML file,
     * iterates through the 'item' elements, and inserts each job offer into the database.
     *
     * @param string $file The path to the XML file to import.
     *
     * @return int The number of successfully imported job offers.
     */
    public function import(string $file): int {
        $xml = simplexml_load_file($file);
        $count = 0;

        foreach ($xml->item as $item) {
            $newJob = new Job($item->ref, $item->title, $item->description, $item->url, $item->company, $item->pubDate);
            $jobData = $this->jobRepository->selectJobByRef($item->ref);
            if (count($jobData) === 0) {
                $this->jobRepository->insertJobData($newJob);
                $count++;
            }
        }
        return $count;
    }

    public static function getRule(): string
    {
        return RULE_REGIONSJOB;
    }
}
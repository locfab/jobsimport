<?php

namespace JobMangement\Importers\ImportJobteaser;

use JobMangement\Entity\Job;
use JobMangement\Database\JobRepository;
use JobMangement\Importers\FileImporter;


class ImportJobteaser implements FileImporter {

    protected JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository){
        $this->jobRepository = $jobRepository;
    }
    /**
     * Import JobTeaser data from a JSON file.
     *
     * This function is used to import data from a JSON file following the JobTeaser format.
     * It iterates through job offers in the JSON file and inserts each offer into the database.
     *
     * @param string $file The path to the JSON file to import.
     *
     * @return int The number of successfully imported job offers.
     */
    public function import(string $file): int {
        $jsonData = file_get_contents($file);
        $json = json_decode($jsonData, true);

        $count = 0;

        /* Insert each item into the database */
        foreach ($json['offers'] as $offer) {
            $jobData = $this->jobRepository->selectJobByRef($offer['reference']);
            if (count($jobData) === 0) {
                // Process each offer item here
                $id = $offer['reference'];
                $title = $offer['title'];
                $description = $offer['description'];
                $url = $offer['urlPath'];
                $company = $offer['companyname'];
                $pubDate = date("Y/m/d", strtotime($offer['publishedDate']));

                $newJob = new Job($id, $title, $description, $url, $company, $pubDate);
                $this->jobRepository->insertJobData($newJob);
                $count++;
            }
        }
        return $count;
    }

    public static function getRule(): string
    {
        return RULE_JOBTEASER;
    }
}
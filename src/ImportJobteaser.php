<?php


class ImportJobteaser extends FileImporter {
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
            // Process each offer item here
            $id = $offer['reference'];
            $title = $offer['title'];
            $description = $offer['description'];
            $url = $offer['urlPath'];
            $company = $offer['companyname'];
            $pubDate = date("Y/m/d", strtotime($offer['publishedDate']));

            $this->jobRepository->insertJobData($id, $title, $description, $url, $company, $pubDate);
            $count++;
        }
        return $count;
    }
}
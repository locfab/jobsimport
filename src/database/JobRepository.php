<?php

namespace JobMangement\Database;

use JobMangement\Entity\Job;
use PDO;

class JobRepository {

    private DatabaseConnectionInterface $db;

    public function __construct(DatabaseConnectionInterface $db) {
        $this->db = $db;
    }

    public function insertJobData(Job $job) {
        $query = "INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindParam(1, $job->ref, PDO::PARAM_STR);
            $stmt->bindParam(2, $job->title, PDO::PARAM_STR);
            $stmt->bindParam(3, $job->description, PDO::PARAM_STR);
            $stmt->bindParam(4, $job->url, PDO::PARAM_STR);
            $stmt->bindParam(5, $job->company, PDO::PARAM_STR);
            $stmt->bindParam(6, $job->pubDate, PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();
        } else {
            die('DB error: Unable to prepare the SQL statement.' . "\n");
        }
    }

    public function selectJobByRef($jobRef): array
    {
        $query = 'SELECT id, reference, title, description, url, company_name, publication FROM job WHERE reference = :reference';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->bindParam(':reference', $jobRef);
        $statement->execute();

        $job = $statement->fetch(PDO::FETCH_ASSOC);

        return $job ?: [];
    }

    public function selectJobData(): array
    {
        $jobs = $this->db->getConnection()->query('SELECT id, reference, title, description, url, company_name, publication FROM job')->fetchAll(PDO::FETCH_ASSOC);

        return $jobs;
    }
    public function deleteAllJobData(): void
    {
        $this->db->getConnection()->exec('DELETE FROM job');
    }
    public function selectJobIds(): array
    {
        $jobIds = $this->db->getConnection()->query('SELECT id FROM job')->fetchAll(PDO::FETCH_COLUMN);

        return $jobIds;
    }
    public function deleteJobsByIds(array $jobIds): void
    {
        if (empty($jobIds)) {
            return;
        }

        $jobIds = implode(',', $jobIds);

        $this->db->getConnection()->query("DELETE FROM job WHERE id IN ($jobIds)");
    }
}

<?php
class JobRepository {
    private DatabaseConnectionInterface $db;

    public function __construct(DatabaseConnectionInterface $db) {
        $this->db = $db;
    }

    public function insertJobData(string $reference, string $title, string $description, string $url, string $company, string $pubDate) {
        $query = "INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->getConnection()->prepare($query);

        if ($stmt) {
            $stmt->bindParam(1, $reference, PDO::PARAM_STR);
            $stmt->bindParam(2, $title, PDO::PARAM_STR);
            $stmt->bindParam(3, $description, PDO::PARAM_STR);
            $stmt->bindParam(4, $url, PDO::PARAM_STR);
            $stmt->bindParam(5, $company, PDO::PARAM_STR);
            $stmt->bindParam(6, $pubDate, PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();
        } else {
            die('DB error: Unable to prepare the SQL statement.' . "\n");
        }
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
}

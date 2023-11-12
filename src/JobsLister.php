<?php

class JobsLister
{
    protected JobRepository $jobRepository;

    public function __construct($jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function listJobs(): array
    {
        return $this->jobRepository->selectJobData();
    }
}

<?php

namespace JobMangement\Lists;

use JobMangement\Database\JobRepository;

class JobsLister
{
    protected JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function listJobs(): array
    {
        return $this->jobRepository->selectJobData();
    }
}

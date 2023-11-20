<?php

namespace JobMangement\Entity;

class Job {
    public $ref;
    public $title;
    public $description;
    public $url;
    public $company;
    public $pubDate;

    public function __construct($ref, $title, $description, $url, $company, $pubDate) {
        $this->ref = $ref;
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->company = $company;
        $this->pubDate = $pubDate;
    }
}

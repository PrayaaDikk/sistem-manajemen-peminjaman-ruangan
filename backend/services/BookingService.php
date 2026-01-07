<?php
class BookingService {
    private $repo;
    private $config;

    public function __construct($repo)
    {
        $this->repo = $repo;
        $this->config = simplexml_load_file(__DIR__ . "/../config/config.xml");
    }

    public function createBooking($data) {
        $data[] = 'pending';
        return $this->repo->save($data);
    }
}
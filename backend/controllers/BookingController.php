<?php
class BookingController {
    private $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function store($request) {
        return $this->service->createBooking($request);
    }
}
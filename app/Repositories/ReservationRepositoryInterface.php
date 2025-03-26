<?php
    namespace App\Repositories;
    use App\Models\Reservation;

    interface ReservationRepositoryInterface
    {
        public function all();

        public function find($id);
        public function create(array $data);
        public function delete(Reservation $reservation);
        public function update(Reservation $reservation , array $data);
    }

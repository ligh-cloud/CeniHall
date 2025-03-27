<?php


namespace App\Repositories;

interface PaymentRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update(\App\Models\Payment $payment, array $data);

    public function delete(\App\Models\Payment $payment);
}

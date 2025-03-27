<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function all()
    {
        return Payment::with(['user'])->get();
    }

    public function find($id)
    {
        return Payment::with(['user'])->find($id);
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update(Payment $payment, array $data)
    {
        return $payment->update($data);
    }

    public function delete(Payment $payment)
    {
        return $payment->delete();
    }
}

<?php
use App\Repositories\PaymentRepositoryInterface;
class PaiementService
{
    protected $PaiementRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->PaiementRepository = $paymentRepository;
    }

    public function index(){
        //here  i will return the view
    }

    public function getAccessToken(){

    }
    public function create($amount){

    }
}

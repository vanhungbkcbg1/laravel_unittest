<?php


namespace App\Services;


class OrderService
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function placeOrder()
    {

        if ($this->emailService->sendMail()) {
            return 1;
        }
        return 2;

    }
}

<?php

namespace Tests\Unit\Services;

use App\Services\EmailService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    protected $orderService;
    public function testPlaceOrderSendMailFailed(){
        $emailService=\Mockery::mock(EmailService::class);
        $emailService->shouldReceive("sendMail")->andReturn(false);
        $this->orderService=new OrderService($emailService);
        $this->assertEquals(2,$this->orderService->placeOrder());
    }
    public function testPlaceOrderSendMailSuccess(){
        $emailService=\Mockery::mock(EmailService::class);
        $emailService->shouldReceive("sendMail")->andReturn(true);
        $this->orderService=new OrderService($emailService);
        $this->assertEquals(1,$this->orderService->placeOrder());
    }
}

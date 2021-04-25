<?php

namespace App\Http\Controllers;

use App\Mail\AnalyzedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        Mail::send(new AnalyzedMail());
    }
}

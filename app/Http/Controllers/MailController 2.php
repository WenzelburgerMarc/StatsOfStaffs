<?php

namespace App\Http\Controllers;

class MailController extends Controller
{
    public function create()
    {
        return view('broadcast-mail.create');
    }
}

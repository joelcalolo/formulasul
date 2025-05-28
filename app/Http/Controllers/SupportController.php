<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Regulation;

class SupportController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        $regulations = Regulation::all();
        return view('suporte', compact('faqs', 'regulations'));
    }
}
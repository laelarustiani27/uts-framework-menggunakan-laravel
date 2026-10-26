<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index() {
        return view('landing.index');
    }
    public function shop() {
        return view('landing.shop');
    }
    public function shopDetail() {
        return view('landing.shop-detail');
    }
    public function cart() {
        return view('landing.cart');
    }
    public function checkout() {
        return view('landing.checkout');
    }
    public function contact() {
        return view('landing.contact');
    }
}
<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $mode = session()->get('screenshot_mode');

        if ($mode === 'welcome') {
            return view('welcome_message');
        }

        if ($mode === 'plain') {
            return "Merupakan Halaman Utama Dari Halaman Home.";
        }

        if ($mode === 'simple') {
            return view('about_no_layout', [
                'title'   => 'Halaman Home',
                'content' => 'Merupakan Halaman Utama Dari Halaman Home.',
            ]);
        }

        if ($mode === 'layout') {
            return view('home_layout_simple', [
                'title'   => 'Halaman Home',
                'content' => 'Merupakan Halaman Utama Dari Halaman Home.',
            ]);
        }

        return view('home', [
            'title'   => 'Halaman Home',
            'content' => 'Merupakan Halaman Utama Dari Halaman Home.',
        ]);
    }
}

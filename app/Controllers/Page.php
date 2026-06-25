<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
        $mode = session()->get('screenshot_mode');

        if ($mode === 'plain') {
            return "Ini halaman About";
        }

        if ($mode === 'simple') {
            return view('about_no_layout', [
                'title'   => 'Halaman About',
                'content' => 'Ini adalah halaman about yang menjelaskan tentang isi halaman ini.',
            ]);
        }

        if ($mode === 'layout') {
            return view('about_layout_simple', [
                'title'   => 'Halaman About',
                'content' => 'Ini adalah halaman about yang menjelaskan tentang isi halaman ini.',
            ]);
        }

        return view('about', [
            'title'   => 'Halaman About',
            'content' => 'Halaman ini berisi informasi singkat mengenai website praktikum yang dibangun menggunakan CodeIgniter 4.',
        ]);
    }

    public function contact()
    {
        $mode = session()->get('screenshot_mode');

        if ($mode === 'plain') {
            return "Ini halaman Contact";
        }

        if ($mode === 'simple') {
            return view('contact_no_layout', [
                'title'   => 'Halaman Contact',
                'content' => 'Ini adalah halaman contact yang menjelaskan tentang isi halaman ini.',
            ]);
        }

        if ($mode === 'layout') {
            return view('contact_layout_simple', [
                'title'   => 'Halaman Contact',
                'content' => 'Ini adalah halaman contact yang menjelaskan tentang isi halaman ini.',
            ]);
        }

        return view('contact', [
            'title'   => 'Halaman Contact',
            'content' => 'Silakan hubungi kami melalui email atau media lain yang tersedia untuk pertanyaan dan masukan.',
        ]);
    }

    public function faqs()
    {
        // For all screenshot helper modes in Modul 1, we return plain text
        $mode = session()->get('screenshot_mode');
        if ($mode !== null) {
            return 'Ini halaman FAQ';
        }

        return 'Ini halaman FAQ';
    }

    public function tos()
    {
        $mode = session()->get('screenshot_mode');
        if ($mode !== null) {
            return 'ini halaman Term of Services';
        }

        return 'Ini halaman Term of Services';
    }
}

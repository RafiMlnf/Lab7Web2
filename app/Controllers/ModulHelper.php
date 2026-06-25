<?php

namespace App\Controllers;

class ModulHelper extends BaseController
{
    public function index()
    {
        $currentMode = session()->get('screenshot_mode') ?? 'normal';
        
        $modes = [
            'normal' => 'Normal Mode (Completed Web Application)',
            'welcome' => 'Welcome Mode (Default CodeIgniter 4 Welcome Screen)',
            'plain' => 'Plain Text Mode (Plain text without HTML)',
            'simple' => 'Simple View Mode (Basic HTML structure without CSS header/footer)',
            'layout' => 'Layout Template Mode (Modul 1 layout using template/header and template/footer)',
        ];

        return view('modul1_helper_view', [
            'title' => 'Screenshot Helper - Modul 1',
            'currentMode' => $currentMode,
            'modes' => $modes
        ]);
    }

    public function set($mode)
    {
        if ($mode === 'normal') {
            session()->remove('screenshot_mode');
        } else {
            session()->set('screenshot_mode', $mode);
        }

        return redirect()->to('/modul1-helper')->with('success', 'Mode berhasil diubah ke: ' . $mode);
    }
}

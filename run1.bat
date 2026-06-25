@echo off
title Menjalankan XAMPP dan CodeIgniter

echo Menyalakan Apache...
start "" "D:\xampp\apache_start.bat"

echo Menyalakan MySQL...
start "" "D:\xampp\mysql_start.bat"

echo Menunggu XAMPP aktif...
timeout /t 5 /nobreak >nul

echo Menjalankan CodeIgniter server...
start "CI4 Server" cmd /k "cd /d D:\xampp\htdocs\lab11_ci\Lab7Web-2 && php spark serve"

echo Menunggu server CI4...
timeout /t 3 /nobreak >nul

echo Membuka browser...
start "" "http://localhost:8080"

exit
@echo off
title Menjalankan XAMPP dan Project Web

echo Menyalakan Apache...
start "" "D:\xampp\apache_start.bat"

echo Menyalakan MySQL...
start "" "D:\xampp\mysql_start.bat"

echo Tunggu sebentar...
timeout /t 5 /nobreak >nul

echo Membuka browser...
start "" "http://localhost/lab11_ci/Lab7Web-2/public"

exit
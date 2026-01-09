@echo off
title Warung Madura Server
echo ===================================================
echo     WARUNG MADURA MANAGEMENT SYSTEM
echo ===================================================
echo.
echo Menjalankan server aplikasi...
echo Jangan tutup jendela ini selama aplikasi digunakan.
echo.
echo Akses di browser: http://localhost:8000
echo.
bin\php\php.exe artisan serve
pause

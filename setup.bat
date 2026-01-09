@echo off
title Install/Update Warung Madura
echo ===================================================
echo     SETUP WARUNG MADURA SYSTEM
echo ===================================================
echo.
echo 1. Generate App Key...
bin\php\php.exe artisan key:generate
echo.
echo 2. Reset & Seed Database...
bin\php\php.exe artisan migrate:fresh --seed
echo.
echo 3. Create Storage Link...
bin\php\php.exe artisan storage:link
echo.
echo ===================================================
echo SETUP SELESAI!
echo Silakan jalankan 'start.bat' untuk membuka aplikasi.
echo ===================================================
pause

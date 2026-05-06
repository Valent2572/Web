@echo off
title Laravel Server - Portofolio Obed
cls
echo ==========================================
echo    STARTING LARAVEL DEVELOPMENT SERVER
echo ==========================================
echo.
echo [1/2] Opening browser at http://127.0.0.1:8000...
start http://127.0.0.1:8000

echo [2/2] Starting PHP Artisan Serve...
echo.
php artisan serve
pause

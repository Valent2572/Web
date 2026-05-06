@echo off
title Laravel + Vite - Portofolio Obed
cls
echo ==========================================
echo    STARTING DEVELOPMENT ENVIRONMENT
echo ==========================================
echo.

echo [1/3] Starting Vite (Assets)...
start /min cmd /c "npm run dev"

echo [2/3] Opening browser at http://127.0.0.1:8000...
timeout /t 2 >nul
start http://127.0.0.1:8000

echo [3/3] Starting Laravel Server...
echo.
php artisan serve
pause

@echo off
title ToDo App - Starter
echo ========================================
echo   TODO APP STARTER
echo ========================================
echo.
echo Starting development server...
echo.

:: Open browser after a short delay to ensure server is ready
start "" "http://127.0.0.1:8000"

:: Start Laravel server
php artisan serve

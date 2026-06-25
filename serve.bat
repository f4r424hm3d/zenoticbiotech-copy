@echo off
REM Starts the Laravel backend without needing php on PATH.
REM Double-click this file, or run  .\serve.bat  from server-laravel.
set "PHP=C:\Users\AMAN\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
REM Handle several requests at once (the admin panel fires parallel API calls).
set PHP_CLI_SERVER_WORKERS=6
"%PHP%" artisan serve --host=127.0.0.1 --port=5000 %*

@echo off
echo ========================================
echo Importando Base de Datos KioscoNet
echo ========================================
echo.

REM Cambiar a la ruta de MySQL de XAMPP
set MYSQL_PATH=C:\xampp\mysql\bin

REM Verificar si existe MySQL
if not exist "%MYSQL_PATH%\mysql.exe" (
    echo ERROR: No se encontro MySQL en %MYSQL_PATH%
    echo Por favor, verifica la ruta de instalacion de XAMPP
    pause
    exit /b 1
)

echo Conectando a MySQL...
echo.
echo NOTA: Ingresa tu contrasena de MySQL cuando se solicite
echo       (Por defecto en XAMPP es vacia, solo presiona ENTER)
echo.

"%MYSQL_PATH%\mysql.exe" -u root -p < kiosconet.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Base de datos importada exitosamente!
    echo ========================================
    echo.
    echo Se han creado:
    echo - Todas las tablas con motor InnoDB
    echo - Foreign Keys (relaciones)
    echo - Indices optimizados
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR: Hubo un problema al importar
    echo ========================================
    echo.
)

pause

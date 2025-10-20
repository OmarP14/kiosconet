# Script para corregir el problema de autenticación
# Ejecutar desde la raíz del proyecto Laravel

$files = @(
    "resources\views\layouts\app.blade.php",
    "resources\views\ventas\index.blade.php", 
    "resources\views\dashboard.blade.php"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "Corrigiendo: $file"
        
        # Leer contenido
        $content = Get-Content $file -Raw
        
        # Reemplazar las ocurrencias problemáticas
        $content = $content -replace "@if\(auth\(\)->user\(\)->esAdministrador\(\)\)", "@if(optional(auth()->user())->esAdministrador())"
        $content = $content -replace "auth\(\)->user\(\)->esAdministrador\(\)", "optional(auth()->user())->esAdministrador()"
        
        # Guardar archivo corregido
        Set-Content $file -Value $content -Encoding UTF8
        
        Write-Host "✅ Corregido: $file"
    } else {
        Write-Host "❌ No se encontró: $file"
    }
}

Write-Host "`n🎉 Corrección completada!"
Write-Host "Ahora ejecuta: php artisan view:clear"
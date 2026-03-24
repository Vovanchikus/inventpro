# run_migration_full.ps1
# Автоматизирует: создание пользователя migrator в MySQL, обновление .env.migration и запуск миграции
# Запускайте из PowerShell в каталоге проекта: .\run_migration_full.ps1

Set-StrictMode -Version Latest
$scriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $scriptRoot

function SecureStringToPlain($s) {
    $bstr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($s)
    try { [Runtime.InteropServices.Marshal]::PtrToStringAuto($bstr) }
    finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($bstr) }
}

Write-Host "Этот скрипт создаст пользователя migrator в MySQL и запустит миграцию."
Write-Host "Пароли не будут сохранены в репозитории (кроме временной подстановки в .env.migration)." -ForegroundColor Yellow

$rootSecure = Read-Host 'Введите пароль MySQL root (будет использован для выполнения CREATE USER)' -AsSecureString
$rootPlain = SecureStringToPlain $rootSecure

# If .env.migration already contains a MYSQL_PASSWORD, use it as migrator password and skip prompt
$envPathCheck = Join-Path $scriptRoot '.env.migration'
$migratorPlain = $null
if (Test-Path $envPathCheck) {
    $envContent = Get-Content $envPathCheck -Raw
    $m = [System.Text.RegularExpressions.Regex]::Match($envContent, 'MYSQL_PASSWORD=(.*)')
    if ($m.Success) {
        $pwdVal = $m.Groups[1].Value.Trim()
        if ($pwdVal -ne '' -and $pwdVal -ne 'migrator_tmp_pass') {
            $migratorPlain = $pwdVal
            Write-Host 'Использую пароль для migrator из .env.migration' -ForegroundColor Green
        }
    }
}
if (-not $migratorPlain) {
    $migratorSecure = Read-Host 'Введите пароль для пользователя migrator (будет установлен)' -AsSecureString
    $migratorPlain = SecureStringToPlain $migratorSecure
}

$createSqlPath = Join-Path $scriptRoot 'CREATE_MIGRATOR.sql'
if (-not (Test-Path $createSqlPath)) { Write-Error "Не найден $createSqlPath"; exit 1 }

$tmpSql = Join-Path $scriptRoot 'CREATE_MIGRATOR_exec.sql'
(Get-Content $createSqlPath -Raw) -replace 'migrator_tmp_pass', [System.Text.RegularExpressions.Regex]::Escape($migratorPlain) | Set-Content -Path $tmpSql -NoNewline

## Try to find mysql.exe: prefer explicit MYSQL_CMD, then system PATH, then common OpenServer locations
$mysqlCmdPath = $env:MYSQL_CMD
if ($mysqlCmdPath -and (Test-Path $mysqlCmdPath)) {
    $mysqlCmd = @{ Path = $mysqlCmdPath }
} else {
    $mysqlCmd = Get-Command mysql -ErrorAction SilentlyContinue
    if (-not $mysqlCmd) {
        # try OpenServer typical install folders
        $openServerCandidates = @(
            'C:\OSPanel\modules\database',
            'C:\OSPanel\userdata\config',
            'C:\OSPanel\modules',
            'C:\OpenServer\modules\database'
        )
        $found = $null
        foreach ($cand in $openServerCandidates) {
            if (Test-Path $cand) {
                $found = Get-ChildItem -Path $cand -Filter mysql.exe -Recurse -ErrorAction SilentlyContinue | Select-Object -First 1
                if ($found) { break }
            }
        }
        if ($found) {
            $mysqlCmd = @{ Path = $found.FullName }
            Write-Host "Найден mysql.exe: $($found.FullName)" -ForegroundColor Green
        }
    }
}

if (-not $mysqlCmd) { Write-Error 'mysql.exe не найден в PATH и не обнаружен в типичных папках OpenServer. Установите клиент MySQL или укажите $env:MYSQL_CMD.'; Remove-Item $tmpSql -ErrorAction SilentlyContinue; exit 1 }

Write-Host 'Создаю пользователя migrator в MySQL...' -ForegroundColor Cyan
try {
    # Передаём SQL через stdin
    Get-Content $tmpSql -Raw | & $mysqlCmd.Path -u root --password="$rootPlain"
} catch {
    Write-Error "Ошибка при выполнении CREATE_MIGRATOR.sql: $_"; Remove-Item $tmpSql -ErrorAction SilentlyContinue; exit 1
}

# Резервная копия .env.migration и подстановка пароля мигратора
$envPath = Join-Path $scriptRoot '.env.migration'
if (-not (Test-Path $envPath)) { Write-Error ".env.migration не найден в проекте"; Remove-Item $tmpSql -ErrorAction SilentlyContinue; exit 1 }
$envBak = "$envPath.bak"
Copy-Item $envPath $envBak -Force

try {
    (Get-Content $envPath) -replace 'MYSQL_PASSWORD=.*', "MYSQL_PASSWORD=$migratorPlain" | Set-Content $envPath
} catch {
    Write-Error "Не удалось обновить .env.migration: $_"; Move-Item $envBak $envPath -Force; Remove-Item $tmpSql -ErrorAction SilentlyContinue; exit 1
}

Write-Host 'Устанавливаю npm-зависимости...' -ForegroundColor Cyan
npm install
if ($LASTEXITCODE -ne 0) { Write-Error 'npm install завершился с ошибкой'; Move-Item $envBak $envPath -Force; Remove-Item $tmpSql -ErrorAction SilentlyContinue; exit 1 }

Write-Host 'Запускаю миграцию (migrate:products)...' -ForegroundColor Cyan
npm run migrate:products
if ($LASTEXITCODE -ne 0) { Write-Error 'npm run migrate:products завершился с ошибкой'; Move-Item $envBak $envPath -Force; Remove-Item $tmpSql -ErrorAction SilentlyContinue; exit 1 }

# Восстанавливаем оригинальный .env.migration
Move-Item $envBak $envPath -Force
Remove-Item $tmpSql -Force

Write-Host 'Миграция завершена успешно.' -ForegroundColor Green

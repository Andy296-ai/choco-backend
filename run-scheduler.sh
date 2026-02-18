#!/bin/bash
# Простой скрипт для запуска планировщика в фоне
# Используйте этот скрипт, если cron не установлен

PROJECT_PATH="/home/dzona/Documents/diplom-kursovaya/choco-backend_22"
LOG_FILE="$PROJECT_PATH/storage/logs/scheduler.log"

cd "$PROJECT_PATH"

echo "🚀 Запуск планировщика Laravel..."
echo "Логи: $LOG_FILE"
echo "Для остановки нажмите Ctrl+C"
echo ""

# Запускаем планировщик каждую минуту
while true; do
    php artisan schedule:run >> "$LOG_FILE" 2>&1
    sleep 60
done


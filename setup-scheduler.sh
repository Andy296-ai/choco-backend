#!/bin/bash
# Скрипт для настройки планировщика задач Laravel

PROJECT_PATH="/home/dzona/Documents/diplom-kursovaya/choco-backend_22"
CRON_JOB="* * * * * cd $PROJECT_PATH && php artisan schedule:run >> /dev/null 2>&1"

# Проверяем, существует ли уже эта задача
if crontab -l 2>/dev/null | grep -q "schedule:run"; then
    echo "✅ Планировщик уже настроен!"
    crontab -l | grep "schedule:run"
else
    # Добавляем задачу в crontab
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    echo "✅ Планировщик успешно настроен!"
    echo "Задача добавлена: $CRON_JOB"
fi

echo ""
echo "📋 Текущие задачи cron:"
crontab -l


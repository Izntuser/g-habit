#!/usr/bin/env bash

nohup php artisan queue:listen > /dev/null 2>&1 &

echo "[INFO] Checking background process....."

if ps aux | grep '[p]hp artisan queue:listen' > /dev/null
then
   echo "[INFO] php artisan queue:listen is running as a background process."
else
   echo "[ERROR ERROR ERROR ERROR ERROR] Failed to start php artisan queue:listen."
   exit 1
fi

echo "[INFO] Starting php-fpm..."

php-fpm

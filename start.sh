#!/bin/bash

# Railway 啟動腳本
# 設定預設 PORT 如果沒有提供
PORT=${PORT:-8000}

echo "Starting PHP server on port $PORT..."
php -S 0.0.0.0:$PORT -t .

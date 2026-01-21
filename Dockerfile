FROM php:8.3-cli

# 安裝 MySQL 擴展
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 安裝其他需要的擴展
RUN docker-php-ext-install mbstring

# 設定工作目錄
WORKDIR /app

# 複製應用程式文件
COPY . /app

# 開放端口
EXPOSE 8000

# 啟動 PHP 內建伺服器
CMD ["php", "-S", "0.0.0.0:8000", "-t", "."]

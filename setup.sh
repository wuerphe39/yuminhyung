#!/bin/bash
set -e

echo "=== 게시판 시스템 설치 시작 ==="

# 1. MySQL DB 및 테이블 생성
echo ">>> 데이터베이스 생성 중..."
sudo mysql <<'SQL'
CREATE DATABASE IF NOT EXISTS board_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE board_system;

CREATE TABLE IF NOT EXISTS users (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    post_id    INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    title      VARCHAR(200) NOT NULL,
    content    TEXT NOT NULL,
    views      INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id    INT NOT NULL,
    user_id    INT NOT NULL,
    content    TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

SELECT 'DB 및 테이블 생성 완료' AS status;
SQL

# 2. PHP 파일을 /var/www/html/board/ 로 복사
echo ">>> PHP 파일 복사 중..."
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
sudo cp -r "$SCRIPT_DIR/board" /var/www/html/
sudo chown -R www-data:www-data /var/www/html/board
sudo chmod -R 755 /var/www/html/board

echo ""
echo "=== 설치 완료 ==="
echo "브라우저에서 http://localhost/board 로 접속하세요."

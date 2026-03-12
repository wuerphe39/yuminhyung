<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판 시스템</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="/board/index.php">게시판</a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="navbar-text me-3 text-white">
                    <?= htmlspecialchars($_SESSION['username']) ?>님
                </span>
                <a class="nav-link text-white" href="/board/board/list.php">글 목록</a>
                <a class="nav-link text-white" href="/board/auth/logout.php">로그아웃</a>
            <?php else: ?>
                <a class="nav-link text-white" href="/board/auth/login.php">로그인</a>
                <a class="nav-link text-white" href="/board/auth/register.php">회원가입</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">

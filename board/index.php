<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="text-center py-5">
    <h1 class="display-5 fw-bold">게시판 시스템</h1>
    <p class="lead text-muted mb-4">LAMP 스택 기반 게시판 + 로그인 시스템</p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p class="mb-3">
            <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>님, 환영합니다!
        </p>
        <a href="/board/board/list.php" class="btn btn-primary btn-lg me-2">게시판 보기</a>
        <a href="/board/board/write.php" class="btn btn-outline-primary btn-lg">글쓰기</a>
    <?php else: ?>
        <a href="/board/auth/login.php" class="btn btn-primary btn-lg me-2">로그인</a>
        <a href="/board/auth/register.php" class="btn btn-outline-primary btn-lg">회원가입</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: /board/board/list.php');
    exit;
}

// 조회수 증가
$pdo->prepare('UPDATE posts SET views = views + 1 WHERE post_id = ?')->execute([$id]);

$stmt = $pdo->prepare(
    'SELECT p.*, u.username FROM posts p
     JOIN users u ON p.user_id = u.user_id
     WHERE p.post_id = ?'
);
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    echo '<div class="alert alert-warning">존재하지 않는 게시글입니다.</div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// 댓글 목록
$stmt = $pdo->prepare(
    'SELECT c.*, u.username FROM comments c
     JOIN users u ON c.user_id = u.user_id
     WHERE c.post_id = ?
     ORDER BY c.comment_id ASC'
);
$stmt->execute([$id]);
$comments = $stmt->fetchAll();

// 댓글 등록
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content = trim($_POST['content'] ?? '');
    if ($content !== '') {
        $stmt = $pdo->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->execute([$id, $_SESSION['user_id'], $content]);
        header("Location: /board/board/view.php?id=$id");
        exit;
    }
}
?>

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-start">
        <div>
            <h5 class="mb-1"><?= htmlspecialchars($post['title']) ?></h5>
            <small class="text-muted">
                <?= htmlspecialchars($post['username']) ?> &nbsp;|&nbsp;
                <?= $post['created_at'] ?> &nbsp;|&nbsp;
                조회 <?= $post['views'] ?>
            </small>
        </div>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
        <div>
            <a href="/board/board/edit.php?id=<?= $id ?>" class="btn btn-sm btn-outline-secondary">수정</a>
            <a href="/board/board/edit.php?id=<?= $id ?>&action=delete"
               class="btn btn-sm btn-outline-danger"
               onclick="return confirm('삭제하시겠습니까?')">삭제</a>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div style="white-space: pre-wrap;"><?= htmlspecialchars($post['content']) ?></div>
    </div>
</div>

<a href="/board/board/list.php" class="btn btn-secondary btn-sm mb-4">목록으로</a>

<!-- 댓글 -->
<h6>댓글 <?= count($comments) ?>개</h6>
<div class="mb-3">
<?php foreach ($comments as $c): ?>
    <div class="border rounded p-2 mb-2 bg-white">
        <strong><?= htmlspecialchars($c['username']) ?></strong>
        <small class="text-muted ms-2"><?= $c['created_at'] ?></small>
        <p class="mb-0 mt-1"><?= htmlspecialchars($c['content']) ?></p>
    </div>
<?php endforeach; ?>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
<form method="post">
    <div class="input-group">
        <textarea name="content" class="form-control" rows="2" placeholder="댓글을 입력하세요" required></textarea>
        <button type="submit" class="btn btn-primary">등록</button>
    </div>
</form>
<?php else: ?>
    <p class="text-muted">댓글을 작성하려면 <a href="/board/auth/login.php">로그인</a>하세요.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

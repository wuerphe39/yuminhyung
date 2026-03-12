<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /board/auth/login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: /board/board/list.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
    echo '<div class="alert alert-danger">접근 권한이 없습니다.</div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// 삭제
if (($_GET['action'] ?? '') === 'delete') {
    $pdo->prepare('DELETE FROM posts WHERE post_id = ?')->execute([$id]);
    header('Location: /board/board/list.php');
    exit;
}

$error = '';

// 수정 저장
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = '제목과 내용을 모두 입력해주세요.';
    } else {
        $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ? WHERE post_id = ?');
        $stmt->execute([$title, $content, $id]);
        header("Location: /board/board/view.php?id=$id");
        exit;
    }
}

$title   = $_POST['title']   ?? $post['title'];
$content = $_POST['content'] ?? $post['content'];
?>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <h5 class="card-title mb-4">글 수정</h5>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">제목</label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($title) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">내용</label>
                <textarea name="content" class="form-control" rows="12" required><?= htmlspecialchars($content) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">저장</button>
                <a href="/board/board/view.php?id=<?= $id ?>" class="btn btn-secondary">취소</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

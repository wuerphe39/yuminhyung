<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 10;
$offset   = ($page - 1) * $perPage;

$total = $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);

$stmt = $pdo->prepare(
    'SELECT p.post_id, p.title, p.views, p.created_at, u.username
     FROM posts p
     JOIN users u ON p.user_id = u.user_id
     ORDER BY p.post_id DESC
     LIMIT ? OFFSET ?'
);
$stmt->execute([$perPage, $offset]);
$posts = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">게시글 목록</h4>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/board/board/write.php" class="btn btn-primary btn-sm">글쓰기</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:60px">번호</th>
                <th>제목</th>
                <th style="width:110px">작성자</th>
                <th style="width:120px">작성일</th>
                <th style="width:70px">조회</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($posts)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">등록된 글이 없습니다.</td></tr>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $post['post_id'] ?></td>
                <td>
                    <a href="/board/board/view.php?id=<?= $post['post_id'] ?>" class="text-decoration-none text-dark">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($post['username']) ?></td>
                <td><?= date('Y-m-d', strtotime($post['created_at'])) ?></td>
                <td><?= $post['views'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<nav class="mt-3 d-flex justify-content-center">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
if (!isset($page) || !isset($totalPages) || !isset($searchQuery)) {
    return;
}
?>
<nav class="app-pagination">
  <ul class="pagination justify-content-center">
    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
      <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchQuery); ?>" aria-label="Sebelumnya" tabindex="-1">
        <span aria-hidden="true">Sebelumnya</span>
      </a>
    </li>

    <?php if ($page > 3): ?>
    <li class="page-item"><span class="page-link">...</span></li>
    <?php endif; ?>

    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
      <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>"><?php echo $i; ?></a>
    </li>
    <?php endfor; ?>

    <?php if ($page < $totalPages - 2): ?>
    <li class="page-item"><span class="page-link">...</span></li>
    <?php endif; ?>

    <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
      <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchQuery); ?>" aria-label="Berikutnya" tabindex="-1">
        <span aria-hidden="true">Berikutnya</span>
      </a>
    </li>
  </ul>
</nav>

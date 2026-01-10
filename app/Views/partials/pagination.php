<?php
/**
 * Required variables:
 * $page
 * $limit
 * $totalPages
 * $totalCount
 * $sort
 * $dir
 */

$startItem = ($page - 1) * $limit + 1;
$endItem   = min($page * $limit, $totalCount);

$window = 10;
$startPage = max(1, $page - floor($window / 2));
$endPage   = min($totalPages, $startPage + $window - 1);

if ($endPage - $startPage + 1 < $window) {
    $startPage = max(1, $endPage - $window + 1);
}

$queryBase = http_build_query([
    'sort' => $sort,
    'dir'  => $dir
]);
?>

<?php if ($totalPages > 1): ?>
<div class="flex flex-col md:flex-row justify-between items-center gap-4 border border-0 border-t border-gray-300">
  <!-- Info -->
  <div class="text-sm text-gray-600 p-2">
    Showing
    <span class="font-medium"><?= $startItem ?></span>
    –
    <span class="font-medium"><?= $endItem ?></span>
    of
    <span class="font-medium"><?= $totalCount ?></span>
  </div>

  <!-- Controls -->
  <div class="flex items-center gap-1 p-2">
    <!-- Prev -->
    <?php if ($page > 1): ?>
    <a href="?page=<?= $page - 1 ?>&<?= $queryBase ?>" class="text-sm px-3 py-1 border rounded hover:bg-gray-100">
      < </a>
        <?php else: ?>
        <span class="px-3 py-1 border rounded text-gray-400 text-sm">
          < </span>
            <?php endif; ?>

            <!-- Pages -->
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <a href="?page=<?= $i ?>&<?= $queryBase ?>" class="px-3 py-1 rounded border text-sm
                <?= $i === $page
                    ? 'bg-primary-600 text-white border-primary-600'
                    : 'bg-white hover:bg-gray-100' ?>">
              <?= $i ?>
            </a>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&<?= $queryBase ?>"
              class="text-sm px-3 py-1 border rounded hover:bg-gray-100">
              >
            </a>
            <?php else: ?>
            <span class="px-3 py-1 border rounded text-gray-400 text-sm">
              >
            </span>
            <?php endif; ?>

  </div>
</div>
<?php endif; ?>
<?php
  $state = ListStateHelper::get();
  $sort  = $state['sort'];
  $dir   = $state['dir']; 
?>

<div class="w-full p-6 mx-6">
  <div class="flex justify-between items-end mb-6">
    <h2 class="text-lg font-semibold">Transactions</h2>
    <div class="flex justify-end">
      <div class="bg-green-50 border border-green-200 rounded-xl px-6 py-4 shadow-sm">
        <p class="text-xs text-gray-500 uppercase tracking-wide">
          Total Revenue
        </p>
        <p class="text-2xl font-bold text-green-700 mt-1">
          $<?= number_format($revenue, 3) ?>
        </p>
      </div>
    </div>
  </div>
  <div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full text-sm">
      <thead class="bg-primary-700 text-white">
        <tr>
          <th class="w-1/4 px-4 py-3 text-left"><?= TableHelper::sortLink('Product', 'p.name', $sort, $dir) ?></th>
          <th class="w-1/6 px-4 py-3 text-right"><?= TableHelper::sortLink('Qty', 'quantity', $sort, $dir) ?></th>
          <th class="w-1/6 px-4 py-3 text-right"><?= TableHelper::sortLink('Price', 'price', $sort, $dir) ?></th>
          <th class="w-1/6 px-4 py-3 text-right"><?= TableHelper::sortLink('Total', 'total', $sort, $dir) ?></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php if (empty($transactions)): ?>
        <tr>
          <td colspan="5" class="px-4 py-6 text-center text-gray-500">
            No transactions found
          </td>
        </tr>
        <?php else: ?>
        <?php foreach ($transactions as $t): ?>

        <tr class="hover:bg-gray-50">
          <td class="px-4 py-2"><?= htmlspecialchars($t['product']) ?></td>
          <td class="px-4 py-2 text-right"><?= $t['quantity'] ?></td>
          <td class="px-4 py-2 text-right">$<?= number_format($t['price'], 2) ?></td>
          <td class="px-4 py-2 text-right font-medium">
            $<?= number_format($t['total'], 3) ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <!-- pagination -->
    <?php include __DIR__ . '/../partials/pagination.php'; ?>
  </div>
</div>
</div>
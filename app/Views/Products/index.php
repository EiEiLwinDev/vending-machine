<?php
  $isAdmin = (Session::get('user')['role'] ?? '') === 'admin';
  $errors  = Session::get('error', null);
  $success = Session::get('success', null);
  $state = ListStateHelper::get();
  $sort  = $state['sort'];
  $dir   = $state['dir'];
?>

<div class="w-full p-6 mx-6">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold">
      Available Products
    </h2>
    <?php if ($isAdmin): ?>
    <button onclick="window.location.href='/products/create'"
      class="ml-4 bg-secondary-600 text-white text-sm px-4 py-2 rounded hover:bg-secondary-700 transition">
      Create Product
    </button>
    <?php endif; ?>
  </div>

  <?php if (!empty($errors)): ?>

  <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
    <p class="text-error-500"><?= htmlspecialchars($errors) ?></p>
  </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
  <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
    <?= htmlspecialchars($success); ?>
  </div>
  <?php endif; ?>

  <div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full text-sm">
      <thead class="bg-primary-700 text-white">
        <tr>
          <th class="w-1/4 px-4 py-2 text-left">
            <?= TableHelper::sortLink('Name', 'name', $sort, $dir) ?>
          </th>

          <th class="w-1/4 px-4 py-2 text-right">
            <?= TableHelper::sortLink('Price', 'price', $sort, $dir) ?>
          </th>

          <th class="w-1/4 px-4 py-2 text-right">
            <?= TableHelper::sortLink('Stock', 'quantity', $sort, $dir) ?>
          </th>
          <th class="w-1/4 px-4 py-2 text-right">
          </th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        <?php if (empty($products)): ?>
        <tr class="text-gray-600 italic">
          <td colspan="4" class="px-4 py-2 text-center">
            No products available.
          </td>
        </tr>
        <?php return; ?>
        <?php endif; ?>
        <?php foreach ($products as $product): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-2">
            <?= htmlspecialchars($product['name']) ?>
          </td>

          <td class="px-4 py-2 text-right w-32">
            $<?= $product['price'] ?>
          </td>

          <td class="px-4 py-2 text-right <?= (int) $product['quantity'] > 0 ? 'text-gray-800' : 'text-red-600' ?>">
            <?= (int) $product['quantity'] > 0 ? (int) $product['quantity'] : 'Out of stock' ?>
          </td>
          <?php if ($isAdmin): ?>
          <td class="px-4 py-2 text-right space-x-2">
            <a href="/products/detail/<?= $product['id'] ?>" class="p-1 text-primary-600 font-medium hover:underline ">
              Detail
            </a>
            <a href="/products/edit/<?= $product['id'] ?>" class="p-1 text-primary-600 font-medium hover:underline ">
              Edit
            </a>
            <form action="/products/delete" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline">
              <?= ListStateHelper::hiddenInputs(); ?>
              <input type="hidden" name="id" value="<?= $product['id'] ?>">
              <button type="submit" class="p-1 text-red-600 font-medium hover:underline">
                Delete
              </button>
            </form>
          </td>
          <?php else: ?>
          <td class="px-4 py-2 text-right space-x-2">
            <?php if ((int)$product['quantity'] > 0): ?>
            <a href="/products/<?= $product['id'] ?>/purchase" class="text-primary-600 hover:underline">
              Purchase
            </a>
            <?php else: ?>
            <span class="text-gray-400 italic">Unavailable</span>
            <?php endif; ?>
          </td>
          <?php endif; ?>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <!-- pagination -->
    <?php
      require __DIR__ . '/../partials/pagination.php';
    ?>
  </div>
</div>
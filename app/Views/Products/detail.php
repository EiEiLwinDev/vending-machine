<div class="w-full max-w-3xl bg-white rounded-xl shadow-md p-8">

  <!-- Header -->
  <div class="flex items-start justify-between mb-6">
    <div>
      <h1 class="text-3xl font-semibold text-primary-700">
        <?= htmlspecialchars($product['name']) ?>
      </h1>
      <p class="text-sm text-gray-500 mt-1">
        Product ID: #<?= $product['id'] ?>
      </p>
    </div>

    <!-- Status Badge -->
    <?php if ($product['quantity'] > 0): ?>
    <span class="inline-flex items-center rounded-full bg-success-100 px-3 py-1 text-sm font-medium text-success-700">
      In Stock
    </span>
    <?php else: ?>
    <span class="inline-flex items-center rounded-full bg-error-100 px-3 py-1 text-sm font-medium text-error-700">
      Out of Stock
    </span>
    <?php endif; ?>
  </div>

  <!-- Product Info -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

    <div>
      <p class="text-sm text-gray-500">Price</p>
      <p class="text-xl font-semibold text-gray-900">
        $<?= number_format($product['price'], 3) ?>
      </p>
    </div>

    <div>
      <p class="text-sm text-gray-500">Available Quantity</p>
      <p class="text-xl font-semibold text-gray-900">
        <?= $product['quantity'] ?>
      </p>
    </div>

    <div class="sm:col-span-2">
      <p class="text-sm text-gray-500">Description</p>
      <p class="mt-1 text-gray-800 leading-relaxed">
        <?= nl2br(htmlspecialchars($product['description'] ?? 'No description provided.')) ?>
      </p>
    </div>

  </div>

  <!-- Actions -->
  <div class="flex items-center justify-between border-t pt-6">

    <a href="<?= ListStateHelper::url('/') ?>" class="text-sm font-medium text-gray-600 hover:text-primary-600">
      ← Back to Products
    </a>

    <div class="flex gap-3">
      <!-- Edit -->
      <a href="/products/edit/<?= $product['id'] ?>?back=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="rounded bg-secondary-500 px-4 py-2 text-white text-sm font-medium
                hover:bg-secondary-600 focus:ring-2 focus:ring-secondary-300">
        Edit
      </a>

      <!-- Delete -->
      <form action="/products/delete" method="POST"
        onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline">
        <?= ListStateHelper::hiddenInputs(); ?>
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <button type="submit" class="rounded bg-error-500 px-4 py-2 text-white text-sm font-medium
                       hover:bg-error-600 focus:ring-2 focus:ring-error-300">
          Delete
        </button>
      </form>
    </div>
  </div>

</div>
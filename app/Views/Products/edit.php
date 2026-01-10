<?php
  $errors = Session::get('errors');
  $old    = Session::get('old'); 
  Session::forget('errors'); 
  Session::forget('old');
  
  $backUrl = $_GET['back'] ?? '/';
  $backUrl = ListStateHelper::url($backUrl); // merge pagination/sort
  $backUrl = htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8');
  
?>

<div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-md">
  <h2 class="text-2xl font-semibold text-primary-700 mb-6">
    Edit Product
  </h2>

  <?php if (!empty(Session::get('error'))): ?>
  <div class="mb-4 rounded border border-error-300 bg-error-50 px-4 py-2 text-error-700">
    <?= htmlspecialchars(Session::get('error')); Session::forget('error'); ?>
  </div>
  <?php endif; ?>

  <?php $errors = Session::get('errors') ?? []; ?>
  <form action="/products/update" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <input type="hidden" name="redirect_to" value="<?= $backUrl ?>">
    <!-- Product Name -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Product Name
      </label>
      <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>" class="w-full rounded border px-3 py-2
          <?= isset($errors['name'])
              ? 'border-error-500 focus:border-error-500 focus:ring-error-200'
              : 'border-gray-300 focus:border-primary-500 focus:ring-primary-200' ?>
          focus:ring focus:outline-none">
      <?php if (isset($errors['name'])): ?>
      <p class="mt-1 text-sm text-error-600">
        <?= htmlspecialchars($errors['name']) ?>
      </p>
      <?php endif; ?>
    </div>

    <!-- Price -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Price ($)
      </label>
      <input type="number" name="price" step="0.001"
        value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" class="w-full rounded border px-3 py-2
          <?= isset($errors['price'])
              ? 'border-error-500 focus:border-error-500 focus:ring-error-200'
              : 'border-gray-300 focus:border-primary-500 focus:ring-primary-200' ?>
          focus:ring focus:outline-none">
      <?php if (isset($errors['price'])): ?>
      <p class="mt-1 text-sm text-error-600">
        <?= htmlspecialchars($errors['price']) ?>
      </p>
      <?php endif; ?>
    </div>

    <!-- Quantity -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Stock Quantity
      </label>
      <input type="number" name="quantity" min="0"
        value="<?= htmlspecialchars($_POST['quantity'] ?? $product['quantity']) ?>" class="w-full rounded border px-3 py-2
          <?= isset($errors['quantity'])
              ? 'border-error-500 focus:border-error-500 focus:ring-error-200'
              : 'border-gray-300 focus:border-primary-500 focus:ring-primary-200' ?>
          focus:ring focus:outline-none">
      <?php if (isset($errors['quantity'])): ?>
      <p class="mt-1 text-sm text-error-600">
        <?= htmlspecialchars($errors['quantity']) ?>
      </p>
      <?php endif; ?>
    </div>

    <!-- Description -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Description
      </label>
      <textarea name="description"
        class="w-full rounded border px-3 py-2
          <?= isset($errors['description'])
              ? 'border-error-500 focus:border-error-500 focus:ring-error-200'
              : 'border-gray-300 focus:border-primary-500 focus:ring-primary-200' ?>
          focus:ring focus:outline-none"><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
      <?php if (isset($errors['description'])): ?>
      <p class="mt-1 text-sm text-error-600">
        <?= htmlspecialchars($errors['description']) ?>
      </p>
      <?php endif; ?>
    </div>

    <!-- Submit -->
    <div class="flex justify-end space-x-2">
      <a href="<?= $backUrl ?>" class="px-5 py-2 rounded bg-secondary-500 text-white hover:bg-secondary-600">
        Cancel
      </a>

      <button type="submit" class="rounded bg-primary-500 px-5 py-2 text-white font-medium
               hover:bg-primary-600 focus:ring-2 focus:ring-primary-300">
        Update Product
      </button>
    </div>

  </form>

  <?php Session::forget('errors'); ?>
</div>
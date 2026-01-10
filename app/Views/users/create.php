<?php
  $errors = Session::get('errors');
  $old    = Session::get('old');  
  Session::forget('errors');
  Session::forget('old');
?>

<div class="bg-white p-8 rounded shadow w-full max-w-md">
  <h2 class="text-2xl font-bold mb-6 text-center">
    Create User
  </h2>

  <?php if (!empty($errors['general'])): ?>
  <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
    <?= $errors['general'] ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="/users/store" class="space-y-4">

    <!-- Name -->
    <div>
      <label class="block text-gray-700 mb-1">Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" class="w-full border rounded px-3 py-2
                       <?= isset($errors['name']) ? 'border-red-500' : 'border-gray-300' ?>">
      <?php if (isset($errors['name'])): ?>
      <p class="text-red-600 text-sm mt-1"><?= $errors['name'] ?></p>
      <?php endif; ?>
    </div>

    <!-- Email -->
    <div>
      <label class="block text-gray-700 mb-1">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="w-full border rounded px-3 py-2
                       <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?>">
      <?php if (isset($errors['email'])): ?>
      <p class="text-red-600 text-sm mt-1"><?= $errors['email'] ?></p>
      <?php endif; ?>
    </div>

    <!-- Password -->
    <div>
      <label class="block text-gray-700 mb-1">Password</label>
      <input type="password" name="password" class="w-full border rounded px-3 py-2
                       <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?>">
      <?php if (isset($errors['password'])): ?>
      <p class="text-red-600 text-sm mt-1"><?= $errors['password'] ?></p>
      <?php endif; ?>
    </div>

    <!-- Role -->
    <div>
      <label class="block text-gray-700 mb-1">Role</label>
      <select name="role" class="w-full border rounded px-3 py-2
                       <?= isset($errors['role']) ? 'border-red-500' : 'border-gray-300' ?>">
        <option value="user" <?= ($old['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
      <?php if (isset($errors['role'])): ?>
      <p class="text-red-600 text-sm mt-1"><?= $errors['role'] ?></p>
      <?php endif; ?>
    </div>

    <button type="submit" class="w-full bg-secondary-600 text-white py-2 rounded hover:bg-secondary-700 transition">
      Create User
    </button>
  </form>
</div>
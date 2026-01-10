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
      Users
    </h2>

    <a href="/users/create"
      class="bg-secondary-600 text-white text-sm px-4 py-2 rounded hover:bg-secondary-700 transition">
      Create User
    </a>
  </div>

  <?php if (!empty(Session::get('success'))): ?>
  <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
    <?= Session::get('success'); Session::forget('success'); ?>
  </div>
  <?php endif; ?>

  <div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full text-sm">
      <thead class="bg-primary-700 text-white">
        <tr>
          <th class="w-1/4 px-4 py-2 text-left"><?= TableHelper::sortLink('Name', 'name', $sort, $dir) ?></th>
          <th class="w-1/3 px-4 py-2 text-left"><?= TableHelper::sortLink('Email', 'email', $sort, $dir) ?></th>
          <th class="w-1/4 px-4 py-2 text-left"><?= TableHelper::sortLink('Role', 'role', $sort, $dir) ?></th>
          <th class="w-1/4"></th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        <?php foreach ($users as $user): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-2">
            <?= htmlspecialchars($user['name']) ?>
          </td>

          <td class="px-4 py-2">
            <?= htmlspecialchars($user['email']) ?>
          </td>

          <td class="px-4 py-2 capitalize">
            <span class="px-2 py-1 rounded text-sm <?= $user['role'] === 'admin'? 'bg-secondary-100 text-secondary-700' : 'bg-primary-100 text-primary-700'
                                ?>">
              <?= $user['role'] ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($users)): ?>
        <tr>
          <td colspan="4" class="text-center py-6 text-gray-500">
            No users found
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <!-- pagination -->
    <?php
      require __DIR__ . '/../partials/pagination.php';
    ?>
  </div>
</div>
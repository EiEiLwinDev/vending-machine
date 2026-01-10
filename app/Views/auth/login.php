<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
  <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

  <?php if (!empty(Session::get('error'))): ?>
  <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
    <?= Session::get('error'); Session::set('error', null); ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="/login" class="space-y-4">
    <div>
      <label class="block text-gray-700 mb-1">Email</label>
      <input type="email" name="email" required
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
    </div>

    <div>
      <label class="block text-gray-700 mb-1">Password</label>
      <input type="password" name="password" required
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
    </div>

    <button type="submit" class="w-full bg-secondary-600 text-white py-2 rounded hover:bg-secondary-700">
      Login
    </button>
  </form>
</div>
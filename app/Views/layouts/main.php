<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Vending Machine' ?></title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: {
            50: '#f1f4fa',
            100: '#e3e9f5',
            200: '#c5d2eb',
            300: '#a7bbe1',
            400: '#6f8ac5',
            500: '#4863A0',
            600: '#3f568c',
            700: '#344872',
            800: '#2a3a59',
            900: '#202c41',
          },
          secondary: {
            50: '#faf7f1',
            100: '#f3ecdf',
            200: '#e6d8bf',
            300: '#d9c59f',
            400: '#c2a76f',
            500: '#A08548',
            600: '#8d753f',
            700: '#756233',
            800: '#5d4e28',
            900: '#453a1d',
          },
          success: {
            50: '#e6f4e6',
            100: '#cce9cc',
            200: '#99d399',
            300: '#66bd66',
            400: '#33a733',
            500: '#008000',
            600: '#006d00',
            700: '#005a00',
            800: '#004700',
            900: '#003300',
          },
          error: {
            50: '#fde8e8',
            100: '#fbcfcf',
            200: '#f79f9f',
            300: '#f36f6f',
            400: '#ef3f3f',
            500: '#FF0000',
            600: '#db0000',
            700: '#b70000',
            800: '#930000',
            900: '#6f0000',
          }
        }
      }
    }
  }
  </script>

  <!-- custom css -->
  <link rel="stylesheet" href="/css/style.css">
</head>

<body class="bg-gray-100 min-h-screen">

  <nav class="bg-primary-700 p-4 text-white flex justify-between">
    <div>
      <a href="/" class="mr-4 hover:underline">Products</a>
      <?php if (isset(Session::get('user')['id']) && Session::get('user')['role'] === 'admin'): ?>
      <a href="/transactions" class="hover:underline mr-4">Transactions</a>
      <a href="/users" class="hover:underline mr-4">Users</a>
      <?php endif; ?>
    </div>

    <div>
      <?php if (isset(Session::get('user')['id'])): ?>
      <span class="mr-4"><?= Session::get('user')['name'] ?></span>
      <a href="/logout" class="hover:underline">Logout</a>
      <?php else: ?>
      <a href="/login" class="hover:underline">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <main class="flex items-top justify-center w-full mt-10">
    <?= $content ?>
  </main>

</body>

</html>
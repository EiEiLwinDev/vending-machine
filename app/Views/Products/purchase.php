<?php
  $errors = Session::get('errors') ?? [];
  $old    = Session::get('old') ?? [];

  Session::forget('errors');
  Session::forget('old');

  $price     = (float) $product['price'];
  $available = (int) $product['quantity'];
  $initialQty = isset($old['quantity'])
      ? (int) $old['quantity']
      : ($available > 0 ? 1 : 0);

  $backUrl = $_GET['back'] ?? '/';
  $backUrl = ListStateHelper::url($backUrl); // merge pagination/sort
  $backUrl = htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8');
?>

<div class="w-full max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8">
  <!-- Header -->
  <div class="text-center mb-8">
    <h1 class="text-2xl font-bold">Purchase Product</h1>
    <p class="text-sm text-gray-500 mt-1">
      Select quantity and confirm your purchase
    </p>
  </div>

  <!-- Product Card -->
  <div class="mb-6 p-5 border rounded-xl bg-gray-50">
    <div class="flex justify-between items-start gap-4">
      <div class="flex-1">
        <h2 class="text-lg font-semibold">
          <?= htmlspecialchars($product['name']) ?>
        </h2>
        <p class="text-sm text-gray-600 mt-1">
          <?= htmlspecialchars($product['description']) ?>
        </p>
      </div>

      <!-- Price -->
      <div class="text-right">
        <span class="text-xs text-gray-500">Price</span>
        <div class="text-xl font-bold text-primary-600">
          $<?= number_format($price, 3) ?>
        </div>
      </div>
    </div>

    <!-- Stock Status -->
    <div class="mt-4">
      <?php if ($available > 5): ?>
      <span class="inline-flex items-center gap-1 text-green-600 text-sm font-medium">
        ● In stock (<?= $available ?>)
      </span>
      <?php elseif ($available > 0): ?>
      <span class="inline-flex items-center gap-1 text-yellow-600 text-sm font-medium">
        ● Only <?= $available ?> left
      </span>
      <?php else: ?>
      <span class="inline-flex items-center gap-1 text-red-600 text-sm font-medium">
        ● Out of stock
      </span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Error -->
  <?php if (isset($errors['general'])): ?>
  <div class="mb-5 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
    <?= htmlspecialchars($errors['general']) ?>
  </div>
  <?php endif; ?>

  <!-- Purchase Form -->
  <form method="POST" action="/products/purchase" class="space-y-6">

    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <input type="hidden" name="redirect_to" value="<?= $backUrl ?>">

    <div class="flex gap-2">
      <!-- Quantity -->
      <div>
        <label class="block text-sm font-medium mb-2">
          Quantity
        </label>

        <div class="inline-flex items-center border rounded-lg overflow-hidden">
          <button type="button" id="decrease" class="px-4 py-2 bg-gray-100 hover:bg-gray-200"
            <?= $available <= 0 ? 'disabled' : '' ?>>−</button>

          <input type="number" id="quantity" name="quantity" min="1" max="<?= $available ?>" value="<?= $initialQty ?>"
            class="w-20 text-center border-x outline-none" <?= $available <= 0 ? 'disabled' : '' ?>>

          <button type="button" id="increase" class="px-4 py-2 bg-gray-100 hover:bg-gray-200"
            <?= $available <= 0 ? 'disabled' : '' ?>>+</button>
        </div>

        <?php if (isset($errors['quantity'])): ?>
        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['quantity']) ?></p>
        <?php endif; ?>
      </div>

      <!-- Payment -->
      <div>
        <label class="block text-sm font-medium mb-2">
          Paid Amount
        </label>

        <div class="relative border rounded-lg overflow-hidden">
          <span class="absolute inset-y-0 left-0 flex items-center px-4 py-2 bg-gray-100 border-r text-gray-500">
            $
          </span>

          <input type="number" step="0.001" id="paidAmount" name="amount" min="0.001"
            value="<?= htmlspecialchars($old['amount'] ?? '') ?>" placeholder="0.000"
            class="w-full text-center border-x outline-none rounded pl-8 pr-3 py-2">
        </div>

        <?php if (isset($errors['amount'])): ?>
        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['amount']) ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 gap-4 bg-gray-100 p-4 rounded-xl">
      <div>
        <p class="text-sm text-gray-600">Total</p>
        <p class="text-xl font-bold" id="totalPrice">
          $<?= number_format($price * $initialQty, 3) ?>
        </p>
      </div>

      <div>
        <p class="text-sm text-gray-600">Change</p>
        <p class="text-xl font-bold text-green-600" id="changeAmount">
          $0.000
        </p>
      </div>

    </div>

    <!-- Payment Warning -->
    <p id="paymentWarning" class="hidden text-red-600 text-sm">
      Insufficient payment
    </p>

    <!-- Actions -->
    <div class="flex justify-between items-center">
      <a href="<?= ListStateHelper::url('/') ?>" class="text-sm text-gray-500 hover:text-primary-600">
        ← Back to Products
      </a>

      <button type="submit" id="confirmBtn" class="bg-primary-600 text-white px-6 py-2 rounded-xl font-medium
             hover:bg-primary-700 transition disabled:opacity-50" disabled>
        Confirm Purchase
      </button>
    </div>

  </form>

</div>

<script>
const price = <?= json_encode($price) ?>;
const maxQty = <?= (int) $available ?>;

const qtyInput = document.getElementById('quantity');
const paidInput = document.getElementById('paidAmount');
const totalEl = document.getElementById('totalPrice');
const changeEl = document.getElementById('changeAmount');
const confirmBtn = document.getElementById('confirmBtn');
const warningEl = document.getElementById('paymentWarning');

const incBtn = document.getElementById('increase');
const decBtn = document.getElementById('decrease');

function update() {
  const qty = Number(qtyInput.value || 0);
  const paid = Number(paidInput.value || 0);
  const total = qty * price;
  const change = paid - total;

  qtyInput.value = qty;

  totalEl.textContent = `$${total.toFixed(3)}`;
  changeEl.textContent = change >= 0 ? `$${change.toFixed(3)}` : `$0.000`;

  if (paid >= total && total > 0) {
    confirmBtn.disabled = false;
    warningEl.classList.add('hidden');
  } else {
    confirmBtn.disabled = true;
    warningEl.classList.remove('hidden');
  }
}

incBtn.onclick = () => {
  if (qtyInput.value < maxQty) {
    qtyInput.value++;
    update();
  }
};

decBtn.onclick = () => {
  if (qtyInput.value > 1) {
    qtyInput.value--;
    update();
  }
};

qtyInput.oninput = update;
paidInput.oninput = update;

update();
</script>
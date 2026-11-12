<?php
defined('APP_ROOT') || exit;
/** @var string $username @var string $error @var bool $saved */
?>
<main class="mx-auto max-w-4xl px-5 pb-20 pt-8 sm:px-8 lg:px-12 lg:pt-12">
  <header class="border-b border-midnight/10 pb-8">
    <p class="eyebrow text-bronze">Account</p>
    <h1 class="mt-3 text-4xl text-midnight md:text-[2.75rem]">Password</h1>
    <p class="mt-2 text-charcoal/75">Signed in as <strong class="font-semibold text-midnight"><?= e($username) ?></strong>.</p>
  </header>

  <form method="post" action="/admin/account" class="admin-panel mt-8 max-w-lg space-y-5 p-6 sm:p-8">
    <?= csrf_field() ?>
    <label class="block">
      <span class="admin-label">Current password</span>
      <input name="current" type="password" required autocomplete="current-password" class="admin-input">
    </label>
    <label class="block">
      <span class="admin-label">New password <span class="font-normal normal-case tracking-normal text-charcoal/50">— at least <?= PASSWORD_MIN_LENGTH ?> characters</span></span>
      <input name="password" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" autocomplete="new-password" class="admin-input">
    </label>
    <label class="block">
      <span class="admin-label">Confirm new password</span>
      <input name="confirm" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" autocomplete="new-password" class="admin-input">
    </label>
    <?php if ($error): ?><p role="alert" class="admin-alert"><?= e($error) ?></p><?php endif ?>
    <?php if ($saved): ?><p role="status" class="flex items-center gap-2 rounded-md bg-sand px-3.5 py-2.5 text-sm text-midnight"><?= icon('check', 'size-4 text-bronze') ?> Password changed.</p><?php endif ?>
    <button class="admin-btn-primary">Update password</button>
  </form>
</main>

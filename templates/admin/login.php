<?php
defined('APP_ROOT') || exit;
/** @var string $error @var string $username */
?>
<form method="post" action="/admin/login" class="space-y-5">
  <div>
    <h1 class="text-3xl text-midnight">Welcome back</h1>
    <p class="mt-1 text-sm text-charcoal/70">Sign in to edit the website.</p>
  </div>
  <?= csrf_field() ?>
  <label class="block">
    <span class="admin-label">Username</span>
    <input name="username" type="text" required autofocus autocomplete="username" autocapitalize="none" spellcheck="false" value="<?= e($username) ?>" class="admin-input">
  </label>
  <label class="block">
    <span class="admin-label">Password</span>
    <input name="password" type="password" required autocomplete="current-password" class="admin-input">
  </label>
  <?php if ($error): ?><p role="alert" class="admin-alert"><?= e($error) ?></p><?php endif ?>
  <button class="admin-btn-primary w-full">Sign in</button>
</form>

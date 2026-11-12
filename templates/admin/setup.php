<?php
defined('APP_ROOT') || exit;
/** @var string $error @var string $username */
?>
<form method="post" action="/admin/setup" class="space-y-5">
  <div>
    <h1 class="text-3xl text-midnight">Welcome</h1>
    <p class="mt-1 text-sm leading-relaxed text-charcoal/70">
      Create the administrator account. This also prepares the database. This page is only available until the first account exists.
    </p>
  </div>
  <?= csrf_field() ?>
  <label class="block">
    <span class="admin-label">Username</span>
    <input name="username" type="text" required minlength="3" maxlength="<?= USERNAME_MAX_LENGTH ?>" autofocus autocomplete="username" autocapitalize="none" spellcheck="false" value="<?= e($username) ?>" class="admin-input">
  </label>
  <label class="block">
    <span class="admin-label">Password <span class="font-normal normal-case tracking-normal text-charcoal/50">— at least <?= PASSWORD_MIN_LENGTH ?> characters</span></span>
    <input name="password" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" autocomplete="new-password" class="admin-input">
  </label>
  <label class="block">
    <span class="admin-label">Confirm password</span>
    <input name="confirm" type="password" required minlength="<?= PASSWORD_MIN_LENGTH ?>" autocomplete="new-password" class="admin-input">
  </label>
  <?php if ($error): ?><p role="alert" class="admin-alert"><?= e($error) ?></p><?php endif ?>
  <button class="admin-btn-primary w-full">Create account</button>
</form>

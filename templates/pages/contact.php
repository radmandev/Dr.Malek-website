<?php
defined('APP_ROOT') || exit;
/** @var array $c @var array|null $form  Outcome of a no-JavaScript form submission, if any. */

$contact = $c['contact'];
$profile = $c['profile'];
$form ??= null;
$sent = !empty($form['ok']);
$error = $form['error'] ?? '';
$old = fn (string $k) => e(is_string($form['old'][$k] ?? null) ? $form['old'][$k] : '');

$email = $contact['email'] ?? '';
$linkedin = safe_url($contact['linkedin'] ?? '');
$rows = [
    ['Institution', e($contact['institution'])],
    ['Department', e($contact['department'])],
    ['Address', or_placeholder($contact['address'], 'verified address to be added')],
    ['Email', filter_var($email, FILTER_VALIDATE_EMAIL)
        ? '<a href="mailto:' . e($email) . '" class="link-underline">' . e($email) . '</a>'
        : placeholder('verified professional email to be added')],
    ['Secretary', or_placeholder($contact['secretaryPhone'], 'verified number to be added')],
    ['LinkedIn', $linkedin
        ? '<a href="' . e($linkedin) . '" target="_blank" rel="noopener noreferrer" class="link-underline">View professional profile ↗</a>'
        : placeholder('LinkedIn profile URL to be added')],
    ['GMC', or_placeholder($contact['gmcNumber'], 'GMC reference to be added')],
];

$field = 'peer w-full border-0 border-b border-midnight/20 bg-transparent px-0 pb-3 pt-6 text-lg text-midnight placeholder-transparent transition-colors focus:border-champagne focus:outline-none focus:ring-0';
$label = 'pointer-events-none absolute left-0 top-0 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-charcoal/55';
?>
<?= page_hero([
    'index' => '08',
    'eyebrow' => 'Contact',
    'title_html' => 'Professional Enquiries <span class="italic text-bronze">&amp; Contact</span>',
    'lead' => 'For professional, academic, media and collaboration enquiries.',
]) ?>

<section class="bg-ivory py-24 md:py-32">
  <div class="container-x grid gap-20 lg:grid-cols-12">
    <div class="lg:col-span-7">
      <div<?= rv() ?>><?= eyebrow('Send an enquiry', 'I') ?></div>
      <div<?= rv(0.1) ?>>
        <div data-contact-sent role="status" tabindex="-1" class="mt-10 border-t border-champagne pt-10 outline-none"<?= $sent ? '' : ' hidden' ?>>
          <?= icon('check', 'size-8 text-bronze', 1.25) ?>
          <p class="mt-6 font-serif text-4xl text-midnight">Thank you.</p>
          <p class="mt-3 max-w-md text-charcoal/70">Your enquiry has been received and will be reviewed in due course.</p>
        </div>

        <form data-contact-form method="post" action="/contact#main" class="relative mt-10 grid gap-10 sm:grid-cols-2"<?= $sent ? ' hidden' : '' ?>>
          <!-- Honeypot — hidden from people and assistive tech -->
          <div aria-hidden="true" class="absolute -left-[9999px]">
            <label>Company <input name="company" tabindex="-1" autocomplete="off"></label>
          </div>
          <div class="relative">
            <input id="name" name="name" required maxlength="120" autocomplete="name" placeholder="Name" value="<?= $old('name') ?>" class="<?= $field ?>">
            <label for="name" class="<?= $label ?>">Name</label>
          </div>
          <div class="relative">
            <input id="email" name="email" type="email" required maxlength="200" autocomplete="email" placeholder="Email" value="<?= $old('email') ?>" class="<?= $field ?>">
            <label for="email" class="<?= $label ?>">Email</label>
          </div>
          <div class="relative sm:col-span-2">
            <input id="subject" name="subject" required maxlength="200" placeholder="Subject" value="<?= $old('subject') ?>" class="<?= $field ?>">
            <label for="subject" class="<?= $label ?>">Subject</label>
          </div>
          <div class="relative sm:col-span-2">
            <textarea id="message" name="message" required maxlength="5000" rows="6" placeholder="Message" class="<?= $field ?> resize-y"><?= $old('message') ?></textarea>
            <label for="message" class="<?= $label ?>">Message</label>
          </div>
          <div class="flex flex-col gap-6 sm:col-span-2 sm:flex-row sm:items-center sm:justify-between">
            <p class="max-w-sm text-xs leading-relaxed text-charcoal/55">Please do not include confidential medical information. This form is for professional enquiries only.</p>
            <button type="submit" class="group inline-flex items-center gap-3 bg-midnight px-8 py-4 text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-ivory transition-colors hover:bg-champagne hover:text-midnight disabled:opacity-60">
              <span data-label>Send enquiry</span>
              <?= icon('arrow-up-right', 'size-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5') ?>
            </button>
          </div>
          <p data-error role="alert" class="border-l border-red-700 pl-4 text-sm text-red-800 sm:col-span-2"<?= $error ? '' : ' hidden' ?>><?= e($error) ?></p>
        </form>
      </div>
    </div>

    <aside class="lg:col-span-4 lg:col-start-9">
      <div<?= rv() ?>>
        <?php if ($avatar = safe_url($profile['avatar'] ?? '')): ?>
        <div class="mb-12 flex items-center gap-5">
          <img src="<?= e($avatar) ?>" alt="Dr. Malek Gaashan" width="80" height="80" loading="lazy" class="size-20 rounded-full object-cover ring-1 ring-champagne ring-offset-4 ring-offset-ivory">
          <div>
            <p class="font-serif text-2xl text-midnight"><?= e($profile['name']) ?></p>
            <p class="text-xs uppercase tracking-[0.2em] text-bronze"><?= e($profile['postnominals']) ?></p>
          </div>
        </div>
        <?php endif ?>
        <?= eyebrow('Practice details', 'II') ?>
        <dl class="mt-10 border-t border-midnight/15">
          <?php foreach ($rows as [$k, $v]): ?>
          <div class="grid grid-cols-[7rem_1fr] gap-4 border-b border-midnight/10 py-5 text-sm">
            <dt class="eyebrow pt-0.5 text-charcoal/50"><?= e($k) ?></dt>
            <dd class="text-midnight"><?= $v ?></dd>
          </div>
          <?php endforeach ?>
        </dl>
      </div>
      <div<?= rv(0.1) ?>>
        <div class="mt-12 border-l-2 border-champagne bg-white p-8 text-sm leading-relaxed text-charcoal/75">
          <p class="eyebrow mb-3 text-bronze">Please note</p>
          <p>This form is not monitored for clinical or urgent matters and cannot be used to book appointments. Please do not include confidential medical information. For medical advice, contact your GP or treating team; in an emergency call 999 (UK) or your local emergency number.</p>
        </div>
      </div>
    </aside>
  </div>
</section>

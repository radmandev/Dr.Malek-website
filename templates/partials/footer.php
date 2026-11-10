<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$profile = $c['profile'];
$contact = $c['contact'];
$links = [
    ['About', '/about'],
    ['Surgical Expertise', '/expertise'],
    ['Experience & Achievements', '/experience'],
    ['Research & Publications', '/research'],
    ['Conferences & Education', '/conferences'],
    ['Patient Information', '/patient-information'],
    ['Professional Enquiries', '/contact'],
];
$linkedin = safe_url($contact['linkedin'] ?? '');
?>
<footer class="grain relative overflow-hidden bg-midnight text-ivory">
  <div class="container-x relative pb-10 pt-24">
    <div class="grid gap-16 lg:grid-cols-12">
      <div class="lg:col-span-6">
        <p class="font-serif text-5xl leading-[1.05] md:text-6xl">Malek <span class="italic text-champagne-light">Gaashan</span></p>
        <p class="mt-4 text-[0.68rem] font-semibold uppercase tracking-[0.28em] text-ivory/50"><?= e($profile['postnominals']) ?></p>
        <p class="mt-8 max-w-md font-serif text-xl italic leading-relaxed text-ivory/65">“<?= e($profile['philosophy']) ?>”</p>
      </div>
      <nav aria-label="Footer" class="lg:col-span-3">
        <p class="eyebrow text-champagne">Explore</p>
        <ul class="mt-6 space-y-3 text-sm text-ivory/70">
          <?php foreach ($links as [$label, $href]): ?>
          <li><a href="<?= e($href) ?>" class="link-underline hover:text-ivory"><?= e($label) ?></a></li>
          <?php endforeach ?>
        </ul>
      </nav>
      <div class="text-sm text-ivory/70 lg:col-span-3">
        <p class="eyebrow text-champagne">Practice</p>
        <p class="mt-6 leading-relaxed"><?= e($contact['department']) ?><br><?= e($contact['institution']) ?></p>
        <?php if ($linkedin): ?>
        <a href="<?= e($linkedin) ?>" target="_blank" rel="noopener noreferrer" class="link-underline mt-6 inline-block hover:text-ivory">LinkedIn ↗</a>
        <?php endif ?>
      </div>
    </div>
    <div class="mt-20 flex flex-col gap-4 border-t border-ivory/10 pt-8 text-xs leading-relaxed text-ivory/40 md:flex-row md:justify-between">
      <p>© <?= date('Y') ?> <?= e($profile['name']) ?>. All rights reserved.</p>
      <p class="max-w-2xl md:text-right">General information only — not a substitute for individual medical advice. This website does not provide emergency services; in an emergency call 999 (UK) or your local emergency number.</p>
    </div>
  </div>
</footer>

<?php
defined('APP_ROOT') || exit;
/** @var array $c */
?>
<?= page_hero([
    'index' => '05',
    'eyebrow' => 'Research & Publications',
    'title_html' => 'An academic <span class="italic text-bronze">portfolio.</span>',
    'lead' => 'Contributions to the urological literature, from rare oncological presentations to refinements in laparoscopic technique.',
]) ?>

<section class="bg-ivory py-16 md:py-24 lg:py-32">
  <div class="container-x">
    <div<?= rv() ?>><?= eyebrow('Publications', 'I') ?></div>
    <ol class="mt-12">
      <?php foreach ($c['publications'] as $i => $p):
          $href = safe_url($p['url'] !== '' ? $p['url'] : ($p['doi'] !== '' ? 'https://doi.org/' . $p['doi'] : ''));
      ?>
      <li<?= rv($i * 0.08) ?> class="mb-6 grid gap-6 bg-white p-8 md:grid-cols-12 md:gap-10 md:p-12">
        <p class="font-serif text-5xl text-bronze md:col-span-2"><?= e($p['year']) ?></p>
        <div class="md:col-span-8">
          <h2 class="text-3xl leading-snug text-midnight md:text-[2.1rem]"><?= e($p['title']) ?></h2>
          <dl class="mt-6 grid gap-x-10 gap-y-3 text-sm sm:grid-cols-2">
            <div>
              <dt class="eyebrow text-charcoal/50">Journal</dt>
              <dd class="mt-1 text-charcoal/80"><?= or_placeholder($p['journal'], 'journal to be verified') ?></dd>
            </div>
            <div>
              <dt class="eyebrow text-charcoal/50">DOI</dt>
              <dd class="mt-1 break-all text-charcoal/80"><?= or_placeholder($p['doi'], 'DOI to be added') ?></dd>
            </div>
          </dl>
        </div>
        <div class="md:col-span-2 md:text-right">
          <?php if ($href): ?>
          <a href="<?= e($href) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-[0.7rem] font-semibold uppercase tracking-[0.16em] md:tracking-[0.2em] text-midnight">
            <span class="link-underline">Read</span>
            <?= icon('arrow-up-right', 'size-4 text-bronze') ?>
            <span class="sr-only"><?= e($p['title']) ?> (opens in a new tab)</span>
          </a>
          <?php endif ?>
        </div>
      </li>
      <?php endforeach ?>
    </ol>
  </div>
</section>

<section class="bg-sand py-20 md:py-28 lg:py-36">
  <div class="container-x grid gap-12 md:grid-cols-12">
    <div<?= rv() ?> class="md:col-span-5">
      <?= eyebrow('Forthcoming', 'II') ?>
      <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Future publications &amp; <span class="italic">academic contributions</span></h2>
    </div>
    <div class="md:col-span-6 md:col-start-7">
      <?php if (!empty($c['academicContributions'])): ?>
      <ul class="border-t border-midnight/15">
        <?php foreach ($c['academicContributions'] as $item): ?>
        <li class="border-b border-midnight/10 py-5 font-serif text-xl text-midnight"><?= e($item) ?></li>
        <?php endforeach ?>
      </ul>
      <?php else: ?>
      <p class="leading-relaxed text-charcoal/80">New publications, book chapters, peer-review activity and academic contributions will be listed here as they are confirmed.</p>
      <?php endif ?>
    </div>
  </div>
</section>

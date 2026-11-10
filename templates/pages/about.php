<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$profile = $c['profile'];
?>
<?= page_hero([
    'index' => '02',
    'eyebrow' => 'About',
    'title_html' => 'Meet Dr. Malek <span class="italic text-bronze">Gaashan</span>',
    'lead' => $profile['position'] . ' — ' . $profile['postnominals'],
]) ?>

<!-- BIOGRAPHY -->
<section class="py-28 md:py-36">
  <div class="container-x grid gap-16 lg:grid-cols-12">
    <div<?= rv() ?> class="lg:col-span-5">
      <div class="lg:sticky lg:top-32">
        <?= photo([
            'src' => $profile['portrait'],
            'alt' => 'Portrait of ' . $profile['name'],
            'label' => 'Approved portrait of ' . $profile['name'],
            'position' => 'center 20%',
            'class' => 'aspect-[4/5] rounded-t-full',
        ]) ?>
        <p class="mt-6 font-serif text-xl italic leading-relaxed text-midnight/80">“<?= e($profile['philosophy']) ?>”</p>
      </div>
    </div>
    <div class="lg:col-span-6 lg:col-start-7">
      <div<?= rv() ?>><?= eyebrow('Biography', 'I') ?></div>
      <div<?= rv(0.1) ?>>
        <p class="mt-8 font-serif text-3xl leading-snug text-midnight md:text-[2.1rem]"><?= e($profile['bioBody'][0] ?? '') ?></p>
        <div class="prose-editorial mt-8 text-lg leading-relaxed text-charcoal/75"><?= paragraphs(array_slice($profile['bioBody'], 1)) ?></div>
      </div>
      <div<?= rv(0.15) ?>>
        <div class="mt-16 border-t border-midnight/15 pt-10">
          <p class="eyebrow text-bronze">Clinical interests</p>
          <ul class="mt-6">
            <?php foreach ($profile['interests'] as $i => $interest): ?>
            <li class="flex items-baseline gap-6 border-b border-midnight/10 py-4">
              <span class="font-serif text-sm italic text-bronze"><?= pad2($i + 1) ?></span>
              <span class="font-serif text-2xl text-midnight"><?= e($interest) ?></span>
            </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TIMELINE -->
<section class="bg-sand py-28 md:py-36">
  <div class="container-x">
    <div class="mb-20 grid gap-8 md:grid-cols-4 md:gap-10">
      <div class="md:col-span-3 md:col-start-2 md:pl-12">
        <div<?= rv() ?>><?= eyebrow('Professional Journey', 'II') ?></div>
        <div<?= rv(0.1) ?>>
          <h2 class="mt-6 max-w-2xl text-4xl leading-tight text-midnight md:text-6xl">
            Three health systems. <span class="italic">One surgical focus.</span>
          </h2>
        </div>
      </div>
    </div>
    <?= render('partials/timeline', ['items' => $c['timeline']]) ?>
  </div>
</section>

<!-- EDUCATION -->
<section class="py-28 md:py-36">
  <div class="container-x grid gap-16 lg:grid-cols-12">
    <div class="lg:col-span-4">
      <div<?= rv() ?>>
        <?= eyebrow('Qualifications', 'III') ?>
        <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Education &amp; Professional <span class="italic">Qualifications</span></h2>
      </div>
    </div>
    <div class="lg:col-span-7 lg:col-start-6">
      <ul class="border-t border-midnight/15">
        <?php foreach ($c['education'] as $i => $ed): ?>
        <li<?= rv($i * 0.05) ?> class="grid gap-2 border-b border-midnight/10 py-7 sm:grid-cols-[1fr_auto] sm:items-baseline sm:gap-8">
          <div>
            <p class="font-serif text-3xl text-midnight"><?= e($ed['title']) ?></p>
            <p class="mt-1 text-sm text-charcoal/65"><?= e($ed['institution']) ?></p>
          </div>
          <p class="text-sm font-semibold uppercase tracking-[0.16em] text-bronze"><?= or_placeholder($ed['year'], 'year to confirm') ?></p>
        </li>
        <?php endforeach ?>
      </ul>

      <div<?= rv() ?>>
        <div class="mt-16">
          <p class="eyebrow text-bronze">Professional registration</p>
          <ul class="mt-6 grid gap-4 sm:grid-cols-2">
            <?php foreach ($c['registrations'] as $reg): ?>
            <li class="bg-white p-6">
              <p class="font-serif text-xl text-midnight"><?= e($reg['body']) ?></p>
              <p class="mt-2 text-sm text-charcoal/65"><?= or_placeholder($reg['detail'], 'registration details to be added') ?></p>
            </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- AWARDS -->
<section class="bg-sand py-28 md:py-36">
  <div class="container-x relative">
    <div<?= rv() ?>><?= eyebrow('Awards & Distinctions', 'IV') ?></div>
    <div class="mt-14 grid gap-6 md:grid-cols-3">
      <?php foreach ($c['awards'] as $i => $award): ?>
      <div<?= rv($i * 0.1) ?> class="bg-white transition-shadow duration-700 hover:shadow-[0_30px_60px_-30px_rgba(16,29,44,0.3)]">
        <div class="flex h-full flex-col justify-between p-8 md:p-10">
          <p class="font-serif text-6xl text-bronze"><?= $award['year'] !== '' ? e($award['year']) : '—' ?></p>
          <div class="mt-16">
            <p class="font-serif text-2xl leading-snug text-midnight"><?= e($award['title']) ?></p>
            <p class="mt-3 text-sm text-charcoal/60"><?= e($award['organisation']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach ?>
    </div>
    <div class="mt-16"><?= button_link('/expertise', 'Explore Surgical Expertise', 'dark') ?></div>
  </div>
</section>

<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$featured = null;
$others = [];
foreach ($c['conferences'] as $conf) {
    if ($featured === null && !empty($conf['featured'])) {
        $featured = $conf;
    } else {
        $others[] = $conf;
    }
}
?>
<?= page_hero([
    'index' => '06',
    'eyebrow' => 'Conferences & Education',
    'title_html' => 'Sharing technique. <span class="italic text-bronze">Shaping the learning curve.</span>',
    'lead' => 'Professional speaking engagements, surgical education and international academic meetings.',
]) ?>

<?php if ($featured): ?>
<section class="bg-ivory py-16 md:py-24 lg:py-32">
  <div class="container-x">
    <div<?= rv() ?>><?= eyebrow('Featured Presentation', 'I') ?></div>
    <div class="mt-12 grid bg-white shadow-[0_40px_80px_-50px_rgba(16,29,44,0.45)] lg:grid-cols-12">
      <div<?= rv() ?> class="lg:col-span-5">
        <?php if (!empty($featured['image'])): ?>
        <?= photo([
            'src' => $featured['image'],
            'alt' => $featured['event'] . ', ' . $featured['location'],
            'label' => 'Medical conference & academic presentation',
            'class' => 'h-full min-h-80',
        ]) ?>
        <?php else: ?>
        <div class="h-full bg-sand p-6"><?= conference_card($featured['event'], $featured['location'], $featured['type']) ?></div>
        <?php endif ?>
      </div>
      <div class="relative p-8 md:p-14 lg:col-span-7">
        <div<?= rv() ?>>
          <p class="eyebrow text-bronze"><?= e($featured['event']) ?> · <?= e($featured['location']) ?></p>
          <h2 class="mt-8 text-3xl leading-snug text-midnight md:text-[2.4rem]">“<?= e($featured['title']) ?>”</h2>
          <?php if (!empty($featured['summary'])): ?>
          <p class="mt-6 leading-relaxed text-charcoal/80"><?= e($featured['summary']) ?></p>
          <?php endif ?>
        </div>
        <div<?= rv(0.1) ?>>
          <dl class="mt-12 grid gap-4 sm:grid-cols-3">
            <?php foreach ([['Format', $featured['type']], ['Date', $featured['date']], ['Theme', $featured['theme']]] as [$k, $v]): ?>
            <div class="bg-paper p-5">
              <dt class="eyebrow text-bronze"><?= e($k) ?></dt>
              <dd class="mt-2 text-sm leading-relaxed text-charcoal/80"><?= e($v) ?></dd>
            </div>
            <?php endforeach ?>
          </dl>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif ?>

<section class="bg-sand py-20 md:py-28 lg:py-36">
  <div class="container-x grid gap-12 md:grid-cols-12">
    <div<?= rv() ?> class="md:col-span-5">
      <?= eyebrow('Engagements', 'II') ?>
      <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Conferences, workshops &amp; <span class="italic">speaking</span></h2>
    </div>
    <div class="md:col-span-6 md:col-start-7">
      <?php if ($others): ?>
      <ul class="border-t border-midnight/15">
        <?php foreach ($others as $conf): ?>
        <li class="grid gap-2 border-b border-midnight/10 py-7 sm:grid-cols-[8rem_1fr] sm:gap-8">
          <p class="font-serif text-2xl text-bronze"><?= e($conf['date']) ?></p>
          <div>
            <p class="font-serif text-2xl text-midnight"><?= e($conf['title']) ?></p>
            <p class="mt-2 text-sm text-charcoal/75"><?= e(implode(' · ', array_filter([$conf['event'], $conf['location'], $conf['type']]))) ?></p>
          </div>
        </li>
        <?php endforeach ?>
      </ul>
      <?php else: ?>
      <p class="leading-relaxed text-charcoal/80">Future conferences, workshops and speaking engagements will be published here as they are confirmed.</p>
      <?php endif ?>
    </div>
  </div>
</section>

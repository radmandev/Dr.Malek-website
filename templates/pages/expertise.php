<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$expertise = $c['expertise'];
$heroImage = null;
foreach ($expertise as $x) {
    if (!empty($x['image'])) {
        $heroImage = $x;
        break;
    }
}
?>
<?= page_hero([
    'index' => '03',
    'eyebrow' => 'Surgical Expertise',
    'title_html' => 'Advanced robotic surgery, <span class="italic text-bronze">planned around the individual.</span>',
    'lead' => 'Specialist practice in robotic bladder cancer surgery and reconstruction of the urinary tract — delivered within a multidisciplinary team and grounded in current evidence.',
    'image' => $heroImage['image'] ?? '',
    'image_alt' => $heroImage['imageLabel'] ?? '',
]) ?>

<div class="container-x grid gap-16 pb-24 pt-8 lg:grid-cols-12 lg:pb-32">
  <nav aria-label="Areas of expertise" class="hidden lg:col-span-3 lg:block">
    <ol class="sticky top-32 space-y-1 border-l border-midnight/10">
      <?php foreach ($expertise as $i => $x): ?>
      <li>
        <a href="#<?= e($x['slug']) ?>" class="-ml-px flex gap-4 border-l border-transparent py-2.5 pl-5 text-sm text-charcoal/75 transition-colors hover:border-bronze hover:text-midnight">
          <span class="font-serif italic text-bronze"><?= pad2($i + 1) ?></span>
          <?= e($x['title']) ?>
        </a>
      </li>
      <?php endforeach ?>
    </ol>
  </nav>

  <div class="space-y-32 lg:col-span-9">
    <?php foreach ($expertise as $i => $x): ?>
    <article id="<?= e($x['slug']) ?>" class="scroll-mt-32">
      <div<?= rv() ?>>
        <?= eyebrow('Expertise', pad2($i + 1)) ?>
        <h2 class="mt-6 text-4xl leading-[1.05] text-midnight md:text-6xl"><?= e($x['title']) ?></h2>
        <p class="mt-6 max-w-2xl font-serif text-2xl italic leading-snug text-charcoal/80"><?= e($x['summary']) ?></p>
      </div>
      <div class="mt-12 grid gap-12 md:grid-cols-5">
        <div<?= rv() ?> class="md:col-span-3">
          <div class="prose-editorial leading-relaxed text-charcoal/80"><?= paragraphs($x['body']) ?></div>
        </div>
        <div<?= rv(0.1) ?> class="md:col-span-2">
          <?php if (!empty($x['image'])): ?>
          <?= photo([
              'src' => $x['image'],
              'alt' => $x['title'],
              'label' => $x['imageLabel'],
              'position' => $x['imagePosition'],
              'zoom' => $x['imageZoom'],
              'class' => 'mb-6 aspect-[4/3]',
          ]) ?>
          <?php endif ?>
          <div class="bg-white p-7">
            <p class="eyebrow text-bronze">Key elements</p>
            <ul class="mt-5 border-t border-midnight/15">
              <?php foreach ($x['points'] as $point): ?>
              <li class="border-b border-midnight/10 py-3.5 font-serif text-xl text-midnight"><?= e($point) ?></li>
              <?php endforeach ?>
            </ul>
          </div>
        </div>
      </div>
    </article>
    <?php endforeach ?>

    <div<?= rv() ?>>
      <?= disclaimer() ?>
      <div class="mt-12 flex flex-wrap gap-4"><?= button_link('/patient-information', 'Patient Information', 'dark') ?></div>
    </div>
  </div>
</div>

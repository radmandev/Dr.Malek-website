<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$achievements = $c['achievements'];
$lead = $c['stats'][0] ?? ['value' => 50, 'suffix' => '+'];
$hero = $achievements['cases'][0] ?? null;
foreach ($achievements['cases'] as $case) {
    if (str_contains($case['image'] ?? '', 'team')) {
        $hero = $case;
        break;
    }
}
?>
<?= page_hero([
    'index' => '04',
    'eyebrow' => 'Experience & Achievements',
    'title_html' => 'A practice measured in <span class="italic text-bronze">precision.</span>',
    'image' => $hero['image'] ?? '',
    'image_alt' => $hero['caption'] ?? '',
]) ?>

<!-- FEATURE -->
<section class="bg-ivory py-20 md:py-28 lg:py-36">
  <div class="container-x grid items-end gap-16 lg:grid-cols-12">
    <div<?= rv() ?> class="lg:col-span-7">
      <p class="font-serif text-[10rem] leading-[0.78] tracking-tight text-midnight sm:text-[14rem] lg:text-[17rem]"><?= counter($lead['value'], $lead['suffix']) ?></p>
    </div>
    <div class="lg:col-span-5">
      <div<?= rv() ?>>
        <?= eyebrow('Milestone', 'I') ?>
        <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl"><?= e($achievements['headline']) ?></h2>
        <p class="mt-6 font-serif text-2xl italic leading-snug text-charcoal/80"><?= e($achievements['statement']) ?></p>
        <?php if (!empty($achievements['reflection'])): ?>
        <figure class="mt-10 border-l-2 border-champagne pl-6">
          <blockquote class="leading-relaxed text-charcoal/80">“<?= e($achievements['reflection']) ?>”</blockquote>
          <figcaption class="eyebrow mt-3 text-bronze">Dr. Malek Gaashan</figcaption>
        </figure>
        <?php endif ?>
      </div>
    </div>
  </div>
</section>

<!-- MILESTONES -->
<section class="bg-sand py-20 md:py-28 lg:py-36">
  <div class="container-x relative">
    <div<?= rv() ?>><?= eyebrow('Selected Milestones', 'II') ?></div>
    <ol class="mt-14 grid gap-6 md:grid-cols-2">
      <?php foreach ($achievements['milestones'] as $i => $m): ?>
      <li<?= rv(($i % 2) * 0.1) ?> class="group h-full bg-white p-8 transition-shadow duration-700 hover:shadow-[0_30px_60px_-30px_rgba(16,29,44,0.3)] md:p-12">
        <span class="font-serif text-5xl italic text-bronze"><?= pad2($i + 1) ?></span>
        <p class="mt-10 font-serif text-3xl leading-tight text-midnight"><?= e($m['title']) ?></p>
        <p class="mt-4 max-w-md text-sm leading-relaxed text-charcoal/75"><?= e($m['detail']) ?></p>
      </li>
      <?php endforeach ?>
    </ol>
  </div>
</section>

<?php if (!empty($achievements['approaches'])): ?>
<!-- TAILORED APPROACHES -->
<section class="bg-paper py-20 md:py-28 lg:py-36">
  <div class="container-x grid gap-16 lg:grid-cols-12">
    <div<?= rv() ?> class="lg:col-span-5">
      <?= eyebrow('Tailored Approaches', 'III') ?>
      <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Three patients. <span class="italic text-bronze">Three approaches.</span></h2>
      <p class="mt-6 leading-relaxed text-charcoal/80"><?= e($achievements['approachesIntro']) ?></p>
    </div>
    <div class="lg:col-span-6 lg:col-start-7">
      <ol>
        <?php foreach ($achievements['approaches'] as $i => $a): ?>
        <li<?= rv($i * 0.08) ?> class="mb-4 grid grid-cols-[3rem_1fr] gap-4 bg-white p-7">
          <span class="font-serif text-3xl italic text-bronze"><?= $i + 1 ?></span>
          <div>
            <p class="font-serif text-2xl leading-snug text-midnight"><?= e($a['title']) ?></p>
            <p class="mt-2 text-sm leading-relaxed text-charcoal/75"><?= e($a['detail']) ?></p>
          </div>
        </li>
        <?php endforeach ?>
      </ol>
      <p class="mt-6 text-xs leading-relaxed text-charcoal/50">Described in general terms only. Suitability for any approach depends on individual assessment.</p>
    </div>
  </div>
</section>
<?php endif ?>

<?php if (!empty($achievements['teamQuote'])): ?>
<!-- TEAM QUOTE -->
<section class="border-y border-midnight/10 bg-white py-24 text-midnight md:py-32">
  <div<?= rv() ?> class="container-x text-center">
    <span aria-hidden="true" class="font-serif text-7xl leading-none text-champagne">“</span>
    <blockquote class="mx-auto mt-2 max-w-4xl font-serif text-3xl italic leading-snug md:text-5xl"><?= e($achievements['teamQuote']) ?></blockquote>
    <p class="eyebrow mt-8 text-bronze">Dr. Malek Gaashan</p>
  </div>
</section>
<?php endif ?>

<!-- CASE STUDY IMAGERY -->
<section class="py-20 md:py-28 lg:py-36">
  <div class="container-x">
    <div class="grid gap-10 md:grid-cols-12">
      <div<?= rv() ?> class="md:col-span-6">
        <?= eyebrow('Visual Case Studies', 'IV') ?>
        <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Inside robotic <span class="italic">practice</span>.</h2>
      </div>
      <div<?= rv(0.1) ?> class="md:col-span-5 md:col-start-8">
        <p class="flex gap-4 text-sm leading-relaxed text-charcoal/80">
          <?= icon('shield-check', 'mt-0.5 size-5 shrink-0 text-bronze', 1.25) ?>
          Only anonymised, approved surgical and educational imagery is shown. No identifiable patient information or confidential clinical photographs are ever published.
        </p>
      </div>
    </div>
    <div class="mt-16 grid gap-8 md:grid-cols-2">
      <?php foreach ($achievements['cases'] as $i => $case): ?>
      <div<?= rv($i * 0.1) ?> class="<?= $i % 2 ? 'md:mt-20' : '' ?>">
        <figure>
          <?= photo(['src' => $case['image'], 'alt' => $case['title'], 'label' => $case['title'], 'class' => 'aspect-[4/3]']) ?>
          <figcaption class="mt-5">
            <p class="font-serif text-2xl text-midnight"><?= e($case['title']) ?></p>
            <p class="mt-1 text-sm text-charcoal/75"><?= e($case['caption']) ?></p>
          </figcaption>
        </figure>
      </div>
      <?php endforeach ?>
    </div>
    <div class="mt-20"><?= button_link('/conferences', 'Conferences & Education', 'dark') ?></div>
  </div>
</section>

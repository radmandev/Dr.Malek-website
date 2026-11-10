<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$profile = $c['profile'];
$credentials = $c['credentials'];
$expertise = $c['expertise'];
$stats = $c['stats'];
$achievements = $c['achievements'];
$featured = array_slice($expertise, 0, 4);

$conference = null;
foreach ($c['conferences'] as $item) {
    if (!empty($item['featured'])) {
        $conference = $item;
        break;
    }
}
$conference ??= $c['conferences'][0] ?? null;

$teamPhoto = $achievements['cases'][0] ?? null;
foreach ($achievements['cases'] as $case) {
    if (str_contains($case['image'] ?? '', 'team')) {
        $teamPhoto = $case;
        break;
    }
}

// Italicises the final three words of the editable headline.
$words = explode(' ', $profile['heroHeadline']);
[$headA, $headB] = count($words) > 3
    ? [implode(' ', array_slice($words, 0, -3)), implode(' ', array_slice($words, -3))]
    : [$profile['heroHeadline'], ''];

$lead = $stats[0] ?? ['value' => 50, 'suffix' => '+', 'label' => ''];

$achievementList = [
    ['Completion of 50+ robotic cystectomies', 'A published career milestone in robotic bladder cancer surgery.'],
    ['Advanced robotic surgical practice', 'Consultant pelvic robotic surgeon at University Hospitals of Leicester.'],
    ['Complex reconstructive procedures', 'Intracorporeal ileal conduit and orthotopic neobladder reconstruction.'],
    ['International academic participation', 'Invited oral presentation at AAU 2026, Jeddah.'],
];
?>
<!-- HERO -->
<section class="relative overflow-hidden bg-paper pb-24 pt-32 md:pt-40 lg:min-h-[min(100svh,980px)] lg:pb-16">
  <svg aria-hidden="true" viewBox="0 0 800 800" class="pointer-events-none absolute -right-72 top-1/2 hidden w-[1100px] -translate-y-1/2 text-champagne opacity-40 lg:block">
    <g class="origin-center animate-orbit" style="transform-box:fill-box">
      <circle cx="400" cy="400" r="390" fill="none" stroke="currentColor" stroke-width="0.8" stroke-dasharray="2 10" />
      <circle cx="790" cy="400" r="5" fill="currentColor" />
    </g>
    <circle cx="400" cy="400" r="300" fill="none" stroke="currentColor" stroke-width="0.6" />
  </svg>
  <div class="container-x relative grid items-center gap-16 lg:grid-cols-12 lg:gap-10">
    <div class="order-2 lg:order-1 lg:col-span-7">
      <div<?= rv() ?>><?= eyebrow('Consultant Urological Surgeon · United Kingdom') ?></div>
      <div<?= rv(0.1) ?>>
        <h1 class="mt-8 text-[2.9rem] leading-[0.98] text-midnight sm:text-6xl md:text-7xl xl:text-[5.4rem]">
          <?= e($headA) ?> <span class="italic text-bronze"><?= e($headB) ?></span>
        </h1>
      </div>
      <div<?= rv(0.2) ?>>
        <p class="mt-8 max-w-xl text-lg leading-relaxed text-charcoal/70"><?= e($profile['heroText']) ?></p>
      </div>
      <div<?= rv(0.3) ?>>
        <div class="mt-10 max-w-xl border-l-2 border-champagne pl-6">
          <p class="font-serif text-3xl text-midnight"><?= e($profile['name']) ?></p>
          <p class="mt-2 text-[0.68rem] font-semibold uppercase tracking-[0.26em] text-bronze"><?= e($profile['postnominals']) ?></p>
          <p class="mt-3 text-sm leading-relaxed text-charcoal/65"><?= e($profile['position']) ?><br><?= e($profile['focus']) ?></p>
        </div>
      </div>
      <div<?= rv(0.4) ?>>
        <div class="mt-12 flex flex-wrap gap-4">
          <?= button_link('/expertise', 'Explore Surgical Expertise', 'dark') ?>
          <?= button_link('/contact', 'Professional Enquiries', 'outline') ?>
        </div>
      </div>
    </div>

    <div<?= rv(0.15, 40) ?> class="order-1 lg:order-2 lg:col-span-5">
      <figure class="relative mx-auto max-w-[26rem] lg:ml-auto">
        <span aria-hidden="true" class="absolute -right-5 -top-5 bottom-10 left-10 rounded-t-full bg-sand"></span>
        <span aria-hidden="true" class="absolute -right-5 -top-5 bottom-10 left-10 rounded-t-full border border-champagne/50"></span>
        <?= photo([
            'src' => $profile['portrait'],
            'alt' => 'Portrait of ' . $profile['name'],
            'label' => 'Approved portrait of ' . $profile['name'],
            'position' => 'center 20%',
            'class' => 'relative aspect-[4/5] rounded-t-full shadow-[0_40px_80px_-40px_rgba(16,29,44,0.45)]',
            'priority' => true,
        ]) ?>
        <div class="absolute -left-4 bottom-10 bg-white px-6 py-5 shadow-[0_20px_50px_-20px_rgba(16,29,44,0.35)] sm:-left-12">
          <p class="font-serif text-5xl leading-none text-midnight"><?= counter($lead['value'], $lead['suffix']) ?></p>
          <p class="mt-2 text-[0.6rem] font-semibold uppercase tracking-[0.22em] text-charcoal/60"><?= e($lead['label']) ?></p>
        </div>
        <figcaption class="absolute -right-2 top-1/3 bg-midnight px-4 py-2.5 text-[0.6rem] font-semibold uppercase tracking-[0.24em] text-ivory sm:-right-8">FRCS (Urol)</figcaption>
      </figure>
    </div>
  </div>
  <a href="#credentials" class="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-[0.6rem] uppercase tracking-[0.3em] text-charcoal/40 transition-colors hover:text-bronze lg:flex">
    Scroll <?= icon('arrow-down', 'size-4 animate-bounce', 1) ?>
  </a>
</section>

<!-- CREDENTIALS STRIP -->
<section id="credentials" aria-label="Credentials" class="overflow-hidden border-y border-midnight/10 bg-white py-6">
  <ul class="sr-only">
    <?php foreach ($credentials as $item): ?><li><?= e($item) ?></li><?php endforeach ?>
  </ul>
  <div aria-hidden="true" class="flex w-max animate-marquee">
    <?php for ($k = 0; $k < 2; $k++): ?>
    <div class="flex shrink-0">
      <?php foreach (array_merge($credentials, $credentials) as $item): ?>
      <span class="flex items-center whitespace-nowrap font-serif text-2xl italic text-midnight/80 md:text-3xl">
        <span class="px-10"><?= e($item) ?></span>
        <span class="text-xs not-italic text-champagne">◆</span>
      </span>
      <?php endforeach ?>
    </div>
    <?php endfor ?>
  </div>
</section>

<!-- INTRODUCTION -->
<section class="bg-ivory py-28 md:py-36">
  <div class="container-x grid gap-14 lg:grid-cols-12">
    <div class="lg:col-span-5">
      <div<?= rv() ?>><?= eyebrow('Philosophy', '01') ?></div>
      <div<?= rv(0.1) ?>>
        <h2 class="mt-8 text-5xl leading-[1.02] text-midnight md:text-6xl">
          Precision in Surgery.<br><span class="italic text-bronze">Excellence in Care.</span>
        </h2>
      </div>
      <div<?= rv(0.2) ?>>
        <?= photo([
            'src' => $expertise[0]['image'] ?? '',
            'alt' => 'Robotic operating theatre',
            'label' => 'Robotic operating theatre',
            'class' => 'mt-12 aspect-[4/3]',
        ]) ?>
      </div>
    </div>
    <div class="lg:col-span-6 lg:col-start-7 lg:pt-20">
      <div<?= rv() ?>>
        <p class="font-serif text-2xl leading-snug text-midnight md:text-[1.9rem]"><?= e($profile['introBody'][0] ?? '') ?></p>
      </div>
      <div<?= rv(0.1) ?>>
        <div class="prose-editorial mt-8 leading-relaxed text-charcoal/75"><?= paragraphs(array_slice($profile['introBody'], 1)) ?></div>
      </div>
      <div<?= rv(0.15) ?>>
        <blockquote class="mt-12 border-t border-midnight/10 pt-8 font-serif text-2xl italic leading-snug text-midnight/80">“<?= e($profile['philosophy']) ?>”</blockquote>
      </div>
      <div<?= rv(0.2) ?>>
        <a href="/about" class="group mt-10 inline-flex items-center gap-3 text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-midnight">
          <span class="link-underline">Meet Dr. Malek</span>
          <?= icon('arrow-up-right', 'size-4 text-bronze transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5') ?>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED EXPERTISE -->
<section class="bg-paper py-28 md:py-36">
  <div class="container-x">
    <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
      <div>
        <div<?= rv() ?>><?= eyebrow('Surgical Expertise', '02') ?></div>
        <div<?= rv(0.1) ?>>
          <h2 class="mt-6 max-w-2xl text-4xl leading-tight text-midnight md:text-5xl">
            Focused on the most demanding areas of <span class="italic text-bronze">robotic urology</span>.
          </h2>
        </div>
      </div>
      <div<?= rv(0.2) ?>>
        <a href="/expertise" class="link-underline text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-midnight">All areas of expertise →</a>
      </div>
    </div>

    <div class="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($featured as $i => $x): ?>
      <div<?= rv($i * 0.08) ?> class="<?= $i % 2 ? 'lg:mt-16' : '' ?>">
        <a href="/expertise#<?= e($x['slug']) ?>" class="group flex h-full flex-col bg-white shadow-[0_1px_0_rgba(16,29,44,0.06)] transition-shadow duration-700 hover:shadow-[0_30px_60px_-30px_rgba(16,29,44,0.35)]">
          <?= photo([
              'src' => $x['image'],
              'alt' => $x['title'],
              'label' => $x['imageLabel'],
              'position' => $x['imagePosition'],
              'zoom' => $x['imageZoom'],
              'class' => 'aspect-[4/3]',
          ]) ?>
          <div class="flex flex-1 flex-col p-7">
            <p class="font-serif text-sm italic text-bronze"><?= pad2($i + 1) ?></p>
            <h3 class="mt-2 text-[1.75rem] leading-tight text-midnight"><?= e($x['title']) ?></h3>
            <p class="mt-3 flex-1 text-sm leading-relaxed text-charcoal/70"><?= e($x['summary']) ?></p>
            <span class="mt-6 inline-flex items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-midnight">
              Discover
              <?= icon('arrow-up-right', 'size-4 text-bronze transition-transform duration-500 group-hover:-translate-y-0.5 group-hover:translate-x-0.5', 1.5) ?>
            </span>
          </div>
        </a>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</section>

<!-- MILESTONES -->
<section class="bg-sand py-28 md:py-36">
  <div class="container-x grid items-center gap-16 lg:grid-cols-12">
    <div<?= rv() ?> class="lg:col-span-6">
      <figure class="relative">
        <?= photo([
            'src' => $teamPhoto['image'] ?? '',
            'alt' => $teamPhoto['title'] ?? 'Surgical team',
            'label' => 'Surgical team collaboration',
            'class' => 'aspect-[4/3]',
        ]) ?>
        <figcaption class="mt-4 text-xs uppercase tracking-[0.2em] text-charcoal/55"><?= e($teamPhoto['caption'] ?? '') ?></figcaption>
      </figure>
    </div>
    <div class="lg:col-span-5 lg:col-start-8">
      <div<?= rv() ?>>
        <?= eyebrow('Milestones', '03') ?>
        <p class="mt-6 font-serif text-[7rem] leading-[0.85] text-midnight md:text-[9rem]"><?= counter($lead['value'], $lead['suffix']) ?></p>
        <p class="mt-6 font-serif text-3xl italic text-midnight/85"><?= e($lead['label']) ?></p>
        <p class="mt-5 leading-relaxed text-charcoal/70"><?= e($achievements['statement']) ?></p>
      </div>
      <ul class="mt-10 border-t border-midnight/15">
        <?php foreach ($achievementList as $i => [$title, $detail]): ?>
        <li<?= rv($i * 0.06) ?> class="grid grid-cols-[2.5rem_1fr] gap-3 border-b border-midnight/15 py-5">
          <span class="font-serif italic text-bronze"><?= pad2($i + 1) ?></span>
          <div>
            <p class="font-serif text-xl text-midnight"><?= e($title) ?></p>
            <p class="mt-1 text-sm text-charcoal/60"><?= e($detail) ?></p>
          </div>
        </li>
        <?php endforeach ?>
      </ul>
    </div>
  </div>
  <div class="container-x mt-20 grid gap-6 sm:grid-cols-3">
    <?php foreach ($stats as $s): ?>
    <div<?= rv() ?> class="bg-white p-8">
      <p class="font-serif text-6xl text-midnight"><?= counter($s['value'], $s['suffix']) ?></p>
      <p class="eyebrow mt-4 text-bronze"><?= e($s['label']) ?></p>
      <p class="mt-2 text-sm text-charcoal/60"><?= e($s['note']) ?></p>
    </div>
    <?php endforeach ?>
  </div>
</section>

<?php if ($conference): ?>
<!-- CONFERENCE -->
<section class="bg-paper py-28 md:py-36">
  <div class="container-x grid items-center gap-14 lg:grid-cols-12">
    <div<?= rv() ?> class="lg:col-span-6">
      <?= !empty($conference['image'])
          ? photo(['src' => $conference['image'], 'alt' => $conference['event'] . ' conference', 'label' => 'Conference', 'class' => 'aspect-[5/4]'])
          : conference_card($conference['event'], $conference['location'], $conference['type']) ?>
    </div>
    <div class="lg:col-span-5 lg:col-start-8">
      <div<?= rv() ?>><?= eyebrow('Academic Contribution', '04') ?></div>
      <div<?= rv(0.1) ?>>
        <h2 class="mt-8 text-3xl leading-tight text-midnight md:text-[2.5rem]">“<?= e($conference['title']) ?>”</h2>
        <p class="mt-6 text-sm text-charcoal/65">Theme — <?= e($conference['theme']) ?></p>
      </div>
      <div<?= rv(0.2) ?>>
        <div class="mt-10"><?= button_link('/conferences', 'Conferences & Education', 'outline') ?></div>
      </div>
    </div>
  </div>
</section>
<?php endif ?>

<!-- CLOSING -->
<section class="border-t border-midnight/10 bg-ivory py-24 md:py-28">
  <div class="container-x flex flex-col items-start justify-between gap-10 md:flex-row md:items-center">
    <div<?= rv() ?> class="flex items-center gap-8">
      <?php if ($avatar = safe_url($profile['avatar'] ?? '')): ?>
      <img src="<?= e($avatar) ?>" alt="" width="112" height="112" loading="lazy" class="hidden size-28 shrink-0 rounded-full object-cover ring-1 ring-champagne ring-offset-4 ring-offset-ivory sm:block">
      <?php endif ?>
      <h2 class="max-w-2xl text-4xl leading-tight text-midnight md:text-5xl">
        Professional enquiries, collaborations &amp; <span class="italic text-bronze">academic invitations</span>.
      </h2>
    </div>
    <div<?= rv(0.1) ?>><?= button_link('/contact', 'Get in touch', 'dark') ?></div>
  </div>
</section>

<?php
defined('APP_ROOT') || exit;
/** @var string $path @var array $expertise */

$nav = [
    ['/', 'Home'],
    ['/about', 'About'],
    ['/expertise', 'Expertise', true],
    ['/experience', 'Experience'],
    ['/research', 'Research'],
    ['/conferences', 'Conferences'],
    ['/patient-information', 'Patient Information'],
    ['/contact', 'Contact'],
];
$isActive = fn (string $href) => $href === '/' ? $path === '/' : str_starts_with($path, $href);
?>
<header data-header class="fixed inset-x-0 top-0 z-50 border-b border-transparent py-6 text-midnight transition-[background-color,padding,border-color,box-shadow] duration-700 ease-editorial data-scrolled:border-midnight/10 data-scrolled:bg-paper/90 data-scrolled:py-3 data-scrolled:shadow-[0_8px_30px_-12px_rgba(16,29,44,0.15)] data-scrolled:backdrop-blur-md">
  <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:bg-champagne focus:px-4 focus:py-2 focus:text-midnight">Skip to content</a>
  <div class="container-x flex items-center justify-between gap-8">
    <a href="/" class="group flex flex-col leading-none" aria-label="Dr. Malek Gaashan — home">
      <span class="font-serif text-[1.6rem] tracking-tight">Dr. Malek <span class="italic text-bronze">Gaashan</span></span>
      <span class="mt-1.5 text-[0.56rem] font-semibold uppercase tracking-[0.32em] text-charcoal/55">Consultant Urological Surgeon</span>
    </a>

    <nav aria-label="Primary" class="hidden xl:block">
      <ul class="flex items-center gap-7">
        <?php foreach (array_slice($nav, 1) as $item): $active = $isActive($item[0]); ?>
        <li class="group/nav relative">
          <a href="<?= e($item[0]) ?>"<?= $active ? ' aria-current="page"' : '' ?> class="relative flex items-center gap-1 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.18em] transition-colors duration-300 hover:text-bronze <?= $active ? 'text-midnight' : 'text-charcoal/70' ?>">
            <?= e($item[1]) ?>
            <?php if (!empty($item[2])): ?><?= icon('chevron-down', 'size-3 transition-transform group-hover/nav:rotate-180') ?><?php endif ?>
            <span class="absolute -bottom-0.5 left-0 h-px bg-champagne transition-all duration-500 ease-editorial <?= $active ? 'w-full' : 'w-0 group-hover/nav:w-full' ?>"></span>
          </a>
          <?php if (!empty($item[2])): ?>
          <div class="invisible absolute left-1/2 top-full w-80 -translate-x-1/2 pt-5 opacity-0 transition-all duration-500 ease-editorial group-focus-within/nav:visible group-focus-within/nav:opacity-100 group-hover/nav:visible group-hover/nav:opacity-100">
            <ul class="border border-midnight/10 bg-paper p-2 shadow-[0_24px_60px_-20px_rgba(16,29,44,0.25)]">
              <?php foreach ($expertise as $i => $x): ?>
              <li>
                <a href="/expertise#<?= e($x['slug']) ?>" class="flex items-baseline gap-4 px-4 py-3 text-charcoal transition-colors hover:bg-sand hover:text-midnight">
                  <span class="font-serif text-sm italic text-bronze"><?= pad2($i + 1) ?></span>
                  <span class="font-serif text-lg"><?= e($x['title']) ?></span>
                </a>
              </li>
              <?php endforeach ?>
            </ul>
          </div>
          <?php endif ?>
        </li>
        <?php endforeach ?>
      </ul>
    </nav>

    <button type="button" data-menu-open aria-expanded="false" aria-controls="mobile-menu" class="flex items-center gap-3 text-[0.68rem] font-semibold uppercase tracking-[0.22em] xl:hidden">
      Menu <?= icon('menu', 'size-5', 1.25) ?>
    </button>
  </div>

  <div data-progress aria-hidden="true" class="absolute bottom-0 left-0 h-px w-full origin-left bg-champagne" style="transform:scaleX(0)"></div>
</header>

<?php /* Outside <header>: the header's backdrop blur would otherwise trap this fixed overlay inside it. */ ?>
<div id="mobile-menu" role="dialog" aria-modal="true" aria-label="Site menu" hidden class="fixed inset-0 z-50 flex flex-col overflow-y-auto bg-paper text-midnight">
  <div class="container-x flex items-center justify-between py-6">
    <span class="font-serif text-[1.6rem]">Dr. Malek <span class="italic text-bronze">Gaashan</span></span>
    <button type="button" data-menu-close class="flex items-center gap-3 text-[0.68rem] font-semibold uppercase tracking-[0.22em]">
      Close <?= icon('x', 'size-5', 1.25) ?>
    </button>
  </div>
  <nav aria-label="Mobile" class="container-x flex-1 py-8">
    <ul>
      <?php foreach ($nav as $i => $item): ?>
      <li data-menu-item style="--i:<?= $i ?>" class="border-b border-midnight/10">
        <a href="<?= e($item[0]) ?>"<?= $isActive($item[0]) ? ' aria-current="page"' : '' ?> class="flex items-baseline gap-5 py-4 font-serif text-4xl sm:text-5xl <?= $isActive($item[0]) ? 'text-bronze' : 'text-midnight' ?>">
          <span class="w-8 font-sans text-xs tracking-widest text-charcoal/40"><?= pad2($i + 1) ?></span>
          <?= e($item[1]) ?>
        </a>
      </li>
      <?php endforeach ?>
    </ul>
  </nav>
  <p class="container-x pb-8 text-[0.65rem] uppercase tracking-[0.24em] text-charcoal/50">MD · MRCS · FRCS (Urol)</p>
</div>

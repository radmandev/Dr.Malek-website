<?php
defined('APP_ROOT') || exit;
/** @var array $items */

// Vertical career timeline: a champagne line draws as you scroll; entries expand on click/keyboard focus.
// Behaviour lives in public/assets/js/site.js; open/closed styling in resources/css/input.css (.tl-*).
?>
<ol data-timeline class="relative">
  <span aria-hidden="true" class="absolute bottom-0 left-[7px] top-2 w-px bg-midnight/10 md:left-[calc(25%+7px)]"></span>
  <span data-timeline-line aria-hidden="true" class="absolute bottom-0 left-[7px] top-2 w-px origin-top bg-champagne md:left-[calc(25%+7px)]" style="transform:scaleY(0)"></span>
  <?php foreach ($items as $i => $item):
      $roles = $item['roles'];
      preg_match('/\d{4}/', end($roles)['period'] ?? '', $start);
      preg_match('/(\d{4}|Present)\s*$/', $roles[0]['period'] ?? '', $end);
      $start = $start[0] ?? '';
      $end = $end[0] ?? '';
      $id = 'tl-roles-' . $i;
  ?>
  <li data-tl-item<?= $i === 0 ? ' data-open' : '' ?> class="relative grid gap-2 pb-14 pl-10 md:grid-cols-4 md:gap-10 md:pl-0">
    <p class="font-serif text-2xl text-midnight/50 md:pr-12 md:text-right md:text-3xl">
      <?= e($start) ?><?php if ($end !== '' && $end !== $start): ?><span class="text-bronze"> — <?= e($end) ?></span><?php endif ?>
    </p>
    <span aria-hidden="true" class="tl-dot absolute left-0 top-2.5 size-[15px] rounded-full border transition-colors duration-500 md:left-[25%]"></span>
    <div class="md:col-span-3 md:pl-12">
      <button type="button" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="<?= $id ?>" class="group text-left">
        <span class="eyebrow block text-bronze"><?= e($item['location']) ?></span>
        <span class="mt-2 block font-serif text-3xl leading-tight text-midnight transition-colors group-hover:text-bronze md:text-4xl"><?= e($item['organisation']) ?></span>
      </button>
      <div id="<?= $id ?>" class="tl-roles">
        <div>
          <ul class="mt-6 space-y-4 border-l border-midnight/10 pl-6">
            <?php foreach ($roles as $role): ?>
            <li>
              <p class="text-lg font-medium text-midnight"><?= e($role['title']) ?></p>
              <p class="mt-1 text-sm text-charcoal/75"><?= e($role['period']) ?></p>
            </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
      <p class="tl-summary mt-3 text-sm text-charcoal/75"><?= e(implode(' · ', array_column($roles, 'title'))) ?></p>
    </div>
  </li>
  <?php endforeach ?>
</ol>

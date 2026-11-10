<?php
defined('APP_ROOT') || exit;
/** @var array $c */

$articles = $c['patientArticles'];
$faqs = $c['faqs'];
$topics = array_merge($articles, [['slug' => 'faq', 'title' => 'Frequently Asked Questions']]);
$faqJsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn ($f) => [
        '@type' => 'Question',
        'name' => $f['question'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['answer']],
    ], $faqs),
];
?>
<script type="application/ld+json"><?= json_for_script($faqJsonLd) ?></script>
<?= page_hero([
    'index' => '07',
    'eyebrow' => 'Patient Information',
    'title_html' => 'Understanding your <span class="italic text-bronze">options.</span>',
    'lead' => 'Clear, general information to help you prepare for conversations with your clinical team. It does not replace individual medical advice.',
]) ?>

<div class="container-x"><?= disclaimer('max-w-3xl') ?></div>

<div class="container-x grid gap-16 py-20 lg:grid-cols-12 lg:py-28">
  <nav aria-label="Topics" class="lg:col-span-3">
    <div class="lg:sticky lg:top-32">
      <p class="eyebrow text-bronze">Topics</p>
      <ol class="mt-5 space-y-1 border-l border-midnight/10">
        <?php foreach ($topics as $i => $topic): ?>
        <li>
          <a href="#<?= e($topic['slug']) ?>" class="-ml-px flex gap-4 border-l border-transparent py-2 pl-5 text-sm text-charcoal/60 transition-colors hover:border-bronze hover:text-midnight">
            <span class="font-serif italic text-bronze"><?= pad2($i + 1) ?></span>
            <?= e($topic['title']) ?>
          </a>
        </li>
        <?php endforeach ?>
      </ol>
    </div>
  </nav>

  <div class="space-y-24 lg:col-span-8 lg:col-start-5">
    <?php foreach ($articles as $i => $a): ?>
    <article<?= rv() ?> id="<?= e($a['slug']) ?>" class="scroll-mt-32">
      <p class="font-serif text-sm italic text-bronze"><?= pad2($i + 1) ?></p>
      <h2 class="mt-2 text-4xl leading-tight text-midnight md:text-5xl"><?= e($a['title']) ?></h2>
      <?php if ($image = safe_url($a['image'] ?? '')): ?>
      <figure class="mt-8 bg-white p-6">
        <img src="<?= e($image) ?>" alt="<?= e($a['imageAlt']) ?>" width="640" height="360" loading="lazy" decoding="async" class="mx-auto h-auto w-full max-w-md">
        <?php if (!empty($a['imageAlt'])): ?>
        <figcaption class="mt-4 text-center text-xs text-charcoal/55">Illustration: how a robotic operating theatre is arranged.</figcaption>
        <?php endif ?>
      </figure>
      <?php endif ?>
      <div class="prose-editorial mt-8 text-lg leading-relaxed text-charcoal/80"><?= paragraphs($a['body']) ?></div>
    </article>
    <?php endforeach ?>

    <section id="faq" class="scroll-mt-32">
      <div<?= rv() ?>>
        <?= eyebrow('FAQ') ?>
        <h2 class="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Frequently Asked Questions</h2>
      </div>
      <div class="mt-10 bg-white px-6 md:px-8">
        <?php foreach ($faqs as $f): ?>
        <details class="group border-b border-midnight/10">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-6 font-serif text-2xl text-midnight [&::-webkit-details-marker]:hidden">
            <?= e($f['question']) ?>
            <?= icon('plus', 'size-5 shrink-0 text-bronze transition-transform duration-500 group-open:rotate-45', 1.25) ?>
          </summary>
          <p class="max-w-2xl pb-8 leading-relaxed text-charcoal/75"><?= e($f['answer']) ?></p>
        </details>
        <?php endforeach ?>
      </div>
    </section>
  </div>
</div>

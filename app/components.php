<?php
defined('APP_ROOT') || exit;

// Reusable UI pieces. Each returns an HTML string; all content values are escaped here.
// Arguments named $html are trusted markup written in the templates themselves.

/**
 * Attributes for a scroll-reveal element (fades and rises into view; see public/assets/js/site.js).
 * Usage: <div<?= rv(0.1) ?> class="...">
 */
function rv(float $delay = 0, ?int $y = null): string
{
    $style = [];
    if ($delay > 0) {
        $style[] = '--reveal-delay:' . round($delay, 2) . 's';
    }
    if ($y !== null) {
        $style[] = '--reveal-y:' . $y . 'px';
    }
    return ' data-reveal' . ($style ? ' style="' . implode(';', $style) . '"' : '');
}

/** Sanitises a CSS object-position / transform-origin value from editable content. */
function css_position(?string $position): string
{
    $position = preg_replace('/[^a-z0-9%. -]/i', '', (string) $position);
    return $position !== '' ? $position : 'center';
}

/** Approved photograph, or a refined labelled placeholder when none has been supplied. */
function photo(array $o): string
{
    $src = safe_url($o['src'] ?? '');
    $alt = (string) ($o['alt'] ?? '');
    $class = (string) ($o['class'] ?? '');
    $position = css_position($o['position'] ?? 'center');
    $zoom = (float) ($o['zoom'] ?? 1) ?: 1;

    if ($src !== '') {
        $loading = !empty($o['priority']) ? 'fetchpriority="high"' : 'loading="lazy"';
        $inner = '<div class="absolute inset-0" style="transform:scale(' . $zoom . ');transform-origin:' . $position . '">'
            . '<img src="' . e($src) . '" alt="' . e($alt) . '" ' . $loading . ' decoding="async" style="object-position:' . $position . '"'
            . ' class="absolute inset-0 size-full object-cover transition-transform duration-[1.6s] ease-editorial group-hover/photo:scale-[1.04]">'
            . '</div>';
    } else {
        $inner = '<div role="img" aria-label="' . e($alt . ' (image placeholder)') . '" class="texture-lines absolute inset-0 flex flex-col justify-between bg-sand p-5 text-midnight/50">'
            . '<span class="flex items-center gap-3"><span class="h-px w-6 bg-bronze/60"></span><span class="eyebrow text-[0.6rem] text-bronze">Photograph</span></span>'
            . '<span class="max-w-[24ch]">'
            . '<span class="block font-serif text-xl italic leading-snug text-midnight/70">' . e($o['label'] ?? '') . '</span>'
            . '<span class="mt-2 block text-[0.62rem] uppercase tracking-[0.16em] md:tracking-[0.2em] text-midnight/40">Approved image to be supplied</span>'
            . '</span></div>';
    }
    return '<div class="group/photo relative overflow-hidden bg-sand ' . e($class) . '">' . $inner . '</div>';
}

function eyebrow(string $text, ?string $index = null, bool $light = false): string
{
    return '<p class="eyebrow flex items-center gap-4 ' . ($light ? 'text-champagne-light' : 'text-bronze') . '">'
        . ($index !== null ? '<span class="font-serif text-sm tracking-normal opacity-80">' . e($index) . '</span>' : '')
        . '<span class="h-px w-10 bg-current opacity-50"></span><span>' . e($text) . '</span></p>';
}

/** Light editorial page header with an optional photograph. `title_html` is trusted template markup. */
function page_hero(array $o): string
{
    $image = $o['image'] ?? '';
    $html = '<section class="relative overflow-hidden bg-paper pb-16 pt-36 md:pb-24 md:pt-44">'
        . '<span aria-hidden="true" class="pointer-events-none absolute -left-6 top-20 select-none font-serif text-[14rem] leading-none text-midnight/[0.035] md:text-[24rem]">' . e($o['index']) . '</span>'
        . '<div class="container-x relative grid items-end gap-12 ' . ($image ? 'lg:grid-cols-12' : '') . '">'
        . '<div class="' . ($image ? 'lg:col-span-7' : '') . '">'
        . '<div' . rv() . '>' . eyebrow($o['eyebrow'], $o['index']) . '</div>'
        . '<div' . rv(0.1) . '><h1 class="mt-8 max-w-5xl text-5xl leading-[1.02] text-midnight sm:text-6xl md:text-7xl lg:text-[5.2rem]">' . $o['title_html'] . '</h1></div>';
    if (!empty($o['lead'])) {
        $html .= '<div' . rv(0.2) . '><p class="mt-8 max-w-2xl text-lg leading-relaxed text-charcoal/80">' . e($o['lead']) . '</p></div>';
    }
    $html .= '</div>';
    if ($image) {
        $alt = (string) ($o['image_alt'] ?? '');
        $html .= '<div' . rv(0.2) . ' class="lg:col-span-5"><div class="relative">'
            . '<span aria-hidden="true" class="absolute -bottom-4 -right-4 left-6 top-6 bg-sand"></span>'
            . photo(['src' => $image, 'alt' => $alt, 'label' => $alt, 'position' => $o['image_position'] ?? 'center', 'priority' => true, 'class' => 'relative aspect-[4/3]'])
            . '</div></div>';
    }
    return $html . '</div>'
        . '<div class="container-x relative mt-16 md:mt-20"><div class="h-px bg-midnight/10"></div></div>'
        . '</section>';
}

function button_link(string $href, string $label, string $variant = 'solid'): string
{
    $styles = [
        'solid' => 'bg-champagne text-midnight hover:bg-midnight hover:text-ivory',
        'ghost' => 'border border-ivory/25 text-ivory hover:border-champagne hover:text-champagne-light',
        'dark' => 'bg-midnight text-ivory hover:bg-champagne hover:text-midnight',
        'outline' => 'border border-midnight/25 text-midnight hover:border-midnight hover:bg-midnight hover:text-ivory',
    ][$variant];
    return '<a href="' . e($href) . '" class="group inline-flex w-full items-center justify-center gap-3 px-7 py-4 sm:w-auto text-[0.72rem] font-semibold uppercase tracking-[0.16em] md:tracking-[0.2em] transition-colors duration-500 ' . $styles . '">'
        . e($label)
        . icon('arrow-up-right', 'size-4 transition-transform duration-500 ease-editorial group-hover:-translate-y-0.5 group-hover:translate-x-0.5', 1.5)
        . '</a>';
}

/** Shown wherever a fact has not yet been verified/supplied, so nothing is invented. */
function placeholder(string $text): string
{
    return '<span class="italic text-charcoal/50">[' . e($text) . ']</span>';
}

/** Returns the escaped value, or a labelled placeholder when it is empty. */
function or_placeholder(mixed $value, string $text): string
{
    return (string) $value !== '' ? e($value) : placeholder($text);
}

function disclaimer(string $class = ''): string
{
    return '<aside class="border-l-2 border-champagne bg-white px-6 py-5 text-sm leading-relaxed text-charcoal/85 ' . e($class) . '">'
        . '<p class="eyebrow mb-2 text-bronze">Important</p>'
        . '<p>The information on this website is general and educational. It is not a substitute for individual medical '
        . 'advice, diagnosis or treatment, and no specific outcome can be guaranteed. Please discuss your own situation '
        . 'with your GP or treating team. This website does not provide emergency services — in an emergency, call 999 in '
        . 'the UK or your local emergency number.</p>'
        . '</aside>';
}

/** Typographic stand-in when no conference photograph has been supplied. */
function conference_card(string $event, string $location, string $type): string
{
    preg_match('/\d{4}$/', $event, $year);
    $name = preg_replace('/\s*\d{4}$/', '', $event);
    return '<div class="relative flex aspect-[5/4] flex-col justify-between overflow-hidden bg-white p-7 sm:p-10 shadow-[0_30px_60px_-40px_rgba(16,29,44,0.4)]">'
        . '<span aria-hidden="true" class="absolute -right-24 -top-24 size-80 rounded-full border border-champagne/40"></span>'
        . '<span aria-hidden="true" class="absolute -right-10 -top-10 size-52 rounded-full border border-champagne/30"></span>'
        . '<p class="eyebrow text-bronze">' . e($type) . '</p>'
        . '<div><p class="whitespace-nowrap font-serif text-6xl leading-none text-midnight sm:text-7xl md:text-8xl">' . e($name) . ' <span class="italic text-bronze">' . e($year[0] ?? '') . '</span></p>'
        . '<p class="mt-4 text-sm uppercase tracking-[0.16em] md:tracking-[0.24em] text-charcoal/75">' . e($location) . '</p></div>'
        . '</div>';
}

/** A figure that counts up from 0 once visible. Use for verified figures only. */
function counter(int|float $value, string $suffix = ''): string
{
    $value = (int) $value;
    return '<span data-counter="' . $value . '" aria-label="' . e($value . $suffix) . '"><span aria-hidden="true">'
        . '<span data-num>' . $value . '</span>'
        . ($suffix !== '' ? '<span class="text-bronze">' . e($suffix) . '</span>' : '')
        . '</span></span>';
}

/** Paragraphs from a list of strings. */
function paragraphs(array $items): string
{
    return implode('', array_map(fn ($p) => '<p>' . e($p) . '</p>', $items));
}

<?php
defined('APP_ROOT') || exit;

// Lucide icons (https://lucide.dev, ISC licence) as inline SVG.
const ICONS = [
    'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
    'arrow-down' => '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
    'arrow-up' => '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
    'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
    'menu' => '<path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/>',
    'x' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
    'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
    'plus' => '<path d="M5 12h14"/><path d="M12 5v14"/>',
    'check' => '<path d="M20 6 9 17l-5-5"/>',
];

function icon(string $name, string $class = 'size-4', float $stroke = 2): string
{
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="'
        . $stroke . '" stroke-linecap="round" stroke-linejoin="round" class="' . e($class) . '" aria-hidden="true">'
        . ICONS[$name] . '</svg>';
}

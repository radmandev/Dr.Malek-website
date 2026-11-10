// Admin content editor: renders a form for any JSON shape (text, numbers, switches, groups and reorderable lists),
// uploads images, and saves the section to /admin/save. Data comes from <script id="editor-data"> (see templates/admin/editor.php).
(() => {
  'use strict';

  const data = JSON.parse(document.getElementById('editor-data').textContent);
  const root = document.getElementById('editor');
  const saveBtn = document.querySelector('[data-save]');
  const status = document.querySelector('[data-status]');
  const state = { value: data.value };
  let dirty = false;

  const INPUT = 'w-full border border-midnight/15 bg-white px-3 py-2 text-sm focus:border-champagne focus:outline-none';
  const ICONS = {
    up: '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
    down: '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
    trash: '<path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
    plus: '<path d="M5 12h14"/><path d="M12 5v14"/>',
    upload: '<path d="M12 3v12"/><path d="m17 8-5-5-5 5"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>',
  };

  const humanize = (k) => String(k).replace(/([A-Z])/g, ' $1').replace(/^./, (c) => c.toUpperCase());
  const isImageKey = (k) => /^(image|portrait|avatar)$|Image$/.test(k);
  const isObject = (v) => v !== null && typeof v === 'object' && !Array.isArray(v);

  /** Tiny DOM builder: h('div', { class, onclick, value }, ...children). */
  function h(tag, attrs = {}, ...children) {
    const el = document.createElement(tag);
    for (const [k, v] of Object.entries(attrs)) {
      if (v === undefined || v === null || v === false) continue;
      if (k.startsWith('on')) el.addEventListener(k.slice(2), v);
      else if (k === 'value' || k === 'checked' || k === 'disabled') el[k] = v;
      else el.setAttribute(k, v === true ? '' : String(v));
    }
    el.append(...children.flat().filter((c) => c !== null && c !== undefined && c !== false));
    return el;
  }

  function icon(name) {
    const wrap = document.createElement('span');
    wrap.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">${ICONS[name]}</svg>`;
    return wrap.firstChild;
  }

  const get = (path) => path.reduce((obj, key) => obj[key], state);
  function set(path, value) {
    get(path.slice(0, -1))[path[path.length - 1]] = value;
    markDirty();
  }

  function markDirty() {
    dirty = true;
    saveBtn.disabled = false;
    status.textContent = 'Unsaved changes';
  }

  /** Empty copy of an item, used when adding a new entry to a list. */
  function blank(v) {
    if (Array.isArray(v)) return [];
    if (isObject(v)) return Object.fromEntries(Object.entries(v).map(([k, x]) => [k, blank(x)]));
    return typeof v === 'number' ? 0 : typeof v === 'boolean' ? false : '';
  }

  function render() {
    root.replaceChildren(field('', ['value'], data.template, true));
  }

  /** `tmpl` is the matching part of the bundled default content, used as the shape for new list items. */
  function field(name, path, tmpl, isRoot) {
    const value = get(path);
    const label = isRoot
      ? null
      : h('span', { class: 'mb-1.5 block text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-charcoal/60' }, humanize(name));

    if (typeof value === 'boolean') {
      return h('label', { class: 'flex items-center gap-3 text-sm' },
        h('input', { type: 'checkbox', checked: value, onchange: (e) => set(path, e.target.checked) }),
        humanize(name));
    }

    if (typeof value === 'number') {
      return h('label', { class: 'block' }, label,
        h('input', { type: 'number', step: 'any', class: INPUT, value: String(value), oninput: (e) => set(path, Number(e.target.value) || 0) }));
    }

    if (typeof value === 'string' || value === null) {
      const v = value ?? '';
      if (isImageKey(name)) return imageField(label, path, v);
      const onInput = (e) => set(path, e.target.value);
      const control = v.length > 90
        ? h('textarea', { class: `${INPUT} min-h-28`, value: v, oninput: onInput })
        : h('input', { class: INPUT, value: v, oninput: onInput });
      return h('label', { class: 'block' }, label, control);
    }

    if (Array.isArray(value)) {
      const itemTmpl = Array.isArray(tmpl) && tmpl.length ? tmpl[0] : undefined;
      const list = h('fieldset', { class: 'space-y-3' }, label);
      value.forEach((item, i) => {
        const nested = item !== null && typeof item === 'object';
        list.append(h('div', { class: 'flex gap-2 border border-midnight/10 bg-white/50 p-3' },
          h('div', { class: 'flex-1 space-y-4' },
            field(nested ? `${humanize(name)} ${i + 1}` : `Item ${i + 1}`, [...path, i], itemTmpl, !nested)),
          h('div', { class: 'flex flex-col gap-1 text-charcoal/50' },
            h('button', { type: 'button', 'aria-label': 'Move up', disabled: i === 0, class: 'p-1 hover:text-midnight disabled:opacity-30', onclick: () => move(path, i, -1) }, icon('up')),
            h('button', { type: 'button', 'aria-label': 'Move down', disabled: i === value.length - 1, class: 'p-1 hover:text-midnight disabled:opacity-30', onclick: () => move(path, i, 1) }, icon('down')),
            h('button', { type: 'button', 'aria-label': 'Remove', class: 'p-1 hover:text-red-700', onclick: () => remove(path, i) }, icon('trash')))));
      });
      list.append(h('button', {
        type: 'button',
        class: 'flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-champagne hover:text-midnight',
        onclick: () => {
          get(path).push(blank(value.length ? value[0] : itemTmpl ?? ''));
          markDirty();
          render();
        },
      }, icon('plus'), 'Add'));
      return list;
    }

    const group = h('div', { class: 'space-y-5' }, isRoot ? null : h('p', { class: 'font-serif text-xl text-midnight' }, name));
    for (const key of Object.keys(value)) {
      group.append(field(key, [...path, key], isObject(tmpl) ? tmpl[key] : undefined, false));
    }
    return group;
  }

  function move(path, i, d) {
    const list = get(path);
    [list[i], list[i + d]] = [list[i + d], list[i]];
    markDirty();
    render();
  }

  function remove(path, i) {
    if (!confirm('Remove this item?')) return;
    get(path).splice(i, 1);
    markDirty();
    render();
  }

  function imageField(label, path, initial) {
    const preview = (src) => src
      ? h('img', { src, alt: '', class: 'size-16 shrink-0 border border-midnight/10 object-cover' })
      : h('span', { class: 'size-16 shrink-0 bg-surgical' });
    let thumb = preview(initial);
    const input = h('input', {
      class: INPUT,
      value: initial,
      placeholder: 'Image URL (or upload)',
      oninput: (e) => {
        set(path, e.target.value);
        const next = preview(e.target.value);
        thumb.replaceWith(next);
        thumb = next;
      },
    });
    const buttonText = document.createTextNode('Upload');
    const file = h('input', {
      type: 'file',
      accept: 'image/jpeg,image/png,image/webp,image/gif',
      class: 'sr-only',
      onchange: async (e) => {
        const chosen = e.target.files[0];
        if (!chosen) return;
        buttonText.data = 'Uploading…';
        const body = new FormData();
        body.append('file', chosen);
        body.append('_csrf', data.csrf);
        try {
          const res = await fetch('/admin/upload', { method: 'POST', body, headers: { Accept: 'application/json' } });
          const json = await res.json().catch(() => ({}));
          if (!res.ok) throw new Error(json.error || `Server error (${res.status})`);
          input.value = json.url;
          input.dispatchEvent(new Event('input'));
        } catch (err) {
          alert(`Upload failed: ${err.message}`);
        } finally {
          buttonText.data = 'Upload';
          e.target.value = '';
        }
      },
    });
    return h('div', {}, label,
      h('div', { class: 'flex items-center gap-3' }, thumb, input,
        h('label', { class: 'flex cursor-pointer items-center gap-2 whitespace-nowrap border border-midnight/20 px-3 py-2 text-xs hover:border-champagne focus-within:border-champagne' },
          icon('upload'), buttonText, file)));
  }

  async function save() {
    if (!dirty) return;
    status.textContent = 'Saving…';
    saveBtn.disabled = true;
    try {
      const res = await fetch('/admin/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-Token': data.csrf },
        body: JSON.stringify({ section: data.section, value: state.value }),
      });
      const json = await res.json().catch(() => ({}));
      if (!res.ok) throw new Error(json.error || `Server error (${res.status})`);
      dirty = false;
      status.textContent = 'Saved. Changes are live on the site.';
    } catch (err) {
      status.textContent = `Save failed: ${err.message}`;
      saveBtn.disabled = false;
    }
  }

  saveBtn.addEventListener('click', save);
  addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 's') {
      e.preventDefault();
      save();
    }
  });
  document.querySelectorAll('[data-section-link]').forEach((a) => a.addEventListener('click', (e) => {
    if (!dirty) return;
    if (confirm('Discard unsaved changes?')) dirty = false; // so beforeunload doesn't ask again
    else e.preventDefault();
  }));
  addEventListener('beforeunload', (e) => {
    if (dirty) e.preventDefault();
  });

  render();
})();

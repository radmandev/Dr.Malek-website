// Website editor (/admin).
// 1. Shell: the sidebar drawer on small screens.
// 2. Editor (only on section pages): builds a friendly form for any section of app/content.php — text, paragraphs,
//    switches, figures, photographs with framing controls, and collapsible, reorderable lists — then saves the
//    section to /admin/save. Data comes from <script id="editor-data"> (see templates/admin/editor.php).
(() => {
  'use strict';

  /* ---------------- Shell: sidebar drawer ---------------- */
  const nav = document.querySelector('[data-nav]');
  const backdrop = document.querySelector('[data-nav-backdrop]');
  const setNav = (open) => {
    if (!nav) return;
    nav.toggleAttribute('data-open', open);
    backdrop.hidden = !open;
    document.body.style.overflow = open ? 'hidden' : '';
  };
  document.querySelector('[data-nav-open]')?.addEventListener('click', () => setNav(true));
  document.querySelector('[data-nav-close]')?.addEventListener('click', () => setNav(false));
  backdrop?.addEventListener('click', () => setNav(false));
  addEventListener('keydown', (e) => e.key === 'Escape' && setNav(false));

  const dataEl = document.getElementById('editor-data');
  if (!dataEl) return;

  /* ---------------- Editor ---------------- */
  const data = JSON.parse(dataEl.textContent);
  const root = document.getElementById('editor');
  const saveBar = document.querySelector('[data-savebar]');
  const saveBtn = document.querySelector('[data-save]');
  const status = document.querySelector('[data-status]');
  const toastEl = document.querySelector('[data-toast]');
  const state = { value: structuredClone(data.value) };
  let snapshot = JSON.stringify(data.value);
  let dirty = false;
  let titleUpdaters = [];
  const openItems = new WeakSet(); // list items whose card is expanded (survives re-renders and reordering)

  // Friendly labels and hints, by field name. Anything not listed is labelled from its name.
  const FIELDS = {
    name: ['Full name'],
    postnominals: ['Post-nominal letters', 'Shown under the name, e.g. MD | MRCS | FRCS (Urol).'],
    position: ['Position'],
    focus: ['Areas of focus', 'One line, separated by | .'],
    heroHeadline: ['Home page headline', 'The last three words appear in gold italics.'],
    heroText: ['Text under the headline'],
    portrait: ['Main portrait', 'Used on the home and About pages. A tall, high-resolution photo works best.'],
    avatar: ['Small round photo', 'Used beside “Get in touch” and on the Contact page.'],
    introTitle: ['Introduction heading'],
    introBody: ['Introduction paragraphs', 'Home page. The first paragraph is shown larger.'],
    philosophy: ['Philosophy quote'],
    bioBody: ['Biography paragraphs', 'About page. The first paragraph is shown larger.'],
    interests: ['Clinical interests'],
    value: ['Number', 'Counts up when it comes into view. Use verified figures only.'],
    suffix: ['After the number', 'For example “+”. Leave empty for none.'],
    label: ['Label'],
    note: ['Small note'],
    slug: ['Page anchor', 'Lower-case words joined by hyphens. Used in links such as /expertise#this-anchor.'],
    title: ['Title'],
    summary: ['Short summary', 'One or two sentences.'],
    image: ['Photograph'],
    imageLabel: ['Photo description', 'Describes the photo for visually impaired visitors.'],
    imageAlt: ['Illustration description', 'Describes the image for visually impaired visitors.'],
    body: ['Paragraphs'],
    points: ['Key points'],
    organisation: ['Hospital or organisation'],
    location: ['Location'],
    roles: ['Roles'],
    period: ['Dates', 'For example “August 2023 – Present”.'],
    institution: ['Institution'],
    year: ['Year'],
    detail: ['Details'],
    headline: ['Headline'],
    statement: ['Statement'],
    reflection: ['Dr. Malek’s reflection', 'Shown as a quote on the Experience page.'],
    teamQuote: ['Team quote'],
    approachesIntro: ['Introduction to the approaches'],
    approaches: ['Tailored approaches'],
    milestones: ['Milestones'],
    cases: ['Case-study photographs', 'Only anonymised, approved images. Never identifiable patients.'],
    caption: ['Caption'],
    journal: ['Journal'],
    doi: ['DOI', 'For example 10.1000/xyz123. A link is created automatically.'],
    url: ['Link', 'Optional. Used instead of the DOI link.'],
    featured: ['Featured', 'Shown on the home page and at the top of the Conferences page.'],
    event: ['Event', 'For example “AAU 2026”.'],
    date: ['Date'],
    type: ['Format', 'For example “Oral Presentation”.'],
    theme: ['Theme'],
    question: ['Question'],
    answer: ['Answer'],
    department: ['Department'],
    address: ['Address'],
    email: ['Professional email', 'Shown publicly on the Contact page.'],
    secretaryPhone: ['Secretary phone'],
    linkedin: ['LinkedIn profile link'],
    gmcNumber: ['GMC number'],
  };
  // What one item of a list is called, for “Add …” buttons and empty titles.
  const SINGULAR = {
    credentials: 'credential', stats: 'figure', expertise: 'area of expertise', timeline: 'workplace', roles: 'role',
    education: 'qualification', registrations: 'registration', awards: 'award', milestones: 'milestone',
    approaches: 'approach', cases: 'photograph', publications: 'publication', academicContributions: 'entry',
    conferences: 'conference', patientArticles: 'article', faqs: 'question', introBody: 'paragraph',
    bioBody: 'paragraph', body: 'paragraph', interests: 'interest', points: 'point',
  };
  const LONG = /^(body|introBody|bioBody|heroText|summary|philosophy|statement|reflection|teamQuote|approachesIntro|answer|detail|caption|note|address|imageAlt)$/;
  const PARAGRAPHS = /^(body|introBody|bioBody)$/;
  const isImageKey = (k) => /^(image|portrait|avatar)$|Image$/.test(k);
  const isObject = (v) => v !== null && typeof v === 'object' && !Array.isArray(v);
  const humanize = (k) => String(k).replace(/([A-Z])/g, ' $1').replace(/^./, (c) => c.toUpperCase());
  const labelOf = (k) => FIELDS[k]?.[0] ?? humanize(k);
  const hintOf = (k) => FIELDS[k]?.[1];
  const singular = (k) => SINGULAR[k] ?? 'item';
  /** “area of expertise” → “areas of expertise”, “approach” → “approaches”, “entry” → “entries”. */
  function plural(word) {
    const [head, ...rest] = word.split(' ');
    const p = /(s|sh|ch|x)$/.test(head) ? `${head}es` : /[^aeiou]y$/.test(head) ? `${head.slice(0, -1)}ies` : `${head}s`;
    return [p, ...rest].join(' ');
  }

  const ICONS = {
    up: '<path d="m18 15-6-6-6 6"/>',
    down: '<path d="m6 9 6 6 6-6"/>',
    trash: '<path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
    plus: '<path d="M5 12h14"/><path d="M12 5v14"/>',
    upload: '<path d="M12 3v12"/><path d="m17 8-5-5-5 5"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>',
    chevron: '<path d="m9 18 6-6-6-6"/>',
    check: '<path d="M20 6 9 17l-5-5"/>',
    alert: '<circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
    image: '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
    x: '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
  };

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
  function icon(name, cls = 'size-4') {
    const wrap = document.createElement('span');
    wrap.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="${cls}" aria-hidden="true">${ICONS[name]}</svg>`;
    return wrap.firstChild;
  }

  /* ----- State ----- */
  const get = (path) => path.reduce((obj, key) => obj[key], state);
  function set(path, value) {
    get(path.slice(0, -1))[path[path.length - 1]] = value;
    changed();
  }
  function changed() {
    dirty = JSON.stringify(state.value) !== snapshot;
    saveBar.toggleAttribute('data-show', dirty);
    saveBar.setAttribute('aria-hidden', String(!dirty));
    resetStatus();
    titleUpdaters.forEach((fn) => fn());
  }
  function resetStatus() {
    status.replaceChildren(h('span', { class: 'size-2 animate-pulse rounded-full bg-champagne' }), ' Unsaved changes');
    saveBtn.disabled = false;
  }

  /** Empty copy of an item, used when adding a new entry to a list. */
  function blank(v) {
    if (Array.isArray(v)) return [];
    if (isObject(v)) return Object.fromEntries(Object.entries(v).map(([k, x]) => [k, k === 'imageZoom' ? 1 : k === 'imagePosition' ? 'center' : blank(x)]));
    return typeof v === 'number' ? 0 : typeof v === 'boolean' ? false : '';
  }

  /* ----- Building blocks ----- */
  const labelEl = (key, forId) => h('label', { class: 'admin-label', for: forId }, labelOf(key));
  const hintEl = (key) => hintOf(key) && h('p', { class: 'admin-hint' }, hintOf(key));
  let uid = 0;
  const nextId = () => `f${++uid}`;

  /** Textareas grow with their text. Hidden ones (inside closed cards) are measured again when shown. */
  function autogrow(el) {
    const fit = () => {
      if (!el.offsetParent) return;
      el.style.height = 'auto';
      el.style.height = `${el.scrollHeight + 2}px`;
    };
    el.dataset.autogrow = '';
    el.fit = fit;
    el.addEventListener('input', fit);
    requestAnimationFrame(fit);
    return el;
  }
  const refit = (scope) => scope.querySelectorAll('textarea[data-autogrow]').forEach((t) => t.fit());

  function textInput(key, path, value, { long = false, bare = false, placeholder } = {}) {
    const id = nextId();
    const onInput = (e) => set(path, e.target.value);
    const control = long || LONG.test(key) || value.length > 80
      ? autogrow(h('textarea', { id, rows: 3, class: 'admin-input min-h-[5.5rem] resize-none leading-relaxed', value, oninput: onInput, placeholder }))
      : h('input', { id, type: key === 'email' ? 'email' : /^(url|linkedin)$/.test(key) ? 'url' : 'text', class: 'admin-input', value, oninput: onInput, placeholder });
    return bare ? control : h('div', {}, labelEl(key, id), control, hintEl(key));
  }

  function toggle(key, path, value) {
    const id = nextId();
    return h('div', { class: 'flex items-start justify-between gap-6 rounded-lg bg-ivory px-4 py-3' },
      h('div', {}, h('label', { for: id, class: 'text-sm font-semibold text-midnight' }, labelOf(key)), hintEl(key)),
      h('input', { id, type: 'checkbox', role: 'switch', class: 'admin-switch mt-0.5', checked: value, onchange: (e) => set(path, e.target.checked) }));
  }

  function numberInput(key, path, value) {
    const id = nextId();
    return h('div', {}, labelEl(key, id),
      h('input', { id, type: 'number', step: 'any', class: 'admin-input max-w-[12rem]', value: String(value), oninput: (e) => set(path, Number(e.target.value) || 0) }),
      hintEl(key));
  }

  /* ----- Photographs: preview with framing, upload by button or drag-and-drop ----- */
  const POSITIONS = [['0% 0%', 'Top left'], ['50% 0%', 'Top'], ['100% 0%', 'Top right'], ['0% 50%', 'Left'], ['center', 'Centre'], ['100% 50%', 'Right'], ['0% 100%', 'Bottom left'], ['50% 100%', 'Bottom'], ['100% 100%', 'Bottom right']];
  const normalisePos = (p) => (p === '50% 50%' ? 'center' : String(p || 'center').trim());

  function imageField(key, path, owner) {
    const hasFraming = isObject(owner) && 'imagePosition' in owner && 'imageZoom' in owner;
    const id = nextId();
    const img = h('img', { alt: '', class: 'absolute inset-0 size-full object-cover transition-[object-position] duration-300' });
    const empty = h('div', { class: 'absolute inset-0 flex flex-col items-center justify-center gap-2 text-charcoal/50' },
      icon('image', 'size-8'), h('span', { class: 'text-sm' }, 'No photograph yet'), h('span', { class: 'text-xs' }, 'Drop an image here, or use Upload'));
    const zoomWrap = h('div', { class: 'absolute inset-0' }, img);
    const busy = h('div', { class: 'absolute inset-0 hidden items-center justify-center bg-white/70 text-sm font-semibold text-midnight backdrop-blur-sm' }, 'Uploading…');
    const frame = h('div', { class: 'relative aspect-[4/3] w-full overflow-hidden rounded-lg bg-sand ring-1 ring-midnight/10 transition-shadow' }, zoomWrap, empty, busy);

    const refresh = () => {
      const src = get(path);
      img.src = src || '';
      img.hidden = !src;
      empty.hidden = !!src;
      if (hasFraming) {
        const pos = normalisePos(owner.imagePosition);
        img.style.objectPosition = pos;
        zoomWrap.style.transform = `scale(${Number(owner.imageZoom) || 1})`;
        zoomWrap.style.transformOrigin = pos;
      }
      removeBtn.hidden = !src;
    };

    async function upload(file) {
      if (!file) return;
      if (!/^image\/(jpeg|png|webp|gif)$/.test(file.type)) return toast('Please choose a JPEG, PNG, WebP or GIF image.', true);
      busy.classList.replace('hidden', 'flex');
      const body = new FormData();
      body.append('file', file);
      body.append('_csrf', data.csrf);
      try {
        const res = await fetch('/admin/upload', { method: 'POST', body, headers: { Accept: 'application/json' } });
        const json = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(json.error || `Server error (${res.status})`);
        set(path, json.url);
        urlInput.value = json.url;
        refresh();
        toast('Photo uploaded. Remember to save.');
      } catch (err) {
        toast(`Upload failed: ${err.message}`, true);
      } finally {
        busy.classList.replace('flex', 'hidden');
      }
    }

    const fileInput = h('input', { id, type: 'file', accept: 'image/jpeg,image/png,image/webp,image/gif', class: 'sr-only', onchange: (e) => { upload(e.target.files[0]); e.target.value = ''; } });
    const urlInput = h('input', { type: 'text', class: 'admin-input text-xs', value: get(path), placeholder: '/uploads/… or https://…', 'aria-label': `${labelOf(key)} address`, oninput: (e) => { set(path, e.target.value.trim()); refresh(); } });
    const removeBtn = h('button', { type: 'button', class: 'admin-btn-ghost', onclick: () => { set(path, ''); urlInput.value = ''; refresh(); } }, icon('x'), 'Remove');

    frame.addEventListener('dragover', (e) => { e.preventDefault(); frame.classList.add('ring-2', 'ring-champagne'); });
    frame.addEventListener('dragleave', () => frame.classList.remove('ring-2', 'ring-champagne'));
    frame.addEventListener('drop', (e) => { e.preventDefault(); frame.classList.remove('ring-2', 'ring-champagne'); upload(e.dataTransfer.files[0]); });

    const framing = hasFraming && framingControls(path.slice(0, -1), owner, refresh);
    const block = h('div', { class: 'grid gap-5 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]' },
      h('div', {}, h('span', { class: 'admin-label' }, labelOf(key)), frame),
      h('div', { class: 'flex flex-col gap-4 sm:pt-6' },
        h('div', { class: 'flex flex-wrap gap-2' },
          h('label', { for: id, class: 'admin-btn-secondary cursor-pointer focus-within:ring-4 focus-within:ring-champagne/20' }, icon('upload'), 'Upload photo', fileInput),
          removeBtn),
        hintEl(key),
        framing,
        h('details', { class: 'text-xs text-charcoal/60' }, h('summary', { class: 'cursor-pointer select-none hover:text-midnight' }, 'Image address'), h('div', { class: 'mt-2' }, urlInput))));
    refresh();
    return block;
  }

  function framingControls(ownerPath, owner, refresh) {
    const zoomVal = h('span', { class: 'tabular-nums text-charcoal/60' });
    const showZoom = () => (zoomVal.textContent = `${Number(owner.imageZoom || 1).toFixed(2)}×`);
    const zoom = h('input', {
      type: 'range', min: '1', max: '2.5', step: '0.05', value: String(owner.imageZoom || 1), class: 'admin-range w-full', 'aria-label': 'Zoom',
      oninput: (e) => { set([...ownerPath, 'imageZoom'], Number(e.target.value)); showZoom(); refresh(); },
    });
    const grid = h('div', { class: 'grid size-[4.5rem] shrink-0 grid-cols-3 gap-1 rounded-md bg-ivory p-1.5 ring-1 ring-midnight/10', role: 'group', 'aria-label': 'Focus point' });
    const paint = () => grid.querySelectorAll('button').forEach((b) => b.toggleAttribute('data-active', b.dataset.pos === normalisePos(owner.imagePosition)));
    for (const [pos, name] of POSITIONS) {
      grid.append(h('button', {
        type: 'button', 'data-pos': pos, title: name, 'aria-label': name,
        class: 'rounded-sm bg-midnight/15 transition-colors hover:bg-champagne/70 data-active:bg-champagne',
        onclick: () => { set([...ownerPath, 'imagePosition'], pos); paint(); refresh(); },
      }));
    }
    showZoom();
    paint();
    return h('div', { class: 'rounded-lg border border-midnight/10 bg-white p-4' },
      h('p', { class: 'admin-label' }, 'Framing'),
      h('div', { class: 'mt-2 flex items-center gap-4' },
        grid,
        h('div', { class: 'min-w-0 flex-1' },
          h('div', { class: 'mb-1 flex justify-between text-xs' }, h('span', { class: 'text-charcoal/70' }, 'Zoom'), zoomVal),
          zoom,
          h('p', { class: 'mt-2 text-xs leading-snug text-charcoal/55' }, 'Pick the part of the photo to keep in view.'))));
  }

  /* ----- Lists ----- */
  function stringList(key, path) {
    const list = get(path);
    const paragraph = PARAGRAPHS.test(key);
    const rows = list.map((item, i) => h('div', { class: 'group flex items-start gap-2' },
      h('span', { class: 'mt-3 w-6 shrink-0 text-right font-serif text-sm italic text-bronze' }, String(i + 1)),
      h('div', { class: 'min-w-0 flex-1' }, textInput(key, [...path, i], item, { long: paragraph, bare: true, placeholder: `Write a ${singular(key)}…` })),
      rowButtons(path, i, list.length, singular(key), 'mt-1.5')));
    return h('div', { class: 'space-y-2.5' }, ...rows,
      addButton(key, () => { list.push(''); changed(); render(); focusLast(path); }));
  }

  function objectList(key, path, tmpl) {
    const list = get(path);
    const itemTmpl = Array.isArray(tmpl) && tmpl.length ? tmpl[0] : list[0];
    const cards = list.map((item, i) => itemCard(key, [...path, i], item, i, list.length, itemTmpl));
    return h('div', { class: 'space-y-3' }, ...cards,
      addButton(key, () => {
        const fresh = blank(itemTmpl ?? {});
        list.push(fresh);
        openItems.add(fresh);
        changed();
        render();
        focusLast(path);
      }));
  }

  function itemCard(key, path, item, i, count, tmpl) {
    const titleText = h('span', { class: 'block truncate font-serif text-lg text-midnight sm:text-xl' });
    const subText = h('span', { class: 'block truncate text-xs text-charcoal/60' });
    const thumbSrc = item.image || item.portrait;
    const updateTitle = () => {
      const t = [item.title, item.question, item.organisation, item.event, item.label, item.name, item.body]
        .find((x) => typeof x === 'string' && x.trim());
      titleText.textContent = t?.trim() || `New ${singular(key)}`;
      titleText.classList.toggle('italic', !t);
      const s = [item.period, item.year, item.date, item.location, item.institution, item.journal, item.detail, item.caption]
        .filter((x) => typeof x === 'string' && x.trim()).slice(0, 2).join(' · ');
      subText.textContent = s || (Array.isArray(item.roles) && item.roles.map((r) => r.title).join(' · ')) || '';
      subText.hidden = !subText.textContent;
    };
    titleUpdaters.push(updateTitle);
    updateTitle();

    const open = openItems.has(item);
    const body = h('div', { class: 'space-y-6 border-t border-midnight/10 px-4 pb-6 pt-5 sm:px-5' }, objectFields(path, item, tmpl));
    body.hidden = !open;
    const chevron = icon('chevron', `size-4 shrink-0 text-charcoal/40 transition-transform duration-200 ${open ? 'rotate-90' : ''}`);
    const head = h('button', {
      type: 'button', class: 'flex min-w-0 flex-1 items-center gap-3 py-3.5 pl-3 text-left sm:gap-4 sm:py-4 sm:pl-5', 'aria-expanded': String(open),
      onclick: () => {
        const nowOpen = body.hidden;
        body.hidden = !nowOpen;
        head.setAttribute('aria-expanded', String(nowOpen));
        chevron.classList.toggle('rotate-90', nowOpen);
        nowOpen ? openItems.add(item) : openItems.delete(item);
        if (nowOpen) refit(body);
      },
    },
    chevron,
    thumbSrc
      ? h('img', { src: thumbSrc, alt: '', class: 'hidden size-11 shrink-0 rounded-md object-cover ring-1 ring-midnight/10 sm:block' })
      : h('span', { class: 'hidden size-11 shrink-0 items-center justify-center rounded-md bg-sand font-serif text-lg italic text-bronze sm:flex' }, String(i + 1)),
    h('span', { class: 'min-w-0 flex-1' }, titleText, subText));

    return h('div', { class: 'overflow-hidden rounded-xl border border-midnight/10 bg-white transition-shadow hover:shadow-[0_16px_40px_-28px_rgba(16,29,44,0.4)]', 'data-item': '' },
      h('div', { class: 'flex items-center gap-1 pr-1.5 sm:gap-2 sm:pr-3' }, head, rowButtons(path.slice(0, -1), i, count, singular(key))),
      body);
  }

  function rowButtons(listPath, i, count, what, extra = '') {
    return h('div', { class: `flex shrink-0 items-center ${extra}` },
      h('button', { type: 'button', class: 'admin-icon-btn', title: 'Move up', 'aria-label': `Move ${what} up`, disabled: i === 0, onclick: () => move(listPath, i, -1) }, icon('up')),
      h('button', { type: 'button', class: 'admin-icon-btn', title: 'Move down', 'aria-label': `Move ${what} down`, disabled: i === count - 1, onclick: () => move(listPath, i, 1) }, icon('down')),
      h('button', { type: 'button', class: 'admin-icon-btn hover:bg-red-50 hover:text-red-700', title: 'Delete', 'aria-label': `Delete ${what}`, onclick: () => remove(listPath, i, what) }, icon('trash')));
  }

  const addButton = (key, onclick) => h('button', {
    type: 'button', onclick,
    class: 'flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-midnight/20 py-3 text-sm font-semibold text-bronze transition-colors hover:border-champagne hover:bg-white',
  }, icon('plus'), `Add ${singular(key)}`);

  function move(path, i, d) {
    const list = get(path);
    [list[i], list[i + d]] = [list[i + d], list[i]];
    changed();
    render();
  }
  function remove(path, i, what) {
    if (!confirm(`Delete this ${what}? You can still press “Discard” before saving to get it back.`)) return;
    get(path).splice(i, 1);
    changed();
    render();
  }
  /** Scrolls to the newest item of a list (the one just before its “Add” button) and focuses its first field. */
  function focusLast(path) {
    requestAnimationFrame(() => {
      const rows = root.querySelector(`[data-list="${path.join('.')}"] > div`)?.children;
      const newest = rows?.[rows.length - 2];
      newest?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      newest?.querySelector('input:not([type=file]):not([type=range]):not([type=checkbox]), textarea')?.focus({ preventScroll: true });
    });
  }

  /* ----- Groups of fields ----- */
  function field(key, path, tmpl, owner) {
    const value = get(path);
    if (typeof value === 'boolean') return toggle(key, path, value);
    if (typeof value === 'number') return numberInput(key, path, value);
    if (typeof value === 'string' || value === null) {
      if (value === null) get(path.slice(0, -1))[path[path.length - 1]] = '';
      return isImageKey(key) ? imageField(key, path, owner) : textInput(key, path, value ?? '');
    }
    if (Array.isArray(value)) {
      const items = value.length ? value : Array.isArray(tmpl) ? tmpl : [];
      const isObjects = items.some(isObject);
      return h('div', {},
        h('div', { class: 'mb-3 flex items-baseline justify-between gap-4' },
          h('span', { class: 'admin-label mb-0' }, labelOf(key)),
          h('span', { class: 'text-xs text-charcoal/50' }, `${value.length} ${value.length === 1 ? singular(key) : plural(singular(key))}`)),
        hintEl(key) && h('div', { class: '-mt-2 mb-3' }, hintEl(key)),
        h('div', { 'data-list': path.join('.') }, isObjects ? objectList(key, path, tmpl) : stringList(key, path)));
    }
    return h('fieldset', { class: 'space-y-5 rounded-lg bg-ivory/60 p-4' }, h('legend', { class: 'admin-label' }, labelOf(key)), objectFields(path, value, tmpl));
  }

  function objectFields(path, obj, tmpl) {
    const frag = document.createDocumentFragment();
    for (const key of Object.keys(obj)) {
      if (key === 'imagePosition' || key === 'imageZoom') continue; // edited with the photo's framing controls
      frag.append(field(key, [...path, key], isObject(tmpl) ? tmpl[key] : undefined, obj));
    }
    return frag;
  }

  /** Top level: simple fields together on one panel; each photo and list on its own panel. */
  function render() {
    titleUpdaters = [];
    const value = state.value;
    const panel = (...children) => h('section', { class: 'admin-panel space-y-6 p-4 sm:p-7' }, ...children);
    let nodes;
    if (Array.isArray(value)) {
      nodes = [panel(field(data.section, ['value'], data.template))];
      nodes[0].querySelector('.admin-label')?.remove(); // the page title already names the list
    } else {
      const simple = [];
      const big = [];
      for (const key of Object.keys(value)) {
        if (key === 'imagePosition' || key === 'imageZoom') continue;
        const node = field(key, ['value', key], data.template?.[key], value);
        (Array.isArray(value[key]) || isImageKey(key) || isObject(value[key]) ? big : simple).push(node);
      }
      nodes = [simple.length && panel(...simple), ...big.map((n) => panel(n))].filter(Boolean);
    }
    root.replaceChildren(...nodes);
  }

  /* ----- Saving ----- */
  function toast(message, isError = false) {
    toastEl.replaceChildren(
      h('span', { class: `flex size-7 shrink-0 items-center justify-center rounded-full ${isError ? 'bg-red-50 text-red-700' : 'bg-sand text-bronze'}` }, icon(isError ? 'alert' : 'check')),
      message);
    toastEl.setAttribute('data-show', '');
    clearTimeout(toast.t);
    toast.t = setTimeout(() => toastEl.removeAttribute('data-show'), isError ? 6000 : 3200);
  }

  async function post(url, body) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-Token': data.csrf },
      body: JSON.stringify(body),
    });
    const json = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(json.error || `Server error (${res.status})`);
    return json;
  }

  function markSaved(text, customised) {
    const badge = document.querySelector('[data-saved-at]');
    badge.replaceChildren(h('span', { class: `size-1.5 rounded-full ${customised ? 'bg-champagne' : 'bg-charcoal/30'}` }), ` ${text}`);
    document.querySelector('[data-reset-wrap]').hidden = !customised;
  }

  async function save() {
    if (!dirty) return;
    status.textContent = 'Saving…';
    saveBtn.disabled = true;
    try {
      await post('/admin/save', { section: data.section, value: state.value });
      snapshot = JSON.stringify(state.value);
      changed();
      markSaved('Last edited just now', true);
      toast('Saved — the website is updated.');
    } catch (err) {
      status.textContent = `Not saved: ${err.message}`;
      saveBtn.disabled = false;
      toast(`Not saved: ${err.message}`, true);
    }
  }

  function discard() {
    if (!confirm('Discard all unsaved changes on this page?')) return;
    state.value = JSON.parse(snapshot);
    changed();
    render();
  }

  async function restoreOriginal() {
    if (!confirm('Restore the original content for this section? Your edits to it will be removed from the website.')) return;
    try {
      const json = await post('/admin/reset', { section: data.section });
      state.value = json.value;
      snapshot = JSON.stringify(json.value);
      changed();
      render();
      markSaved('Showing the original content', false);
      toast('Original content restored.');
    } catch (err) {
      toast(`Could not restore: ${err.message}`, true);
    }
  }

  saveBtn.addEventListener('click', save);
  document.querySelector('[data-discard]').addEventListener('click', discard);
  document.querySelector('[data-reset]').addEventListener('click', restoreOriginal);
  addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 's') {
      e.preventDefault();
      save();
    }
  });
  document.querySelectorAll('[data-section-link], a[href="/admin"]').forEach((a) => a.addEventListener('click', (e) => {
    if (!dirty) return;
    if (confirm('You have unsaved changes. Leave without saving?')) dirty = false; // so beforeunload doesn't ask again
    else e.preventDefault();
  }));
  addEventListener('beforeunload', (e) => {
    if (dirty) e.preventDefault();
  });

  render();
})();

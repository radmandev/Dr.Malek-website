// Site interactions: scroll reveals, counters, header, mobile menu, career timeline, contact form.
// No dependencies. Everything degrades gracefully: without JS the content is fully visible and the form posts normally.
(() => {
  'use strict';
  window.siteReady = true;

  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  // Close approximation of cubic-bezier(0.22, 1, 0.36, 1), the site's editorial ease.
  const ease = (t) => 1 - Math.pow(1 - t, 4);
  const clamp = (n) => Math.min(1, Math.max(0, n));

  /** Runs `update` every animation frame until `current` settles on `target()`, smoothing like a spring. */
  function smoothed(target, apply, factor = 0.16) {
    let current = target();
    let raf = 0;
    const step = () => {
      const t = target();
      current = reduce ? t : current + (t - current) * factor;
      if (Math.abs(t - current) < 0.0005) current = t;
      apply(current);
      raf = current !== t ? requestAnimationFrame(step) : 0;
    };
    apply(current);
    return () => { if (!raf) raf = requestAnimationFrame(step); };
  }

  /* Scroll reveal: [data-reveal] elements fade up once they enter the viewport (see input.css). */
  const reveals = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      for (const entry of entries) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      }
    }, { rootMargin: '-10% 0px' });
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add('is-visible'));
  }

  /* Counters: count up to the server-rendered value once visible. Years count up briefly rather than from zero. */
  document.querySelectorAll('[data-counter]').forEach((el) => {
    const value = Number(el.dataset.counter);
    const num = el.querySelector('[data-num]');
    const from = value > 1000 ? value - 40 : 0;
    if (reduce || !('IntersectionObserver' in window)) {
      el.classList.add('counter-ready');
      return;
    }
    num.textContent = String(from);
    el.classList.add('counter-ready');
    const io = new IntersectionObserver(([entry]) => {
      if (!entry.isIntersecting) return;
      io.disconnect();
      const start = performance.now();
      const tick = (now) => {
        const t = clamp((now - start) / 2200);
        num.textContent = String(Math.round(from + (value - from) * ease(t)));
        if (t < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    });
    io.observe(el);
  });

  /* Header: compact style once scrolled, plus a reading-progress hairline. */
  const header = document.querySelector('[data-header]');
  const bar = document.querySelector('[data-progress]');
  if (header && bar) {
    const progress = () => {
      const max = document.documentElement.scrollHeight - innerHeight;
      return max > 0 ? clamp(scrollY / max) : 0;
    };
    const update = smoothed(progress, (v) => { bar.style.transform = `scaleX(${v})`; });
    const onScroll = () => {
      header.toggleAttribute('data-scrolled', scrollY > 24);
      update();
    };
    addEventListener('scroll', onScroll, { passive: true });
    addEventListener('resize', onScroll, { passive: true });
    onScroll();
  }

  /* Mobile menu */
  const menu = document.getElementById('mobile-menu');
  const openBtn = document.querySelector('[data-menu-open]');
  if (menu && openBtn) {
    const closeBtn = menu.querySelector('[data-menu-close]');
    let closeTimer = 0;
    const open = () => {
      clearTimeout(closeTimer);
      menu.hidden = false;
      void menu.offsetHeight; // commit the closed state so the reveal transition runs
      menu.classList.add('is-open');
      document.body.style.overflow = 'hidden';
      openBtn.setAttribute('aria-expanded', 'true');
      closeBtn.focus();
    };
    const close = () => {
      if (menu.hidden) return;
      menu.classList.remove('is-open');
      document.body.style.overflow = '';
      openBtn.setAttribute('aria-expanded', 'false');
      closeTimer = setTimeout(() => { menu.hidden = true; }, reduce ? 0 : 700);
      openBtn.focus();
    };
    openBtn.addEventListener('click', open);
    closeBtn.addEventListener('click', close);
    addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    // Same-page anchor links don't navigate away, so close explicitly.
    menu.addEventListener('click', (e) => { if (e.target.closest('a')) close(); });
  }

  /* Career timeline: one entry open at a time; the champagne line draws as you scroll. */
  document.querySelectorAll('[data-timeline]').forEach((list) => {
    const items = [...list.querySelectorAll('[data-tl-item]')];
    const setOpen = (index) => items.forEach((item, i) => {
      item.toggleAttribute('data-open', i === index);
      item.querySelector('button').setAttribute('aria-expanded', String(i === index));
    });
    items.forEach((item, i) => {
      const button = item.querySelector('button');
      button.addEventListener('click', () => setOpen(item.hasAttribute('data-open') ? -1 : i));
      // Keyboard focus opens an entry (mouse clicks are handled above, so a click doesn't open then close it).
      button.addEventListener('focus', () => { if (button.matches(':focus-visible')) setOpen(i); });
    });

    const line = list.querySelector('[data-timeline-line]');
    // 0 when the list's top reaches 75% down the viewport; 1 when its bottom reaches 60%.
    const progress = () => {
      const r = list.getBoundingClientRect();
      return clamp((0.75 * innerHeight - r.top) / (r.height + 0.15 * innerHeight));
    };
    const update = smoothed(progress, (v) => { line.style.transform = `scaleY(${v})`; }, 0.12);
    addEventListener('scroll', update, { passive: true });
    addEventListener('resize', update, { passive: true });
  });

  /* Contact form: submit in the background and show the result in place. */
  const form = document.querySelector('[data-contact-form]');
  if (form && window.fetch) {
    const sent = document.querySelector('[data-contact-sent]');
    const button = form.querySelector('[type="submit"]');
    const label = button.querySelector('[data-label]');
    const error = form.querySelector('[data-error]');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      button.disabled = true;
      label.textContent = 'Sending…';
      error.hidden = true;
      try {
        const res = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { Accept: 'application/json' },
        });
        const json = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(json.error || 'Something went wrong. Please try again.');
        form.reset();
        form.hidden = true;
        sent.hidden = false;
        sent.focus();
      } catch (err) {
        error.textContent = err instanceof TypeError
          ? 'Could not reach the server. Please check your connection and try again.'
          : err.message;
        error.hidden = false;
      } finally {
        button.disabled = false;
        label.textContent = 'Send enquiry';
      }
    });
  }
})();

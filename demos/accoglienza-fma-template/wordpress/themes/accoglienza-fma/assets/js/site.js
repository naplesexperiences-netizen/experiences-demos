/* Accoglienza FMA — template demo. Nessuna dipendenza obbligatoria:
 * GSAP e Leaflet migliorano l'esperienza ma il contenuto resta leggibile senza. */
(function () {
  'use strict';

  var doc = document.documentElement;
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var hasGsap = typeof window.gsap !== 'undefined';
  var mqMobile = matchMedia('(max-width: 60rem)');
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  if (hasGsap && window.ScrollTrigger) gsap.registerPlugin(ScrollTrigger);
  var motion = hasGsap && !reduce;

  function ready() { doc.classList.add('motion-ready'); }

  /* ------------------------------------------------------------ date helpers */
  function iso(d) { return d.toISOString().slice(0, 10); }
  function addDays(isoStr, n) { var d = new Date(isoStr + 'T12:00:00'); d.setDate(d.getDate() + n); return iso(d); }
  function nights(a, b) { return Math.round((new Date(b + 'T12:00:00') - new Date(a + 'T12:00:00')) / 864e5); }
  var MESI = ['gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug', 'ago', 'set', 'ott', 'nov', 'dic'];
  function short(isoStr) { var d = new Date(isoStr + 'T12:00:00'); return d.getDate() + ' ' + MESI[d.getMonth()]; }
  var today = iso(new Date());

  function linkDates(form) {
    var inp = $('[data-date-in]', form), out = $('[data-date-out]', form);
    if (!inp || !out) return;
    inp.min = today;
    out.min = addDays(today, 1);
    inp.addEventListener('change', function () {
      if (!inp.value) return;
      out.min = addDays(inp.value, 1);
      if (!out.value || out.value <= inp.value) out.value = addDays(inp.value, 1);
      out.dispatchEvent(new Event('change', { bubbles: true }));
    });
  }

  function steppers(root) {
    $$('[data-stepper]', root).forEach(function (st) {
      var input = $('input', st);
      function sync() {
        var v = parseInt(input.value, 10) || 0, min = +input.min, max = +input.max;
        st.querySelector('[data-step="-1"]').disabled = v <= min;
        st.querySelector('[data-step="1"]').disabled = v >= max;
      }
      $$('[data-step]', st).forEach(function (b) {
        b.addEventListener('click', function () {
          var v = (parseInt(input.value, 10) || 0) + (+b.dataset.step);
          input.value = Math.max(+input.min, Math.min(+input.max, v));
          input.dispatchEvent(new Event('change', { bubbles: true }));
          sync();
        });
      });
      input.addEventListener('input', sync);
      sync();
    });
  }

  /* ------------------------------------------------------------ masthead */
  var mast = $('[data-mast]');
  if (mast) {
    var sentinel = document.createElement('div');
    sentinel.setAttribute('aria-hidden', 'true');
    sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:80px;pointer-events:none';
    document.body.prepend(sentinel);
    new IntersectionObserver(function (en) { mast.classList.toggle('is-compact', !en[0].isIntersecting); }).observe(sentinel);

    var toggle = $('[data-menu-toggle]', mast), sheet = $('#menu-mobile');
    if (toggle && sheet) {
      toggle.addEventListener('click', function () {
        var open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!open));
        sheet.hidden = open;
        if (!open) { var f = $('a', sheet); if (f) f.focus(); }
      });
      $$('a', sheet).forEach(function (a) { a.addEventListener('click', function () { toggle.setAttribute('aria-expanded', 'false'); sheet.hidden = true; }); });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !sheet.hidden) { sheet.hidden = true; toggle.setAttribute('aria-expanded', 'false'); toggle.focus(); }
      });
    }
  }

  /* ------------------------------------------------------------ ingresso pagina */
  var heroIn = $$('[data-hero-in]');
  var sliderEl = $('[data-slider]');
  if (motion && (heroIn.length || sliderEl)) {
    var tl = gsap.timeline({ defaults: { ease: 'power3.out' }, onComplete: ready });
    if (sliderEl) {
      tl.fromTo(sliderEl, { autoAlpha: 0, clipPath: 'inset(0% 0% 0% 18% round 20px)' },
        { autoAlpha: 1, clipPath: 'inset(0% 0% 0% 0% round 20px)', duration: 1.1, ease: 'power3.inOut', clearProps: 'clipPath' }, 0);
      var firstImg = $('.slide.is-active .slide__img', sliderEl);
      if (firstImg) tl.fromTo(firstImg, { scale: 1.12 }, { scale: 1.04, duration: 1.6 }, 0);
    }
    if (heroIn.length) tl.fromTo(heroIn, { autoAlpha: 0, y: 18 }, { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.09 }, 0.15);
  } else {
    ready();
  }

  /* ------------------------------------------------------------ slider hero */
  if (sliderEl) (function () {
    var slides = $$('[data-slide]', sliderEl);
    var now = $('[data-slider-now]', sliderEl);
    var bar = $('[data-slider-bar]', sliderEl);
    var btnToggle = $('[data-slider-toggle]', sliderEl);
    var DUR = 6.5;
    var index = 0, paused = reduce, hoverHold = false, progressTween = null, timer = null;

    // Le slide oltre la prima arrivano con data-src: si caricano quando stanno per comparire.
    function load(s) {
      var img = s && $('img[data-src]', s);
      if (!img) return;
      if (img.dataset.sizes) img.sizes = img.dataset.sizes;
      if (img.dataset.srcset) img.srcset = img.dataset.srcset;
      img.src = img.dataset.src;
      img.removeAttribute('data-src'); img.removeAttribute('data-srcset'); img.removeAttribute('data-sizes');
    }
    function preloadNext() { load(slides[(index + 1) % slides.length]); }

    function setA11y(i) {
      slides.forEach(function (s, k) {
        var on = k === i;
        s.classList.toggle('is-active', on);
        if (on) s.removeAttribute('aria-hidden'); else s.setAttribute('aria-hidden', 'true');
        var a = $('a', s); if (a) { if (on) a.removeAttribute('tabindex'); else a.setAttribute('tabindex', '-1'); }
      });
      now.textContent = String(i + 1).padStart(2, '0');
    }

    function show(i, user) {
      var next = (i + slides.length) % slides.length;
      if (next === index) return;
      var cur = slides[index], nxt = slides[next];
      index = next;
      load(nxt);
      if (hasGsap && !reduce) {
        gsap.killTweensOf([cur, nxt]);
        slides.forEach(function (s) { if (s !== cur && s !== nxt) gsap.set(s, { autoAlpha: 0, zIndex: 0 }); });
        gsap.set(cur, { autoAlpha: 1, zIndex: 1 });
        gsap.set(nxt, { autoAlpha: 0, zIndex: 2 });
      }
      setA11y(next);
      if (hasGsap && !reduce) {
        gsap.to(nxt, { autoAlpha: 1, duration: 0.9, ease: 'power2.inOut' });
        gsap.fromTo($('.slide__img', nxt), { scale: 1.1 }, { scale: 1, duration: DUR + 1.2, ease: 'none' });
        gsap.fromTo($$('.slide__cap > *', nxt), { autoAlpha: 0, y: 14 }, { autoAlpha: 1, y: 0, duration: 0.7, stagger: 0.07, delay: 0.25, ease: 'power3.out' });
        gsap.to(cur, { autoAlpha: 0, duration: 0.9, ease: 'power2.inOut' });
      } else {
        slides.forEach(function (s) { s.style.opacity = ''; s.style.visibility = ''; });
      }
      if (user) sliderEl.setAttribute('aria-live', 'polite');
      restart();
      preloadNext();
    }

    function restart() {
      if (progressTween) progressTween.kill();
      clearTimeout(timer);
      if (bar) bar.style.transform = 'scaleX(0)';
      if (paused || hoverHold) return;
      if (hasGsap && !reduce) {
        progressTween = gsap.fromTo(bar, { scaleX: 0 }, { scaleX: 1, duration: DUR, ease: 'none', onComplete: function () { show(index + 1); } });
      } else {
        timer = setTimeout(function () { show(index + 1); }, DUR * 1000);
      }
    }

    function setPaused(p) {
      paused = p;
      btnToggle.setAttribute('aria-pressed', String(p));
      btnToggle.setAttribute('aria-label', p ? 'Riprendi lo scorrimento' : 'Metti in pausa lo scorrimento');
      if (p) { if (progressTween) progressTween.pause(); clearTimeout(timer); } else restart();
    }

    $('[data-slider-next]', sliderEl).addEventListener('click', function () { show(index + 1, true); });
    $('[data-slider-prev]', sliderEl).addEventListener('click', function () { show(index - 1, true); });
    btnToggle.addEventListener('click', function () { setPaused(!paused); });
    sliderEl.addEventListener('pointerenter', function () { hoverHold = true; if (progressTween) progressTween.pause(); clearTimeout(timer); });
    sliderEl.addEventListener('pointerleave', function () { hoverHold = false; if (!paused) { if (progressTween) progressTween.resume(); else restart(); } });
    sliderEl.addEventListener('focusin', function () { hoverHold = true; if (progressTween) progressTween.pause(); clearTimeout(timer); });
    sliderEl.addEventListener('focusout', function (e) { if (!sliderEl.contains(e.relatedTarget)) { hoverHold = false; if (!paused) restart(); } });
    sliderEl.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowRight') { show(index + 1, true); }
      if (e.key === 'ArrowLeft') { show(index - 1, true); }
    });
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) { if (progressTween) progressTween.pause(); clearTimeout(timer); }
      else if (!paused && !hoverHold) { if (progressTween) progressTween.resume(); else restart(); }
    });
    // swipe
    var x0 = null;
    sliderEl.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    sliderEl.addEventListener('touchend', function (e) {
      if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; x0 = null;
      if (Math.abs(dx) > 50) show(index + (dx < 0 ? 1 : -1), true);
    });

    if (reduce) setPaused(true); else setTimeout(restart, motion ? 1200 : 0);
    // La seconda slide si prepara a pagina carica, così il primo passaggio non mostra un riquadro vuoto.
    if (document.readyState === 'complete') preloadNext(); else window.addEventListener('load', preloadNext);
  })();

  /* ------------------------------------------------------------ home: barra in basso su mobile */
  // Porta all'elenco delle case (ogni casa si contatta dalla sua pagina). Nascosta su hero, elenco e piè di pagina.
  var homeBar = $('[data-bottom-bar="home"]');
  if (homeBar && 'IntersectionObserver' in window) (function () {
    var mq = window.matchMedia('(max-width: 60rem)');
    var hero = $('.hero'), places = $('#strutture'), foot = $('.foot');
    var pastHero = false, placesOn = false, footOn = false;
    function upd() { var on = mq.matches && pastHero && !placesOn && !footOn; homeBar.classList.toggle('is-on', on); homeBar.inert = !on; }
    homeBar.hidden = false;
    upd();
    if (hero) new IntersectionObserver(function (en) { pastHero = !en[0].isIntersecting && en[0].boundingClientRect.top < 0; upd(); }).observe(hero);
    if (places) new IntersectionObserver(function (en) { placesOn = en[0].isIntersecting; upd(); }, { rootMargin: '0px 0px -30% 0px' }).observe(places);
    if (foot) new IntersectionObserver(function (en) { footOn = en[0].isIntersecting; upd(); }).observe(foot);
    mq.addEventListener('change', upd);
  })();

  /* ------------------------------------------------------------ strutture: filtri, mappa, ricerca */
  var list = $('[data-places-list]');
  var mapData = (function () { var n = $('#fma-map-data'); try { return n ? JSON.parse(n.textContent) : []; } catch (e) { return []; } })();
  var markers = {}, map = null, activeFilter = { regione: '', slug: '' };

  function visibleSlugs() {
    return mapData.filter(function (d) {
      if (activeFilter.slug) return d.slug === activeFilter.slug;
      if (activeFilter.regione) return d.regione === activeFilter.regione;
      return true;
    }).map(function (d) { return d.slug; });
  }

  function applyFilter() {
    if (!list) return;
    var vis = visibleSlugs();
    $$('[data-place]', list).forEach(function (li) { li.hidden = vis.indexOf(li.dataset.place) < 0; });
    var st = $('[data-places-status]');
    if (st) st.textContent = vis.length === 1 ? '1 casa' : vis.length + ' case';
    var empty = $('[data-places-empty]'); if (empty) empty.hidden = vis.length > 0;
    $$('.chip').forEach(function (c) {
      var on = !activeFilter.slug && c.dataset.filter === activeFilter.regione;
      c.classList.toggle('is-active', on); c.setAttribute('aria-pressed', String(on));
    });
    if (map) {
      var pts = [];
      Object.keys(markers).forEach(function (slug) {
        var m = markers[slug];
        if (vis.indexOf(slug) >= 0) { m.addTo(map); pts.push(m.getLatLng()); } else { m.remove(); }
      });
      if (pts.length === 1) map.flyTo(pts[0], 10, { duration: reduce ? 0 : 0.9 });
      else if (pts.length) map.flyToBounds(L.latLngBounds(pts), { padding: [48, 48], maxZoom: 9, duration: reduce ? 0 : 0.9 });
    }
  }

  $$('.chip').forEach(function (c) {
    c.addEventListener('click', function () {
      activeFilter = { regione: c.dataset.filter, slug: '' };
      applyFilter();
    });
  });

  function hot(slug, on) {
    var li = list && $('[data-place="' + slug + '"]', list); if (li) li.classList.toggle('is-hot', on);
    var m = markers[slug]; if (m && m._icon) m._icon.classList.toggle('is-hot', on);
    if (m && on) m.setZIndexOffset(1000); else if (m) m.setZIndexOffset(0);
  }
  if (list) $$('[data-place]', list).forEach(function (li) {
    var slug = li.dataset.place;
    li.addEventListener('pointerenter', function () { hot(slug, true); });
    li.addEventListener('pointerleave', function () { hot(slug, false); });
    li.addEventListener('focusin', function () { hot(slug, true); });
    li.addEventListener('focusout', function () { hot(slug, false); });
  });

  var TILES = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
  var ATTR = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>';

  function pinIcon(n) {
    return L.divIcon({ className: 'pin', html: '<div class="pin__dot"><span>' + n + '</span></div>', iconSize: [34, 34], iconAnchor: [4, 34], popupAnchor: [13, -30] });
  }

  var mapEl = $('[data-map]');
  if (mapEl && window.L && mapData.length) {
    map = L.map(mapEl, { scrollWheelZoom: false, zoomControl: true, attributionControl: true });
    L.tileLayer(TILES, { attribution: ATTR, maxZoom: 18 }).addTo(map);
    mapData.forEach(function (d, i) {
      var m = L.marker([d.lat, d.lng], { icon: pinIcon(i + 1), title: d.nome, alt: d.nome, riseOnHover: true });
      m.bindPopup('<a class="popup" href="' + d.url + '"><img src="' + d.img + '" alt=""><strong>' + d.nome + '</strong><span>' + d.luogo + '</span><em>Scopri la casa →</em></a>');
      m.on('mouseover', function () { hot(d.slug, true); });
      m.on('mouseout', function () { hot(d.slug, false); });
      m.on('click', function () {
        var li = list && $('[data-place="' + d.slug + '"]', list);
        if (li && !mqMobile.matches) li.scrollIntoView({ block: 'nearest', behavior: reduce ? 'auto' : 'smooth' });
      });
      markers[d.slug] = m.addTo(map);
    });
    map.fitBounds(L.latLngBounds(mapData.map(function (d) { return [d.lat, d.lng]; })), { padding: [40, 40] });
    mapEl.classList.add('is-ready');
    map.on('focus', function () { map.scrollWheelZoom.enable(); });
    map.on('blur', function () { map.scrollWheelZoom.disable(); });

    // pin che compaiono uno a uno
    if (motion && window.ScrollTrigger) {
      var icons = Object.keys(markers).map(function (k) { return markers[k]._icon && markers[k]._icon.firstChild; }).filter(Boolean);
      gsap.set(icons, { autoAlpha: 0, y: -18 });
      ScrollTrigger.create({
        trigger: mapEl, start: 'top 80%', once: true,
        onEnter: function () { gsap.to(icons, { autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.09, clearProps: 'transform' }); }
      });
    }
  }

  /* ricerca hero -> filtro + date sui link */
  var search = $('[data-search]');
  if (search) {
    linkDates(search); steppers(search);
    search.addEventListener('submit', function (e) {
      e.preventDefault();
      var err = $('[data-search-error]', search);
      var dove = search.dove.value, a = search.arrivo.value, p = search.partenza.value, n = search.ospiti.value;
      if ((a && !p) || (!a && p)) { err.textContent = 'Indica sia la data di arrivo sia quella di partenza, oppure lasciale vuote.'; err.hidden = false; return; }
      if (a && p && p <= a) { err.textContent = 'La partenza deve essere dopo l’arrivo.'; err.hidden = false; search.partenza.focus(); return; }
      err.hidden = true;
      activeFilter = dove.indexOf('regione:') === 0 ? { regione: dove.slice(8), slug: '' } : { regione: '', slug: dove };
      applyFilter();
      var q = new URLSearchParams();
      if (a) { q.set('arrivo', a); q.set('partenza', p); }
      q.set('ospiti', n);
      $$('[data-place-link]').forEach(function (l) { l.href = l.getAttribute('href').split('?')[0] + '?' + q.toString(); });
      var echo = $('[data-search-echo]'), txt = $('[data-search-echo-text]');
      var parts = [];
      parts.push(dove ? search.dove.options[search.dove.selectedIndex].text.replace(/ — tutte le case.*$/, '') : 'Tutte le case');
      if (a) parts.push(short(a) + ' – ' + short(p) + ' (' + nights(a, p) + (nights(a, p) === 1 ? ' notte)' : ' notti)'));
      parts.push(n + (n === '1' ? ' ospite' : ' ospiti'));
      txt.textContent = 'Stai cercando: ' + parts.join(' · ') + '. Le date passano al modulo della casa che scegli.';
      echo.hidden = false;
      $('#strutture').scrollIntoView({ behavior: reduce ? 'auto' : 'smooth' });
    });
    $$('[data-search-reset]').forEach(function (b) {
      b.addEventListener('click', function () {
        activeFilter = { regione: '', slug: '' }; applyFilter();
        $('[data-search-echo]').hidden = true; search.reset();
        $$('[data-place-link]').forEach(function (l) { l.href = l.getAttribute('href').split('?')[0]; });
      });
    });
  }

  /* ------------------------------------------------------------ numeri che contano */
  if (motion && window.ScrollTrigger) {
    $$('[data-count]').forEach(function (el) {
      var end = +el.dataset.count, start = end > 1000 ? end - 60 : 0, o = { v: start };
      el.textContent = start;
      ScrollTrigger.create({ trigger: el, start: 'top 85%', once: true, onEnter: function () {
        gsap.to(o, { v: end, duration: end > 1000 ? 1.4 : 0.9, ease: 'power2.out', onUpdate: function () { el.textContent = Math.round(o.v); } });
      } });
    });
  }

  /* ------------------------------------------------------------ foto che si svelano */
  if (motion && window.ScrollTrigger) {
    $$('[data-unveil]').forEach(function (el) {
      var im = $('img', el);
      gsap.fromTo(el, { clipPath: 'inset(12% 8% 12% 8% round 20px)' }, { clipPath: 'inset(0% 0% 0% 0% round 20px)', duration: 1.2, ease: 'power3.out', clearProps: 'clipPath',
        scrollTrigger: { trigger: el, start: 'top 85%', once: true } });
      if (im) gsap.fromTo(im, { scale: 1.15 }, { scale: 1, duration: 1.4, ease: 'power3.out', clearProps: 'transform', scrollTrigger: { trigger: el, start: 'top 85%', once: true } });
    });
    $$('[data-unveil-group]').forEach(function (g) {
      var kids = $$(':scope > *', g).filter(function (k) { return k.offsetParent !== null; });
      gsap.fromTo(kids, { autoAlpha: 0, y: 24 }, { autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: 0.08, clearProps: 'transform,opacity,visibility' });
    });
  }

  /* ------------------------------------------------------------ pagina struttura */
  var single = $('[data-map-single]');
  if (single && window.L) {
    var lat = +single.dataset.lat, lng = +single.dataset.lng;
    var sm = L.map(single, { scrollWheelZoom: false }).setView([lat, lng], 13);
    L.tileLayer(TILES, { attribution: ATTR, maxZoom: 18 }).addTo(sm);
    L.marker([lat, lng], { icon: pinIcon('•'), title: single.dataset.name, alt: single.dataset.name }).addTo(sm);
    single.classList.add('is-ready');
  }

  // lightbox
  var galleryData = (function () { var n = $('#fma-gallery'); try { return n ? JSON.parse(n.textContent) : []; } catch (e) { return []; } })();
  if (galleryData.length) (function () {
    var dlg = document.createElement('dialog');
    dlg.className = 'lightbox';
    dlg.setAttribute('aria-label', 'Foto della struttura');
    dlg.innerHTML = '<div class="lightbox__bar"><p class="lightbox__count" aria-live="polite"></p><button type="button" class="slider__btn" data-lb-close aria-label="Chiudi">' + svg('M6 6l12 12M18 6L6 18') + '</button></div>' +
      '<div class="lightbox__stage"><img alt=""></div>' +
      '<div class="lightbox__nav"><button type="button" class="slider__btn" data-lb-prev aria-label="Foto precedente">' + svg('M15 5l-7 7 7 7') + '</button><button type="button" class="slider__btn" data-lb-next aria-label="Foto successiva">' + svg('M9 5l7 7-7 7') + '</button></div>';
    document.body.appendChild(dlg);
    var im = $('img', dlg), cnt = $('.lightbox__count', dlg), i = 0, opener = null;
    var name = ($('.stay__title') || {}).textContent || '';
    function go(n) {
      i = (n + galleryData.length) % galleryData.length;
      im.src = galleryData[i];
      im.alt = name + ', foto ' + (i + 1) + ' di ' + galleryData.length;
      cnt.textContent = (i + 1) + ' / ' + galleryData.length;
      if (motion) gsap.fromTo(im, { autoAlpha: 0, scale: 0.98 }, { autoAlpha: 1, scale: 1, duration: 0.4, ease: 'power2.out' });
    }
    $$('[data-lightbox-open]').forEach(function (b) {
      b.addEventListener('click', function () { opener = b; go(+b.dataset.lightboxOpen); dlg.showModal(); });
    });
    $('[data-lb-close]', dlg).addEventListener('click', function () { dlg.close(); });
    $('[data-lb-prev]', dlg).addEventListener('click', function () { go(i - 1); });
    $('[data-lb-next]', dlg).addEventListener('click', function () { go(i + 1); });
    dlg.addEventListener('keydown', function (e) { if (e.key === 'ArrowRight') go(i + 1); if (e.key === 'ArrowLeft') go(i - 1); });
    dlg.addEventListener('click', function (e) { if (e.target === dlg || e.target.classList.contains('lightbox__stage')) dlg.close(); });
    dlg.addEventListener('close', function () { if (opener) opener.focus(); });
  })();

  function svg(d) { return '<svg class="icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' + d + '"/></svg>'; }

  // modulo di richiesta
  var req = $('[data-request]');
  if (req) (function () {
    var done = $('[data-request-done]'), slot = $('[data-request-slot]'), aside = $('[data-aside]');
    var errBox = $('[data-request-error]', req);
    linkDates(req); steppers(req);
    var started = $('[data-started]', req);
    if (started) started.value = Math.floor(Date.now() / 1000);

    // precompila dai parametri della ricerca
    var q = new URLSearchParams(location.search);
    if (q.get('arrivo') && q.get('arrivo') >= today) { req.arrivo.value = q.get('arrivo'); req.partenza.min = addDays(q.get('arrivo'), 1); }
    if (q.get('partenza') && q.get('partenza') > req.arrivo.value) req.partenza.value = q.get('partenza');
    if (q.get('ospiti')) req.adulti.value = Math.max(1, Math.min(120, parseInt(q.get('ospiti'), 10) || 2));
    $$('[data-stepper] input', req).forEach(function (i) { i.dispatchEvent(new Event('input')); });

    var nightsEl = $('[data-nights]', req);
    function showNights() {
      var a = req.arrivo.value, p = req.partenza.value;
      nightsEl.textContent = a && p && p > a ? nights(a, p) + (nights(a, p) === 1 ? ' notte' : ' notti') + ' · dal ' + short(a) + ' al ' + short(p) : '';
    }
    req.addEventListener('change', showNights); showNights();

    // form in fondo alla pagina su mobile, nella colonna su desktop
    function place() {
      if (!slot || !aside) return;
      if (mqMobile.matches) { if (req.parentNode !== slot) { slot.appendChild(req); slot.appendChild(done); } }
      else if (req.parentNode !== aside) { aside.appendChild(req); aside.appendChild(done); }
      stickyCheck();
    }
    function stickyCheck() { if (aside) aside.classList.toggle('is-sticky', !mqMobile.matches && aside.offsetHeight < innerHeight - 120); }
    mqMobile.addEventListener('change', place); addEventListener('resize', stickyCheck); place();

    // "Richiedi questa camera"
    $$('[data-pick-room]').forEach(function (b) {
      b.addEventListener('click', function () {
        var sel = req.camera, want = b.dataset.pickRoom;
        for (var k = 0; k < sel.options.length; k++) if (sel.options[k].text === want) sel.selectedIndex = k;
        req.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
        setTimeout(function () { req.arrivo.focus({ preventScroll: true }); }, reduce ? 0 : 500);
      });
    });

    function fieldError(input, msg) {
      var f = input.closest('.field') || input.closest('.consent');
      if (f && f.classList.contains('consent')) f.classList.toggle('is-invalid', !!msg);
      var slotEl = f && $('.field__err', f);
      if (slotEl) slotEl.textContent = msg;
      if (msg) input.setAttribute('aria-invalid', 'true'); else input.removeAttribute('aria-invalid');
    }
    function check(input) {
      var v = input.value.trim(), msg = '';
      if (input.name === 'arrivo' && !v) msg = 'Scegli la data di arrivo.';
      if (input.name === 'partenza') { if (!v) msg = 'Scegli la data di partenza.'; else if (req.arrivo.value && v <= req.arrivo.value) msg = 'La partenza deve essere dopo l’arrivo.'; }
      if (input.name === 'nome' && v.length < 2) msg = 'Scrivi nome e cognome.';
      if (input.name === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) msg = 'Controlla l’indirizzo email (es. nome@esempio.it).';
      if (input.name === 'privacy' && !input.checked) msg = 'Serve il consenso per inviare la richiesta.';
      fieldError(input, msg);
      return !msg;
    }
    ['arrivo', 'partenza', 'nome', 'email'].forEach(function (n) {
      req[n].addEventListener('blur', function () { if (req[n].value) check(req[n]); });
      req[n].addEventListener('input', function () { if (req[n].getAttribute('aria-invalid')) check(req[n]); });
    });
    req.privacy.addEventListener('change', function () { check(req.privacy); });

    req.addEventListener('submit', function (e) {
      e.preventDefault();
      var fields = [req.arrivo, req.partenza, req.nome, req.email, req.privacy];
      var bad = fields.filter(function (f) { return !check(f); });
      if (bad.length) {
        errBox.textContent = bad.length === 1 ? 'Manca un’informazione: la trovi evidenziata qui sopra.' : 'Mancano ' + bad.length + ' informazioni: le trovi evidenziate qui sopra.';
        errBox.hidden = false; bad[0].focus(); return;
      }
      errBox.hidden = true;
      var btn = $('.request__send', req);
      btn.disabled = true;
      var spin = setTimeout(function () { btn.classList.add('is-loading'); }, 150);
      function stop() { clearTimeout(spin); btn.classList.remove('is-loading'); btn.disabled = false; }
      function riepilogo(conCasa) {
        var casa = ($('.stay__title') || {}).textContent || 'la casa';
        var a = req.arrivo.value, p = req.partenza.value, ad = +req.adulti.value, bb = +req.bambini.value;
        var chi = ad + (ad === 1 ? ' adulto' : ' adulti') + (bb ? ', ' + bb + (bb === 1 ? ' bambino' : ' bambini') : '');
        var camera = req.camera.value || 'camera da concordare';
        return (conCasa ? casa + ' · dal ' : 'Dal ') + short(a) + ' al ' + short(p) + ' (' + nights(a, p) + (nights(a, p) === 1 ? ' notte' : ' notti') + ') · ' + chi + ' · ' + camera + ' · ' + req.tipo.value + '.';
      }
      function mostraConferma(testo) {
        $('[data-request-summary]', done).textContent = testo;
        req.hidden = true; done.hidden = false; done.focus();
        if (motion) gsap.fromTo(done, { autoAlpha: 0, y: 12 }, { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power3.out' });
      }

      // Sito WordPress: il plugin fma-richieste manda la richiesta all'email della struttura.
      if (req.dataset.endpoint) {
        var body = new FormData(req);
        body.append('ajax', '1');
        fetch(req.dataset.endpoint, { method: 'POST', body: body, credentials: 'same-origin' })
          .then(function (r) { return r.json(); })
          .then(function (esito) {
            stop();
            if (esito.ok) { mostraConferma(esito.messaggio + ' ' + riepilogo(false)); return; }
            var campi = esito.campi || {}, primo = null;
            Object.keys(campi).forEach(function (n) { if (req[n]) { fieldError(req[n], campi[n]); primo = primo || req[n]; } });
            errBox.textContent = esito.messaggio; errBox.hidden = false;
            if (primo) primo.focus();
          })
          .catch(function () {
            stop();
            var to = $('.request__to strong', req);
            errBox.textContent = 'Connessione non riuscita: la richiesta non è partita.' + (to ? ' Puoi scrivere direttamente a ' + to.textContent + '.' : '');
            errBox.hidden = false;
          });
        return;
      }

      // Demo statica: nessun invio reale.
      setTimeout(function () { stop(); mostraConferma(riepilogo(true)); }, 900);
    });
    $('[data-request-again]').addEventListener('click', function () { done.hidden = true; req.hidden = false; req.arrivo.focus(); });

    // barra in basso su mobile
    var bottom = $('[data-bottom-bar]'), gallery = $('.gallery');
    if (bottom && gallery && slot) {
      bottom.hidden = false;
      var pastGallery = false, formSeen = false;
      function upd() { var on = mqMobile.matches && pastGallery && !formSeen; bottom.classList.toggle('is-on', on); bottom.inert = !on; }
      new IntersectionObserver(function (en) { pastGallery = !en[0].isIntersecting && en[0].boundingClientRect.top < 0; upd(); }).observe(gallery);
      new IntersectionObserver(function (en) { formSeen = en.some(function (x) { return x.isIntersecting; }); upd(); }, { rootMargin: '0px 0px -20% 0px' }).observe(slot);
      mqMobile.addEventListener('change', upd);
    }
  })();

  if (window.ScrollTrigger) addEventListener('load', function () { ScrollTrigger.refresh(); });
})();

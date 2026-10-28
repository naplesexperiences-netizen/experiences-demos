#!/usr/bin/env node
/*
 * Controllo contrasto WCAG AA su una pagina reale, prima e dopo i CSS del design system.
 *
 *   node design-system/naplesexperiences/check-contrast.js [url] [--inject] [--mobile] [--shot out.png] [--json out.json] [-v]
 *
 * Senza --inject misura la pagina com'è; con --inject aggiunge tokens.css,
 * components.css e compat.css in coda al <head>. Per ogni elemento con testo
 * diretto calcola lo sfondo effettivo risalendo gli antenati (per i gradienti
 * usa il colore più chiaro, il caso peggiore per testo chiaro; per immagini di
 * sfondo segnala "image" e salta).
 */
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const args = process.argv.slice(2);
const url = args.find(a => /^(https?|file):/.test(a)) || 'https://naplesexperiences.com/';
const inject = args.includes('--inject');
const jsonOut = args.includes('--json') ? args[args.indexOf('--json') + 1] : null;
const shotOut = args.includes('--shot') ? args[args.indexOf('--shot') + 1] : null;
const viewport = args.includes('--mobile') ? { width: 390, height: 844 } : { width: 1280, height: 900 };
const dir = __dirname;

(async () => {
  const proxy = process.env.HTTPS_PROXY ? { server: process.env.HTTPS_PROXY } : undefined;
  const browser = await chromium.launch({ proxy });
  const page = await browser.newPage({ viewport, ignoreHTTPSErrors: true });
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  if (inject) {
    for (const f of ['tokens.css', 'components.css', 'compat.css']) {
      await page.addStyleTag({ content: fs.readFileSync(path.join(dir, f), 'utf8') });
    }
  }
  // Rivela gli elementi animati da AOS, altrimenti risultano a opacità 0
  await page.addStyleTag({ content: '[data-aos]{opacity:1!important;transform:none!important}' });
  await page.waitForTimeout(300);

  const results = await page.evaluate(() => {
    const parse = s => {
      const m = s.match(/rgba?\(([^)]+)\)/);
      if (!m) return null;
      const p = m[1].split(/[\s,\/]+/).filter(Boolean).map(Number);
      return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
    };
    const lin = c => { c /= 255; return c <= 0.03928 ? c / 12.92 : ((c + 0.055) / 1.055) ** 2.4; };
    const lum = c => 0.2126 * lin(c.r) + 0.7152 * lin(c.g) + 0.0722 * lin(c.b);
    const blend = (top, bot) => ({
      r: top.r * top.a + bot.r * (1 - top.a),
      g: top.g * top.a + bot.g * (1 - top.a),
      b: top.b * top.a + bot.b * (1 - top.a), a: 1,
    });
    const hex = c => '#' + [c.r, c.g, c.b].map(v => Math.round(v).toString(16).padStart(2, '0')).join('');

    function background(el) {
      const layers = [];
      for (let n = el; n; n = n.parentElement) {
        const cs = getComputedStyle(n);
        const bi = cs.backgroundImage;
        if (bi && bi !== 'none') {
          if (/url\(/.test(bi) && !/gradient/.test(bi)) return { kind: 'image' };
          const stops = (bi.match(/rgba?\([^)]+\)/g) || []).map(parse).filter(Boolean);
          if (stops.length) {
            // caso peggiore: lo stop più chiaro se il testo è chiaro, il più scuro altrimenti
            layers.push({ gradient: stops });
            if (stops.every(s => s.a === 1)) break;
          }
        }
        const bc = parse(cs.backgroundColor);
        if (bc && bc.a > 0) { layers.push(bc); if (bc.a === 1) break; }
      }
      return { kind: 'color', layers };
    }

    const out = [];
    const seen = new Set();
    for (const el of document.querySelectorAll('body *')) {
      if (el.closest('svg, script, style, noscript, [aria-hidden="true"], #cookieadmin_wrapper')) continue;
      const text = [...el.childNodes].filter(n => n.nodeType === 3).map(n => n.textContent).join('').trim();
      if (!text) continue;
      const cs = getComputedStyle(el);
      // testo sfumato (background-clip: text): il colore è lo sfondo stesso, non misurabile così
      if (cs.webkitBackgroundClip === 'text' || cs.backgroundClip === 'text') continue;
      if (cs.visibility === 'hidden' || cs.display === 'none' || +cs.opacity === 0) continue;
      const rect = el.getBoundingClientRect();
      if (rect.width === 0 || rect.height === 0) continue;
      const fg = parse(cs.color);
      if (!fg || fg.a === 0) continue;   // testo nascosto di proposito (es. pulsante in caricamento)
      const bg = background(el);
      if (bg.kind === 'image') continue;
      let base = { r: 255, g: 255, b: 255, a: 1 };
      const fgL = lum(fg.a < 1 ? fg : fg);
      for (const layer of bg.layers.reverse()) {
        if (layer.gradient) {
          const pick = layer.gradient.map(s => blend(s, base))
            .sort((a, b) => fgL > 0.5 ? lum(a) - lum(b) : lum(b) - lum(a))[layer.gradient.length - 1];
          base = pick;
        } else base = blend(layer, base);
      }
      const f = fg.a < 1 ? blend(fg, base) : fg;
      const L1 = lum(f), L2 = lum(base);
      const ratio = (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
      const size = parseFloat(cs.fontSize);
      const weight = parseInt(cs.fontWeight, 10);
      const large = size >= 24 || (size >= 18.66 && weight >= 700);
      const need = large ? 3 : 4.5;
      const key = `${hex(f)}|${hex(base)}|${el.className}|${text.slice(0, 30)}`;
      if (seen.has(key)) continue;
      seen.add(key);
      out.push({
        ratio: Math.round(ratio * 100) / 100, need, pass: ratio >= need,
        fg: hex(f), bg: hex(base), size, weight,
        cls: typeof el.className === 'string' ? el.className.slice(0, 90) : '',
        text: text.slice(0, 50),
      });
    }
    return out;
  });

  if (shotOut) await page.screenshot({ path: shotOut, fullPage: true });
  await browser.close();
  const fails = results.filter(r => !r.pass).sort((a, b) => a.ratio - b.ratio);
  const pairs = {};
  for (const f of fails) { const k = `${f.fg} su ${f.bg}`; pairs[k] = (pairs[k] || 0) + 1; }
  console.log(`${url} ${inject ? '(con design system)' : '(com\'è)'} — ${viewport.width}px`);
  console.log(`testi controllati: ${results.length} — sotto soglia AA: ${fails.length}`);
  for (const [k, n] of Object.entries(pairs).sort((a, b) => b[1] - a[1])) console.log(`  ${String(n).padStart(3)}× ${k}`);
  if (args.includes('-v')) for (const f of fails) console.log(`  ${f.ratio}/${f.need} ${f.fg}/${f.bg} ${f.size}px "${f.text}" .${f.cls}`);
  if (jsonOut) fs.writeFileSync(jsonOut, JSON.stringify({ url, inject, results }, null, 2));
})().catch(e => { console.error(e); process.exit(1); });

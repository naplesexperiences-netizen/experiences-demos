// Comparsa allo scroll e parallasse leggero sulle foto. Senza JS o con "riduci movimento" la pagina resta statica.
(function () {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const items = document.querySelectorAll('.reveal');

  if (reduce || !('IntersectionObserver' in window)) {
    items.forEach((el) => el.classList.add('in'));
    return;
  }

  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });
  items.forEach((el) => io.observe(el));

  const layers = [...document.querySelectorAll('[data-parallax]')];
  let ticking = false;
  function update() {
    const vh = window.innerHeight;
    layers.forEach((el) => {
      const r = el.parentElement.getBoundingClientRect();
      if (r.bottom < 0 || r.top > vh) return;
      const progress = (r.top + r.height / 2 - vh / 2) / vh; // -1..1 attorno al centro dello schermo
      el.style.transform = `translate3d(0, ${(-progress * parseFloat(el.dataset.parallax) * 400).toFixed(1)}px, 0)`;
    });
    ticking = false;
  }
  window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
  window.addEventListener('resize', update);
  update();
})();

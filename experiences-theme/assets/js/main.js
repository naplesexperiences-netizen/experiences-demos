/* Experiences Srl – Main Theme JS
   Version: 1.0.1 — guard difensivi su elementi DOM opzionali
   (un null su typing-text / particles-container / header bloccava
    l'intero file e il binding del contact form)
*/


        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, easing: 'ease-out-cubic', once: true, offset: 50 });
        }

        // ── WP AJAX Contact Form ─────────────────────────────────────────
        // Bound presto in modo che un errore in qualsiasi blocco successivo
        // non comprometta l'invio del form. Richiede che experiencesAjax
        // (url + nonce) sia localizzato da PHP via wp_localize_script().
        (function bindContactForm() {
            const cf = document.getElementById('contact-form');
            if (!cf) return;

            const btn = cf.querySelector('button[type="submit"]');
            const fs  = document.getElementById('form-status');
            const btnOriginal = btn ? btn.innerHTML : '';

            const setStatus = (msg, ok) => {
                if (!fs) return;
                fs.textContent = msg;
                fs.className = 'text-center text-sm font-medium mt-4 ' + (ok ? 'text-emerald-600' : 'text-red-600');
                fs.classList.remove('hidden');
                clearTimeout(fs._t);
                fs._t = setTimeout(() => fs.classList.add('hidden'), 8000);
            };

            const resetBtn = () => {
                if (!btn) return;
                btn.disabled = false;
                btn.innerHTML = btnOriginal || '<span>Invia Richiesta</span><i class="fas fa-paper-plane"></i>';
            };

            cf.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!cf.checkValidity()) {
                    cf.reportValidity();
                    return;
                }

                const ajaxCfg = (typeof experiencesAjax !== 'undefined') ? experiencesAjax : null;
                if (!ajaxCfg || !ajaxCfg.url || !ajaxCfg.nonce) {
                    setStatus('Configurazione AJAX non disponibile. Ricarica la pagina o contattaci su WhatsApp.', false);
                    return;
                }

                const fd = new FormData(this);
                fd.append('action', 'experiences_contact');
                fd.append('nonce',  ajaxCfg.nonce);

                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Invio in corso...';
                }

                fetch(ajaxCfg.url, {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin'
                })
                    .then(r => r.json().catch(() => ({ success: false, data: { message: 'Risposta non valida dal server.' } })))
                    .then(data => {
                        const msg = (data && data.data && data.data.message) ? data.data.message
                                  : (data && data.success ? 'Messaggio inviato!' : "Errore nell'invio. Riprova.");
                        setStatus(msg, !!(data && data.success));
                        if (data && data.success) { cf.reset(); }
                        resetBtn();
                    })
                    .catch(() => {
                        setStatus('Errore di rete. Contattaci su WhatsApp.', false);
                        resetBtn();
                    });
            });
        })();

        // ── Mobile Menu ──────────────────────────────────────────────────
        (function bindMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu    = document.getElementById('mobile-menu');
            const closeMenu     = document.getElementById('close-menu');
            const menuOverlay   = document.getElementById('menu-overlay');
            const mobileLinks   = document.querySelectorAll('.mobile-link');

            if (!mobileMenuBtn || !mobileMenu) return;

            const openMenu = () => {
                mobileMenu.classList.add('active');
                if (menuOverlay) menuOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };
            const closeMenuFn = () => {
                mobileMenu.classList.remove('active');
                if (menuOverlay) menuOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            };

            mobileMenuBtn.addEventListener('click', openMenu);
            if (closeMenu)   closeMenu.addEventListener('click', closeMenuFn);
            if (menuOverlay) menuOverlay.addEventListener('click', closeMenuFn);
            mobileLinks.forEach(l => l.addEventListener('click', closeMenuFn));
        })();

        // ── Header scroll shadow ─────────────────────────────────────────
        (function bindHeaderScroll() {
            const header = document.getElementById('header');
            if (!header) return;
            window.addEventListener('scroll', () => {
                header.classList.toggle('shadow-md', window.scrollY > 50);
            });
        })();

        // ── Typing animation ─────────────────────────────────────────────
        (function bindTypingAnimation() {
            const typingText = document.getElementById('typing-text');
            if (!typingText) return;

            const questions = [
                "Quanti clienti ti trovano online?",
                "Il tuo sito è ottimizzato per mobile?",
                "Le tue prenotazioni sono sincronizzate?",
                "Hai un assistente virtuale AI?",
                "Le tue OTA sono ottimizzate?"
            ];
            let qi = 0, ci = 0, del = false;

            function typeEffect() {
                const q = questions[qi];
                if (del) { typingText.textContent = q.substring(0, ci - 1); ci--; }
                else     { typingText.textContent = q.substring(0, ci + 1); ci++; }
                let speed = del ? 30 : 60;
                if (!del && ci === q.length) { speed = 2000; del = true; }
                else if (del && ci === 0)    { del = false; qi = (qi + 1) % questions.length; speed = 500; }
                setTimeout(typeEffect, speed);
            }
            typeEffect();
        })();

        // ── Stats counter ────────────────────────────────────────────────
        (function bindStatsCounter() {
            const counters = document.querySelectorAll('.stats-counter');
            if (!counters.length || typeof IntersectionObserver === 'undefined') return;

            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-target'), 10) || 0;
                    const suffix = el.getAttribute('data-suffix') || '';
                    let cur = 0;
                    const inc = target / 60;
                    const timer = setInterval(() => {
                        cur += inc;
                        if (cur >= target) { el.textContent = target + suffix; clearInterval(timer); }
                        else               { el.textContent = Math.floor(cur); }
                    }, 30);
                    statsObserver.unobserve(el);
                });
            }, { threshold: 0.5 });

            counters.forEach(el => statsObserver.observe(el));
        })();

        // ── Particles ────────────────────────────────────────────────────
        (function bindParticles() {
            const pc = document.getElementById('particles-container');
            if (!pc) return;

            for (let i = 0; i < 35; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                const size  = Math.random() * 4 + 1;
                const x     = Math.random() * 100;
                const drift = (Math.random() - 0.5) * 100;
                const dur   = Math.random() * 15 + 10;
                const delay = Math.random() * 10;
                p.style.cssText = `width:${size}px;height:${size}px;left:${x}%;bottom:-10px;--drift:${drift}px;background:rgba(20,163,163,${Math.random()*0.4+0.1});animation:particle-float ${dur}s ease-in ${delay}s infinite;`;
                pc.appendChild(p);
            }
        })();

        // ── Smooth scroll ────────────────────────────────────────────────
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') return;
                const t = document.querySelector(href);
                if (!t) return;
                e.preventDefault();
                window.scrollTo({ top: t.getBoundingClientRect().top + window.pageYOffset - 80, behavior: 'smooth' });
            });
        });

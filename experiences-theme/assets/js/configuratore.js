/**
 * Configuratore anteprima — Experiences Srl
 *
 * Le fotografie non lasciano il dispositivo: si leggono con
 * URL.createObjectURL e restano nella memoria della scheda. Al modulo
 * finale viaggiano solo le scelte e i contatti.
 *
 * L'invio passa dall'azione experiences_contact del tema, cosi eredita
 * il recupero del nonce quando la pagina arriva dalla cache.
 */
(function () {
    'use strict';

    var radice = document.querySelector('.expcfg');
    if (!radice) return;

    var MAX_FOTO  = 6;
    var MIN_FOTO  = 3;
    var MAX_BYTE  = 10 * 1024 * 1024;
    var TIPI_OK   = ['image/jpeg', 'image/png', 'image/webp'];

    var stato = { tipo: '', nome: '', citta: '', foto: [], stile: 'classico' };

    var el = {
        scelte:     radice.querySelectorAll('.expcfg-scelta'),
        nome:       document.getElementById('expcfg-nome'),
        citta:      document.getElementById('expcfg-citta'),
        file:       document.getElementById('expcfg-file'),
        dropzone:   document.getElementById('expcfg-dropzone'),
        errore:     document.getElementById('expcfg-errore'),
        miniature:  document.getElementById('expcfg-miniature'),
        vuoto:      document.getElementById('expcfg-vuoto'),
        risultato:  document.getElementById('expcfg-risultato'),
        chiusura:   document.getElementById('expcfg-chiusura'),
        tela:       document.getElementById('expcfg-tela'),
        url:        document.getElementById('expcfg-url'),
        stili:      radice.querySelectorAll('.expcfg-stile'),
        form:       document.getElementById('expcfg-form'),
        invia:      document.getElementById('expcfg-invia'),
        esito:      document.getElementById('expcfg-esito')
    };

    /* ── Testi per tipo di attività ─────────────────────────────── */
    var TESTI = {
        hotel: {
            occhiello: 'Hotel · {citta}',
            sub:       'Camere vista mare, colazione inclusa, a due passi dal centro.',
            cta:       'Verifica disponibilità',
            sezione:   'Le camere',
            schede:    [
                { t: 'Camera Vista Mare', s: 'da 120 € a notte' },
                { t: 'Suite Panoramica',  s: 'da 190 € a notte' },
                { t: 'Camera Giardino',   s: 'da 95 € a notte' },
                { t: 'Camera Familiare',  s: 'da 150 € a notte' },
                { t: 'Doppia Classic',    s: 'da 110 € a notte' }
            ]
        },
        tour: {
            occhiello: 'Esperienze · {citta}',
            sub:       'Escursioni in piccoli gruppi, guide locali, partenze ogni giorno.',
            cta:       'Prenota l’esperienza',
            sezione:   'Le esperienze',
            schede:    [
                { t: 'Tour del centro storico', s: '3 ore · da 35 €' },
                { t: 'Escursione in barca',     s: 'giornata intera · da 85 €' },
                { t: 'Degustazione guidata',    s: '2 ore · da 45 €' },
                { t: 'Trekking al tramonto',    s: '4 ore · da 40 €' },
                { t: 'Tour privato',            s: 'su misura · da 120 €' }
            ]
        },
        altro: {
            occhiello: '{citta}',
            sub:       'Qualità, accoglienza e attenzione ai dettagli, tutti i giorni.',
            cta:       'Contattaci',
            sezione:   'I nostri spazi',
            schede:    [
                { t: 'La sala',        s: 'fino a 60 coperti' },
                { t: 'La terrazza',    s: 'aperta da aprile' },
                { t: 'Il dehors',      s: 'vista sulla piazza' },
                { t: 'La cantina',     s: 'oltre 120 etichette' },
                { t: 'Eventi privati', s: 'su prenotazione' }
            ]
        }
    };

    /* ── Utilità ────────────────────────────────────────────────── */
    function esc(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function mostraErrore(msg) {
        if (!msg) { el.errore.hidden = true; el.errore.textContent = ''; return; }
        el.errore.textContent = msg;
        el.errore.hidden = false;
    }

    function slug(s) {
        return String(s).toLowerCase()
            .normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9]+/g, '').slice(0, 28);
    }

    /* ── Passo 1 ────────────────────────────────────────────────── */
    el.scelte.forEach(function (b) {
        b.addEventListener('click', function () {
            stato.tipo = b.dataset.tipo;
            el.scelte.forEach(function (x) {
                x.setAttribute('aria-checked', String(x === b));
            });
            disegna();
        });
    });

    /* ── Passo 2 ────────────────────────────────────────────────── */
    ['nome', 'citta'].forEach(function (k) {
        el[k].addEventListener('input', function () {
            stato[k] = el[k].value.trim();
            disegna();
        });
    });

    /* ── Passo 3: fotografie ────────────────────────────────────── */
    function aggiungiFile(lista) {
        mostraErrore('');
        var scartate = [];

        Array.prototype.forEach.call(lista, function (f) {
            if (stato.foto.length >= MAX_FOTO) { scartate.push(f.name + ' (oltre le ' + MAX_FOTO + ')'); return; }
            if (TIPI_OK.indexOf(f.type) === -1) { scartate.push(f.name + ' (formato non supportato)'); return; }
            if (f.size > MAX_BYTE) { scartate.push(f.name + ' (oltre 10 MB)'); return; }
            stato.foto.push({ file: f, url: URL.createObjectURL(f) });
        });

        if (scartate.length) {
            mostraErrore('Non ho potuto usare: ' + scartate.join(', ') + '.');
        }
        disegnaMiniature();
        disegna();
    }

    function togliFoto(i) {
        // Senza revoke l'immagine resta in memoria finche la scheda e aperta.
        URL.revokeObjectURL(stato.foto[i].url);
        stato.foto.splice(i, 1);
        mostraErrore('');
        disegnaMiniature();
        disegna();
    }

    function disegnaMiniature() {
        el.miniature.textContent = '';
        stato.foto.forEach(function (f, i) {
            var li = document.createElement('li');
            li.className = 'expcfg-min';

            var img = document.createElement('img');
            img.src = f.url;
            img.alt = 'Fotografia ' + (i + 1);
            li.appendChild(img);

            if (i === 0) {
                var tag = document.createElement('span');
                tag.className = 'expcfg-min-prima';
                tag.textContent = 'IN CIMA';
                li.appendChild(tag);
            }

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'expcfg-min-togli';
            btn.setAttribute('aria-label', 'Togli la fotografia ' + (i + 1));
            btn.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i>';
            btn.addEventListener('click', function () { togliFoto(i); });
            li.appendChild(btn);

            el.miniature.appendChild(li);
        });
    }

    el.file.addEventListener('change', function () {
        aggiungiFile(el.file.files);
        el.file.value = '';   // permette di ricaricare lo stesso file dopo averlo tolto
    });

    ['dragenter', 'dragover'].forEach(function (ev) {
        el.dropzone.addEventListener(ev, function (e) {
            e.preventDefault();
            el.dropzone.classList.add('is-sopra');
        });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        el.dropzone.addEventListener(ev, function (e) {
            e.preventDefault();
            el.dropzone.classList.remove('is-sopra');
        });
    });
    el.dropzone.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files) aggiungiFile(e.dataTransfer.files);
    });

    /* ── Stili ──────────────────────────────────────────────────── */
    el.stili.forEach(function (b) {
        b.addEventListener('click', function () {
            stato.stile = b.dataset.stile;
            el.stili.forEach(function (x) { x.setAttribute('aria-checked', String(x === b)); });
            el.tela.dataset.stile = stato.stile;
        });
    });

    /* ── Anteprima ──────────────────────────────────────────────── */
    function pronto() {
        return stato.tipo && stato.nome && stato.foto.length >= MIN_FOTO;
    }

    function disegna() {
        if (!pronto()) {
            el.risultato.hidden = true;
            el.chiusura.hidden  = true;
            el.vuoto.hidden     = false;
            el.vuoto.querySelector('p').textContent = manca();
            return;
        }

        el.vuoto.hidden     = true;
        el.risultato.hidden = false;
        el.chiusura.hidden  = false;

        var t     = TESTI[stato.tipo];
        var citta = stato.citta || 'Italia';
        var altre = stato.foto.slice(1);

        el.url.textContent = 'www.' + (slug(stato.nome) || 'latuastruttura') + '.it';

        var schede = altre.map(function (f, i) {
            var s = t.schede[i % t.schede.length];
            return '<div class="cfg-card">'
                 +   '<img src="' + f.url + '" alt="">'
                 +   '<div class="cfg-card-corpo">'
                 +     '<div class="cfg-card-tit">' + esc(s.t) + '</div>'
                 +     '<div class="cfg-card-sub">' + esc(s.s) + '</div>'
                 +   '</div>'
                 + '</div>';
        }).join('');

        el.tela.innerHTML =
            '<div class="cfg-hero">'
          +   '<img src="' + stato.foto[0].url + '" alt="">'
          +   '<div class="cfg-hero-testo">'
          +     '<div class="cfg-occhiello">' + esc(t.occhiello.replace('{citta}', citta)) + '</div>'
          +     '<div class="cfg-h1">' + esc(stato.nome) + '</div>'
          +     '<div class="cfg-sub">' + esc(t.sub) + '</div>'
          +     '<span class="cfg-cta">' + esc(t.cta) + '</span>'
          +   '</div>'
          + '</div>'
          + (schede
              ? '<div class="cfg-sezione">'
              +   '<div class="cfg-sez-tit">' + esc(t.sezione) + '</div>'
              +   '<div class="cfg-griglia">' + schede + '</div>'
              + '</div>'
              : '');
    }

    function manca() {
        var m = [];
        if (!stato.tipo) m.push('scegli il tipo di attività');
        if (!stato.nome) m.push('scrivi il nome');
        if (stato.foto.length < MIN_FOTO) {
            m.push('carica almeno ' + MIN_FOTO + ' fotografie (ne hai ' + stato.foto.length + ')');
        }
        return 'Per vedere l’anteprima: ' + m.join(', ') + '.';
    }

    /* ── Invio ──────────────────────────────────────────────────── */
    function esito(msg, ok) {
        el.esito.textContent = msg;
        el.esito.className = 'expcfg-esito ' + (ok ? 'ok' : 'ko');
        el.esito.hidden = false;
    }

    if (el.form) {
        el.form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!el.form.checkValidity()) { el.form.reportValidity(); return; }

            var cfg = (typeof experiencesAjax !== 'undefined') ? experiencesAjax : null;
            if (!cfg || !cfg.url || !cfg.nonce) {
                esito('Configurazione non disponibile. Ricarica la pagina o scrivici su WhatsApp.', false);
                return;
            }

            var etichetta = { hotel: 'Hotel o B&B', tour: 'Tour ed esperienze', altro: 'Altra attività' }[stato.tipo] || stato.tipo;
            var stili = { classico: 'Classico', editoriale: 'Editoriale', scuro: 'Scuro' };

            var messaggio =
                'Richiesta dal configuratore anteprima.\n\n' +
                'Struttura:  ' + stato.nome + '\n' +
                'Località:   ' + (stato.citta || '—') + '\n' +
                'Tipo:       ' + etichetta + '\n' +
                'Stile:      ' + (stili[stato.stile] || stato.stile) + '\n' +
                'Fotografie: ' + stato.foto.length + ' caricate nell’anteprima ' +
                '(restano sul dispositivo del cliente, vanno richieste via email)';

            var fd = new FormData();
            fd.append('action', 'experiences_contact');
            fd.append('nome',    el.form.nome.value.trim());
            fd.append('cognome', el.form.cognome.value.trim());
            fd.append('email',   el.form.email.value.trim());
            fd.append('tipo_attivita', etichetta);
            fd.append('piano', 'Da definire — configuratore');
            fd.append('messaggio', messaggio);
            fd.append('website', el.form.website.value);

            el.invia.disabled = true;
            el.esito.hidden = true;

            var spedisci = function (nonce) {
                var copia = new FormData();
                fd.forEach(function (v, k) { copia.append(k, v); });
                copia.append('nonce', nonce);
                return fetch(cfg.url, { method: 'POST', body: copia, credentials: 'same-origin' })
                    .then(function (r) {
                        return r.json().catch(function () { return null; })
                            .then(function (b) { return { status: r.status, body: b }; });
                    });
            };

            // Stessa trappola del modulo in homepage: il nonce e dentro
            // l'HTML e l'HTML sta in cache piu a lungo di quanto un nonce viva.
            var scaduto = function (res) {
                return res.status === 403 || res.body === -1 || res.body === 0 || res.body === null;
            };
            var fresco = function () {
                if (!cfg.rest) return Promise.resolve(null);
                return fetch(cfg.rest, { credentials: 'same-origin', cache: 'no-store' })
                    .then(function (r) { return r.ok ? r.json() : null; })
                    .then(function (d) { if (d && d.contact) { cfg.nonce = d.contact; return d.contact; } return null; })
                    .catch(function () { return null; });
            };

            spedisci(cfg.nonce)
                .then(function (res) {
                    if (!scaduto(res)) return res;
                    return fresco().then(function (n) { return n ? spedisci(n) : res; });
                })
                .then(function (res) {
                    var d  = res.body;
                    var ok = !!(d && d.success);
                    esito(
                        (d && d.data && d.data.message) ? d.data.message
                            : (ok ? 'Richiesta inviata! Ti scriviamo presto.'
                                  : 'Errore nell’invio. Riprova o scrivici su WhatsApp.'),
                        ok
                    );
                    if (ok) el.form.reset();
                    el.invia.disabled = false;
                })
                .catch(function () {
                    esito('Errore di rete. Scrivici su WhatsApp.', false);
                    el.invia.disabled = false;
                });
        });
    }

    disegna();
})();

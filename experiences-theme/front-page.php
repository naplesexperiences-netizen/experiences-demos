<?php
/**
 * Front Page Template (Homepage)
 * @package experiences-srl
 */
get_header();
?>

<?php /* Navbar in template-parts/site-header.php (via get_header) */ ?>

    <!-- Section 1: Hero -->
    <section id="home" class="hero-gradient relative min-h-screen flex items-center pt-16 overflow-hidden">
        
        <!-- Grid overlay -->
        <div class="absolute inset-0 hero-grid pointer-events-none"></div>

        <!-- Aurora blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="aurora-1 absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-accent/20 rounded-full blur-[80px]"></div>
            <div class="aurora-2 absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-secondary/20 rounded-full blur-[60px]"></div>
            <div class="aurora-3 absolute top-1/2 left-1/2 w-[300px] h-[300px] bg-primary/30 rounded-full blur-[100px]"></div>
        </div>

        <!-- Particles container -->
        <div id="particles-container" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- Left Content -->
                <div class="text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent/10 border border-accent/20 rounded-full mb-6">
                        <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                        <span class="text-accent text-sm font-medium">Soluzioni per Hotel, Agenzie & Tour Operator</span>
                    </div>
                    
                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Recupera il <span class="text-gradient">50% delle prenotazioni</span>
                        <span class="block mt-2">che stai perdendo oggi</span>
                    </h1>

                    <p class="text-gray-300 text-lg lg:text-xl mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Mentre tu gestisci <strong class="text-white">5 piattaforme manualmente</strong>, i tuoi competitor incassano prenotazioni dirette 24/7.
                        <span class="text-accent font-medium block mt-2">Channel Manager + Sito Moderno + AI = più prenotazioni, meno lavoro.</span>
                    </p>

                    <!-- Verticale Selector -->
                    <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 mb-8 max-w-lg mx-auto lg:mx-0">
                        <p class="text-white/70 text-sm mb-3 font-medium">Per chi vuoi vedere la soluzione?</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="#services" class="verticale-btn px-4 py-2 bg-accent/20 hover:bg-accent text-white text-sm font-semibold rounded-lg border border-accent/30 hover:border-accent transition-all flex items-center gap-2">
                                <i class="fas fa-hotel text-xs"></i> Hotel & B&B
                            </a>
                            <a href="#services" class="verticale-btn px-4 py-2 bg-secondary/20 hover:bg-secondary text-white text-sm font-semibold rounded-lg border border-secondary/30 hover:border-secondary transition-all flex items-center gap-2">
                                <i class="fas fa-suitcase text-xs"></i> Agenzie Viaggi
                            </a>
                            <a href="#services" class="verticale-btn px-4 py-2 bg-primary/40 hover:bg-primary text-white text-sm font-semibold rounded-lg border border-white/20 hover:border-white/40 transition-all flex items-center gap-2">
                                <i class="fas fa-map-marked-alt text-xs"></i> Tour Operator
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#booking" data-booking-trigger class="shimmer-btn px-8 py-4 text-white font-semibold rounded-xl shadow-lg hover:shadow-accent/25 hover:-translate-y-1 transition-transform flex items-center justify-center gap-2">
                            <span>Prenota Audit Gratuito</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="https://wa.me/393926917657" class="px-8 py-4 border border-white/20 text-white hover:bg-white/10 font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp text-xl text-green-400"></i>
                            <span>Parla con noi</span>
                        </a>
                    </div>
                    
                    <!-- Trust signals -->
                    <div class="mt-6 flex items-center gap-4 text-xs text-white/60 justify-center lg:justify-start">
                        <span class="flex items-center gap-1"><i class="fas fa-check-circle text-accent"></i> Consulenza gratuita</span>
                        <span class="flex items-center gap-1"><i class="fas fa-check-circle text-accent"></i> Risposta in 24h</span>
                        <span class="flex items-center gap-1"><i class="fas fa-check-circle text-accent"></i> No vincoli</span>
                    </div>
                </div>

                <!-- Right Content - Logo + orbiting elements -->
                <div class="relative flex flex-col items-center" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">

                    <!-- Central globe -->
                    <div class="relative w-72 h-72 lg:w-96 lg:h-96 mx-auto globe-container">
                        <!-- Outer ring -->
                        <div class="absolute inset-0 border-2 border-dashed border-accent/25 rounded-full rotate-slow"></div>
                        <!-- Middle ring -->
                        <div class="absolute inset-6 border border-secondary/20 rounded-full rotate-reverse"></div>
                        <!-- Inner ring -->
                        <div class="absolute inset-12 border border-accent/15 rounded-full rotate-slow" style="animation-duration:35s"></div>

                        <!-- SVG connection lines -->
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" style="opacity:0.3">
                            <circle cx="200" cy="200" r="130" fill="none" stroke="rgba(20,163,163,0.2)" stroke-width="1"/>
                            <line class="connection-line" x1="200" y1="70" x2="200" y2="200" stroke="#14A3A3" stroke-width="1.5" style="animation-delay:0s"/>
                            <line class="connection-line" x1="330" y1="200" x2="200" y2="200" stroke="#14A3A3" stroke-width="1.5" style="animation-delay:0.8s"/>
                            <line class="connection-line" x1="200" y1="330" x2="200" y2="200" stroke="#14A3A3" stroke-width="1.5" style="animation-delay:1.6s"/>
                            <line class="connection-line" x1="70" y1="200" x2="200" y2="200" stroke="#14A3A3" stroke-width="1.5" style="animation-delay:2.4s"/>
                        </svg>

                        <!-- Center logo -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="pulse-ring w-36 h-36 lg:w-44 lg:h-44 rounded-full bg-white/8 backdrop-blur-sm border border-white/10 flex items-center justify-center p-3">
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo.webp" alt="Experiences" width="282" height="300" class="w-full h-full object-contain drop-shadow-2xl" style="filter: drop-shadow(0 0 20px rgba(20,163,163,0.4))">
                            </div>
                        </div>

                        <!-- Orbiting badges -->
                        <div class="orbit-badge orbit-1">
                            <div class="-translate-x-1/2 -translate-y-1/2 bg-accent text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-globe text-xs"></i> Online
                            </div>
                        </div>
                        <div class="orbit-badge orbit-2">
                            <div class="-translate-x-1/2 -translate-y-1/2 bg-secondary text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-mobile-alt text-xs"></i> Mobile
                            </div>
                        </div>
                        <div class="orbit-badge orbit-3">
                            <div class="-translate-x-1/2 -translate-y-1/2 bg-primary border border-accent/30 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-chart-line text-xs text-accent"></i> +60%
                            </div>
                        </div>
                        <div class="orbit-badge orbit-4">
                            <div class="-translate-x-1/2 -translate-y-1/2 bg-accent/80 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg whitespace-nowrap flex items-center gap-1.5">
                                <i class="fas fa-robot text-xs"></i> AI
                            </div>
                        </div>
                    </div>

                    <!-- Stats - Focused on Client Pain Points -->
                    <div class="grid grid-cols-2 gap-4 mt-8 w-full max-w-sm">
                        <div class="stat-card rounded-xl p-4 text-center" data-aos="zoom-in" data-aos-delay="400">
                            <div class="text-2xl lg:text-3xl font-bold text-accent stat-number stats-counter" data-target="30" data-suffix="h">0</div>
                            <div class="text-xs text-gray-400 mt-1">Ore/sett. risparmiate</div>
                        </div>
                        <div class="stat-card rounded-xl p-4 text-center" data-aos="zoom-in" data-aos-delay="500">
                            <div class="text-2xl lg:text-3xl font-bold text-accent stat-number stats-counter" data-target="50" data-suffix="%">0</div>
                            <div class="text-xs text-gray-400 mt-1">Più prenotazioni dirette</div>
                        </div>
                        <div class="stat-card rounded-xl p-4 text-center" data-aos="zoom-in" data-aos-delay="600">
                            <div class="text-2xl lg:text-3xl font-bold text-accent stat-number stats-counter" data-target="0" data-suffix="">0</div>
                            <div class="text-xs text-gray-400 mt-1">Overbooking</div>
                        </div>
                        <div class="stat-card rounded-xl p-4 text-center" data-aos="zoom-in" data-aos-delay="700">
                            <div class="text-2xl lg:text-3xl font-bold text-accent stat-number stats-counter" data-target="24" data-suffix="/7">0</div>
                            <div class="text-xs text-gray-400 mt-1">Supporto AI clienti</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator - centered via absolute positioning -->
            <div class="scroll-indicator">
                <a href="#services" class="flex flex-col items-center text-white/50 hover:text-accent transition">
                    <span class="text-xs mb-2">Scorri</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Section 2: Services -->
    <section id="services" class="py-20 lg:py-28 bg-white relative">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 lg:mb-20" data-aos="fade-up">
                <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-4">SOLUZIONI CHE FUNZIONANO</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-6">Da problema a <span class="text-gradient">prenotazione</span> in 30 giorni</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto leading-relaxed">Ogni servizio risolve un problema reale che ti costa tempo e prenotazioni. Implementazione veloce, risultati misurabili.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-gray-100 shadow-sm group" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-icon w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-secondary text-2xl"><i class="fas fa-laptop-code"></i></div>
                    <h3 class="font-heading text-xl font-bold text-primary mb-3">Sito Web Moderno</h3>
                    <p class="text-red-600 text-xs font-semibold mb-2"><i class="fas fa-exclamation-triangle"></i> Problema</p>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Il tuo sito attuale è pre-2020, non funziona da mobile e perde l'<strong>89% dei visitatori</strong>.</p>
                    <p class="text-accent text-xs font-semibold mb-2"><i class="fas fa-check-circle"></i> Soluzione</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Design mobile-first (carica in &lt;2 sec)</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Booking integrato che converte</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Gestione contenuti autonoma</li>
                    </ul>
                    <p class="text-secondary text-xs font-bold border-t border-gray-100 pt-3">📈 Risultato: +60% conversione mobile</p>
                </div>
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-gray-100 shadow-sm group" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-icon w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-secondary text-2xl"><i class="fas fa-search-dollar"></i></div>
                    <h3 class="font-heading text-xl font-bold text-primary mb-3">SEO/SEM Marketing</h3>
                    <p class="text-red-600 text-xs font-semibold mb-2"><i class="fas fa-exclamation-triangle"></i> Problema</p>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">I clienti cercano "hotel Napoli" e trovano i tuoi competitor. <strong>Tu sei invisibile</strong>.</p>
                    <p class="text-accent text-xs font-semibold mb-2"><i class="fas fa-check-circle"></i> Soluzione</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> SEO locale (top 3 su Google)</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Google Ads con ROI tracking</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Social Media targeting clienti ideali</li>
                    </ul>
                    <p class="text-secondary text-xs font-bold border-t border-gray-100 pt-3">📈 Risultato: +200% traffico organico in 90gg</p>
                </div>
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border-2 border-accent shadow-lg group ring-2 ring-accent/20" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-start justify-between mb-3">
                        <div class="service-icon w-14 h-14 rounded-xl flex items-center justify-center text-secondary text-2xl"><i class="fas fa-exchange-alt"></i></div>
                        <span class="px-2 py-1 bg-accent/10 text-accent text-xs font-bold rounded-full">⭐ TOP</span>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-primary mb-3">Channel Manager</h3>
                    <p class="text-red-600 text-xs font-semibold mb-2"><i class="fas fa-exclamation-triangle"></i> Problema</p>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Gestisci Booking, Airbnb, Expedia <strong>manualmente</strong>. Rischi overbooking e perdi 30 ore/settimana.</p>
                    <p class="text-accent text-xs font-semibold mb-2"><i class="fas fa-check-circle"></i> Soluzione</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Sync real-time con tutte le OTA</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Zero overbooking garantito</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Pricing dinamico + analytics</li>
                    </ul>
                    <p class="text-secondary text-xs font-bold border-t border-gray-100 pt-3">📈 Risultato: -30h/sett, +45% diretti</p>
                </div>
                <div class="card-hover bg-white rounded-2xl p-6 lg:p-8 border border-gray-100 shadow-sm group" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-icon w-14 h-14 rounded-xl flex items-center justify-center mb-5 text-secondary text-2xl"><i class="fas fa-bullhorn"></i></div>
                    <h3 class="font-heading text-xl font-bold text-primary mb-3">Annunci OTA Ottimizzati</h3>
                    <p class="text-red-600 text-xs font-semibold mb-2"><i class="fas fa-exclamation-triangle"></i> Problema</p>
                    <p class="text-gray-600 text-sm leading-relaxed mb-3">Il tuo annuncio su Booking sembra come migliaia di altri. <strong>Conversione bassa = revenue persa</strong>.</p>
                    <p class="text-accent text-xs font-semibold mb-2"><i class="fas fa-check-circle"></i> Soluzione</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Copy persuasivo (testato A/B)</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Foto professionali + ottimizzazione SEO</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-accent text-xs"></i> Pricing dinamico per stagione</li>
                    </ul>
                    <p class="text-secondary text-xs font-bold border-t border-gray-100 pt-3">📈 Risultato: +35% conversione annunci</p>
                </div>
                <div class="card-hover bg-gradient-to-br from-primary to-secondary rounded-2xl p-6 lg:p-8 shadow-lg group sm:col-span-2 lg:col-span-2" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex flex-col">
                        <div class="flex items-start justify-between mb-5">
                            <div class="service-icon w-14 h-14 rounded-xl flex items-center justify-center bg-white/10 text-white text-2xl flex-shrink-0"><i class="fas fa-robot"></i></div>
                            <span class="px-2 py-1 bg-accent text-white text-xs font-bold rounded-full">🚀 ESCLUSIVO</span>
                        </div>
                        <h3 class="font-heading text-xl lg:text-2xl font-bold text-white mb-3">Assistente AI 24/7 per i tuoi clienti</h3>
                        <p class="text-yellow-300 text-xs font-semibold mb-2"><i class="fas fa-exclamation-triangle"></i> Problema</p>
                        <p class="text-white/80 text-sm leading-relaxed mb-3">I clienti scrivono di notte, chiedono prezzi, vogliono informazioni. <strong>Tu dormi. Loro vanno dai competitor.</strong></p>
                        <p class="text-accent text-xs font-semibold mb-2"><i class="fas fa-check-circle"></i> Soluzione</p>
                        <div class="lg:flex lg:gap-8">
                            <p class="text-white/90 text-sm leading-relaxed mb-4 lg:max-w-sm">Assistente AI configurato sul tuo business: risponde in 12 lingue, prenota direttamente, suggerisce attività locali, integra con il tuo Channel Manager.</p>
                            <div class="flex flex-wrap gap-3 lg:items-start lg:content-start">
                                <span class="px-3 py-1 bg-white/10 text-white text-xs rounded-full font-medium">⚡ Risposta in 2 sec</span>
                                <span class="px-3 py-1 bg-white/10 text-white text-xs rounded-full font-medium">🌍 12 lingue</span>
                                <span class="px-3 py-1 bg-white/10 text-white text-xs rounded-full font-medium">💰 Prenotazione diretta</span>
                                <span class="px-3 py-1 bg-white/10 text-white text-xs rounded-full font-medium">🎯 Lead capture</span>
                            </div>
                        </div>
                        <p class="text-accent text-xs font-bold border-t border-white/10 pt-3 mt-4">📈 Risultato: +40% prenotazioni notturne, 0 clienti persi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Section: Avatar AI Assistente Virtuale -->
    <section id="avatar" class="py-20 lg:py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>

        <!-- Background decoration -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/3 right-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/3 left-0 w-80 h-80 bg-secondary/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="text-center mb-14" data-aos="fade-up">
                <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-4">ASSISTENTE VIRTUALE AI</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-6">
                    Parla con il nostro <span class="text-gradient">Esperto Digitale</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto leading-relaxed">
                    Il nostro assistente AI è disponibile 24/7 per rispondere alle tue domande sui servizi, i piani tariffari e le soluzioni digitali per il turismo.
                </p>
            </div>

            <!-- Info badges -->
            <div class="flex flex-wrap justify-center gap-3 mb-10" data-aos="fade-up" data-aos-delay="100">
                <span class="px-4 py-2 bg-light text-secondary text-sm font-semibold rounded-full flex items-center gap-2">
                    <i class="fas fa-clock"></i> Disponibile 24/7
                </span>
                <span class="px-4 py-2 bg-light text-secondary text-sm font-semibold rounded-full flex items-center gap-2">
                    <i class="fas fa-language"></i> Multilingua
                </span>
                <span class="px-4 py-2 bg-light text-secondary text-sm font-semibold rounded-full flex items-center gap-2">
                    <i class="fas fa-bolt"></i> Risposta istantanea
                </span>
                <span class="px-4 py-2 bg-light text-secondary text-sm font-semibold rounded-full flex items-center gap-2">
                    <i class="fas fa-robot"></i> AI Powered
                </span>
            </div>

            <!-- LiveAvatar iframe — full width, 16:9 -->
            <div data-aos="fade-up" data-aos-delay="200"
                 style="width:100%; border-radius:1.5rem; overflow:hidden; box-shadow:0 25px 60px rgba(11,61,97,0.18); border:2px solid rgba(20,163,163,0.15);">
                <iframe
                    src="https://embed.liveavatar.com/v1/c9ba1ee0-5822-4be5-a239-ced83918726f?orientation=horizontal"
                    allow="microphone; camera"
                    title="LiveAvatar — Assistente Virtuale Experiences Srl"
                    style="width:100%; aspect-ratio:16/9; display:block; border:none;">
                </iframe>
            </div>

            <!-- CTA below avatar -->
            <div class="text-center mt-10" data-aos="fade-up" data-aos-delay="300">
                <p class="text-gray-500 text-sm mb-4">Preferisci un contatto diretto?</p>
                <a href="#booking" data-booking-trigger
                   class="inline-flex items-center gap-2 px-6 py-3 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg">
                    <i class="fas fa-rocket"></i> Prenota una call di 30 min
                </a>
            </div>

        </div>
    </section>

    <!-- Section: Case Study con Metriche Reali -->
    <section id="case-study" class="py-20 lg:py-28 bg-gradient-to-br from-primary to-dark relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/30 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1 bg-accent/20 text-accent text-sm font-semibold rounded-full mb-4">RISULTATI REALI</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Cosa abbiamo fatto per <span class="text-gradient">altri come te</span>
                </h2>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                    Numeri veri, clienti veri, risultati misurabili. Ecco l'impatto del nostro lavoro.
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Case Study 1: Hotel -->
                <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 hover:border-accent/40 transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-accent/20 flex items-center justify-center"><i class="fas fa-hotel text-accent text-xl"></i></div>
                        <div>
                            <p class="text-white font-bold">Hotel 4 stelle</p>
                            <p class="text-gray-400 text-xs">Ischia • 80 camere</p>
                        </div>
                    </div>
                    <h3 class="text-white text-lg font-bold mb-3">Da overbooking a +45% prenotazioni dirette</h3>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-accent text-2xl font-bold">+45%</p>
                            <p class="text-gray-400 text-xs">Prenotazioni dirette</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-accent text-2xl font-bold">-25h</p>
                            <p class="text-gray-400 text-xs">Settimana risparmiate</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-accent text-2xl font-bold">0</p>
                            <p class="text-gray-400 text-xs">Overbooking</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-accent text-2xl font-bold">+€50k</p>
                            <p class="text-gray-400 text-xs">Revenue/anno</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm italic">"In 6 mesi abbiamo recuperato il controllo totale. Il direttore ora si concentra sull'esperienza ospiti, non sul gestionale."</p>
                </div>

                <!-- Case Study 2: Agenzia Travel -->
                <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 hover:border-accent/40 transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-secondary/20 flex items-center justify-center"><i class="fas fa-suitcase text-secondary text-xl"></i></div>
                        <div>
                            <p class="text-white font-bold">Agenzia Tour</p>
                            <p class="text-gray-400 text-xs">Napoli • Tour Vesuvio/Pompei</p>
                        </div>
                    </div>
                    <h3 class="text-white text-lg font-bold mb-3">Da 5 a 20 prenotazioni/mese in 90 giorni</h3>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-secondary text-2xl font-bold">+300%</p>
                            <p class="text-gray-400 text-xs">Prenotazioni mensili</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-secondary text-2xl font-bold">80%</p>
                            <p class="text-gray-400 text-xs">Clienti online</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-secondary text-2xl font-bold">30%</p>
                            <p class="text-gray-400 text-xs">Clienti stranieri</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-secondary text-2xl font-bold">+€20k</p>
                            <p class="text-gray-400 text-xs">Revenue/anno</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm italic">"Il sito moderno + integrazione con GetYourGuide ha cambiato tutto. Ora ricevo prenotazioni 24/7."</p>
                </div>

                <!-- Case Study 3: Tour Operator -->
                <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-2xl p-6 hover:border-accent/40 transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-primary/20 flex items-center justify-center"><i class="fas fa-map-marked-alt text-white text-xl"></i></div>
                        <div>
                            <p class="text-white font-bold">Tour Operator</p>
                            <p class="text-gray-400 text-xs">Campania • 300+ tour</p>
                        </div>
                    </div>
                    <h3 class="text-white text-lg font-bold mb-3">Scalare da 15 a 8 dipendenti, +40% revenue</h3>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-white text-2xl font-bold">+40%</p>
                            <p class="text-gray-400 text-xs">Revenue annuale</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-white text-2xl font-bold">-60%</p>
                            <p class="text-gray-400 text-xs">Costi admin</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-white text-2xl font-bold">300+</p>
                            <p class="text-gray-400 text-xs">Tour da 1 dashboard</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3">
                            <p class="text-white text-2xl font-bold">+€300k</p>
                            <p class="text-gray-400 text-xs">Revenue/anno</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm italic">"La gestione centralizzata ci ha permesso di scalare senza assumere. Ora il team è focalizzato sulla crescita."</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-12" data-aos="fade-up">
                <p class="text-gray-300 mb-6">Vuoi sapere come possiamo aiutare il <strong class="text-white">tuo</strong> business?</p>
                <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-accent hover:bg-accent/90 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-accent/30 hover:-translate-y-1">
                    <i class="fas fa-calculator"></i> Calcola il tuo ROI in 5 minuti
                </a>
            </div>
        </div>
    </section>

    <!-- Section 3: Portfolio -->
    <section id="portfolio" class="py-20 lg:py-28 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-4">PORTFOLIO</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-6">
                    I Siti <span class="text-gradient">Live</span> che abbiamo realizzato
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Click su ogni progetto per vederlo dal vivo. Tutti i siti sono in produzione e generano prenotazioni reali.
                </p>
            </div>
            <?php echo do_shortcode('[experiences_portfolio posts="8"]'); ?>
        </div>
    </section>


    <!-- Section: Blog & Risorse -->
    <section id="blog" class="py-20 lg:py-28 bg-gray-50 relative">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14" data-aos="fade-up">
                <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-4">RISORSE GRATUITE</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-6">
                    Guide & <span class="text-gradient">Insights</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Strategie pratiche per digitalizzare il tuo business turistico. Articoli, case study e guide gratuite.
                </p>
            </div>

            <?php
            // Recupera ultimi 3 articoli del blog
            $latest_posts = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC'
            ]);
            
            if ($latest_posts->have_posts()) : ?>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post();
                        $categories = get_the_category();
                        $primary_cat = !empty($categories) ? $categories[0]->name : 'Blog';
                        $cat_slug = !empty($categories) ? $categories[0]->slug : '';
                    ?>
                        <article class="blog-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <div class="aspect-video overflow-hidden bg-gradient-to-br from-primary to-secondary relative">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500']); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-book-open text-white/30 text-6xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute top-4 left-4 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full uppercase tracking-wide">
                                        <?php echo esc_html($primary_cat); ?>
                                    </span>
                                </div>
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                    <span class="flex items-center gap-1"><i class="fas fa-calendar text-accent"></i> <?php echo get_the_date('j M Y'); ?></span>
                                    <span class="flex items-center gap-1"><i class="fas fa-clock text-accent"></i> <?php echo exp_reading_time(); ?> min</span>
                                </div>
                                <h3 class="font-heading text-xl font-bold text-primary mb-3 leading-tight group-hover:text-accent transition-colors">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </p>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 text-secondary font-semibold text-sm hover:text-accent transition-colors">
                                    Leggi articolo <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <div class="text-center mt-12" data-aos="fade-up">
                    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg">
                        Tutti gli articoli <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php else : ?>
                <!-- Placeholder se non ci sono ancora articoli -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <?php 
                    $placeholders = [
                        ['cat' => 'Channel Manager', 'icon' => 'fa-exchange-alt', 'title' => 'Come evitare overbooking su Booking + Airbnb', 'excerpt' => 'Guida pratica per sincronizzare le tue piattaforme e dire addio agli overbooking. Risparmia 30 ore/settimana.'],
                        ['cat' => 'SEO Turismo', 'icon' => 'fa-search', 'title' => 'SEO locale per hotel: arrivare al top di Google', 'excerpt' => 'Strategie testate per far apparire il tuo hotel ai primi risultati di Google in 90 giorni. Senza Google Ads.'],
                        ['cat' => 'Strategia', 'icon' => 'fa-chart-line', 'title' => 'Da 5 a 20 prenotazioni/mese: il metodo step-by-step', 'excerpt' => 'Case study reale di un\'agenzia tour di Napoli. Cosa hanno fatto, in quanto tempo, con quale budget.']
                    ];
                    foreach ($placeholders as $i => $p) : ?>
                        <article class="blog-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                            <div class="aspect-video bg-gradient-to-br from-primary to-secondary relative flex items-center justify-center">
                                <i class="fas <?php echo esc_attr($p['icon']); ?> text-white/40 text-6xl"></i>
                                <span class="absolute top-4 left-4 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full uppercase tracking-wide">
                                    <?php echo esc_html($p['cat']); ?>
                                </span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                    <span class="flex items-center gap-1"><i class="fas fa-calendar text-accent"></i> In arrivo</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-clock text-accent"></i> 5 min</span>
                                </div>
                                <h3 class="font-heading text-xl font-bold text-primary mb-3 leading-tight">
                                    <?php echo esc_html($p['title']); ?>
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    <?php echo esc_html($p['excerpt']); ?>
                                </p>
                                <span class="inline-flex items-center gap-2 text-gray-400 font-semibold text-sm">
                                    Coming soon <i class="fas fa-lock text-xs"></i>
                                </span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-12" data-aos="fade-up">
                    <p class="text-gray-500 mb-4">I primi articoli arriveranno presto. Iscriviti per non perderli.</p>
                    <a href="#contact" class="inline-flex items-center gap-2 px-8 py-4 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-envelope"></i> Iscriviti alla newsletter
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section 4: Pricing -->
    <section id="pricing" class="py-20 lg:py-28 bg-white relative">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-4">PREZZI</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-6">Paghi solo quando <span class="text-gradient">guadagni</span></h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-6">Modello unico nel mercato: <strong>commissione sulle vendite + fee annuale</strong>. Più cresci, meno paghi in fisso.</p>
                
                <!-- Pricing Explainer -->
                <div class="max-w-3xl mx-auto bg-light rounded-2xl p-6 border border-secondary/20" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-secondary text-white rounded-lg flex items-center justify-center"><i class="fas fa-info-circle"></i></div>
                        <div class="text-left">
                            <p class="text-primary font-bold mb-2">Come funziona il nostro modello pricing</p>
                            <p class="text-gray-600 text-sm leading-relaxed">Ti aiutiamo a crescere e cresciamo con te. Più vendi, meno paghi in fisso. <strong>Il piano Enterprise è gratis</strong> perché con te abbiamo dimostrato il valore. Tutti i piani includono i 4 servizi core.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="pricing-card bg-white rounded-2xl p-6 lg:p-8 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center mb-6">
                        <span class="inline-block px-3 py-1 bg-light text-secondary text-xs font-bold rounded-full mb-3">BASE</span>
                        <div class="flex items-baseline justify-center gap-1"><span class="text-4xl lg:text-5xl font-bold text-primary">€1.400</span><span class="text-gray-500 text-sm">/anno</span></div>
                        <p class="text-gray-500 text-sm mt-1">+ IVA · fino a 1.000 clienti</p>
                    </div>
                    <div class="border-t border-gray-100 pt-6 mb-6"><div class="text-center mb-4"><span class="text-2xl font-bold text-accent">+10%</span><span class="text-gray-500 text-sm block">commissione sulle vendite</span></div></div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Sviluppo sito web e gestione</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>SEO/SEM marketing</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Gestione Channel Manager</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Creazione e gestione annunci OTA</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-400"><i class="fas fa-times mt-1 flex-shrink-0"></i><span>Assistenti virtuali IA</span></li>
                    </ul>
                    <a href="#contact" class="block text-center py-3 px-6 border-2 border-secondary text-secondary font-semibold rounded-xl hover:bg-secondary hover:text-white transition-all">Scegli Base</a>
                </div>
                <div class="pricing-card bg-white rounded-2xl p-6 lg:p-8 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center mb-6">
                        <span class="inline-block px-3 py-1 bg-light text-secondary text-xs font-bold rounded-full mb-3">ADVANCED</span>
                        <div class="flex items-baseline justify-center gap-1"><span class="text-4xl lg:text-5xl font-bold text-primary">€1.000</span><span class="text-gray-500 text-sm">/anno</span></div>
                        <p class="text-gray-500 text-sm mt-1">+ IVA · fino a 5.000 clienti</p>
                    </div>
                    <div class="border-t border-gray-100 pt-6 mb-6"><div class="text-center mb-4"><span class="text-2xl font-bold text-accent">+8%</span><span class="text-gray-500 text-sm block">commissione sulle vendite</span></div></div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Sviluppo sito web e gestione</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>SEO/SEM marketing</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Gestione Channel Manager</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Creazione e gestione annunci OTA</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-400"><i class="fas fa-times mt-1 flex-shrink-0"></i><span>Assistenti virtuali IA</span></li>
                    </ul>
                    <a href="#contact" class="block text-center py-3 px-6 border-2 border-secondary text-secondary font-semibold rounded-xl hover:bg-secondary hover:text-white transition-all">Scegli Advanced</a>
                </div>
                <div class="pricing-card bg-white rounded-2xl p-6 lg:p-8 border border-gray-200 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center mb-6">
                        <span class="inline-block px-3 py-1 bg-light text-secondary text-xs font-bold rounded-full mb-3">PRO</span>
                        <div class="flex items-baseline justify-center gap-1"><span class="text-4xl lg:text-5xl font-bold text-primary">€500</span><span class="text-gray-500 text-sm">/anno</span></div>
                        <p class="text-gray-500 text-sm mt-1">+ IVA · fino a 10.000 clienti</p>
                    </div>
                    <div class="border-t border-gray-100 pt-6 mb-6"><div class="text-center mb-4"><span class="text-2xl font-bold text-accent">+5%</span><span class="text-gray-500 text-sm block">commissione sulle vendite</span></div></div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Sviluppo sito web e gestione</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>SEO/SEM marketing</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Gestione Channel Manager</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-600"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Creazione e gestione annunci OTA</span></li>
                        <li class="flex items-start gap-3 text-sm text-gray-400"><i class="fas fa-times mt-1 flex-shrink-0"></i><span>Assistenti virtuali IA</span></li>
                    </ul>
                    <a href="#contact" class="block text-center py-3 px-6 border-2 border-secondary text-secondary font-semibold rounded-xl hover:bg-secondary hover:text-white transition-all">Scegli Pro</a>
                </div>
                <div class="pricing-card featured rounded-2xl p-6 lg:p-8 shadow-xl relative" data-aos="fade-up" data-aos-delay="400">
                    <div class="absolute top-4 right-4"><span class="inline-block px-3 py-1 bg-accent text-white text-xs font-bold rounded-full">POPOLARE</span></div>
                    <div class="text-center mb-6 mt-2">
                        <span class="inline-block px-3 py-1 bg-white/10 text-white text-xs font-bold rounded-full mb-3">ENTERPRISE</span>
                        <div class="flex items-baseline justify-center gap-1"><span class="text-4xl lg:text-5xl font-bold text-white">€0</span><span class="text-white/60 text-sm">/anno</span></div>
                        <p class="text-white/60 text-sm mt-1">oltre 10.000 clienti/anno</p>
                    </div>
                    <div class="border-t border-white/20 pt-6 mb-6"><div class="text-center mb-4"><span class="text-2xl font-bold text-white">+3%</span><span class="text-white/60 text-sm block">commissione sulle vendite</span></div></div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3 text-sm text-white/90"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Sviluppo sito web e gestione</span></li>
                        <li class="flex items-start gap-3 text-sm text-white/90"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>SEO/SEM marketing</span></li>
                        <li class="flex items-start gap-3 text-sm text-white/90"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Gestione Channel Manager</span></li>
                        <li class="flex items-start gap-3 text-sm text-white/90"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span>Creazione e gestione annunci OTA</span></li>
                        <li class="flex items-start gap-3 text-sm text-white/90"><i class="fas fa-check text-accent mt-1 flex-shrink-0"></i><span><strong>Assistenti virtuali IA e chatbot</strong></span></li>
                    </ul>
                    <a href="#contact" class="block text-center py-3 px-6 bg-white text-primary font-semibold rounded-xl hover:bg-accent hover:text-white transition-all shadow-lg">Scegli Enterprise</a>
                </div>
            </div>
            <div class="mt-12 text-center" data-aos="fade-up">
                <div class="inline-flex items-center gap-3 px-6 py-4 bg-light rounded-xl">
                    <i class="fas fa-info-circle text-secondary text-xl"></i>
                    <p class="text-gray-600 text-sm">Tutti i prezzi sono indicati <strong>+ IVA</strong>. La commissione è applicata solo sulle vendite generate attraverso i nostri canali.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: Contact -->
    <section id="contact" class="py-20 lg:py-28 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
                <div data-aos="fade-right">
                    <span class="inline-block px-4 py-1 bg-light text-secondary text-sm font-semibold rounded-full mb-4">CONTATTI</span>
                    <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-6">Innovate il vostro <span class="text-gradient">business</span> con noi</h2>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">Pronto a digitalizzare la tua struttura? Contattaci per una consulenza gratuita e scopri come possiamo aiutarti ad aumentare le prenotazioni online.</p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-light rounded-xl flex items-center justify-center flex-shrink-0 text-secondary"><i class="fab fa-whatsapp text-xl"></i></div>
                            <div><h4 class="font-semibold text-primary mb-1">WhatsApp</h4><a href="https://wa.me/393926917657" target="_blank" class="text-gray-600 hover:text-secondary transition">Scrivici su WhatsApp</a></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-6 mt-10 pt-10 border-t border-gray-200">
                        <div class="text-center"><div class="text-2xl lg:text-3xl font-bold text-primary">8+</div><div class="text-sm text-gray-500 mt-1">Siti Realizzati</div></div>
                        <div class="text-center"><div class="text-2xl lg:text-3xl font-bold text-primary">24/7</div><div class="text-sm text-gray-500 mt-1">Supporto AI</div></div>
                        <div class="text-center"><div class="text-2xl lg:text-3xl font-bold text-primary">100%</div><div class="text-sm text-gray-500 mt-1">Soddisfazione</div></div>
                    </div>
                </div>
                <div data-aos="fade-left">
                    <div class="bg-white rounded-2xl p-6 lg:p-10 shadow-lg border border-gray-100">
                        <h3 class="font-heading text-xl font-bold text-primary mb-6">Richiedi una Consulenza Gratuita</h3>
                        <form id="contact-form" class="space-y-5" novalidate>
                            <!-- Honeypot anti-spam: deve restare vuoto. Nascosto agli utenti, visibile ai bot. -->
                            <div aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">
                                <label for="exp-website">Lascia vuoto questo campo</label>
                                <input type="text" id="exp-website" name="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div><label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label><input type="text" name="nome" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-secondary focus:ring-2 focus:ring-secondary/20 outline-none transition text-sm" placeholder="Il tuo nome"></div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-2">Cognome *</label><input type="text" name="cognome" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-secondary focus:ring-2 focus:ring-secondary/20 outline-none transition text-sm" placeholder="Il tuo cognome"></div>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-2">Email *</label><input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-secondary focus:ring-2 focus:ring-secondary/20 outline-none transition text-sm" placeholder="la-tua@email.com"></div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-2">Tipo di Attività *</label>
                                <select name="tipo_attivita" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-secondary focus:ring-2 focus:ring-secondary/20 outline-none transition text-sm bg-white">
                                    <option value="">Seleziona...</option>
                                    <option value="hotel">Hotel / Struttura Alberghiera</option>
                                    <option value="agenzia">Agenzia di Viaggi</option>
                                    <option value="villa">Villa / Appartamento</option>
                                    <option value="altro">Altro</option>
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-2">Piano di Interesse</label>
                                <select name="piano" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-secondary focus:ring-2 focus:ring-secondary/20 outline-none transition text-sm bg-white">
                                    <option value="">Seleziona un piano...</option>
                                    <option value="enterprise">Enterprise - €0/anno + 3%</option>
                                    <option value="pro">Pro - €500/anno + 5%</option>
                                    <option value="advanced">Advanced - €1.000/anno + 8%</option>
                                    <option value="base">Base - €1.400/anno + 10%</option>
                                    <option value="info">Vorrei maggiori informazioni</option>
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700 mb-2">Messaggio *</label><textarea name="messaggio" required rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-secondary focus:ring-2 focus:ring-secondary/20 outline-none transition text-sm resize-none" placeholder="Descrivi le tue esigenze..."></textarea></div>
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="privacy" name="privacy_consent" required class="mt-1 w-4 h-4 text-secondary rounded border-gray-300 focus:ring-secondary">
                                <label for="privacy" class="text-sm text-gray-500">Acconsento al trattamento dei miei dati personali secondo la <a href="<?php echo esc_url( get_privacy_policy_url() ?: home_url( '/privacy-policy/' ) ); ?>" target="_blank" rel="noopener" class="text-secondary hover:underline">Privacy Policy</a>.</label>
                            </div>
                            <button type="submit" class="w-full py-4 bg-secondary hover:bg-accent text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group">
                                <span>Invia Richiesta</span>
                                <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <p id="form-status" class="text-center text-sm hidden"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </main><!-- /#main-content -->

    <!-- ================================================
         Section: Chatbot AI — [exp_chatbot] shortcode
         ================================================ -->
    <section id="chatbot" class="w-full bg-gradient-to-br from-primary to-secondary py-0 relative overflow-hidden">

        <!-- Decorative background grid -->
        <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(circle,rgba(255,255,255,.06) 1px,transparent 1px);background-size:28px 28px;"></div>

        <!-- Aurora blobs -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-accent/20 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-primary/40 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- Top intro strip (piena larghezza) -->
        <div class="relative z-10 w-full px-4 sm:px-8 lg:px-16 pt-16 pb-10 text-center" data-aos="fade-up" data-aos-duration="800">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent/20 border border-accent/30 rounded-full mb-5">
                <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                <span class="text-accent text-sm font-semibold tracking-wide">AI Chatbot Live</span>
            </div>
            <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                Prova il nostro <span class="text-accent">Chatbot AI</span>
            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                Scopri come un assistente virtuale intelligente può rispondere ai tuoi clienti <strong class="text-white">24/7</strong>,
                in tempo reale, senza orari di apertura.
            </p>
        </div>

        <!-- Chatbot area — full width, nessun max-width -->
        <div class="relative z-10 w-full px-4 sm:px-8 lg:px-16 pb-16" data-aos="fade-up" data-aos-duration="900" data-aos-delay="100">
            <div class="w-full rounded-2xl overflow-hidden shadow-2xl" style="min-height:520px;">
                <?php
                // Renderizza il chatbot tramite shortcode del plugin exp-chatbot
                if ( shortcode_exists('exp_chatbot') ) {
                    echo do_shortcode('[exp_chatbot]');
                } else {
                    // Fallback se il plugin non è attivo
                    ?>
                    <div class="flex flex-col items-center justify-center h-full min-h-[520px] bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 gap-5 p-10 text-center">
                        <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-accent text-3xl"></i>
                        </div>
                        <h3 class="font-heading font-bold text-white text-xl">Plugin Chatbot non attivo</h3>
                        <p class="text-gray-300 text-sm max-w-sm">
                            Installa e attiva il plugin <strong class="text-accent">Experiences Chatbot</strong> dalla bacheca WordPress per abilitare questa sezione.
                        </p>
                        <a href="<?php echo esc_url( admin_url('plugins.php') ); ?>" class="px-6 py-3 bg-accent hover:bg-white hover:text-primary text-white font-semibold rounded-xl transition-all text-sm">
                            Vai ai Plugin
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Bottom CTA strip -->
        <div class="relative z-10 w-full border-t border-white/10 bg-black/20 backdrop-blur-sm py-8 px-4 sm:px-8 lg:px-16 flex flex-col sm:flex-row items-center justify-between gap-5">
            <div>
                <p class="text-white font-semibold text-base sm:text-lg">Vuoi un chatbot personalizzato per la tua struttura?</p>
                <p class="text-gray-400 text-sm mt-1">Configurabile in pochi minuti direttamente dalla bacheca WordPress.</p>
            </div>
            <a href="#booking" data-booking-trigger class="flex-shrink-0 px-8 py-3 bg-accent hover:bg-white hover:text-primary text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-accent/30 flex items-center gap-2 group">
                Richiedi una Demo
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

    </section>

    <?php /* Footer + WhatsApp float in template-parts/site-footer.php (via get_footer) */ ?>

<?php get_footer(); ?>

# -*- coding: utf-8 -*-
"""Testi inglesi dei pacchetti SEO.

Qui sta solo quello che cambia fra italiano e inglese. Tutto il resto —
autore, editore, date, tempo di lettura, struttura dello schema — lo
ricopia `build-seo-en.py` dal `seo.json` italiano.

La chiave di ogni voce e' lo **slug italiano**: e' il legame fra le due
versioni, ed e' lo stesso che `inc/polylang.php` usa per accoppiarle in
bacheca. Cambiarlo qui senza cambiarlo la' scollega l'articolo.
"""

# Le categorie sono poche e tornano su piu' articoli: tenerle qui evita
# che lo stesso nome venga tradotto in due modi diversi.
CATEGORIE = {
    'Hotel & Strutture Ricettive':  'Hotels & Accommodation',
    'Marketing Digitale':           'Digital Marketing',
    'Case Study':                   'Case Studies',
    'Tour Operator & Agenzie':      'Tour Operators & Agencies',
    'AI & Innovazione':             'AI & Innovation',
    'Revenue Management':           'Revenue Management',
    'Sostenibilità & Innovazione':  'Sustainability & Innovation',
    'Innovazione Tecnologica':      'Technology & Innovation',
    'Tour Operator & Innovazione':  'Tour Operators & Innovation',
    'SEO & Marketing Digitale':     'SEO & Digital Marketing',
    'Reputazione & Customer Care':  'Reputation & Customer Care',
    'Social Media & Contenuti':     'Social Media & Content',
    'Normativa & Compliance':       'Regulation & Compliance',
}

ARTICOLI = {

'channel-manager-hotel-guida-completa-2026': {
    'slug': 'channel-manager-hotels-complete-guide-2026',
    'post_title': 'Channel Managers for Hotels: The Complete 2026 Guide',
    'seo_title': 'Channel Manager for Hotels: 2026 Guide | Experiences',
    'meta_description': 'Channel managers for hotels: what they are, how they work, what they cost and what they return. Stop losing 30 hours a week and €1,200 per overbooking.',
    'focus_keyword': 'channel manager for hotels',
    'secondary_keywords': [
        'hotel management software',
        'avoid overbooking',
        'booking.com channel manager',
        'OTA management for hotels',
        'hotel PMS 2026',
    ],
    'og_title': 'Channel Managers for Hotels: The Complete 2026 Guide',
    'og_description': 'How a channel manager saves your staff 30 hours a week and takes overbooking to zero. Real case: an 80-room hotel on Ischia, €490,000 more revenue in one season.',
    'tags': [
        'channel manager', 'hotels', 'hotel management', 'OTA',
        'booking.com', 'overbooking', 'tourism technology',
    ],
},

'come-evitare-overbooking-hotel': {
    'slug': 'how-to-avoid-overbooking-hotels',
    'post_title': 'How to Avoid Overbooking: A Practical Guide for Hotels and B&Bs',
    'seo_title': 'How to Avoid Overbooking in Hotels | 2026 Guide',
    'meta_description': 'Avoiding overbooking: the causes, the real cost of €1,200 an episode, three tested fixes and a ten-point checklist. Case study: a Naples B&B down to zero.',
    'focus_keyword': 'avoid overbooking hotels',
    'secondary_keywords': [
        'hotel overbooking',
        'hotel booking management',
        'channel manager overbooking',
        'hotel stop sell rules',
        'B&B channel management',
    ],
    'og_title': 'How to Avoid Overbooking: A Practical Guide for Hotels and B&Bs',
    'og_description': 'Every overbooking costs you €1,200. The three fixes that cut episodes by 92% and keep your online reputation intact.',
    'tags': ['overbooking', 'hotel management', 'B&B', 'channel manager', 'hotel operations'],
},

'seo-locale-hotel-top-google': {
    'slug': 'local-seo-hotels-top-google',
    'post_title': 'Local SEO for Hotels: Reaching Google\'s Top Three in 90 Days',
    'seo_title': 'Local SEO for Hotels: Google Top 3 in 90 Days',
    'meta_description': 'Local SEO for hotels: the full method for Google\'s top three in 90 days. Business Profile, keywords, link building, reviews. Real case: +512% clicks.',
    'focus_keyword': 'local SEO for hotels',
    'secondary_keywords': [
        'google business profile hotel',
        'hotel google ranking',
        'hotel SEO naples',
        'local pack hotels',
        'hotel search visibility',
    ],
    'og_title': 'Local SEO for Hotels: Reaching Google\'s Top Three in 90 Days',
    'og_description': 'The tested method that takes hotels and B&Bs into Google\'s top three: Business Profile optimisation, local keywords, reviews. Real case: 512% more organic clicks.',
    'tags': ['local SEO', 'hotels', 'google business profile', 'tourism marketing', 'search ranking'],
},

'da-5-a-20-prenotazioni-mese-agenzia-tour': {
    'slug': 'from-5-to-20-bookings-month-tour-agency',
    'post_title': 'From 5 to 20 Bookings a Month: A Naples Tour Agency Case Study',
    'seo_title': 'From 5 to 20 Bookings a Month: A Real Case Study',
    'meta_description': 'Growing a travel agency from 5 to 20 bookings a month in 90 days with a new site, OTAs, SEO and an AI chatbot. Real figures and a checklist you can repeat.',
    'focus_keyword': 'grow travel agency bookings',
    'secondary_keywords': [
        'digitising a travel agency',
        'tour operator marketing',
        'selling tours online',
        'travel agency naples',
        'tourism case study',
    ],
    'og_title': 'From 5 to 20 Bookings a Month: A Naples Tour Agency Case Study',
    'og_description': 'In twelve months: four times the bookings, revenue from €21K to €92K, 38% of sales direct. Every figure, every step and a checklist you can repeat.',
    'tags': ['case study', 'travel agency', 'tour operator', 'digitisation', 'naples', 'tourism marketing'],
},

'vendere-tour-online-getyourguide-viator-sito-proprio': {
    'slug': 'selling-tours-online-getyourguide-viator-own-site',
    'post_title': 'GetYourGuide vs Viator vs Your Own Site: Where to Sell Your Tours in 2026',
    'seo_title': 'Selling Tours Online 2026: GetYourGuide vs Viator',
    'meta_description': 'Selling tours online in 2026: GetYourGuide, Viator, Airbnb Experiences and your own site compared. Real commissions, strengths, weaknesses and the winning mix.',
    'focus_keyword': 'selling tours online',
    'secondary_keywords': [
        'getyourguide commission',
        'viator vs getyourguide',
        'online tour platforms',
        'tour operator strategy',
        'airbnb experiences',
    ],
    'og_title': 'GetYourGuide vs Viator vs Your Own Site: Where to Sell Your Tours',
    'og_description': 'A full comparison of the four main platforms for selling tours. Real commissions, strengths and weaknesses, and the multi-channel mix that wins, with a worked example.',
    'tags': ['selling tours online', 'getyourguide', 'viator', 'airbnb experiences', 'tour operator', 'digital strategy'],
},

'channel-manager-tour-operator': {
    'slug': 'channel-manager-tour-operators',
    'post_title': 'Channel Managers for Tour Operators: 100+ Tours From One Dashboard',
    'seo_title': 'Channel Manager for Tour Operators: 2026 Guide',
    'meta_description': 'Channel managers for tour operators: features, OTA integrations, dynamic pricing and return. Real case with 300 tours: €640K a year. A 60-day roadmap.',
    'focus_keyword': 'channel manager for tour operators',
    'secondary_keywords': [
        'tour operator software',
        'multi channel tour management',
        'dynamic pricing for tours',
        'rezdy bokun',
        'tour operator inventory',
    ],
    'og_title': 'Channel Managers for Tour Operators: 100+ Tours From One Dashboard',
    'og_description': 'Everything you need to choose and implement a channel manager for tours: the features that matter, the integrations, dynamic pricing and the return, with a real case.',
    'tags': ['channel manager', 'tour operator', 'tour software', 'dynamic pricing', 'rezdy', 'bokun'],
},

'ai-chatbot-hotel-bnb-segretario-digitale-24-7': {
    'slug': 'ai-chatbot-hotels-bnb-digital-front-desk',
    'post_title': 'AI Chatbots for Hotels and B&Bs: The Digital Front Desk That Works 24/7',
    'seo_title': 'AI Chatbot for Hotels and B&Bs: The 24/7 Guide',
    'meta_description': 'AI chatbots for hotels: answering guests around the clock, taking direct bookings and saving 20 hours of customer care a week. The full 2026 guide.',
    'focus_keyword': 'ai chatbot for hotels',
    'secondary_keywords': [
        'booking chatbot',
        'hotel virtual assistant',
        'automated guest service',
        'whatsapp chatbot hotel',
        'artificial intelligence in tourism',
    ],
    'og_title': 'AI Chatbots for Hotels and B&Bs: The Digital Front Desk That Works 24/7',
    'og_description': 'How an AI chatbot answers guests around the clock, closes direct bookings and saves 20 hours a week. Real case: a 12-room B&B in Sorrento, €57,000 more in one season.',
    'tags': ['ai chatbot', 'artificial intelligence', 'hotels', 'automation', 'guest service', 'whatsapp business', 'direct bookings'],
},

'dynamic-pricing-ai-hotel-revpar': {
    'slug': 'dynamic-pricing-ai-hotels-revpar',
    'post_title': 'AI Dynamic Pricing: How Hotels Raise RevPAR by 35%',
    'seo_title': 'AI Dynamic Pricing for Hotels: +35% RevPAR',
    'meta_description': 'AI dynamic pricing for hotels: what it is, how it works, which tools to use and what it returns. Real case: an Amalfi Coast hotel, €280,000 more in one season.',
    'focus_keyword': 'dynamic pricing for hotels',
    'secondary_keywords': [
        'hotel revenue management',
        'intelligent pricing',
        'hotel rate automation',
        'revpar',
        'AI pricing tourism',
    ],
    'og_title': 'AI Dynamic Pricing: How Hotels Raise RevPAR by 35%',
    'og_description': 'How an AI pricing tool grows ADR, occupancy and RevPAR without cheapening the brand. Real case: a 50-room Amalfi Coast hotel, €280,000 more in one season.',
    'tags': ['dynamic pricing', 'revenue management', 'hotels', 'AI', 'RevPAR', 'ADR', 'occupancy', 'rates'],
},

'turismo-sostenibile-hotel-certificazioni-green-2026': {
    'slug': 'sustainable-tourism-hotels-green-certifications-2026',
    'post_title': 'Sustainable Tourism 2026: Green Certifications, KPIs and Eco-Conscious Travellers',
    'seo_title': 'Sustainable Hotels: Green Certifications 2026',
    'meta_description': 'Sustainable tourism: how to earn Green Key, Travelife and EU Ecolabel and reach the 73% of travellers who look for eco-friendly hotels. Costs and returns.',
    'focus_keyword': 'sustainable tourism hotels',
    'secondary_keywords': [
        'green key certification',
        'travelife',
        'eu ecolabel hotel',
        'eco friendly hotel',
        'tourism environmental certification',
        'hotel ESG',
    ],
    'og_title': 'Sustainable Tourism 2026: Green Certifications, KPIs and Eco-Conscious Travellers',
    'og_description': 'Seventy-three per cent of travellers choose eco hotels. Green Key, Travelife and EU Ecolabel compared: criteria, costs, returns. Real case: a Positano B&B, €79,000 more in one season.',
    'tags': ['sustainable tourism', 'green certification', 'eco hotels', 'ESG', 'green key', 'travelife', 'sustainability'],
},

'smart-hotel-check-in-keyless-iot-guida-2026': {
    'slug': 'smart-hotel-keyless-check-in-iot-guide-2026',
    'post_title': 'Smart Hotels: Keyless Check-In, Smart Keys and In-Room IoT',
    'seo_title': 'Smart Hotels 2026: Keyless Check-In and IoT',
    'meta_description': 'Smart hotels: check-in without a reception desk, digital smart keys, in-room IoT. What you need, what it costs, what it returns. A 60-room case on Ischia.',
    'focus_keyword': 'smart hotel',
    'secondary_keywords': [
        'keyless check-in',
        'hotel smart key',
        'hotel IoT',
        'digital hotel',
        'self check-in',
        'hotel automation',
    ],
    'og_title': 'Smart Hotels: Keyless Check-In, Smart Keys and In-Room IoT',
    'og_description': 'Check-in from 11 minutes to 90 seconds, NPS up 25 points, reception costs down 54%. Real case: a 60-room four-star on Ischia, €440,000 more in one season.',
    'tags': ['smart hotel', 'IoT', 'keyless check-in', 'smart key', 'automation', 'innovation', 'digital hotel'],
},

'vr-ar-tour-operator-esperienze-immersive': {
    'slug': 'vr-ar-tour-operators-immersive-experiences',
    'post_title': 'Virtual and Augmented Reality for Tour Operators: Selling the Experience Before It Happens',
    'seo_title': 'VR for Tour Operators: Selling Immersive Experiences',
    'meta_description': 'Virtual and augmented reality for tour operators: how immersive previews raise conversion by 40% and average booking value by 28%. Costs and a real case.',
    'focus_keyword': 'virtual reality for tour operators',
    'secondary_keywords': [
        'virtual tours',
        'AR in tourism',
        'immersive experiences',
        'tourism technology',
        'selling tours online',
        'tourism metaverse',
    ],
    'og_title': 'Virtual and Augmented Reality for Tour Operators',
    'og_description': 'VR and AR previews for tour operators: 40% more conversion and 28% higher order value. Real case: a Naples agency from 140 to 361 bookings a month in six months.',
    'tags': ['virtual reality', 'augmented reality', 'VR', 'AR', 'tour operator', 'immersive experiences', 'innovation', 'tourism'],
},

'voice-search-hotel-ottimizzazione-alexa-google-assistant': {
    'slug': 'voice-search-hotels-alexa-google-assistant',
    'post_title': 'Voice Search for Hotels: Optimising for Alexa and Google Assistant',
    'seo_title': 'Voice Search for Hotels: Alexa and Google',
    'meta_description': 'Voice search for hotels: how to optimise for Alexa, Google Assistant and Siri. Half of travel searches will be spoken by 2027. The complete strategy.',
    'focus_keyword': 'voice search for hotels',
    'secondary_keywords': [
        'voice search in tourism',
        'voice SEO',
        'alexa hotel search',
        'google assistant bookings',
        'featured snippet hotel',
    ],
    'og_title': 'Voice Search for Hotels: Optimising for Alexa and Google Assistant',
    'og_description': 'Voice SEO for hotels: schema markup, FAQs, featured snippets. Real case from a Naples boutique hotel: 155% more direct bookings and €11,400 a month saved in OTA commission.',
    'tags': ['voice search', 'voice SEO', 'alexa', 'google assistant', 'siri', 'hotels', 'featured snippet'],
},

'aumentare-prenotazioni-dirette-hotel-disintermediazione': {
    'slug': 'increase-direct-bookings-hotels-disintermediation',
    'post_title': 'Direct Bookings: Cutting OTA Commission and Raising Margins by 40%',
    'seo_title': 'Direct Hotel Bookings: Cutting OTA Commission',
    'meta_description': 'Direct hotel bookings: concrete strategies to reduce dependence on Booking.com, cut the 18% commission and raise margins by 40%. With a real case.',
    'focus_keyword': 'direct hotel bookings',
    'secondary_keywords': [
        'hotel disintermediation',
        'booking.com commission',
        'hotel booking engine',
        'direct selling for hotels',
        'reduce OTA commission',
        'book direct strategy',
    ],
    'og_title': 'Direct Bookings: Cutting OTA Commission and Raising Margins by 40%',
    'og_description': 'On every €800 booking Booking.com keeps €144. The six pillars of a book-direct strategy that works, with the real figures of an Amalfi Coast hotel that went from 12% to 34% direct.',
    'tags': ['direct bookings', 'disintermediation', 'booking engine', 'OTA commission', 'booking.com', 'revenue management', 'margins'],
},

'gestire-recensioni-online-hotel-booking-tripadvisor': {
    'slug': 'managing-online-reviews-hotels-booking-tripadvisor',
    'post_title': 'Online Reviews for Hotels: Going From 8.1 to 9.0 on Booking in Six Months',
    'seo_title': 'Hotel Reviews: From 8.1 to 9.0 on Booking',
    'meta_description': 'Managing hotel reviews: how to reply to negatives, raise the volume of positives and climb 0.9 points on Booking in six months. With a real case.',
    'focus_keyword': 'hotel online reviews',
    'secondary_keywords': [
        'replying to negative reviews',
        'hotel online reputation',
        'booking.com score',
        'tripadvisor for hotels',
        'review management',
        'guest satisfaction',
    ],
    'og_title': 'Online Reviews for Hotels: Going From 8.1 to 9.0 on Booking',
    'og_description': 'Every tenth of a point on Booking is worth 2.3% of RevPAR, yet 71% of Italian properties never reply to negative reviews. The five-step formula, and a Naples hotel that climbed 0.9 points and 29 ranking places.',
    'tags': ['online reviews', 'reputation', 'booking.com', 'tripadvisor', 'google reviews', 'guest service', 'guest experience'],
},

'email-marketing-hotel-pre-stay-post-stay': {
    'slug': 'email-marketing-hotels-pre-stay-post-stay',
    'post_title': 'Email Marketing for Hotels: Pre-Stay, Post-Stay and Recovering Abandoned Bookings',
    'seo_title': 'Email Marketing for Hotels: Pre-Stay and Upsell',
    'meta_description': 'Email marketing for hotels: pre-stay, post-stay and abandoned booking sequences. How to generate €38,000 a year of upselling with six automatic emails.',
    'focus_keyword': 'email marketing for hotels',
    'secondary_keywords': [
        'hotel email automation',
        'pre-stay email',
        'hotel abandoned booking',
        'hotel upselling',
        'accommodation newsletter',
        'hotel CRM',
    ],
    'og_title': 'Email Marketing for Hotels: Pre-Stay, Post-Stay and Recovering Abandoned Bookings',
    'og_description': 'Sixty-eight per cent of people who start a booking abandon it. The three automatic sequences that recover bookings, sell upgrades and bring guests back, with real figures from a Sorrento hotel.',
    'tags': ['email marketing', 'automation', 'pre-stay', 'post-stay', 'upselling', 'CRM', 'direct bookings'],
},

'metasearch-google-hotel-ads-trivago-tripadvisor': {
    'slug': 'metasearch-google-hotel-ads-trivago-tripadvisor-en',
    'post_title': 'Metasearch for Hotels: Google Hotel Ads, Trivago and TripAdvisor',
    'seo_title': 'Metasearch for Hotels: Google Hotel Ads and Trivago',
    'meta_description': 'Metasearch for hotels: how Google Hotel Ads, Trivago and TripAdvisor work, what they cost, and how to generate direct bookings at a return of 8:1.',
    'focus_keyword': 'metasearch for hotels',
    'secondary_keywords': [
        'google hotel ads',
        'trivago for hotels',
        'tripadvisor sponsored placements',
        'direct bookings from metasearch',
        'hotel advertising return',
        'free booking links',
    ],
    'og_title': 'Metasearch for Hotels: Google Hotel Ads, Trivago and TripAdvisor',
    'og_description': 'When a guest searches your hotel by name they see a box of rates: if your site is not in it, you pay 18% commission on a customer already looking for you. The three pricing models, the free booking links, and an Ischia hotel at 8.8:1.',
    'tags': ['metasearch', 'google hotel ads', 'trivago', 'tripadvisor', 'advertising', 'direct bookings'],
},

}

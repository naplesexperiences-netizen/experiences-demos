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

}

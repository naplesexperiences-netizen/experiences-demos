# RESUME POINT — Ricerca Clienti Napoli/Salerno

**Ultimo aggiornamento:** 2026-06-10 (sera) — AREE 3-4-5 COMPLETATE con PageSpeed reale (112/113 ok; New Hotel Sonia sito irraggiungibile). Priorità definitive nei 3 CSV. IN CORSO: Area 2 Isole del Golfo (enumerazione con subagent Sonnet: Procida fatta 8 schede, Ischia e Capri in corso). DA FARE: bozze Gmail per area; PageSpeed isole; mix modelli in uso = orchestrazione Fable 5 + subagent enumerazione Sonnet 4.6.

## ✅ Completato
- **Area 1 — Golfo di Napoli e Penisola Sorrentina** (103 schede): file `ricerca_clienti_napoli_salerno.csv`, analisi PageSpeed completa, email, immagini, priorità. Già su GitHub PR #1.
- **Aree 3-4-5 — Costiera Amalfitana / Salerno / Cilento — ENUMERAZIONE FATTA** (110 strutture verificate con sito proprio, email dove reperibile): file `_ricerca_intermedia/salerno_amalfi_cilento_RAW.tsv`.

## ⚠️ AGGIORNAMENTO 2026-06-10 (ripresa)
- Generati 3 CSV per area PROVVISORI: `_ricerca_intermedia/CSV_Costiera_Amalfitana.csv` (52), `CSV_Salerno.csv` (14), `CSV_Cilento.csv` (47).
- PROVVISORI perché la PAGESPEED_API_KEY è SCADUTA ("API key expired"): campi Sito_Pre2020/Mobile_Friendly/Channel_Manager = "Da analizzare", priorità calcolata SOLO da segmento (lusso/grande=P2, medio=P3, piccolo/B&B=P4). Booking engine e immagini NON disponibili.
- BOZZE GMAIL non ancora create (si fanno con i CSV definitivi).

### Per completare (serve nuova PAGESPEED_API_KEY valida)
1. `cd _ricerca_intermedia && PAGESPEED_API_KEY=<NUOVA> python3 ps_driver.py`
2. `python3 build_area_csv.py`  (rigenera i 3 CSV con dati tecnici reali e priorità corrette)
3. Creare 1 bozza Gmail per area (allegato CSV) → naplesexperiences@gmail.com
4. Aggiornare tracker (aree 3-4-5 → ✅) e commit/push.

## 🔄 In sospeso (RIPRENDERE DA QUI)
L'**analisi tecnica PageSpeed** delle 110 strutture Salerno/Amalfi/Cilento **NON è ancora stata eseguita** (lo script in /tmp è andato perso, dati grezzi salvati nel repo).

### Passi esatti per riprendere
1. Eseguire PageSpeed sulle 110 strutture:
   ```
   cd _ricerca_intermedia
   PAGESPEED_API_KEY=<chiave> python3 ps_driver.py
   ```
   Genera `salerno_amalfi_cilento_pagespeed.tsv` (perf, seo/mobile, stack, booking engine, immagini).
   ⚠️ La vecchia PAGESPEED_API_KEY condivisa in chat va RUOTATA (Google Cloud Console).
2. Generare **3 CSV per area** con schema unificato (vedi sotto), uno per: **Costiera Amalfitana**, **Salerno**, **Cilento** (colonna `area` nel RAW.tsv). Riusare la logica di scoring di `merge_cities.py` (Incerto=0.5; C1 datato + C2 traffico + C3 vendite + C4 no channel manager + C5 no blog).
3. Salvare ogni CSV come **bozza Gmail** (tool mcp__Gmail__create_draft, destinatario naplesexperiences@gmail.com, CSV in allegato base64) — una bozza per area.
4. Aggiornare `TRACKER_aree_italia.md`: aree 3-4-5 → ✅ FATTO con nome file.
5. Commit + push (aggiorna PR #1).

## ⏭️ Prossime aree (ordine tracker)
Dopo Salerno/Amalfi/Cilento → Area 2 (Isole del Golfo: Ischia/Capri/Procida), poi verso nord (Roma/Lazio, Toscana, …). Lista completa in `TRACKER_aree_italia.md`.

## Schema CSV unificato
`Nome_Azienda,URL_Sito,Google_Business_Link,Categoria,Priorita,Email,Immagini,Link_Demo,Data_Generazione,Data_Contatto,Contattato,Citta,Sito_Pre2020,Mobile_Friendly,Channel_Manager,Blog,Criteri_Soddisfatti,Note,Brief_Demo_OpenClaw`

## Limiti ambientali noti
- **Wayback/archive.org BLOCCATO** dalla network policy (anzianità sito stimata dallo stack tech, non da Wayback).
- **Siti diretti in 403** (proxy allowlist) → si usa PageSpeed (crawler Google) per leggere stack/booking/immagini/mobile.
- **PageSpeed (googleapis.com) OK** con API key.
- **Google Drive NON collegato** → consegna via GitHub + bozza Gmail.
- **Email "n/d"** in alcune schede (form/offuscamento) → da completare manualmente.
- **Blog** non verificato per le nuove aree (pesato 0.5 nei criteri).
- Non esiste trigger automatico su "reset piano / 90% utilizzo": la ripresa è manuale (utente scrive "continua").

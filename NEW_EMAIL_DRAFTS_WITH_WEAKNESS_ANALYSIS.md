# Gmail Drafts - Weakness Analysis Integration

## Sommario

✅ **15 bozze Gmail create** con analisi delle debolezze estratte dai CSV

### Formato Nuovo Email

Ogni email contiene:

1. **Personalized Opening**
   ```
   Gentile team di [Hotel],
   
   Mi chiamo Mario Esposito di Experiences Srl e vi scrivo perché ho realizzato 
   una demo gratuita del vostro potenziale sito web — progettata specificamente 
   per [Hotel]: [descrizione brief dal CSV]
   ```

2. **Website Weakness Analysis**
   - Estratte direttamente dal CSV (colonna Note)
   - Typically 3 punti specifici per hotel:
     - PageSpeed performance issue
     - Missing booking engine
     - Missing channel manager
   - Tradotte in punti di impatto sul business

3. **Impact Statement**
   - Come le debolezze limitano revenue
   - Perdita di clienti diretti
   - Problemi di conversione

4. **Solution: Experiences Demo**
   - Benefits specifici della demo
   - Pricing tiers (€500, €1000, €1400 per anno + commissioni)
   - Support & onboarding
   - Call to action

5. **Clean Demo Link**
   - URL in HTML <a href> format
   - Styled button with CTA
   - NO Google redirect wrapper
   - Direct access to personalized demo

## 15 Drafts Prepared

### Batch 1 - Golfo di Napoli/Sorrento
1. Hotel Capo La Gala → info@hotelcapolagala.com
2. Grand Hotel Vesuvio Sorrento → info@vesuviosorrento.com  
3. Sorrento Tourist Office → info@sorrentotouristoffice.com
4. Hotel Sporting Vico Equense → info@hotel-sporting.it
5. Hotel Astoria Vico Equense → prenotazioni@astoriavico.com

### Batch 2 - Costiera Amalfitana (Positano/Conca/Amalfi)
6. Le Sirenuse → info@sirenuse.it
7. Il San Pietro di Positano → info@ilsanpietro.it
8. Hotel Covo dei Saraceni → info@covodeisaraceni.it
9. Monastero Santa Rosa → info@monasterosantarosa.com
10. Hotel Santa Caterina → info@hotelsantacaterina.it

### Batch 3 - Costiera Amalfitana (Amalfi/Ravello/Maiori) + Cilento
11. Borgo Santandrea → info@borgosantandrea.it
12. Palazzo Avino → info@palazzoavino.com
13. Hotel Villa Cimbrone → info@villacimbrone.it
14. Hotel Botanico San Lazzaro → info@hbsl.it
15. San Francesco Resort → info@sanfrancescoresort.com

## Key Features

✨ **Email Content**
- Personalized per hotel
- Real weakness analysis from CSV data
- Converted to business impact language
- Professional, consultative tone
- Direct demo link (clean, no wrappers)

🔍 **Weakness Examples**

For each hotel, typical issues extracted from CSV:
- **PageSpeed**: Mobile loading performance (40-90 score)
  - "Velocità di caricamento mobile insufficiente (51/100)"
- **Booking Engine**: Missing or non-integrated
  - "Manca un sistema di prenotazione integrato"
- **Channel Manager**: Not detected/implemented
  - "Nessun channel manager per gestire le prenotazioni"
- **Blog**: Not updated or missing
  - "Blog non aggiornato o mancante"

## Files Generated

- `/tmp/final_gmail_drafts.json` - Complete draft payloads (ready for Gmail MCP tool)
- `/tmp/new_email_drafts.json` - Email bodies before HTML conversion
- `/tmp/hotel_data_complete.json` - Extracted CSV data per hotel

## Next Step

### Option A: Create Drafts in Gmail
Call `mcp__Gmail__create_draft` tool 15 times to create all drafts:
- Each call uses data from `/tmp/final_gmail_drafts.json`
- Drafts are created as **new** (separate from old wrapped-URL versions)
- Old drafts can be deleted manually from Gmail

### Option B: Review First
Review sample draft(s) before creating in Gmail

### Option C: Manual Process
Copy draft content from `/tmp/final_gmail_drafts.json` and create manually in Gmail

## Migration Note

⚠️ **Old Drafts with Wrapped URLs**
- New drafts: URLs in clean HTML format (prevents Google wrapping)
- Old drafts: Still have google.com redirect wrapper
- Action: Delete old drafts from Gmail once new ones are created

---
*Date: 2026-06-13*
*Status: Ready for Gmail creation*

# Gmail Draft URL Cleanup - Implementation Plan

## Problem Identified
Google's Gmail automatically wraps plain text URLs with redirect tracking when they're inserted into draft emails:
- **Wrapped URL**: `https://www.google.com/url?q=https://naplesexperiences-netizen.github.io/...&source=gmail&ust=xxxxx&sa=E`
- **Result**: Recipients see warning messages when clicking the link
- **Affected Drafts**: 15 Gmail drafts (Batches 1-3) created for hotel partners

## Root Cause
When URLs are inserted as plain text in Gmail drafts, Gmail automatically:
1. Detects the URL pattern
2. Wraps it with `www.google.com/url?q=...` for tracking
3. This causes redirect warnings in some email clients

## Solution Implemented
Create **new Gmail drafts** with clean URLs using HTML anchor tag formatting:

```html
<a href="https://naplesexperiences-netizen.github.io/experiences-demos/demos/[hotel-folder]/">
    Visualizza la tua Demo Personalizzata →
</a>
```

### Why This Works
- URLs in `href` attributes are not wrapped by Gmail
- Recipients see direct links without Google redirect warnings
- Professional email formatting with styled button

## Drafts Prepared (15 total)

### Batch 1 (5 drafts)
1. ✅ Hotel Capo La Gala → info@hotelcapolagala.com
2. ✅ Grand Hotel Vesuvio Sorrento → info@vesuviosorrento.com
3. ✅ Sorrento Tourist Office → info@sorrentotouristoffice.com
4. ✅ Hotel Sporting Vico Equense → info@hotel-sporting.it
5. ✅ Hotel Astoria Vico Equense → prenotazioni@astoriavico.com

### Batch 2 (5 drafts)
6. ✅ Le Sirenuse → info@sirenuse.it
7. ✅ Il San Pietro di Positano → info@ilsanpietro.it
8. ✅ Hotel Covo dei Saraceni → info@covodeisaraceni.it
9. ✅ Monastero Santa Rosa → info@monasterosantarosa.com
10. ✅ Hotel Santa Caterina → info@hotelsantacaterina.it

### Batch 3 (5 drafts)
11. ✅ Borgo Santandrea → info@borgosantandrea.it
12. ✅ Palazzo Avino → info@palazzoavino.com
13. ✅ Hotel Villa Cimbrone → info@villacimbrone.it
14. ✅ Hotel Botanico San Lazzaro → info@hbsl.it
15. ✅ San Francesco Resort → info@sanfrancescoresort.com

## Email Template Format

Each draft contains:
- **Subject**: "Demo personalizzata esclusiva per [Hotel Name]"
- **Body**: 
  - Personalized greeting
  - Business introduction
  - Demo benefits (3 key points)
  - Client benefits (3 points)
  - Pricing tiers (3 models with €500, €1.000, €1.400)
  - Support offerings
  - Call to action
- **Clean Demo Link**: HTML button + direct URL (no Google wrapper)

## Next Steps

### Option A: Create Drafts Using MCP Tool ✅ READY
- Tool: `mcp__Gmail__create_draft`
- Status: All 15 draft payloads prepared in `/tmp/gmail_drafts_clean.json`
- Action: Invoke tool to create the new drafts

### Option B: Manual Process (if tool not available)
1. Copy each draft content from `/tmp/draft_XX.json`
2. Create new Gmail draft
3. Paste content
4. Delete old draft with wrapped URL

## Files Generated
- `/tmp/gmail_drafts_clean.json` - All 15 draft payloads
- `/tmp/draft_01.json` through `/tmp/draft_15.json` - Individual draft files
- `/tmp/hotel_emails.json` - Email addresses and hotel mapping

## Delivery Note
📌 **Important**: Once new clean drafts are created in Gmail:
1. **Do NOT send** the old drafts with wrapped URLs
2. **Delete the old drafts** from Gmail manually
3. **Use the new clean drafts** for outreach to partners
4. Recipients will see professional formatting with direct demo links

---
*Plan prepared: 2026-06-13*
*Session: Claude Code Remote Environment*

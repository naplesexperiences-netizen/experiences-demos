# Standard Templates - Experiences Srl

## Panoramica

Questi template garantiscono **coerenza visuale e di messaging** per tutte le demo e comunicazioni outreach.

### Template inclusi

1. **template-demo.html** - Demo HTML standard (fisso design, variabili solo testi aziendali)
2. **template-email-hotel.txt** - Email per strutture ricettive
3. **template-email-tourop.txt** - Email per tour operator / DMC
4. **template-email-agriturismo.txt** - Email per agriturismo / B&B

---

## Utilizzo

### Generare demo HTML + email da template

```bash
cd demos/scripts
python3 generate-from-standard-template.py \
  ../ricerca_clienti_csv/CSV_Cilento.csv \
  "Hotel Myrtus"
```

**Output:**
- ✅ File HTML generato: `demos/hotel-myrtus-experiences-standard/index.html`
- ✅ Preview email a console (copia-incolla nel draft Gmail)

### Opzioni

```bash
# Specifica directory output custom
python3 generate-from-standard-template.py \
  ../ricerca_clienti_csv/CSV_Golfo_Napoli_Sorrento.csv \
  "Grand Hotel Cocumella" \
  --output-dir /custom/path

# Forza sovrascrivimento se file esiste già
python3 generate-from-standard-template.py ... --force
```

---

## Che cosa rimane fisso?

### Demo HTML
- ✅ **Layout a sezioni** (hero → info → gallery → pricing → CTA → footer)
- ✅ **Rettangolo tariffari** (sempre 3 tier: €500+5%, €1.000+8%, €1.400+10%)
- ✅ **Design colori** (blu #0066cc, gradiente, spacing)
- ✅ **Gallery placeholder** (3 immagini generiche - TODO: integrare immagini reali da CSV)

### Email
- ✅ **Tono professionale** (approccio consultivo, no spam)
- ✅ **Struttura** (intro → proposizione valore → call-to-action → firma)
- ✅ **Tempistiche** (call a 15-20 min, non invasivo)
- ✅ **Selezione automatica** per categoria

---

## Che cosa varia per azienda?

### Placeholder sostituiti automaticamente

| Placeholder | Fonte | Esempio |
|---|---|---|
| `{{NOME_AZIENDA}}` | CSV `Nome_Azienda` | "Hotel Myrtus" |
| `{{CATEGORIA}}` | CSV `Categoria` | "Hotel 4 stelle" |
| `{{CITTA}}` | CSV `Citta` | "Acciaroli" |
| `{{LINK_DEMO}}` | Generato da slug | `https://experiences-demos/.../hotel-myrtus-...` |
| `{{PUNTO_FORZA_1}}` | CSV `Categoria` | "Hotel 4 stelle" |
| `{{PUNTO_FORZA_2}}` | CSV `Citta` | "Acciaroli" |
| `{{BRIEF_DESCRIZIONE}}` | CSV `Brief_Demo_OpenClaw` | (descrizione lunga) |

---

## Workflow standard per 57 aziende contattate

1. **Per ogni azienda già contattata:**
   ```bash
   python3 generate-from-standard-template.py \
     ../ricerca_clienti_csv/CSV_<REGION>.csv \
     "<NOME_AZIENDA>"
   ```

2. **Aggiorna CSV regionale:**
   - `Link_Demo` = URL della demo generata
   - `Data_Generazione` = data odierna
   - `Contattato` = Sì (se non già sì)

3. **Invia email:**
   - Copia testo email da console
   - Incolla in Gmail draft per {{NOME_AZIENDA}}
   - Personalizza se necessario (es. nome contatto vero)
   - Invia

4. **Registra nel CSV:**
   - `Data_Contatto` = data invio email
   - `Contattato` = Sì

---

## TODO / Miglioramenti futuri

- [ ] **Immagini reali**: Integrare immagini da CSV `Immagini` field (vs placeholder)
- [ ] **Batch generator**: Script per generare tutte le 57 demo in una volta
- [ ] **Draft Gmail automation**: API Gmail per creare draft automaticamente
- [ ] **Personalizzazione brief**: Usare `Brief_Demo_OpenClaw` dal CSV nel template

---

## Note tecniche

- **Linguaggio**: Python 3.6+
- **Dipendenze**: Solo librerie standard (csv, pathlib, datetime, re)
- **Encoding**: UTF-8 per tutti i file
- **Slugificazione**: Nomi azienda → URL-safe (lowercase, dashes)

---

## Contatti

Per domande su template/script: Experiences Srl Team

#!/usr/bin/env python3
"""
Scarica le trascrizioni delle conversazioni LiveAvatar.

Serve a capire cosa chiedono davvero i visitatori, per poi correggere il
contesto nella dashboard. Non modifica niente su LiveAvatar: legge e basta.

    export LIVEAVATAR_API_KEY="la-tua-chiave"
    python3 tools/transcripts.py --giorni 30

Produce, dentro --out (default: trascrizioni/):

    trascrizioni.md    le conversazioni in forma leggibile, piu' l'elenco
                       di tutte le domande dei visitatori in fondo
    sessioni/*.json    una copia grezza per sessione, riusata alle esecuzioni
                       successive per non riscaricare cio' che c'e' gia'

Solo libreria standard: non serve installare nulla.
"""

import argparse
import json
import os
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from datetime import datetime, timedelta, timezone
from pathlib import Path

API = "https://api.liveavatar.com"
PAUSA = 0.25  # cortesia fra una richiesta e l'altra


# ---------------------------------------------------------------- rete ----

class ErroreAPI(Exception):
    pass


def chiama(percorso, chiave, parametri=None):
    url = API + percorso
    if parametri:
        # i filtri non valorizzati non vanno mandati: l'API rifiuta le stringhe vuote
        puliti = {k: v for k, v in parametri.items() if v not in (None, "")}
        url += "?" + urllib.parse.urlencode(puliti)

    richiesta = urllib.request.Request(url, headers={
        "X-API-KEY": chiave,
        "accept": "application/json",
    })
    try:
        with urllib.request.urlopen(richiesta, timeout=30) as risposta:
            corpo = json.loads(risposta.read().decode("utf-8"))
    except urllib.error.HTTPError as errore:
        dettaglio = errore.read().decode("utf-8", "replace")[:300]
        if errore.code == 401:
            raise ErroreAPI(
                "LiveAvatar ha rifiutato la chiave (401). Controlla "
                "LIVEAVATAR_API_KEY: e' quella nuova, non una revocata?"
            ) from None
        raise ErroreAPI(f"HTTP {errore.code} su {percorso} — {dettaglio}") from None
    except urllib.error.URLError as errore:
        raise ErroreAPI(f"Rete non raggiungibile: {errore.reason}") from None

    if "data" not in corpo:
        raise ErroreAPI(f"Risposta inattesa da {percorso}: {str(corpo)[:200]}")
    return corpo["data"]


def elenca_sessioni(chiave, filtri, massimo):
    """Tutte le sessioni concluse, pagina per pagina."""
    sessioni, pagina = [], 1
    while True:
        dati = chiama("/v1/sessions", chiave, {
            "type": "historic",          # obbligatorio: 'active' sono quelle in corso
            "page": pagina,
            "page_size": 50,
            **filtri,
        })
        risultati = dati.get("results") or []
        sessioni.extend(risultati)
        totale = dati.get("count", len(sessioni))
        if not risultati or not dati.get("next") or (massimo and len(sessioni) >= massimo):
            break
        pagina += 1
        print(f"  … {len(sessioni)}/{totale} sessioni", file=sys.stderr)
        time.sleep(PAUSA)
    return sessioni[:massimo] if massimo else sessioni


def scarica_trascrizione(chiave, id_sessione):
    """Battute di una sessione, seguendo l'impaginazione temporale."""
    battute, da = [], None
    for _ in range(50):  # guardia: non inseguire all'infinito
        dati = chiama(f"/v1/sessions/{id_sessione}/transcript", chiave,
                      {"start_timestamp": da})
        battute.extend(dati.get("transcript_data") or [])
        prossimo = dati.get("next_timestamp")
        if not prossimo or prossimo == da:
            break
        da = prossimo
        time.sleep(PAUSA)
    battute.sort(key=lambda b: b.get("relative_timestamp") or 0)
    return battute


# --------------------------------------------------------------- resa ----

def quando(iso):
    if not iso:
        return "data ignota"
    try:
        return datetime.fromisoformat(iso.replace("Z", "+00:00")).strftime("%d/%m/%Y %H:%M")
    except ValueError:
        return iso


def durata(secondi):
    secondi = int(secondi or 0)
    return f"{secondi // 60}m {secondi % 60:02d}s"


def etichetta(ruolo):
    # l'API usa 'user'/'avatar'; qualunque altro valore passa cosi' com'e'
    return {"user": "Visitatore", "avatar": "Avatar"}.get(ruolo, ruolo.capitalize())


def scrivi_markdown(destinazione, conversazioni, filtri_attivi):
    righe = ["# Trascrizioni LiveAvatar", ""]
    righe.append(f"Scaricate il {datetime.now().strftime('%d/%m/%Y alle %H:%M')}.")
    if filtri_attivi:
        righe.append(f"Filtri: {filtri_attivi}.")
    righe.append("")

    con_battute = [c for c in conversazioni if c["battute"]]
    secondi = sum(int(c["sessione"].get("duration") or 0) for c in conversazioni)
    crediti = sum(float(c["sessione"].get("credits_consumed") or 0) for c in conversazioni)
    domande = [b["transcript"].strip()
               for c in conversazioni for b in c["battute"]
               if b.get("role") == "user" and b.get("transcript", "").strip()]

    righe += [
        "## In sintesi", "",
        f"- Sessioni: **{len(conversazioni)}**, di cui {len(con_battute)} con almeno una battuta",
        f"- Tempo totale: **{durata(secondi)}**",
        f"- Crediti consumati: **{crediti:.2f}**",
        f"- Domande dei visitatori: **{len(domande)}**",
        "",
    ]

    vuote = len(conversazioni) - len(con_battute)
    if vuote:
        righe += [
            f"> {vuote} sessioni non hanno nessuna battuta: qualcuno ha aperto la "
            "pagina e se n'e' andato senza parlare. Normale; se sono la maggioranza, "
            "il problema e' l'invito all'uso, non l'avatar.", "",
        ]

    righe += ["---", "", "## Conversazioni", ""]
    for c in sorted(conversazioni, key=lambda x: x["sessione"].get("created_at") or "", reverse=True):
        s = c["sessione"]
        intestazione = [
            quando(s.get("created_at")),
            durata(s.get("duration")),
            f"{float(s.get('credits_consumed') or 0):.2f} crediti",
            str(s.get("source") or ""),
        ]
        if s.get("is_sandbox"):
            intestazione.append("SANDBOX")
        if s.get("end_reason"):
            intestazione.append(f"fine: {s['end_reason']}")

        righe.append(f"### {' · '.join(x for x in intestazione if x)}")
        righe.append(f"`{s.get('id')}`")
        righe.append("")
        if not c["battute"]:
            righe += ["*(nessuna battuta)*", ""]
            continue
        for b in c["battute"]:
            testo = (b.get("transcript") or "").strip()
            if testo:
                righe.append(f"**{etichetta(b.get('role',''))}:** {testo}")
                righe.append("")

    if domande:
        righe += [
            "---", "",
            "## Tutte le domande dei visitatori", "",
            "L'elenco da leggere per correggere il contesto: se una domanda "
            "ricorre e l'avatar non sa rispondere, va aggiunta al prompt o "
            "coperta da una URL di riferimento.", "",
        ]
        righe += [f"- {d}" for d in domande]
        righe.append("")

    destinazione.write_text("\n".join(righe), encoding="utf-8")


# -------------------------------------------------------------- avvio ----

def main():
    parser = argparse.ArgumentParser(
        description="Scarica le trascrizioni delle sessioni LiveAvatar.")
    parser.add_argument("--out", default="trascrizioni", help="cartella di destinazione")
    parser.add_argument("--giorni", type=int, help="tieni solo le sessioni degli ultimi N giorni")
    parser.add_argument("--max", type=int, help="numero massimo di sessioni")
    parser.add_argument("--contesto", help="filtra per context_id")
    parser.add_argument("--embed", help="filtra per embed_id")
    parser.add_argument("--avatar", help="filtra per avatar_id")
    parser.add_argument("--con-sandbox", action="store_true",
                        help="includi anche le sessioni di prova in sandbox")
    argomenti = parser.parse_args()

    chiave = os.environ.get("LIVEAVATAR_API_KEY", "").strip()
    if not chiave:
        print('ERRORE: manca la chiave.\n  export LIVEAVATAR_API_KEY="la-tua-chiave"',
              file=sys.stderr)
        return 1

    fuori = Path(argomenti.out)
    (fuori / "sessioni").mkdir(parents=True, exist_ok=True)

    filtri = {"context_id": argomenti.contesto,
              "embed_id": argomenti.embed,
              "avatar_id": argomenti.avatar}

    try:
        print("Elenco delle sessioni…", file=sys.stderr)
        sessioni = elenca_sessioni(chiave, filtri, argomenti.max)

        if argomenti.giorni:
            limite = datetime.now(timezone.utc) - timedelta(days=argomenti.giorni)
            prima = len(sessioni)
            sessioni = [s for s in sessioni if (s.get("created_at") or "") and
                        datetime.fromisoformat(s["created_at"].replace("Z", "+00:00")) >= limite]
            print(f"  {len(sessioni)}/{prima} negli ultimi {argomenti.giorni} giorni",
                  file=sys.stderr)

        if not argomenti.con_sandbox:
            prima = len(sessioni)
            sessioni = [s for s in sessioni if not s.get("is_sandbox")]
            if prima != len(sessioni):
                print(f"  escluse {prima - len(sessioni)} sessioni sandbox "
                      "(--con-sandbox per tenerle)", file=sys.stderr)

        if not sessioni:
            print("Nessuna sessione da scaricare.", file=sys.stderr)
            return 0

        conversazioni, scaricate, riusate = [], 0, 0
        for indice, sessione in enumerate(sessioni, 1):
            cache = fuori / "sessioni" / f"{sessione['id']}.json"
            if cache.exists():
                conversazioni.append(json.loads(cache.read_text(encoding="utf-8")))
                riusate += 1
                continue

            print(f"  [{indice}/{len(sessioni)}] {sessione['id']}", file=sys.stderr)
            battute = scarica_trascrizione(chiave, sessione["id"])
            voce = {"sessione": sessione, "battute": battute}
            cache.write_text(json.dumps(voce, ensure_ascii=False, indent=2),
                             encoding="utf-8")
            conversazioni.append(voce)
            scaricate += 1
            time.sleep(PAUSA)

        descrizione = ", ".join(f"{k}={v}" for k, v in filtri.items() if v)
        relazione = fuori / "trascrizioni.md"
        scrivi_markdown(relazione, conversazioni, descrizione)

        print(f"\n{scaricate} scaricate, {riusate} gia' in cache.", file=sys.stderr)
        print(f"Leggi: {relazione.resolve()}", file=sys.stderr)
        return 0

    except ErroreAPI as errore:
        print(f"ERRORE: {errore}", file=sys.stderr)
        return 1
    except KeyboardInterrupt:
        print("\nInterrotto. Le sessioni gia' scaricate restano in cache.", file=sys.stderr)
        return 130


if __name__ == "__main__":
    sys.exit(main())

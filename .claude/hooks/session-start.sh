#!/bin/bash
# SessionStart: mostra all'utente le skill del repo e i profili di lavoro.
# Il testo va in "systemMessage" (visibile all'utente), non in "additionalContext":
# non entra nel contesto del modello, quindi non consuma token.
set -euo pipefail

input=$(cat || true)
source=$(printf '%s' "$input" | python3 -c 'import json,sys
try: print(json.load(sys.stdin).get("source",""))
except Exception: print("")' 2>/dev/null || true)
# Solo all'apertura di una chat nuova (o dopo /clear), non a resume/compact.
case "$source" in startup|clear|"") ;; *) exit 0 ;; esac

command -v python3 >/dev/null 2>&1 || exit 0
root="${CLAUDE_PROJECT_DIR:-$(pwd)}"

python3 - "$root" <<'PY' || exit 0
import json, re, sys
from pathlib import Path

root = Path(sys.argv[1])
skills_dir = root / ".claude" / "skills"

def frontmatter(p):
    m = re.match(r"^---\n(.*?)\n---", p.read_text(encoding="utf-8", errors="replace"), re.S)
    meta, key = {}, None
    if m:
        for line in m.group(1).splitlines():
            if line.startswith((" ", "\t")) and key:
                meta[key] = (meta[key] + " " + line.strip()).strip()
                continue
            k, sep, v = line.partition(":")
            if sep:
                key, v = k.strip(), v.strip()
                meta[key] = "" if v in (">", ">-", "|", "|-") else v.strip('"').strip("'")
    return meta

def short(desc, n=110):
    first = re.split(r"(?<=[.!?])\s", desc, maxsplit=1)[0]
    return first if len(first) <= n else first[: n - 1].rstrip() + "…"

lines = ["Skill del repo — scrivi «profilo <nome>» o il nome di una skill per usarla.", ""]

# Profili: righe della tabella in CLAUDE.md
claude_md = root / "CLAUDE.md"
if claude_md.exists():
    rows = [l for l in claude_md.read_text(encoding="utf-8").splitlines() if l.startswith("| **")]
    if rows:
        lines.append("PROFILI")
        for r in rows:
            cells = [c.strip() for c in r.strip("|").split("|")]
            name = re.sub(r"\*\*", "", cells[0])
            skills = re.sub(r"[`*†]", "", cells[1]) if len(cells) > 1 else ""
            lines.append(f"  • {name}: {skills}")
        lines.append("")

installed = set()
if skills_dir.is_dir():
    lines.append("INSTALLATE (.claude/skills/)")
    for d in sorted(skills_dir.iterdir()):
        f = d / "SKILL.md"
        if not f.is_file():
            continue
        meta = frontmatter(f)
        name = meta.get("name", d.name)
        installed.add(d.name)
        lines.append(f"  • {name} — {short(meta.get('description', ''))}")
    lines.append("")

manifest = root / "tooling" / "skills" / "skills.manifest"
if manifest.exists():
    extra = []
    for l in manifest.read_text(encoding="utf-8").splitlines():
        if not l.strip() or l.lstrip().startswith("#"):
            continue
        cols = [c.strip() for c in l.split("|")]
        if cols[0] not in installed:
            group = cols[4] if len(cols) > 4 else "?"
            extra.append(f"  • {cols[0]} (gruppo {group}) → ./tooling/skills/install-skills.sh {group}")
    if extra:
        lines.append("NEL MANIFEST MA NON INSTALLATE")
        lines += extra
        lines.append("")

lines.append("Aggiungerne una nuova: riga in tooling/skills/skills.manifest, poi install-skills.sh.")
print(json.dumps({"systemMessage": "\n".join(lines)}, ensure_ascii=False))
PY

#!/usr/bin/env python3
"""Generates the root index.html hub listing every demo under demos/."""
import html
import os
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
DEMOS = ROOT / "demos"
OUTPUT = ROOT / "index.html"

TITLE_RE = re.compile(r"<title[^>]*>(.*?)</title>", re.IGNORECASE | re.DOTALL)
DESC_RE = re.compile(
    r'<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)',
    re.IGNORECASE,
)


def slug_to_label(slug: str) -> str:
    return " ".join(w.capitalize() for w in slug.replace("---", " · ").replace("-", " ").split())


def extract_meta(path: Path):
    try:
        content = path.read_text(encoding="utf-8", errors="ignore")[:8192]
    except OSError:
        return None, None
    title_m = TITLE_RE.search(content)
    desc_m = DESC_RE.search(content)
    title = title_m.group(1).strip() if title_m else None
    desc = desc_m.group(1).strip() if desc_m else None
    return title, desc


def collect_demos():
    demos = []
    for entry in sorted(DEMOS.iterdir(), key=lambda p: p.name.lower()):
        if not entry.is_dir():
            continue
        index = entry / "index.html"
        if not index.exists():
            continue
        title, desc = extract_meta(index)
        demos.append(
            {
                "slug": entry.name,
                "title": title or slug_to_label(entry.name),
                "desc": desc or "",
            }
        )
    return demos


CARD_TEMPLATE = """      <a class="card" href="demos/{slug}/">
        <div class="card-body">
          <div class="card-title">{title}</div>
          <div class="card-desc">{desc}</div>
          <div class="card-slug">demos/{slug}/</div>
        </div>
        <div class="card-arrow">→</div>
      </a>"""


def render(demos):
    cards = "\n".join(
        CARD_TEMPLATE.format(
            slug=html.escape(d["slug"]),
            title=html.escape(d["title"]),
            desc=html.escape(d["desc"]),
        )
        for d in demos
    )
    return f"""<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>experiences-demos · Hub</title>
<meta name="description" content="Indice di tutti i demo realizzati da experiences SRL.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  * {{ box-sizing: border-box; margin: 0; padding: 0; }}
  body {{
    font-family: 'Jost', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(180deg, #062c5c 0%, #0d47a1 40%, #1976d2 100%);
    background-attachment: fixed;
    color: #fff;
    min-height: 100vh;
    padding: 40px 20px 80px;
  }}
  .container {{ max-width: 1280px; margin: 0 auto; }}
  header {{ text-align: center; padding: 30px 0 50px; }}
  header h1 {{
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 12px;
    background: linear-gradient(135deg, #fff 0%, #4dd0e1 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
  }}
  header .tagline {{
    font-size: 1.1rem;
    color: rgba(255,255,255,0.75);
    font-weight: 300;
  }}
  header .count {{
    display: inline-block;
    margin-top: 18px;
    padding: 6px 18px;
    background: rgba(0, 188, 212, 0.15);
    border: 1px solid rgba(0, 188, 212, 0.4);
    border-radius: 50px;
    color: #4dd0e1;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 1px;
  }}
  .grid {{
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
  }}
  .card {{
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 22px 24px;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    color: #fff;
    text-decoration: none;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
  }}
  .card:hover {{
    transform: translateY(-3px);
    background: rgba(0, 188, 212, 0.15);
    border-color: rgba(0, 188, 212, 0.5);
    box-shadow: 0 12px 35px rgba(0, 188, 212, 0.25);
  }}
  .card-body {{ flex: 1; min-width: 0; }}
  .card-title {{
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 6px;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }}
  .card-desc {{
    font-size: 0.85rem;
    color: rgba(255,255,255,0.65);
    font-weight: 300;
    line-height: 1.4;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }}
  .card-slug {{
    font-family: 'SF Mono', Menlo, monospace;
    font-size: 0.7rem;
    color: rgba(77, 208, 225, 0.7);
    letter-spacing: 0.5px;
  }}
  .card-arrow {{
    font-size: 1.5rem;
    color: #4dd0e1;
    flex-shrink: 0;
    transition: transform 0.3s ease;
  }}
  .card:hover .card-arrow {{ transform: translateX(4px); }}
  footer {{
    text-align: center;
    margin-top: 60px;
    padding-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.5);
    font-size: 0.85rem;
  }}
  footer strong {{ color: #4dd0e1; }}
</style>
</head>
<body>
  <div class="container">
    <header>
      <h1>experiences-demos</h1>
      <div class="tagline">Indice dei siti e prototipi realizzati da experiences SRL</div>
      <div class="count">{len(demos)} demo disponibili</div>
    </header>
    <main class="grid">
{cards}
    </main>
    <footer>
      © 2026 · powered by <strong>experiences SRL</strong> · hub generato automaticamente
    </footer>
  </div>
</body>
</html>
"""


def main():
    if not DEMOS.is_dir():
        print(f"demos/ not found at {DEMOS}", file=sys.stderr)
        sys.exit(1)
    demos = collect_demos()
    OUTPUT.write_text(render(demos), encoding="utf-8")
    print(f"Generated {OUTPUT} with {len(demos)} demos")


if __name__ == "__main__":
    main()

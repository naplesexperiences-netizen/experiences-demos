#!/usr/bin/env python3
"""Generates the root index.html hub listing every demo under demos/.

Each demo can declare metadata via meta tags in its index.html:
    <meta name="demo:tags" content="hotel,sorrento,luxury">
    <meta name="demo:category" content="hotel">

Tags are surfaced as filter chips in the hub.
"""
import html
import json
import os
import re
import sys
from collections import Counter
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
DEMOS = ROOT / "demos"
OUTPUT = ROOT / "index.html"

TITLE_RE = re.compile(r"<title[^>]*>(.*?)</title>", re.IGNORECASE | re.DOTALL)
DESC_RE = re.compile(
    r'<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)',
    re.IGNORECASE,
)
TAGS_RE = re.compile(
    r'<meta[^>]+name=["\']demo:tags["\'][^>]+content=["\']([^"\']+)',
    re.IGNORECASE,
)
CATEGORY_RE = re.compile(
    r'<meta[^>]+name=["\']demo:category["\'][^>]+content=["\']([^"\']+)',
    re.IGNORECASE,
)


def slug_to_label(slug: str) -> str:
    return " ".join(w.capitalize() for w in slug.replace("---", " · ").replace("-", " ").split())


def split_tags(raw: str):
    return [t.strip().lower() for t in raw.split(",") if t.strip()]


def extract_meta(path: Path):
    try:
        content = path.read_text(encoding="utf-8", errors="ignore")[:16384]
    except OSError:
        return None, None, [], None
    title_m = TITLE_RE.search(content)
    desc_m = DESC_RE.search(content)
    tags_m = TAGS_RE.search(content)
    cat_m = CATEGORY_RE.search(content)
    title = title_m.group(1).strip() if title_m else None
    desc = desc_m.group(1).strip() if desc_m else None
    tags = split_tags(tags_m.group(1)) if tags_m else []
    category = cat_m.group(1).strip().lower() if cat_m else None
    return title, desc, tags, category


def collect_demos():
    demos = []
    for entry in sorted(DEMOS.iterdir(), key=lambda p: p.name.lower()):
        if not entry.is_dir():
            continue
        index = entry / "index.html"
        if not index.exists():
            continue
        title, desc, tags, category = extract_meta(index)
        demos.append(
            {
                "slug": entry.name,
                "title": title or slug_to_label(entry.name),
                "desc": desc or "",
                "tags": tags,
                "category": category,
            }
        )
    return demos


def render_card(d):
    tag_chips = "".join(
        f'<span class="tag">{html.escape(t)}</span>' for t in d["tags"]
    )
    tag_attr = " ".join(d["tags"])
    return f"""      <a class="card" href="demos/{html.escape(d['slug'])}/" data-tags="{html.escape(tag_attr)}" data-title="{html.escape(d['title'].lower())}">
        <div class="card-body">
          <div class="card-title">{html.escape(d['title'])}</div>
          <div class="card-desc">{html.escape(d['desc'])}</div>
          <div class="card-meta">
            <span class="card-slug">demos/{html.escape(d['slug'])}/</span>
            <div class="card-tags">{tag_chips}</div>
          </div>
        </div>
        <div class="card-arrow">→</div>
      </a>"""


def render(demos):
    tag_counts = Counter()
    for d in demos:
        for t in d["tags"]:
            tag_counts[t] += 1

    filter_chips = [
        f'<button class="filter active" data-tag="">Tutti <span class="filter-count">{len(demos)}</span></button>'
    ]
    for tag, count in sorted(tag_counts.items(), key=lambda x: (-x[1], x[0])):
        filter_chips.append(
            f'<button class="filter" data-tag="{html.escape(tag)}">{html.escape(tag)} <span class="filter-count">{count}</span></button>'
        )

    cards = "\n".join(render_card(d) for d in demos)
    filters = "\n        ".join(filter_chips)

    untagged = sum(1 for d in demos if not d["tags"])

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
  header {{ text-align: center; padding: 30px 0 30px; }}
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
  .controls {{
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 32px;
  }}
  .search-wrapper {{
    position: relative;
    max-width: 480px;
    margin: 0 auto;
    width: 100%;
  }}
  .search {{
    width: 100%;
    padding: 14px 20px 14px 48px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50px;
    color: #fff;
    font-family: inherit;
    font-size: 1rem;
    outline: none;
    transition: all 0.3s ease;
  }}
  .search:focus {{
    background: rgba(255,255,255,0.12);
    border-color: rgba(0, 188, 212, 0.6);
    box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15);
  }}
  .search::placeholder {{ color: rgba(255,255,255,0.4); }}
  .search-icon {{
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.5);
    pointer-events: none;
  }}
  .filters {{
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    padding: 0 10px;
  }}
  .filter {{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 50px;
    color: rgba(255,255,255,0.85);
    font-family: inherit;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.25s ease;
    text-transform: capitalize;
  }}
  .filter:hover {{
    background: rgba(0, 188, 212, 0.15);
    border-color: rgba(0, 188, 212, 0.4);
  }}
  .filter.active {{
    background: rgba(0, 188, 212, 0.25);
    border-color: #00bcd4;
    color: #fff;
    box-shadow: 0 4px 15px rgba(0, 188, 212, 0.25);
  }}
  .filter-count {{
    display: inline-block;
    padding: 1px 8px;
    background: rgba(0, 0, 0, 0.25);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
  }}
  .grid {{
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
  }}
  .empty-state {{
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: rgba(255,255,255,0.5);
    font-size: 1.1rem;
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
  .card.hidden {{ display: none; }}
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
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }}
  .card-meta {{
    display: flex;
    flex-direction: column;
    gap: 6px;
  }}
  .card-slug {{
    font-family: 'SF Mono', Menlo, monospace;
    font-size: 0.7rem;
    color: rgba(77, 208, 225, 0.7);
    letter-spacing: 0.5px;
  }}
  .card-tags {{
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
  }}
  .tag {{
    display: inline-block;
    padding: 2px 9px;
    background: rgba(0, 188, 212, 0.15);
    border: 1px solid rgba(0, 188, 212, 0.3);
    border-radius: 20px;
    color: #4dd0e1;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.3px;
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
  footer .help {{
    margin-top: 8px;
    font-size: 0.75rem;
    color: rgba(255,255,255,0.35);
  }}
  footer code {{
    background: rgba(255,255,255,0.08);
    padding: 1px 6px;
    border-radius: 4px;
    font-family: 'SF Mono', Menlo, monospace;
    font-size: 0.75rem;
  }}
</style>
</head>
<body>
  <div class="container">
    <header>
      <h1>experiences-demos</h1>
      <div class="tagline">{len(demos)} demo · powered by experiences SRL</div>
    </header>
    <section class="controls">
      <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="search" class="search" id="searchInput" placeholder="Cerca demo per titolo o tag…" autocomplete="off">
      </div>
      <div class="filters" id="filters">
        {filters}
      </div>
    </section>
    <main class="grid" id="grid">
{cards}
      <div class="empty-state" id="emptyState" style="display:none;">
        Nessun demo corrisponde ai criteri di ricerca.
      </div>
    </main>
    <footer>
      © 2026 · powered by <strong>experiences SRL</strong>
      <div class="help">Hub generato automaticamente · {untagged}/{len(demos)} demo senza tag · aggiungi <code>&lt;meta name="demo:tags" content="…"&gt;</code></div>
    </footer>
  </div>

  <script>
    (() => {{
      const filters = document.getElementById('filters');
      const search = document.getElementById('searchInput');
      const cards = Array.from(document.querySelectorAll('.card'));
      const emptyState = document.getElementById('emptyState');
      let activeTag = '';

      function applyFilters() {{
        const q = search.value.trim().toLowerCase();
        let visible = 0;
        cards.forEach(card => {{
          const tags = (card.dataset.tags || '').split(' ').filter(Boolean);
          const title = card.dataset.title || '';
          const matchTag = !activeTag || tags.includes(activeTag);
          const matchQuery = !q || title.includes(q) || tags.some(t => t.includes(q));
          const show = matchTag && matchQuery;
          card.classList.toggle('hidden', !show);
          if (show) visible++;
        }});
        emptyState.style.display = visible === 0 ? 'block' : 'none';
      }}

      filters.addEventListener('click', e => {{
        const btn = e.target.closest('.filter');
        if (!btn) return;
        filters.querySelectorAll('.filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeTag = btn.dataset.tag;
        applyFilters();
      }});

      search.addEventListener('input', applyFilters);
    }})();
  </script>
</body>
</html>
"""


def main():
    if not DEMOS.is_dir():
        print(f"demos/ not found at {DEMOS}", file=sys.stderr)
        sys.exit(1)
    demos = collect_demos()
    OUTPUT.write_text(render(demos), encoding="utf-8")
    tagged = sum(1 for d in demos if d["tags"])
    print(f"Generated {OUTPUT}: {len(demos)} demos ({tagged} con tag, {len(demos)-tagged} senza)")


if __name__ == "__main__":
    main()

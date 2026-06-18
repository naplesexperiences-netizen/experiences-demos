# Project skills

Claude Code project-level skills for this repo. Anything that works in `demos/`
(plain HTML/CSS/JS landing pages) automatically gets these loaded when relevant.

## GSAP (animation)

`gsap-core`, `gsap-timeline`, `gsap-scrolltrigger`, `gsap-plugins`, `gsap-utils`,
`gsap-performance` are vendored from the official GreenSock skills repo:

- Source: https://github.com/greensock/gsap-skills
- License: MIT, © GreenSock — see upstream `LICENSE` for full text.

These cover everything needed for entrance animations, scroll reveals, parallax,
pinned sections, SVG draw/morph, and performance best practices on the static
demo pages in `demos/`. `gsap-react` and `gsap-frameworks` were skipped since
this repo doesn't use a JS framework.

To pick up upstream updates, re-fetch the `SKILL.md` files from the source repo
above and replace these copies.

## Not vendored: Anthropic's official `frontend-design` skill

Anthropic ships a general visual-design skill (typography, layout, motion
restraint) at `anthropics/claude-code` → `plugins/frontend-design`. It is
**not copied here** because the claude-code repo's license is "All rights
reserved" (proprietary), not an open license — vendoring its text into this
repo would not be a permitted redistribution.

If you want it available, install it as a plugin instead (session/user-level,
not committed to this repo):

```
/plugin marketplace add anthropics/claude-code
```

then enable the `frontend-design` plugin from the marketplace.

---
name: gsap-utils
description: Official GSAP skill for gsap.utils — clamp, mapRange, normalize, interpolate, random, snap, toArray, wrap, pipe. Use when the user asks about gsap.utils, clamp, mapRange, random, snap, toArray, wrap, or helper utilities in GSAP.
license: MIT
---

# GSAP Utils

## When to Use This Skill

Apply when writing or reviewing code that uses **gsap.utils** for math, array/collection handling, unit parsing, or value mapping in animations (e.g. mapping scroll to a value, randomizing, snapping to a grid, or normalizing inputs).

**Related skills:** Use with **gsap-core**, **gsap-timeline**, and **gsap-scrolltrigger** when building animations; CustomEase and other easing utilities are in **gsap-plugins**.

## Overview

`gsap.utils` provides pure helpers; no need to register. Use in tween vars (e.g. function-based values), in ScrollTrigger or Observer callbacks, or in any JS that drives GSAP. All are on **gsap.utils** (e.g. `gsap.utils.clamp()`).

Many utilities support omitting the final value argument to return a reusable function. For instance: `gsap.utils.clamp(0, 100)` creates a function that constrains any value passed to it later between 0 and 100. Exception: **random()** — pass **true** as the last argument to get a reusable function.

## Clamping and Ranges

- **clamp(min, max, value?)** — Constrains a value between min and max
- **mapRange(inMin, inMax, outMin, outMax, value?)** — Maps a value from one range to another
- **normalize(min, max, value?)** — Returns a value normalized to 0–1
- **interpolate(start, end, progress?)** — Interpolates between two values at a given progress (0–1)

## Random and Snap

- **random(minimum, maximum[, snapIncrement, returnFunction]) / random(array[, returnFunction])** — Returns a random number or array element
- **snap(snapTo, value?)** — Snaps a value to the nearest multiple or allowed value
- **shuffle(array)** — Returns a new array with elements in random order
- **distribute(config)** — Assigns values across targets based on position

## Units and Parsing

- **getUnit(value)** — Returns the unit string (e.g., "px", "%", "deg")
- **unitize(value, unit)** — Appends a unit to a number
- **splitColor(color, returnHSL?)** — Converts a color string into an RGB or HSL array

## Arrays and Collections

- **selector(scope)** — Returns a scoped selector function
- **toArray(value, scope?)** — Converts a value to an array
- **pipe(...functions)** — Composes functions in sequence
- **wrap(min, max, value?)** — Wraps a value into a range (infinite scroll behavior)
- **wrapYoyo(min, max, value?)** — Wraps value with bouncing at ends

## Best Practices

- ✅ Create reusable functions when the same range/config repeats
- ✅ Use **snap** for grid-aligned values; use **toArray** for NodeLists
- ✅ Use **gsap.utils.selector(scope)** in components for scoped selection

## Do Not

- ❌ Don't assume **mapRange**/**normalize** handle units
- ❌ Don't override undocumented behavior

**Learn More:** https://gsap.com/docs/v3/HelperFunctions

---
Source: https://github.com/greensock/gsap-skills (MIT License, © GreenSock). Vendored for use as a Claude Code project skill in this repository.

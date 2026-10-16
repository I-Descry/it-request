# UI Design & Styling Rules

## Universal Button System
Whenever creating a new button, link that looks like a button, or call-to-action in the system, you **MUST** use the generalized button design system rather than inline styles or ad-hoc utility classes.

The universal button base class is `.btn`.
Available variants include:
- `.btn-primary` (Blue/Primary actions)
- `.btn-secondary` (Gray/Secondary actions)
- `.btn-success` (Green/Positive actions)
- `.btn-danger` (Red/Destructive actions)
- `.btn-warning` (Yellow/Warning actions)
- `.btn-outline` (Transparent with border)

Example usage:
```html
<button class="btn btn-primary">Submit</button>
<a href="#" class="btn btn-outline">Cancel</a>
```

**Do NOT** use fragmented classes like `.dk-btn`, `.t-btn-submit`, or arbitrary Tailwind classes for button styling, as they break the unified design language of this IT Request system.

## General UI Consistency
Whenever adding new UI elements, always inspect the existing design patterns (cards, inputs, layout grids) and match their aesthetics. Rely on the centralized CSS (`resources/css/app.css` / `public/build/assets/app-*.css`) rather than hardcoding styles.

# Flatfolio CMS Theme Guide

Welcome to Flatfolio CMS themes! A theme packages templates, assets, and metadata so you can customize the look and feel of a site without touching core code. Themes live alongside the built-in templates and can override or extend them with their own views and styles.

## Theme directory structure
Create a folder under `themes/` named after your theme slug. A minimal structure looks like:

```
themes/<slug>/
├── theme.json              # Theme metadata (name, author, version, etc.)
├── views/                  # PHP view files used by the TemplateEngine
│   ├── layout.php          # Base layout that renders the body and assets
│   ├── home.php            # Homepage view
│   ├── page.php            # Generic page view
│   ├── projects/
│   │   ├── index.php       # Project listing
│   │   └── single.php      # Project detail
│   ├── timeline.php        # Timeline view
│   ├── skills.php          # Skills view
│   └── partials/
│       ├── header.php
│       └── footer.php
└── assets/
    ├── css/
    │   └── theme.css       # Primary stylesheet
    └── js/
        └── theme.js        # Optional script file
```

You can add more views, partials, or assets as needed. Keep theme-specific files inside the theme folder to avoid coupling with the core templates.

## Expected view files
A basic theme should include the following PHP views under `themes/<slug>/views/`:

- `layout.php`
- `home.php`
- `page.php`
- `projects/index.php`
- `projects/single.php`
- `timeline.php`
- `skills.php`
- `partials/header.php` and `partials/footer.php`

## How view resolution works
Flatfolio CMS uses `TemplateEngine` to resolve view names. Dot notation (`partials.header`) is converted to path segments, so `projects.index` becomes `projects/index.php`.

Resolution order:

1. If a theme is active, the engine first checks `themes/<slug>/views/<view>.php`.
2. If not found, it falls back to the base templates in `templates/<view>.php`.
3. If neither exists, rendering throws an error.

This makes it easy for a theme to override only the views it needs while inheriting the defaults for everything else.

## Activating a theme
Set the theme slug in `config/config.yml`:

```yml
theme: "your-theme-slug"
```

On the next request, the TemplateEngine will look for views in `themes/your-theme-slug/views/` before falling back to `templates/`.

## Referencing assets from the layout
Inside `layout.php`, include your CSS/JS. For now you can use a simple helper or a hardcoded path that points to the public assets directory you expose (e.g., via `public/themes/<slug>/`). Example:

```php
<link rel="stylesheet" href="/themes/<slug>/assets/css/theme.css">
<script src="/themes/<slug>/assets/js/theme.js" defer></script>
```

If you add a helper for asset URLs later, keep the include logic minimal inside the layout.

## Best practices
- **Minimize logic in views.** Prepare data in PHP classes/services, and keep templates focused on presentation.
- **Keep theme-specific files inside the theme directory.** Avoid mixing theme assets with core templates or global `public/` files unless they are intentionally shared.
- **Avoid hardcoded absolute URLs.** Prefer relative or base-URL-aware paths so themes remain portable across environments.
- **Document your theme.** Use `theme.json` to store metadata and keep a short README in the theme folder to help other developers.
- **Reuse partials.** Shared fragments (header, footer, nav) should live under `views/partials/` to avoid duplication.

Happy theming! If you run into issues, check that your view names match the expected paths and that your theme slug in the config matches your folder name.

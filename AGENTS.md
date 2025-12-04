# AGENTS.md – Flatfolio CMS

This file describes how AI coding assistants (ChatGPT, GitHub Copilot, Cursor, etc.)
should interact with the Flatfolio CMS codebase.

## Project Intent

Flatfolio CMS is a **minimal flat-file CMS** aimed at:

- Developer/creator **portfolios**
- Simple profile/landing pages
- CV/Timeline + Projects + Skills + optional blog posts

The focus is not on being a full-blown CMS, but on:

- Clear structure
- Readable, maintainable PHP
- Simple, explicit logic
- Good as a **showcase project** in job applications

## Tech Stack & Constraints

- PHP 8.1+ (no heavy framework)
- Flat files in `content/` (Markdown + YAML/JSON frontmatter)
- Basic routing and template engine
- No relational database
- Optional Composer dependency for Markdown parsing (e.g. Parsedown)

### Directories Overview

- `public/`
  - Entry point: `index.php`
  - Optional `.htaccess` for URL rewrites
- `content/`
  - `pages/` – Profile & generic pages (Markdown)
  - `projects/` – Portfolio entries
  - `timeline/` – Career / education entries
  - `posts/` – Optional blog/notes
  - `skills.yml` – Skill groups
- `templates/`
  - `layout.php` – base layout
  - One template per view type (`home`, `projects`, `timeline`, `post`, etc.)
  - `partials/` – header, footer, small reusable fragments
- `themes/soft-teal/`
  - CSS + JS for the Soft Teal theme
- `config/config.yml`
  - Site name, base URL, theme, etc.
- `src/`
  - PHP classes for routing, content loading, parsing, template rendering
- `tools/`
  - Helper scripts for dev/deploy
- `tests/`
  - PHPUnit tests (planned)

## Content Types

AI agents should respect these content types:

1. **Profile / Pages**
   - Markdown with frontmatter: `title`, `slug`, `template`, etc.
   - Loaded via `ContentRepository`

2. **Timeline**
   - One file per entry in `content/timeline/`
   - Fields: `from`, `to`, `role`, `company`, `type`, `location`, `sort`, etc.

3. **Projects**
   - One file per project in `content/projects/`
   - Fields: `title`, `slug`, `tags`, `tech`, `status`, `role`, `link`, `github`, `sort`, etc.

4. **Skills**
   - YAML file `content/skills.yml`
   - Groups: `tech`, `ux`, `tools`, `soft`, ...

5. **Posts** (optional)
   - Markdown with frontmatter similar to pages, but typically listed as a blog/notes section

6. **Config**
   - `config/config.yml`
   - Holds site-level metadata and options

## Design & Code Style Guidelines

- Prefer **small, focused PHP classes** over large monolithic ones.
- Use **typed properties and type hints** where reasonable.
- Avoid global state; pass dependencies explicitly when possible.
- Keep functions short and readable.
- Prefer expressive naming over cleverness.
- No heavy frameworks; only small, focused helper classes.
- When generating PHP templates, keep logic minimal; push data preparation into `src/` classes.

### Routing

- Very small router:
  - Maps URL path or query (`?page=slug`) to a content object and template.
  - Should handle 404 gracefully.
- Example routes:
  - `/` → home page
  - `/projects` → project listing
  - `/projects/<slug>` → project detail
  - `/timeline` → timeline view
  - `/posts/<slug>` → post detail (optional)

### Template Engine

- Simple implementation:
  - A base layout (`layout.php`) including header/footer and yielding a content template.
  - `TemplateEngine` should take a template name and an associative array of data.
- No complex template DSL – just PHP includes with a thin wrapper.

### Markdown & YAML

- Use a small library (via Composer) if needed:
  - Example: `erusev/parsedown` for Markdown.
  - YAML: `symfony/yaml` or a minimal custom parser if necessary.
- Wrap libraries into own classes (`MarkdownParser`) to keep the code replaceable.

## Interaction Guidelines for AI Assistants

When generating or modifying code:

1. **Respect the flat-file design.**  
   Do not introduce database usage unless explicitly requested.

2. **Preserve directory structure and naming.**  
   Follow the layout described above.

3. **Explain migrations briefly in comments.**  
   When changing content formats or adding fields, add short PHP comments.

4. **Prefer incremental changes.**  
   When editing existing files, change only what is necessary and keep diffs small.

5. **Document public methods.**  
   Use short docblocks for public methods in `src/` classes where helpful.

6. **Keep things understandable.**  
   The project should be approachable for reviewers reading the code on GitHub.

7. **No secrets / credentials.**  
   Never hardcode credentials or secrets; this repo should be safe to publish.

## Non-Goals

- Flatfolio CMS is not meant to be a full WordPress replacement.
- It is not intended to support heavy multi-user access or large-scale content.
- It is not meant to implement a complex admin backend (content is edited as files).

## Local Development Usage

- Target editor: VS Code / Cursor (and ChatGPT/Codex as helper).
- Run local dev via: `php -S localhost:8000 -t public`.
- Deploy changes via Git to a test server.

If you (AI agent) suggest larger refactors, make sure to:

- Explain briefly why.
- Keep backward compatibility with existing content files when possible.
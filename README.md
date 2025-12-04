# Flatfolio CMS

Flatfolio CMS is a small flat-file CMS optimized for developer portfolios, profiles and simple content sites.
It is database-free, file-based and designed to be easy to read, understand and extend – ideal as a showcase
project in job applications.

The focus is on:

- Clean structure
- Simple, explicit PHP code (no heavy framework)
- Flat-file content (Markdown / YAML / JSON)
- A minimal template system
- A reusable themes

---

## Features (Planned / MVP)

Flatfolio CMS will support six main content types:

1. **Profile (Pages)**
   - Markdown pages with frontmatter (e.g. `home`, `about`, `contact`)
   - Example: `content/pages/home.md`

2. **Timeline**
   - Work history, education, freelance, etc.
   - Each entry as a small file with metadata (YAML/JSON/Frontmatter)
   - Example: `content/timeline/2024-snash.md`

3. **Projects**
   - Portfolio projects with title, description, tags, tech stack, links
   - Example: `content/projects/nrw-noir-stack.md`

4. **Skills**
   - Skills grouped into categories (tech, ux, tools, soft skills, etc.)
   - Typically stored in `content/skills.yml`

5. **Posts (optional)**
   - Short articles, notes, case studies
   - Example: `content/posts/how-i-built-flatfolio.md`

6. **Config**
   - Global site configuration
   - Example: `config/config.yml`

The goal is to power a personal portfolio site with:

- A profile / landing page
- Timeline / CV section
- Project overview and detail pages
- Skills overview
- Optional blog/notes section

---

## Repository Layout

Planned repo structure:

```text
flatfolio-cms/
├─ public/
│  ├─ index.php           # Front controller / router entry
│  ├─ .htaccess           # (Optional) URL rewriting for Apache
│  └─ assets/
│     ├─ css/
│     │  └─ app.css
│     └─ js/
│        └─ app.js
├─ content/
│  ├─ pages/              # Profile / general pages (Markdown)
│  ├─ projects/           # Projects (Markdown with frontmatter)
│  ├─ timeline/           # Timeline / CV entries
│  ├─ posts/              # Optional blog posts / notes
│  └─ skills.yml          # Skill groups and tags
├─ templates/
│  ├─ layout.php          # Base layout
│  ├─ home.php            # Home/profile template
│  ├─ projects.php        # Project list template
│  ├─ project-single.php  # Single project view
│  ├─ timeline.php        # Timeline view
│  ├─ post.php            # Single post view
│  └─ partials/
│     ├─ header.php
│     └─ footer.php
├─ themes/
│  └─ soft-teal/
│     ├─ css/
│     │  └─ theme.css     # Soft Teal theme (dark/light)
│     └─ js/
│        └─ theme.js
├─ config/
│  └─ config.yml          # Site config (name, base_url, theme, etc.)
├─ src/
│  ├─ Router.php          # Very small routing layer
│  ├─ ContentRepository.php
│  ├─ MarkdownParser.php  # Wrapper for Parsedown or similar
│  ├─ TemplateEngine.php  # Layout + view rendering
│  └─ helpers.php
├─ tools/
│  └─ dev-server.bat      # Optional: local dev helper
├─ tests/
│  └─ (planned)
├─ .gitignore
├─ README.md
└─ AGENTS.md

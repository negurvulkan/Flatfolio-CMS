<?php
/** @var string $content */
/** @var array $site ?? [] */
/** @var \Flatfolio\Content\MarkdownDocument|null $page ?? null */

$siteTitle   = $site['site_name'] ?? 'Hanjo Studio';
$siteSub     = $site['subtitle']  ?? 'Profil & Portfolio – Web · UX · Storytelling';
$initialMode = 'dark';
?>
<!DOCTYPE html>
<html lang="de" data-theme="<?= htmlspecialchars($initialMode, ENT_QUOTES) ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($page?->getTitle() ?? 'Hanjo Winter – Web, UX & Storytelling', ENT_QUOTES) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/themes/soft-teal/css/theme.css">
</head>
<body>
<main>
    <div class="shell">
        <header class="app-header">
            <div class="brand">
                <div class="brand-title"><?= htmlspecialchars($siteTitle, ENT_QUOTES) ?></div>
                <div class="brand-sub"><?= htmlspecialchars($siteSub, ENT_QUOTES) ?></div>
            </div>
            <div class="header-controls">
                <div class="pill">
                    <div class="dot"></div>
                    verfügbar für Web/UX-Rollen
                </div>
                <button class="mode-toggle" id="modeToggle" type="button">
                    <div class="mode-toggle-bg"></div>
                    <span data-mode="light">Light</span>
                    <span data-mode="dark" class="is-active">Dark</span>
                </button>
            </div>
        </header>

        <?= $content ?>

        <footer>
            <div>© <span id="year"></span> Hanjo Winter · Profil-Landing (Soft Teal)</div>
            <div>
                Sections:
                <a href="/projects">Projekte</a> ·
                <a href="/timeline">Werdegang</a> ·
                <a href="/skills">Skills</a>
            </div>
        </footer>
    </div>
</main>

<script src="/themes/soft-teal/js/theme.js"></script>
</body>
</html>

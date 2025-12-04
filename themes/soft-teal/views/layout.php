<?php
/** @var string $content */
/** @var array $site ?? [] */
/** @var \Flatfolio\Content\MarkdownDocument|null $page ?? null */

// site_name aus Config, falls vom Controller/TemplateEngine mitgegeben
$siteName = $site['site_name'] ?? 'Hanjo Winter – Web · UX · Storytelling';

// Dark/Light Mode initial (default: dark)
$initialTheme = 'dark';
?>
<!DOCTYPE html>
<html lang="de" data-theme="<?= htmlspecialchars($initialTheme, ENT_QUOTES) ?>">
<head>
    <meta charset="UTF-8" />
    <title>
        <?= htmlspecialchars($page?->getTitle() ?? $siteName, ENT_QUOTES) ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="/themes/soft-teal/assets/css/theme.css">
</head>
<body>
<main>
    <div class="shell">
        <?php include __DIR__ . '/partials/header.php'; ?>

        <?= $content ?>

        <?php include __DIR__ . '/partials/footer.php'; ?>
    </div>
</main>

<!-- Theme JS -->
<script src="/themes/soft-teal/assets/js/theme.js"></script>
</body>
</html>

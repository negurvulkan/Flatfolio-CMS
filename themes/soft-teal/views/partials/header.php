<?php
/** @var array $site ?? [] */
$siteTitle = $site['site_name'] ?? 'Hanjo Studio';
$siteSubtitle = $site['subtitle'] ?? 'Profil & Portfolio – Web · UX · Storytelling';
?>
<header class="app-header">
    <div class="brand">
        <div class="brand-title"><?= htmlspecialchars($siteTitle, ENT_QUOTES) ?></div>
        <div class="brand-sub"><?= htmlspecialchars($siteSubtitle, ENT_QUOTES) ?></div>
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

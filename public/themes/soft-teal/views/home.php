<?php
/**
 * @var \Flatfolio\Content\MarkdownDocument|null $page
 * @var \Flatfolio\Content\MarkdownDocument[] $projects ?? []
 * @var \Flatfolio\Content\MarkdownDocument[] $entries ?? []
 * @var array $skills ?? []
 * @var string|null $activeSection ?? null */
 */

$meta    = $page?->getMeta() ?? [];
$title   = $page?->getTitle() ?? 'Hanjo Winter – Web, UX & Storytelling';
$tagline = $meta['tagline']
    ?? 'Baut saubere Interfaces, Landingpages und Tools – irgendwo zwischen Code, Design und erzählten Universen. Fokus auf WordPress, Elementor, UX-Flow und KI-gestützter Entwicklung.';

$chips = $meta['chips'] ?? [
    'WordPress · Elementor Pro',
    'Frontend & UX-Engineering',
    'Page Builder · Custom CSS/JS',
    'Figma · Prototyping',
    'KI-gestützte Workflows',
];

$sectionClass = function(string $id) use ($activeSection): string {
    return $activeSection === $id ? ' is-active-section' : '';
};

?>
<div class="layout">
    <!-- HERO / LEFT -->
    <section class="card">
        <div class="card-inner">
            <div class="chip chip-accent" style="margin-bottom:10px;">Soft Teal · Dark</div>
            <h1><?= htmlspecialchars($title, ENT_QUOTES) ?></h1>
            <p class="tagline">
                <?= htmlspecialchars($tagline, ENT_QUOTES) ?>
            </p>

            <div class="chips">
                <?php foreach ($chips as $chip): ?>
                    <div class="chip"><?= htmlspecialchars($chip, ENT_QUOTES) ?></div>
                <?php endforeach; ?>
            </div>

            <div class="hero-actions">
                <a href="/projects" class="btn btn-primary">
                    <span class="btn-icon">➜</span>
                    <span>Projekte ansehen</span>
                </a>
                <a href="/timeline" class="btn btn-ghost">
                    <span class="btn-icon">⏱</span>
                    <span>Werdegang</span>
                </a>
            </div>

            <div class="meta-row">
                <div>
                    <div class="meta-label">Rollen</div>
                    <div>Webentwicklung · UX · WordPress/Elementor · Storytelling</div>
                </div>
                <div>
                    <div class="meta-label">Arbeitsweise</div>
                    <div>Remote &amp; Hybrid · strukturiert · lösungsorientiert</div>
                </div>
            </div>
        </div>
    </section>

 
</div>

<div class="sections-grid">
    <?php if (!empty($entries)): ?>
        <?php include __DIR__ . '/timeline.php'; ?>
    <?php endif; ?>

    <?php if (!empty($projects)): ?>
        <?php include __DIR__ . '/projects/index.php'; ?>
    <?php endif; ?>
</div>
<div class="sections-grid">
    <?php if (!empty($skills)): ?>
        <?php include __DIR__ . '/skills.php'; ?>
    <?php endif; ?>
</div>

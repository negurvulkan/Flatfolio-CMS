<?php
/** @var \Flatfolio\Content\MarkdownDocument|null $project */
if (!$project) {
    echo '<p>Projekt nicht gefunden.</p>';
    return;
}
$meta = $project->getMeta();
$tags = $meta['tags'] ?? [];
$tech = $meta['tech'] ?? [];
$link = $meta['link'] ?? null;
$github = $meta['github'] ?? null;
?>
<section class="card">
    <div class="card-inner">
        <h1><?= htmlspecialchars($project->getTitle() ?? 'Projekt', ENT_QUOTES) ?></h1>
        <?php if (!empty($meta['short'])): ?>
            <p class="tagline"><?= htmlspecialchars($meta['short'], ENT_QUOTES) ?></p>
        <?php endif; ?>

        <div class="chips">
            <?php foreach ($tech as $t): ?>
                <div class="chip"><?= htmlspecialchars($t, ENT_QUOTES) ?></div>
            <?php endforeach; ?>
        </div>

        <div class="project-body">
            <?= $project->getBodyHtml() ?>
        </div>

        <div class="hero-actions" style="margin-top:16px;">
            <?php if ($link): ?>
                <a href="<?= htmlspecialchars($link, ENT_QUOTES) ?>" target="_blank" class="btn btn-primary">
                    <span class="btn-icon">↗</span>
                    <span>Live ansehen</span>
                </a>
            <?php endif; ?>
            <?php if ($github): ?>
                <a href="<?= htmlspecialchars($github, ENT_QUOTES) ?>" target="_blank" class="btn btn-ghost">
                    <span class="btn-icon">{} </span>
                    <span>GitHub</span>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($tags)): ?>
            <div class="project-tags" style="margin-top:12px;">
                <?php foreach ($tags as $tag): ?>
                    <span class="project-tag"><?= htmlspecialchars($tag, ENT_QUOTES) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

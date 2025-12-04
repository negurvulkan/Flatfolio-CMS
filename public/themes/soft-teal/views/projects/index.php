<?php
/** @var \Flatfolio\Content\MarkdownDocument[] $projects */
?>
<section class="subcard" id="projects">
    <div class="subcard-inner">
        <h2>Ausgewählte Projekte</h2>
        <div class="projects">
            <?php foreach ($projects as $project): ?>
                <?php
                $meta  = $project->getMeta();
                $slug  = $project->getSlug() ?? ($meta['slug'] ?? '');
                $short = $meta['short'] ?? '';
                $badge = $meta['badge'] ?? '';
                $tags  = $meta['tags'] ?? [];
                ?>
                <article class="project">
                    <div class="project-header">
                        <div>
                            <div class="project-title">
                                <a href="/projects/<?= htmlspecialchars($slug, ENT_QUOTES) ?>">
                                    <?= htmlspecialchars($project->getTitle() ?? $slug, ENT_QUOTES) ?>
                                </a>
                            </div>
                            <?php if ($short): ?>
                                <div class="project-tagline">
                                    <?= htmlspecialchars($short, ENT_QUOTES) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($badge): ?>
                            <span class="project-tag <?= ($meta['badge_accent'] ?? false) ? 'is-accent' : '' ?>">
                                <?= htmlspecialchars($badge, ENT_QUOTES) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($tags)): ?>
                        <div class="project-tags">
                            <?php foreach ($tags as $tag): ?>
                                <span class="project-tag"><?= htmlspecialchars($tag, ENT_QUOTES) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

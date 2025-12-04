<?php
/** @var \Flatfolio\Content\MarkdownDocument[] $entries */
?>
<section class="subcard" id="timeline">
    <div class="subcard-inner">
        <h2>Werdegang</h2>
        <div class="timeline">
            <?php foreach ($entries as $entry): ?>
                <?php
                $meta = $entry->getMeta();
                ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-range">
                        <?= htmlspecialchars(($meta['from'] ?? '???') . ' – ' . ($meta['to'] ?? 'heute'), ENT_QUOTES) ?>
                    </div>
                    <div class="timeline-role">
                        <?= htmlspecialchars($meta['role'] ?? $entry->getTitle() ?? 'Rolle', ENT_QUOTES) ?>
                    </div>
                    <div class="timeline-meta">
                        <?= htmlspecialchars(($meta['company'] ?? '') . ' · ' . ($meta['location'] ?? ''), ENT_QUOTES) ?>
                    </div>
                    <div class="timeline-note">
                        <?= $entry->getBodyHtml() ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

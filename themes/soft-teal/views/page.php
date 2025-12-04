<?php
/** @var \Flatfolio\Content\MarkdownDocument|null $page */
if (!$page) {
    echo '<p>Page not found.</p>';
    return;
}
$meta = $page->getMeta();
?>
<section class="card">
    <div class="card-inner">
        <h1><?= htmlspecialchars($page->getTitle() ?? ($meta['slug'] ?? 'Seite'), ENT_QUOTES) ?></h1>
        <div class="tagline">
            <?= isset($meta['subtitle']) ? htmlspecialchars($meta['subtitle'], ENT_QUOTES) : '' ?>
        </div>
        <div class="page-body">
            <?= $page->getBodyHtml() ?>
        </div>
    </div>
</section>

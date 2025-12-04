<section>
    <h2><?= htmlspecialchars($title ?? 'Projekte'); ?></h2>
    <?php if (!empty($projects)): ?>
        <ul>
            <?php foreach ($projects as $project): ?>
                <li><?= htmlspecialchars($project['title'] ?? 'Projekt'); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Es sind noch keine Projekte hinterlegt.</p>
    <?php endif; ?>
</section>

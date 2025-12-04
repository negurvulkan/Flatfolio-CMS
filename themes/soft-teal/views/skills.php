<?php
/** @var array $skills ?? [] */
// Erwartete Keys: tech, ux, tools, soft
$groups = [
    'Web & Frontend'   => $skills['tech']  ?? [],
    'UX, Design & Tools' => $skills['ux']  ?? [],
    'Tech & Struktur'  => $skills['tools'] ?? [],
    'Soft Skills & KI' => array_merge($skills['soft'] ?? [], $skills['ai'] ?? []),
];
?>
<section class="subcard" id="skills">
    <div class="subcard-inner">
        <h2>Skills &amp; Fokus</h2>

        <div class="skills-groups">
            <div>
                <div class="skill-group-title">Web &amp; Frontend</div>
                <div class="skill-chips">
                    <?php foreach ($groups['Web & Frontend'] as $name): ?>
                        <span class="skill-chip is-strong"><?= htmlspecialchars($name, ENT_QUOTES) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <div class="skill-group-title">UX, Design &amp; Tools</div>
                <div class="skill-chips">
                    <?php foreach ($groups['UX, Design & Tools'] as $name): ?>
                        <span class="skill-chip"><?= htmlspecialchars($name, ENT_QUOTES) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="skills-groups" style="margin-top:12px;">
            <div>
                <div class="skill-group-title">Tech &amp; Struktur</div>
                <div class="skill-chips">
                    <?php foreach ($groups['Tech & Struktur'] as $name): ?>
                        <span class="skill-chip"><?= htmlspecialchars($name, ENT_QUOTES) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <div class="skill-group-title">Soft Skills &amp; KI</div>
                <div class="skill-chips">
                    <?php foreach ($groups['Soft Skills & KI'] as $name): ?>
                        <span class="skill-chip"><?= htmlspecialchars($name, ENT_QUOTES) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

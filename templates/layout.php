<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) . ' – ' : '' ?>Flatfolio CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header>
    <h1><a href="/">Flatfolio CMS</a></h1>
    <nav>
        <a href="/">Home</a>
        <a href="/projects">Projekte</a>
    </nav>
</header>
<main>
    <?= $content ?? '' ?>
</main>
<footer>
    <small>&copy; <?= date('Y'); ?> Flatfolio CMS</small>
</footer>
</body>
</html>

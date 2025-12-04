@echo off
SETLOCAL ENABLEDELAYEDEXPANSION

REM ======================================================
REM  Flatfolio CMS – Initial Project Setup Script
REM  Usage: Run this inside the folder where you want
REM  the "flatfolio-cms" directory to be created.
REM ======================================================

SET PROJECT_NAME=flatfolio-cms

echo Creating project folder "%PROJECT_NAME%" ...
mkdir "%PROJECT_NAME%"

cd "%PROJECT_NAME%"
A95C-8DFA
echo Creating directory structure ...

mkdir public
mkdir public\assets
mkdir public\assets\css
mkdir public\assets\js

mkdir content
mkdir content\pages
mkdir content\projects
mkdir content\timeline
mkdir content\posts

mkdir templates
mkdir templates\partials

mkdir themes
mkdir themes\soft-teal
mkdir themes\soft-teal\css
mkdir themes\soft-teal\js

mkdir config
mkdir src
mkdir tools
mkdir tests

REM --- Basic placeholder files ---

echo ^<?php echo "Flatfolio CMS public index (placeholder)"; ^?> > public\index.php

echo body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; } > public\assets\css\app.css
echo console.log("Flatfolio CMS frontend ready."); > public\assets\js\app.js

echo "# Flatfolio CMS" > README.md
echo "# AGENTS.md – see main repo description" > AGENTS.md

echo site_name: "Flatfolio CMS" > config\config.yml
echo base_url: "http://localhost:8000" >> config\config.yml
echo theme: "soft-teal" >> config\config.yml

echo tech: []> content\skills.yml

echo ^<?php
echo // Placeholder Router class >> src\Router.php
echo class Router { >> src\Router.php
echo ^    public function handle(): void { >> src\Router.php
echo ^        echo "Router placeholder - implement me."; >> src\Router.php
echo ^    } >> src\Router.php
echo } >> src\Router.php

echo ^<!DOCTYPE html^> > templates\layout.php
echo ^<html lang="de"^> >> templates\layout.php
echo ^<head^> >> templates\layout.php
echo ^    <meta charset="UTF-8" /^> >> templates\layout.php
echo ^    <title^>Flatfolio CMS^</title^> >> templates\layout.php
echo ^</head^> >> templates\layout.php
echo ^<body^> >> templates\layout.php
echo ^    ^<?php // TODO: include header, main content, footer ^?> >> templates\layout.php
echo ^</body^> >> templates\layout.php
echo ^</html^> >> templates\layout.php

echo Setup complete.
echo You can now open the "%PROJECT_NAME%" folder in Cursor/VS Code.
echo Next steps:
echo  - Replace README.md and AGENTS.md with the real ones from your plan
echo  - Implement Router, ContentRepository, TemplateEngine, etc.
echo  - Run: php -S localhost:8000 -t public

ENDLOCAL

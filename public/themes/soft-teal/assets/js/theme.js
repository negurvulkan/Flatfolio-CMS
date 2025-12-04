// /public/themes/soft-teal/js/theme.js

const toggle = document.getElementById("modeToggle");
const html   = document.documentElement;

if (toggle && html) {
    const spans = toggle.querySelectorAll("span[data-mode]");

    function setMode(mode) {
        html.setAttribute("data-theme", mode);
        spans.forEach(s => s.classList.toggle("is-active", s.dataset.mode === mode));
    }

    toggle.addEventListener("click", () => {
        const current = html.getAttribute("data-theme") === "dark" ? "light" : "dark";
        setMode(current);
    });
}

const yearEl = document.getElementById("year");
if (yearEl) {
    yearEl.textContent = new Date().getFullYear().toString();
}

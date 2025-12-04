  // Dark/Light Toggle
  const toggle = document.getElementById("modeToggle");
  const html = document.documentElement;
  const spans = toggle.querySelectorAll("span[data-mode]");

  function setMode(mode) {
    html.setAttribute("data-theme", mode);
    spans.forEach(s => s.classList.toggle("is-active", s.dataset.mode === mode));
  }

  toggle.addEventListener("click", () => {
    const current = html.getAttribute("data-theme") === "dark" ? "light" : "dark";
    setMode(current);
  });

  // current year
  document.getElementById("year").textContent = new Date().getFullYear();
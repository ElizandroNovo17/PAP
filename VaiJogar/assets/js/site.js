// ==========================
// SITE.JS
// ==========================
document.title = dict["login_title"];

document.addEventListener("DOMContentLoaded", () => {

  /* ===== HERO AUTOMÁTICO ===== */
  const sports = ["futebol", "basquete", "volei"];

  let activeSport = localStorage.getItem("sportTheme") || "futebol";

  document.body.classList.remove(...sports);
  document.body.classList.add(activeSport);

  let index = sports.indexOf(activeSport);

  setInterval(() => {
    document.body.classList.remove(...sports);
    index = (index + 1) % sports.length;
    document.body.classList.add(sports[index]);
    localStorage.setItem("sportTheme", sports[index]);
  }, 10000);

  /* ===== ENTER NO FORM ===== */
  const form = document.querySelector("form");
  if (form) {
    form.addEventListener("submit", e => e.preventDefault());

    form.addEventListener("keydown", e => {
      if (e.key === "Enter") {
        e.preventDefault();
        document.getElementById("loginBtn")?.click();
      }
    });
  }

});


  /* =======================================================
     1. Mostrar/Ocultar senha
  ======================================================= */
  document.querySelectorAll(".toggle-password").forEach(button => {
    button.addEventListener("click", () => {
      const input = button.previousElementSibling;
      if (input.type === "password") {
        input.type = "text";
        button.querySelector("i").classList.replace("bi-eye", "bi-eye-slash");
      } else {
        input.type = "password";
        button.querySelector("i").classList.replace("bi-eye-slash", "bi-eye");
      }
    });
  });

  /* =======================================================
     2. Multi-Idioma
  ======================================================= */
  const langBtn = document.getElementById("currentLangBtn");
  const langMenu = document.getElementById("langMenu");
  const flagMap = { pt: "flags/pt.svg", en: "flags/en.svg", es: "flags/es.svg" };
  const savedLang = localStorage.getItem("siteLang") || "pt";

  async function loadLanguage(lang) {
    try {
      const response = await fetch(`lang/${lang}.json`);
      const data = await response.json();

      document.querySelectorAll("[data-i18n]").forEach(el => {
        const key = el.dataset.i18n;
        if (data[key]) el.innerHTML = data[key];
      });

      document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
        const key = el.dataset.i18nPlaceholder;
        if (data[key]) el.placeholder = data[key];
      });

      langBtn.innerHTML = `<img src="${flagMap[lang]}" style="width:20px;">`;
      localStorage.setItem("siteLang", lang);
    } catch (err) {
      console.error("Erro ao carregar idioma:", err);
    }
  }

  loadLanguage(savedLang);

  langBtn.addEventListener("click", e => { e.stopPropagation(); langMenu.classList.toggle("show"); });
  document.querySelectorAll("#langMenu a").forEach(item => {
    item.addEventListener("click", () => {
      const lang = item.dataset.lang;
      loadLanguage(lang);
      langMenu.classList.remove("show");
    });
  });


  document.addEventListener("click", () => langMenu.classList.remove("show"));
  /* ===== HERO AUTOMÁTICO ===== */

  const sports = ["futebol", "basquete", "volei"];

  // se já existir um guardado, usa esse
  let activeSport = localStorage.getItem("sportTheme");

  // se não existir, começa no futebol
  if (!activeSport) {
    activeSport = "futebol";
    localStorage.setItem("sportTheme", activeSport);
  }

  document.body.classList.add(activeSport);

  // (opcional) trocar automaticamente a cada X segundos
  let index = sports.indexOf(activeSport);

  setInterval(() => {
    document.body.classList.remove(...sports);
    index = (index + 1) % sports.length;
    document.body.classList.add(sports[index]);
    localStorage.setItem("sportTheme", sports[index]);
  }, 12000); // 12 segundos



document.querySelector("form").addEventListener("keydown", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    document.getElementById("loginBtn").click();
  }

});

/* =========================
   FULLSCREEN REAL + AVISO
========================= */

const fullscreenBtn = document.querySelector(".fullscreen-btn");

/* Criar aviso */
const fsHint = document.createElement("div");
fsHint.className = "fullscreen-hint";
fsHint.innerHTML = "🔎 Pressiona <b>F11</b> ou clica no botão para entrar/sair do ecrã inteiro";
document.body.appendChild(fsHint);

function showHint() {
  fsHint.classList.add("show");
  setTimeout(() => fsHint.classList.remove("show"), 3500);
}

/* Botão fullscreen */
function toggleFullscreen() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().then(showHint);
  } else {
    document.exitFullscreen();
    showHint();
  }
}

fullscreenBtn.addEventListener("click", toggleFullscreen);

/* Atualiza ícone */
document.addEventListener("fullscreenchange", () => {
  fullscreenBtn.innerHTML = document.fullscreenElement ? "⤫" : "⤢";
});

/* F11 */
document.addEventListener("keydown", e => {
  if (e.key === "F11") {
    e.preventDefault();
    toggleFullscreen();
  }
});
/* =========================
   PARTÍCULAS
========================= */
const canvas = document.createElement("canvas");
canvas.id = "particles";
document.body.appendChild(canvas);

const ctx = canvas.getContext("2d");
let w, h, particles;

function resize() {
  w = canvas.width = window.innerWidth;
  h = canvas.height = window.innerHeight;
}
window.addEventListener("resize", resize);
resize();

particles = Array.from({ length: 60 }, () => ({
  x: Math.random() * w,
  y: Math.random() * h,
  r: Math.random() * 2 + 1,
  dx: (Math.random() - 0.5) * 0.4,
  dy: (Math.random() - 0.5) * 0.4
}));

function drawParticles() {
  ctx.clearRect(0, 0, w, h);
  particles.forEach(p => {
    p.x += p.dx;
    p.y += p.dy;

    if (p.x < 0 || p.x > w) p.dx *= -1;
    if (p.y < 0 || p.y > h) p.dy *= -1;

    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
    ctx.fillStyle = "rgba(150,90,255,0.6)";
    ctx.fill();
  });
  requestAnimationFrame(drawParticles);
}

drawParticles();
/* =========================
   GLOW SEGUINDO O MOUSE
========================= */
document.addEventListener("mousemove", e => {
  document.body.style.setProperty("--mx", `${e.clientX}px`);
  document.body.style.setProperty("--my", `${e.clientY}px`);
});
/* =========================
   SOM AMBIENTE
========================= */
const ambient = new Audio("sounds/ambient.mp3");
ambient.loop = true;
ambient.volume = 0.15;

document.addEventListener("click", () => {
  if (ambient.paused) ambient.play();
}, { once: true });
/* ===== AVISO F11 (TEXTO DINÂMICO) ===== */

const fsHint = document.createElement("div");
fsHint.className = "fullscreen-hint";
document.body.appendChild(fsHint);

function updateFsText() {
  if (document.fullscreenElement) {
    fsHint.innerHTML = "Clique <b>F11</b> para tirar Tela Cheia";
  } else {
    fsHint.innerHTML = "Clique <b>F11</b> para usar Tela Cheia";
  }

  fsHint.classList.add("show");
  setTimeout(() => fsHint.classList.remove("show"), 3000);
}

/* Detecta entrada/saída de fullscreen */
document.addEventListener("fullscreenchange", updateFsText);

/* Detecta F11 */
document.addEventListener("keydown", e => {
  if (e.key === "F11") {
    setTimeout(updateFsText, 300);
  }
});

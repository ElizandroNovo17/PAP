<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title data-i18n="about_title">Sobre — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <style>
    body { font-family: 'Roboto', sans-serif; }

    .sobre-page {
      min-height: 100vh;
      padding: 120px 40px 60px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Título da página */
    .sobre-heading {
      text-align: center;
      margin-bottom: 40px;
      animation: fadeUp .5s ease forwards;
    }
    .sobre-heading h1 {
      font-size: 2rem;
      font-weight: 800;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }
    .sobre-heading h1 i { color: var(--purple-glow); }
    .sobre-heading p {
      color: rgba(255,255,255,0.5);
      font-size: 14px;
      margin-top: 8px;
    }

    /* Grid de cards */
    .sobre-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      width: 100%;
      max-width: 960px;
    }

    /* Card igual ao admin */
    .sobre-card {
      background: rgba(255,255,255,0.07);
      
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 20px;
      padding: 28px 30px;
      color: white;
      animation: fadeUp .6s ease forwards;
      transition: transform .25s;
    }
    .sobre-card:hover {
      transform: translateY(-4px);
      
    }

    .card-icon {
      width: 48px; height: 48px;
      border-radius: 14px;
      background: rgba(123,44,255,0.2);
      border: 1px solid rgba(123,44,255,0.3);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; color: var(--purple-glow);
      margin-bottom: 16px;
    }
    .sobre-card h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 10px;
      color: white;
    }
    .sobre-card p {
      font-size: 14px;
      line-height: 1.7;
      color: rgba(255,255,255,0.6);
    }

    /* Card principal (destaque) */
    .sobre-card.destaque {
      grid-column: 1 / -1;
      display: flex;
      align-items: center;
      gap: 24px;
      border-color: rgba(123,44,255,0.3);
      background: rgba(123,44,255,0.08);
    }
    .sobre-card.destaque .card-icon {
      width: 60px; height: 60px;
      font-size: 1.6rem;
      flex-shrink: 0;
      background: rgba(123,44,255,0.25);
    }
    .sobre-card.destaque h3 { font-size: 1.1rem; }
    .sobre-card.destaque p  { font-size: 14px; }

    /* Botão voltar */
    .btn-voltar {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 7px 18px; border-radius: 10px;
      background: rgba(255,255,255,0.15);
      border: none; color: white; font-size: 13px; font-weight: 700;
      cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .btn-voltar:hover { transform: translateY(-1px); }

    /* Seletor de idioma */
    .lang-selector-wrap {
      display: flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 10px; padding: 5px 12px;
      cursor: pointer; position: relative;
    }
    .lang-selector-wrap i { color: rgba(255,255,255,0.6); font-size: 14px; }
    .lang-btn-inner {
      background: none; border: none; color: white;
      font-size: 13px; font-weight: 700; cursor: pointer; padding: 0;
      font-family: 'Roboto', sans-serif;
    }
    .lang-menu {
      display: none; position: absolute; top: calc(100% + 8px); right: 0;
      background: rgba(30,10,60,0.95);
      border: 1px solid rgba(255,255,255,0.15); border-radius: 12px;
      overflow: hidden; min-width: 100px; z-index: 999;
    }
    .lang-menu.show { display: block; }
    .lang-menu a {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 16px; color: rgba(255,255,255,0.8);
      font-size: 13px; cursor: pointer; transition: background .15s;
      text-decoration: none;
    }
    .lang-menu a:hover { background: rgba(123,44,255,0.2); color: white; }

    #canvas { position:fixed; top:0; left:0; pointer-events:none; z-index:0; }

    /* ===== RODAPÉ ===== */
    .site-footer {
      position: relative;
      z-index: 10;
      background: rgba(10,2,30,0.85);
      
      border-top: 1px solid rgba(123,44,255,0.25);
      padding: 32px 40px 20px;
      color: rgba(255,255,255,0.55);
      font-family: "Roboto", sans-serif;
      font-size: 13px;
    }
    .footer-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      gap: 28px;
      justify-content: space-between;
      align-items: flex-start;
    }
    .footer-brand { display: flex; flex-direction: column; gap: 8px; }
    .footer-brand img { height: 42px; width: auto; opacity: 1; }
    .footer-brand p { margin: 0; font-size: 12px; max-width: 220px; line-height: 1.6; }
    .footer-links { display: flex; flex-direction: column; gap: 8px; }
    .footer-links h4, .footer-sports h4 {
      margin: 0 0 6px;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--purple-glow);
    }
    .footer-links a, .footer-sports a {
      color: rgba(255,255,255,0.5);
      text-decoration: none;
      font-size: 13px;
      transition: color .2s;
      display: flex; align-items: center; gap: 6px;
    }
    .footer-links a:hover, .footer-sports a:hover { color: white; }
    .footer-sports { display: flex; flex-direction: column; gap: 8px; }
    .footer-bottom {
      max-width: 1100px;
      margin: 20px auto 0;
      padding-top: 16px;
      border-top: 1px solid rgba(255,255,255,0.07);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      font-size: 12px;
    }
    .footer-bottom span { color: rgba(255,255,255,0.3); }
    .footer-bottom a { color: rgba(123,44,255,0.8); text-decoration: none; }
    .footer-bottom a:hover { color: var(--purple-glow); }
      .header-logo img { height: 45px; width: auto; }
  </style>
</head>
<body>

  <canvas id="canvas"></canvas>

  <!-- HEADER -->
  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo" /></a>
    </div>
    <nav class="header-nav">
      <a href="index.php"><i class="bi bi-house-fill"></i> <span data-i18n="nav_home">Voltar</span></a>
      <a href="sobre.php" style="color:var(--purple-glow);font-weight:700;"><i class="bi bi-info-circle-fill"></i> <span data-i18n="nav_about">Sobre</span></a>
    </nav>
    <div style="display:flex;align-items:center;gap:10px;margin-left:auto;">
      <div class="lang-selector-wrap" id="langWrap">
        <i class="bi bi-globe"></i>
        <button class="lang-btn-inner" id="currentLangBtn"><span id="currentLangText">PT</span></button>
        <div class="lang-menu" id="langMenu">
          <a data-lang="pt"><i class="bi bi-translate"></i> Português</a>
          <a data-lang="en"><i class="bi bi-translate"></i> English</a>
          <a data-lang="es"><i class="bi bi-translate"></i> Español</a>
        </div>
      </div>
      <div class="user-menu" id="userMenu" style="position:relative;">
        <button class="user-btn" id="userBtn" style="display:inline-flex;align-items:center;gap:8px;padding:7px 14px;border-radius:10px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:white;font-size:13px;font-weight:600;cursor:pointer;font-family:'Roboto',sans-serif;width:auto;">
          <div class="avatar-mini" id="headerAvatar" style="width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;overflow:hidden;flex-shrink:0;color:white;"></div>
          <span id="headerNome">...</span>
          <i class="bi bi-chevron-down" style="font-size:11px;opacity:.6;"></i>
        </button>
        <div class="user-dropdown" id="userDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;background:rgba(20,5,50,0.97);border:1px solid rgba(255,255,255,0.12);border-radius:14px;overflow:hidden;min-width:200px;z-index:9999;">
          <div class="dropdown-header" style="padding:14px 16px 10px;border-bottom:1px solid rgba(255,255,255,0.08);">
            <div class="d-nome" id="dropNome" style="font-size:14px;font-weight:700;color:white;">—</div>
            <div class="d-email" id="dropEmail" style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:2px;">—</div>
          </div>
          <a href="perfil.php" style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:rgba(255,255,255,0.75);font-size:13px;text-decoration:none;transition:background .15s;">
            <i class="bi bi-person-fill" style="color:var(--purple-glow);"></i> O meu perfil
          </a>
          <div id="adminNavLink" style="display:none;">
            <a href="admin.php" style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:rgba(255,255,255,0.75);font-size:13px;text-decoration:none;">
              <i class="bi bi-shield-lock-fill" style="color:var(--purple-glow);"></i> Painel Admin
            </a>
          </div>
          <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:4px 0;" />
          <button onclick="window.location.href='PHP/auth.php?action=logout&redirect=../index.php'" style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:#f87171;font-size:13px;cursor:pointer;background:none;border:none;width:100%;font-family:'Roboto',sans-serif;">
            <i class="bi bi-box-arrow-right" style="color:#f87171;"></i> Sair da conta
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- CONTEÚDO -->
  <main class="sobre-page">

    <div class="sobre-heading">
      <h1><i class="bi bi-info-circle-fill"></i> <span data-i18n="about_heading">Sobre o VaiJogar</span></h1>
      <p data-i18n="about_subtitle">Tudo o que precisas de saber sobre a plataforma</p>
    </div>

    <div class="sobre-grid">

      <!-- Card destaque -->
      <div class="sobre-card destaque">
        <div class="card-icon"><i class="bi bi-trophy-fill"></i></div>
        <div>
          <h3 data-i18n="about_heading">Sobre o VaiJogar</h3>
          <p data-i18n="about_text1">Bem-vindo ao VaiJogar. Esta plataforma destina-se a ligar atletas e clubes desportivos através de geolocalização, facilitando o acesso à informação sobre modalidades como futebol, basquetebol e voleibol.</p>
        </div>
      </div>

      <!-- Card objetivo -->
      <div class="sobre-card">
        <div class="card-icon"><i class="bi bi-bullseye"></i></div>
        <h3 data-i18n="card_objetivo">Objetivo</h3>
        <p data-i18n="about_text2">O nosso principal objetivo é disponibilizar conteúdos rigorosos e informativos, direcionados a adeptos e praticantes, promovendo o conhecimento e a valorização do desporto.</p>
      </div>

      <!-- Card comunidade -->
      <div class="sobre-card">
        <div class="card-icon"><i class="bi bi-people-fill"></i></div>
        <h3 data-i18n="card_comunidade">Comunidade</h3>
        <p data-i18n="about_text3">Navega pelo menu para descobrir os clubes perto de ti e consulta as avaliações da nossa comunidade, que enriquecem a plataforma com opiniões e experiências diversas.</p>
      </div>

      <!-- Card modalidades -->
      <div class="sobre-card">
        <div class="card-icon"><i class="bi bi-dribbble"></i></div>
        <h3 data-i18n="card_modalidades">Modalidades</h3>
        <p data-i18n="card_text_modalidades">Futebol, basquetebol e voleibol — encontra clubes, consulta informações e descobre tudo sobre o desporto que praticas ou acompanhas.</p>
      </div>

      <!-- Card geolocalização -->
      <div class="sobre-card">
        <div class="card-icon"><i class="bi bi-geo-alt-fill"></i></div>
        <h3 data-i18n="card_geolocalizacao">Geolocalização</h3>
        <p data-i18n="card_text_geo">Descobre clubes perto de ti com o nosso mapa interativo. Calcula distâncias e obtém direções para chegares facilmente ao clube que escolheres.</p>
      </div>

      <!-- Card agradecimento -->
      <div class="sobre-card">
        <div class="card-icon"><i class="bi bi-heart-fill"></i></div>
        <h3 data-i18n="card_obrigado">Obrigado</h3>
        <p data-i18n="about_text4">Agradecemos a tua visita e desejamos-te uma experiência enriquecedora e agradável no VaiJogar.</p>
      </div>

    </div>
  </main>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
          <img src="assets/images/logo2.png" alt="VaiJogar" style="height:42px;width:auto;" />
          <span style="font-size:20px;font-weight:900;color:white;letter-spacing:-0.5px">VaiJogar</span>
        </div>
        <p>Plataforma de geolocalização de clubes desportivos em Portugal.</p>
      </div>
      <div class="footer-links">
        <h4>Navegação</h4>
        <a href="index.php"><i class="bi bi-house-fill"></i> Voltar</a>
        <a href="escolha.php"><i class="bi bi-grid-fill"></i> Modalidades</a>
        <a href="sobre.php"><i class="bi bi-info-circle-fill"></i> Sobre</a>
        <a href="perfil.php"><i class="bi bi-person-fill"></i> Perfil</a>
      </div>
      <div class="footer-sports">
        <h4>Modalidades</h4>
        <a href="mapa.php"><i class="bi bi-dribbble"></i> Futebol</a>
        <a href="basket.php"><i class="bi bi-record-circle"></i> Basquetebol</a>
        <a href="volei.php"><i class="bi bi-circle"></i> Voleibol</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 VaiJogar — Todos os direitos reservados.</span>
      <span>Desenvolvido por <a href="#">Elizandro Novo</a> · PAP 2025/2026</span>
    </div>
  </footer>

  <button class="fullscreen-btn" title="Tela cheia"
    onclick="toggleFullscreen()">
    <i class="bi bi-fullscreen"></i>
  </button>

  <script>
    document.addEventListener("DOMContentLoaded", async () => {
      // Sessão + perfil no header
      try {
        const r = await fetch("PHP/auth.php?action=session");
        const d = await r.json();
        if (d.autenticado) {
          const user = d.utilizador;
          const av = document.getElementById("headerAvatar");
          if (user.avatar && user.avatar !== "default.png" && user.avatar.startsWith("data:")) {
            av.innerHTML = `<img src="${user.avatar}" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />`;
          } else {
            av.textContent = user.nome.split(" ").map(n=>n[0]).slice(0,2).join("").toUpperCase();
          }
          document.getElementById("headerNome").textContent = user.nome.split(" ")[0];
          document.getElementById("dropNome").textContent   = user.nome;
          document.getElementById("dropEmail").textContent  = user.email;
          if (user.role === "admin") document.getElementById("adminItem").style.display = "block";
        }
      } catch(e) {}

      document.getElementById("userBtn").addEventListener("click", e => {
        e.stopPropagation();
        const dd = document.getElementById("userDropdown");
        dd.style.display = dd.style.display === "block" ? "none" : "block";
      });
      document.addEventListener("click", () => { document.getElementById("userDropdown").style.display = "none"; });

      // Idioma
      const langWrap = document.getElementById("langWrap");
      const langBtn  = document.getElementById("currentLangBtn");
      const langMenu = document.getElementById("langMenu");
      const langText = document.getElementById("currentLangText");
      const saved    = localStorage.getItem("siteLang") || "pt";

      async function loadLang(lang) {
        try {
          const resp = await fetch(`lang/${lang}.json`);
          if (!resp.ok) throw new Error();
          const dict = await resp.json();
          document.querySelectorAll("[data-i18n]").forEach(el => {
            const k = el.getAttribute("data-i18n");
            if (dict[k]) el.textContent = dict[k];
          });
          langText.textContent = lang.toUpperCase();
          localStorage.setItem("siteLang", lang);
        } catch(e) { console.warn("Erro ao carregar idioma:", lang); }
      }

      loadLang(saved);

      langWrap.addEventListener("click", e => {
        e.stopPropagation();
        langMenu.classList.toggle("show");
      });
      document.querySelectorAll("#langMenu a").forEach(a => {
        a.addEventListener("click", () => {
          loadLang(a.getAttribute("data-lang"));
          langMenu.classList.remove("show");
        });
      });
      document.addEventListener("click", () => langMenu.classList.remove("show"));

      // Partículas
      const canvas = document.getElementById("canvas");
      const ctx = canvas.getContext("2d"); let w, h;
      function resize() { w = canvas.width = window.innerWidth; h = canvas.height = window.innerHeight; }
      window.addEventListener("resize", resize); resize();
      const pts = Array.from({length:60}, () => ({
        x:Math.random()*w, y:Math.random()*h,
        r:Math.random()*2+1, dx:(Math.random()-.5)*.4, dy:(Math.random()-.5)*.4
      }));
      function draw() {
        ctx.clearRect(0,0,w,h);
        pts.forEach(p => {
          p.x+=p.dx; p.y+=p.dy;
          if(p.x<0||p.x>w) p.dx*=-1;
          if(p.y<0||p.y>h) p.dy*=-1;
          ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
          ctx.fillStyle="rgba(150,90,255,0.6)"; ctx.fill();
        });
        requestAnimationFrame(draw);
      }
      draw();
      document.addEventListener("mousemove", e => {
        document.body.style.setProperty("--mx", `${e.clientX}px`);
        document.body.style.setProperty("--my", `${e.clientY}px`);
      });

      // Sport rotation
      const sports = ["futebol","basquete","volei"];
      let activeSport = localStorage.getItem("sportTheme") || "futebol";
      document.body.classList.add(activeSport);
      let idx = sports.indexOf(activeSport);
      setInterval(() => {
        document.body.classList.remove(...sports);
        idx = (idx+1) % sports.length;
        document.body.classList.add(sports[idx]);
        localStorage.setItem("sportTheme", sports[idx]);
      }, 12000);
    });
  </script>
  <script src="assets/js/fullscreen.js"></script>
</body>
</html>
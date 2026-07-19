<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Escolha a Modalidade — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/escolha.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <style>
    .user-menu { position: relative; }
    /* Garante que o user-menu fica sempre no canto direito do header */
    .header > .user-menu { margin-left: 0; }
    .user-btn {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 7px 16px; border-radius: 10px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.15);
      color: white; font-size: 13px; font-weight: 600;
      cursor: pointer; transition: all .2s; font-family: 'Roboto', sans-serif;
    }
    .user-btn:hover { background: rgba(255,255,255,0.14); }
    .avatar-mini {
      width: 26px; height: 26px; border-radius: 50%;
      background: rgba(255,255,255,0.15);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; overflow: hidden; flex-shrink: 0;
    }
    .avatar-mini img { width: 100%; height: 100%; object-fit: cover; }
    .user-dropdown {
      display: none; position: absolute; top: calc(100% + 10px); right: 0;
      background: rgba(20,5,50,0.97); 
      border: 1px solid rgba(255,255,255,0.12); border-radius: 14px;
      overflow: hidden; min-width: 200px; z-index: 999;
      
    }
    .user-dropdown.show { display: block; }
    .dropdown-header { padding: 14px 16px 10px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .dropdown-header .d-nome  { font-size: 14px; font-weight: 700; color: white; }
    .dropdown-header .d-email { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 2px; }
    .dropdown-item {
      display: flex; align-items: center; gap: 10px;
      padding: 11px 16px; color: rgba(255,255,255,0.75);
      font-size: 13px; cursor: pointer; transition: background .15s;
      text-decoration: none; border: none; background: none;
      width: 100%; font-family: 'Roboto', sans-serif;
    }
    .dropdown-item:hover { background: rgba(123,44,255,0.2); color: white; }
    .dropdown-item i { font-size: 15px; color: var(--purple-glow); width: 18px; }
    .dropdown-item.danger { color: #f87171; }
    .dropdown-item.danger i { color: #f87171; }
    .dropdown-item.danger:hover { background: rgba(239,68,68,0.15); }
    .dropdown-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 4px 0; }
    .admin-badge {
      font-size: 10px; background: rgba(245,158,11,0.2);
      color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);
      border-radius: 6px; padding: 1px 6px; font-weight: 700;
    }

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
    .footer-brand img { height: 36px; opacity: .9; }
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

    .lang-selector-wrap { display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;padding:5px 12px;cursor:pointer;position:relative; }
    .lang-selector-wrap i { color:rgba(255,255,255,0.6);font-size:14px; }
    .lang-btn-inner { background:none!important;border:none!important;color:white!important;font-size:13px;font-weight:700;cursor:pointer;padding:0!important;font-family:"Roboto",sans-serif;width:auto!important; }
    .lang-menu { display:none;position:absolute;top:calc(100% + 8px);right:0;background:rgba(20,5,50,0.97);border:1px solid rgba(255,255,255,0.15);border-radius:12px;overflow:hidden;min-width:150px;z-index:9999; }
    .lang-menu.show { display:block; }
    .lang-menu a { display:flex;align-items:center;gap:8px;padding:10px 16px;color:rgba(255,255,255,0.8);font-size:13px;cursor:pointer;transition:background .15s;text-decoration:none; }
    .lang-menu a:hover { background:rgba(123,44,255,0.2);color:white; }
    .header-nav a { text-decoration:none!important; }
  </style>

</head>
<body>

  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo" /></a>
    </div>
    <nav class="header-nav">
      <a href="sobre.php"><i class="bi bi-info-circle"></i> <span data-i18n="nav_about">Sobre</span></a>
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
    <div class="user-menu" id="userMenu">
      <button class="user-btn" id="userBtn">
        <div class="avatar-mini" id="headerAvatar"></div>
        <span id="headerNome">...</span>
        <i class="bi bi-chevron-down" style="font-size:11px;opacity:.6;"></i>
      </button>
      <div class="user-dropdown" id="userDropdown">
        <div class="dropdown-header">
          <div class="d-nome" id="dropNome">—</div>
          <div class="d-email" id="dropEmail">—</div>
        </div>
        <a class="dropdown-item" href="perfil.php">
          <i class="bi bi-person-fill"></i> O meu perfil
        </a>
        <div id="adminItem" style="display:none;">
          <a class="dropdown-item" href="admin.php">
            <i class="bi bi-shield-lock-fill"></i> Painel Admin
            <span class="admin-badge">ADMIN</span>
          </a>
        </div>
        <hr class="dropdown-divider" />
        <button class="dropdown-item danger" onclick="window.location.href='PHP/auth.php?action=logout&redirect=../index.php'">
          <i class="bi bi-box-arrow-right"></i> Sair da conta
        </button>
      </div>
    </div>
    </div>
  </header>

  <!-- CARDS com emojis originais -->
  <div class="cards-container">

    <section class="card futebol" onclick="irPara('mapa.php')">
      <div class="content">
        <div class="icon">⚽</div>
        <h2 data-i18n="footer_football">Futebol</h2>
        <p data-i18n="explore_football">Explorar clubes de Futebol</p>
      </div>
    </section>

    <section class="card basket" onclick="irPara('basket.php')">
      <div class="content">
        <div class="icon">🏀</div>
        <h2 data-i18n="footer_basket">Basquetebol</h2>
        <p data-i18n="explore_basket">Explorar clubes de Basquetebol</p>
      </div>
    </section>

    <section class="card voleibol" onclick="irPara('volei.php')">
      <div class="content">
        <div class="icon">🏐</div>
        <h2 data-i18n="footer_volley">Voleibol</h2>
        <p data-i18n="explore_volley">Explorar clubes de Voleibol</p>
      </div>
    </section>

  </div>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <img src="assets/images/logo2.png" alt="VaiJogar" />
        <p>Plataforma de geolocalização de clubes desportivos em Portugal.</p>
      </div>
      <div class="footer-links">
        <h4>Navegação</h4>
        <a href="index.php"><i class="bi bi-house-fill"></i> Início</a>
        <a href="escolha.php"><i class="bi bi-grid-fill"></i> Modalidades</a>
        <a href="sobre.php"><i class="bi bi-info-circle-fill"></i data-i18n="nav_about"> Sobre</a>
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

  <script src="assets/js/escolha.js"></script>
  <script>
    async function init() {
      try {
        const r = await fetch("PHP/auth.php?action=session");
        const d = await r.json();
        if (!d.autenticado) { window.location.href = "index.php"; return; }
        const user = d.utilizador;
        const av = document.getElementById("headerAvatar");
        if (user.avatar && user.avatar !== "default.png" && user.avatar.startsWith("data:")) {
          av.innerHTML = `<img src="${user.avatar}" alt="avatar" />`;
        } else {
          av.textContent = user.nome.split(" ").map(n=>n[0]).slice(0,2).join("").toUpperCase();
        }
        document.getElementById("headerNome").textContent = user.nome.split(" ")[0];
        document.getElementById("dropNome").textContent   = user.nome;
        document.getElementById("dropEmail").textContent  = user.email;
        if (user.role === "admin") document.getElementById("adminItem").style.display = "block";
      } catch(e) { window.location.href = "index.php"; }
    }

    document.getElementById("userBtn").addEventListener("click", e => {
      e.stopPropagation();
      document.getElementById("userDropdown").classList.toggle("show");
    });
    document.addEventListener("click", () => document.getElementById("userDropdown").classList.remove("show"));

    init();
  
      // Tradução gerida pelo tradutor.js
    </script>
  <script src="assets/js/tradutor.js"></script>
  <script src="assets/js/fullscreen.js"></script>
</body>
</html>
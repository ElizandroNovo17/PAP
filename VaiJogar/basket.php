<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Basquetebol — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="assets/css/mapa.css" />
  <style>
    /* Estilos do header dropdown — específicos desta página */
    .header-right { display: flex; align-items: center; gap: 12px; }
    .lang-selector-wrap { display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; padding: 5px 12px; cursor: pointer; position: relative; }
    .lang-selector-wrap i { color: rgba(255,255,255,0.6); font-size: 14px; }
    .lang-btn-inner { background: none; border: none; color: white; font-size: 13px; font-weight: 700; cursor: pointer; padding: 0; font-family: 'Roboto', sans-serif; }
    .lang-menu { display: none; position: absolute; top: calc(100% + 8px); right: 0; background: rgba(20,5,50,0.97);  border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; overflow: hidden; min-width: 140px; z-index: 9999; }
    .lang-menu.show { display: block; }
    .lang-menu a { display: flex; align-items: center; gap: 8px; padding: 10px 16px; color: rgba(255,255,255,0.8); font-size: 13px; cursor: pointer;  text-decoration: none; }
    .lang-menu a:hover { background: rgba(123,44,255,0.2); color: white; }
    .user-menu { position: relative; }
    .user-btn { display: inline-flex; align-items: center; gap: 8px; padding: 7px 16px; border-radius: 10px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: white; font-size: 13px; font-weight: 600; cursor: pointer;  font-family: 'Roboto', sans-serif;  }
    .user-btn:hover { background: rgba(255,255,255,0.14); transform: none !important; }
    .avatar-mini { width: 26px; height: 26px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; overflow: hidden; flex-shrink: 0; color: white; }
    .avatar-mini img { width: 100%; height: 100%; object-fit: cover; }
    .user-dropdown { display: none; position: absolute; top: calc(100% + 10px); right: 0; background: rgba(20,5,50,0.97);  border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; overflow: hidden; min-width: 200px; z-index: 9999;  }
    .user-dropdown.show { display: block; }
    .dropdown-header { padding: 14px 16px 10px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .dropdown-header .d-nome  { font-size: 14px; font-weight: 700; color: white; }
    .dropdown-header .d-email { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 2px; }
    .dropdown-item { display: flex; align-items: center; gap: 10px; padding: 11px 16px; color: rgba(255,255,255,0.75); font-size: 13px; cursor: pointer;  text-decoration: none; border: none; background: none !important; width: 100%; font-family: 'Roboto', sans-serif;  }
    .dropdown-item:hover { background: rgba(123,44,255,0.2) !important; color: white; transform: none !important; }
    .dropdown-item i { font-size: 15px; color: rgba(255,255,255,0.7); width: 18px; }
    .dropdown-item.danger { color: #f87171; }
    .dropdown-item.danger i { color: #f87171; }
    .dropdown-item.danger:hover { background: rgba(239,68,68,0.15) !important; }
    .dropdown-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 4px 0; }
    .admin-badge { font-size: 10px; background: rgba(245,158,11,0.2); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); border-radius: 6px; padding: 1px 6px; font-weight: 700; }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo" /></a>
    </div>
        <nav class="header-nav">
      <a href="sobre.php"><i class="bi bi-info-circle-fill"></i> <span data-i18n="nav_about">Sobre</span></a>
      <span class="nav-sep"></span>
      <div id="filtro-modalidade">
        <button class="filtro-btn" data-mod="futebol">
          <span data-i18n="futebol">Futebol</span>
        </button>
        <button class="filtro-btn active" data-mod="basquetebol">
          <span data-i18n="basquetebol">Basquetebol</span>
        </button>
        <button class="filtro-btn" data-mod="voleibol">
          <span data-i18n="voleibol">Voleibol</span>
        </button>
      </div>
    </nav>
    <div class="header-right">
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
          <a class="dropdown-item" href="perfil.php"><i class="bi bi-person-fill"></i> <span data-i18n="profile">O meu perfil</span></a>
          <div id="adminItem" style="display:none;">
            <a class="dropdown-item" href="admin.php">
              <i class="bi bi-shield-lock-fill"></i> <span data-i18n="admin_panel">Painel Admin</span>
              <span class="admin-badge">ADMIN</span>
            </a>
          </div>
          <hr class="dropdown-divider" />
          <button class="dropdown-item danger" onclick="window.location.href='PHP/auth.php?action=logout&redirect=../index.php'">
            <i class="bi bi-box-arrow-right"></i> <span data-i18n="logout">Sair da conta</span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- SIDEBAR -->
  <div id="sidebar">
    <div class="search-wrap" id="placeWrap">
      <input id="place-search" type="text" placeholder="Procurar cidade ou local..." data-i18n-placeholder="search_place" autocomplete="off" />
      <div id="place-suggestions"></div>
    </div>
    <div class="search-wrap" id="clubeWrap">
      <input id="search-input" type="text" placeholder="Procurar clube..." data-i18n-placeholder="search_club" autocomplete="off" />
      <div id="suggestions"></div>
    </div>

    <!-- CLUBES MAIS PRÓXIMOS -->
    <div id="proximos-wrap">
      <div class="sidebar-section-title"><i class="bi bi-geo-alt-fill"></i> <span data-i18n="nearby_clubs">Clubes mais próximos</span></div>
      <div id="proximos-list">
        <div class="proximos-loading"><i class="bi bi-compass" style="font-size:22px;opacity:.4;"></i><br><span data-i18n="waiting_location">A aguardar localização...</span></div>
      </div>
    </div>

    <button id="btn-localizacao"><i class="bi bi-geo-alt-fill"></i> <span data-i18n="my_location">Minha localização</span></button>
    <button id="btn-todos"><i class="bi bi-eye"></i> <span data-i18n="show_hide_clubs">Mostrar/Esconder todos os clubes</span></button>
  </div>

  <div id="map-modes">
    <button data-map="streets"><i class="bi bi-map"></i> <span data-i18n="streets">Estradas</span></button>
    <button data-map="dark"><i class="bi bi-moon-stars-fill"></i> <span data-i18n="night">Noite</span></button>
    <button data-map="satellite"><i class="bi bi-globe2"></i> <span data-i18n="satellite">Satélite</span></button>
    <button data-map="topo"><i class="bi bi-triangle"></i> <span data-i18n="topographic">Topográfico</span></button>
    <button data-map="hybrid"><i class="bi bi-layers-fill"></i> <span data-i18n="hybrid">Híbrido</span></button>
    <button data-map="minimal"><i class="bi bi-compass"></i> <span data-i18n="minimal">Minimalista</span></button>
  </div>

  <div id="map"></div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script>window._modalidadeInicial = "basquetebol";</script>
  <script src="assets/js/mapa.js"></script>
  <script src="assets/js/tradutor.js"></script>
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
  </script>
</body>
</html>
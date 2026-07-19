<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Backoffice — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #07060f; --surface: #0f0d1e; --surface2: #151228;
      --border: rgba(255,255,255,0.07); --border2: rgba(255,255,255,0.13);
      --accent: #6c3fff; --accent2: #9b6dff; --accent-dim: rgba(108,63,255,0.14);
      --gold: #f4a623; --green: #22c55e; --red: #ef4444; --blue: #3b82f6;
      --text: #ede9ff; --muted: rgba(237,233,255,0.5); --muted2: rgba(237,233,255,0.28);
      --sidebar-w: 232px;
    }
    html, body { height: 100%; background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 14px; line-height: 1.5; }

    /* LAYOUT */
    .app { display: grid; grid-template-columns: var(--sidebar-w) 1fr; min-height: 100vh; }

    /* SIDEBAR */
    .sidebar {
      background: var(--surface); border-right: 1px solid var(--border);
      display: flex; flex-direction: column;
      position: sticky; top: 0; height: 100vh; overflow-y: auto; z-index: 100;
    }
    .sb-brand {
      padding: 20px 16px 18px; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; gap: 11px;
    }
    .sb-brand img { height: 26px; }
    .sb-brand-name { font-size: 13px; font-weight: 800; letter-spacing: .01em; }
    .sb-brand-sub  { font-size: 10px; color: var(--accent2); font-weight: 600; letter-spacing: .1em; text-transform: uppercase; margin-top: 1px; }
    .sb-section { padding: 18px 10px 6px; }
    .sb-section-lbl {
      font-size: 9px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
      color: var(--muted2); padding: 0 8px; margin-bottom: 4px;
    }
    .nav-item {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: 7px;
      color: var(--muted); font-size: 13px; font-weight: 500;
      cursor: pointer;
      border: 1px solid transparent; background: none; width: 100%;
      text-align: left; font-family: 'DM Sans', sans-serif; text-decoration: none;
    }
    .nav-item i { font-size: 14px; width: 17px; flex-shrink: 0; }
    .nav-item:hover { color: var(--text); background: rgba(255,255,255,0.045); }
    .nav-item.active { color: var(--text); background: var(--accent-dim); border-color: rgba(108,63,255,0.22); }
    .nav-item.active i { color: var(--accent2); }
    .nav-badge {
      margin-left: auto; background: var(--accent); color: #fff;
      font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 20px; min-width: 18px; text-align: center;
    }
    .sb-bottom { margin-top: auto; padding: 14px 10px; border-top: 1px solid var(--border); }
    .admin-profile { display: flex; align-items: center; gap: 10px; padding: 8px 8px 10px; }
    .admin-avatar {
      width: 32px; height: 32px; border-radius: 7px; flex-shrink: 0;
      background: rgba(255,255,255,0.15);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; color: #fff;
    }
    .admin-name { font-size: 12px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .admin-role { font-size: 10px; color: var(--gold); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
    .btn-logout {
      width: 100%; padding: 8px 12px; background: rgba(239,68,68,.08);
      border: 1px solid rgba(239,68,68,.2); border-radius: 7px; color: #f87171;
      font-size: 12px; font-weight: 600; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 7px;
      font-family: 'DM Sans', sans-serif;
    }
    .btn-logout:hover { background: rgba(239,68,68,.18); border-color: rgba(239,68,68,.45); }

    /* MAIN */
    .main { display: flex; flex-direction: column; min-height: 100vh; overflow: hidden; }

    /* TOPBAR */
    .topbar {
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 28px; height: 56px; border-bottom: 1px solid var(--border);
      background: rgba(7,6,15,.85); 
      position: sticky; top: 0; z-index: 50; gap: 14px; flex-shrink: 0;
    }
    .topbar-title { font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 9px; }
    .topbar-title i { color: var(--accent2); }
    .topbar-right { display: flex; align-items: center; gap: 10px; }
    .search-bar {
      display: flex; align-items: center; gap: 7px;
      background: var(--surface2); border: 1px solid var(--border2);
      border-radius: 7px; padding: 0 11px; transition: border-color .18s;
    }
    .search-bar:focus-within { border-color: rgba(108,63,255,.45); }
    .search-bar i { color: var(--muted2); font-size: 12px; }
    .search-bar input {
      background: none; border: none; outline: none; color: var(--text);
      font-size: 12px; padding: 7px 0; width: 180px; font-family: 'DM Sans', sans-serif;
    }
    .search-bar input::placeholder { color: var(--muted2); }
    .topbar-btn {
      display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px;
      border-radius: 7px; font-size: 12px; font-weight: 600; cursor: pointer;
      border: 1px solid var(--border2); background: var(--surface2); color: var(--muted);
      text-decoration: none; font-family: 'DM Sans', sans-serif;
    }
    .topbar-btn:hover { color: var(--text); background: rgba(255,255,255,.06); }

    /* PAGE */
    .page { padding: 26px 28px; flex: 1; }
    .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 14px; flex-wrap: wrap; }
    .page-title    { font-size: 20px; font-weight: 800; letter-spacing: -.3px; }
    .page-subtitle { font-size: 12px; color: var(--muted); margin-top: 3px; }

    /* STAT GRID */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-bottom: 24px; }
    .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 16px 18px; transition: border-color .18s; }
    .stat-card:hover { border-color: var(--border2); }
    .stat-card-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .stat-card-lbl { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .07em; }
    .stat-card-icon { width: 30px; height: 30px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
    .stat-card-num { font-size: 26px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
    .stat-card-sub { font-size: 10px; color: var(--muted2); margin-top: 4px; }
    .icon-purple { background: rgba(108,63,255,.18); color: var(--accent2); }
    .icon-gold   { background: rgba(244,166,35,.18); color: var(--gold); }
    .icon-green  { background: rgba(34,197,94,.18);  color: #4ade80; }
    .icon-red    { background: rgba(239,68,68,.18);  color: #f87171; }
    .icon-blue   { background: rgba(59,130,246,.18); color: #60a5fa; }
    .icon-teal   { background: rgba(20,184,166,.18); color: #2dd4bf; }

    /* PANEL */
    .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
    .panel-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 18px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap;
    }
    .panel-title { font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 7px; }
    .panel-title i { color: var(--accent2); }
    .panel-actions { display: flex; gap: 8px; align-items: center; }

    /* TABLE */
    .tbl-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      padding: 9px 16px; text-align: left; font-size: 10px; font-weight: 700;
      letter-spacing: .08em; text-transform: uppercase; color: var(--muted2);
      border-bottom: 1px solid var(--border); white-space: nowrap;
    }
    tbody tr { transition: background .1s; }
    tbody tr:hover { background: rgba(255,255,255,.025); }
    tbody td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    .tbl-empty { text-align: center; padding: 48px; color: var(--muted2); }
    .tbl-empty i { font-size: 2rem; display: block; margin-bottom: 10px; opacity: .25; }

    /* USER CELLS */
    .user-cell { display: flex; align-items: center; gap: 10px; }
    .u-avatar {
      width: 32px; height: 32px; border-radius: 7px; flex-shrink: 0;
      background: rgba(255,255,255,0.15);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; overflow: hidden; color: #fff;
    }
    .u-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .u-name  { font-size: 13px; font-weight: 600; }
    .u-email { font-size: 11px; color: var(--muted2); }

    /* BADGES */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 8px; border-radius: 20px;
      font-size: 9px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap;
    }
    .badge-admin { background: rgba(244,166,35,.12); color: var(--gold);   border: 1px solid rgba(244,166,35,.25); }
    .badge-user  { background: var(--accent-dim);    color: var(--accent2); border: 1px solid rgba(108,63,255,.25); }
    .badge-pend  { background: rgba(251,191,36,.1);  color: #fbbf24; border: 1px solid rgba(251,191,36,.22); }
    .badge-conf  { background: rgba(74,222,128,.1);  color: #4ade80; border: 1px solid rgba(74,222,128,.22); }
    .badge-canc  { background: rgba(248,113,113,.08);color: #f87171; border: 1px solid rgba(248,113,113,.2); }
    .badge-reemb { background: rgba(165,180,252,.1); color: #a5b4fc; border: 1px solid rgba(165,180,252,.22); }
    .badge-on  { background: rgba(74,222,128,.1);  color: #4ade80; border: 1px solid rgba(74,222,128,.2); }
    .badge-off { background: rgba(248,113,113,.07);color: #f87171; border: 1px solid rgba(248,113,113,.18); }

    /* BUTTONS */
    .btn {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 7px 13px; border-radius: 7px; font-size: 12px; font-weight: 600;
      cursor: pointer; border: none; font-family: 'DM Sans', sans-serif;
    }
    .btn-primary { background: var(--accent); color: #fff;  }
    .btn-primary:hover { background: #7d52ff; }
    .btn-ghost { background: var(--surface2); border: 1px solid var(--border2); color: var(--muted); }
    .btn-ghost:hover { color: var(--text); background: rgba(255,255,255,.06); }
    .btn-role    { background: rgba(108,63,255,.12); border: 1px solid rgba(108,63,255,.22); color: var(--accent2); }
    .btn-role:hover    { background: rgba(108,63,255,.25); }
    .btn-danger  { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.2);  color: #f87171; }
    .btn-danger:hover  { background: rgba(239,68,68,.22); }
    .btn-success { background: rgba(34,197,94,.1);  border: 1px solid rgba(34,197,94,.2);  color: #4ade80; }
    .btn-success:hover { background: rgba(34,197,94,.22); }
    .btn-sm { padding: 5px 9px; font-size: 11px; }

    /* FILTER CHIPS */
    .filter-bar { display: flex; gap: 5px; flex-wrap: wrap; align-items: center; }
    .filter-chip {
      padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600;
      cursor: pointer; border: 1px solid var(--border2); background: transparent;
      color: var(--muted); font-family: 'DM Sans', sans-serif;
      display: inline-flex; align-items: center; gap: 5px;
    }
    .filter-chip:hover { color: var(--text); border-color: rgba(108,63,255,.35); }
    .filter-chip.on { background: var(--accent-dim); border-color: rgba(108,63,255,.35); color: var(--accent2); }

    /* CLUB CARDS */
    .clubs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; padding: 18px; }
    .club-card { background: var(--surface2); border: 1px solid var(--border); border-radius: 9px; padding: 15px; }
    .club-card:hover { border-color: var(--border2);  }
    .club-card-head { display: flex; align-items: center; gap: 11px; margin-bottom: 11px; }
    .club-logo {
      width: 42px; height: 42px; border-radius: 9px; flex-shrink: 0;
      background: var(--accent-dim); border: 1px solid rgba(108,63,255,.18);
      display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .club-logo img { width: 34px; height: 34px; object-fit: contain; }
    .club-logo i { color: var(--accent2); font-size: 1.1rem; }
    .club-name { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
    .club-sub  { font-size: 11px; color: var(--muted2); display: flex; align-items: center; gap: 3px; }
    .club-chips { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 11px; }
    .chip {
      display: inline-flex; align-items: center; gap: 3px;
      padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700;
    }
    .chip-mod { background: var(--accent-dim); color: var(--accent2); }
    .chip-div { background: rgba(244,166,35,.1); color: #fbbf24; }
    .club-actions { display: flex; gap: 6px; }
    .club-actions .btn { flex: 1; justify-content: center; }

    /* BOOKING TABLE */
    .ref-code { font-family: 'DM Mono', monospace; font-size: 11px; color: var(--accent2); background: var(--accent-dim); padding: 2px 7px; border-radius: 5px; }

    /* BOOKING STATS BAR */
    .bk-stats { display: flex; gap: 8px; flex-wrap: wrap; padding: 14px 18px; border-bottom: 1px solid var(--border); }
    .bk-stat { flex: 1; min-width: 75px; padding: 10px 12px; background: var(--surface2); border: 1px solid var(--border); border-radius: 7px; text-align: center; }
    .bk-stat-num { font-size: 1.3rem; font-weight: 800; letter-spacing: -1px; }
    .bk-stat-lbl { font-size: 9px; color: var(--muted2); text-transform: uppercase; letter-spacing: .06em; margin-top: 1px; }

    /* INLINE SEARCH */
    .inline-search {
      display: flex; align-items: center; gap: 7px;
      background: var(--surface2); border: 1px solid var(--border2);
      border-radius: 7px; padding: 0 11px; transition: border-color .18s;
    }
    .inline-search:focus-within { border-color: rgba(108,63,255,.4); }
    .inline-search i { color: var(--muted2); font-size: 12px; }
    .inline-search input {
      background: none; border: none; outline: none; color: var(--text);
      font-size: 12px; padding: 7px 0; width: 160px; font-family: 'DM Sans', sans-serif;
    }
    .inline-search input::placeholder { color: var(--muted2); }

    /* MODAL */
    .modal-overlay {
      display: none; position: fixed; inset: 0; z-index: 9999;
      background: rgba(0,0,0,.72); 
      align-items: center; justify-content: center; padding: 20px;
    }
    .modal-overlay.show { display: flex; }
    .modal {
      background: var(--surface2); border: 1px solid var(--border2);
      border-radius: 12px; width: 100%; max-width: 660px; max-height: 90vh;
      overflow-y: auto; 
      animation: modalIn .2s ease;
    }
    @keyframes modalIn { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    .modal-head {
      padding: 18px 22px; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; background: var(--surface2); z-index: 1;
    }
    .modal-head-title { font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
    .modal-head-title i { color: var(--accent2); }
    .modal-close { background: none; border: none; color: var(--muted2); cursor: pointer; padding: 5px; border-radius: 6px; font-size: 15px; transition: all .15s; }
    .modal-close:hover { color: var(--text); background: rgba(255,255,255,.07); }
    .modal-body { padding: 22px; }

    /* FORM */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }
    .form-full { grid-column: 1/-1; }
    .form-row  { display: flex; flex-direction: column; gap: 5px; }
    .form-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); }
    .form-input, .form-select, .form-textarea {
      width: 100%; padding: 8px 11px; background: rgba(255,255,255,.05);
      border: 1px solid var(--border2); border-radius: 7px; color: var(--text);
      font-size: 13px; font-family: 'DM Sans', sans-serif; outline: none; transition: border .16s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: rgba(108,63,255,.5); background: rgba(108,63,255,.05); }
    .form-select option { background: #0f0d1e; }
    .form-textarea { resize: vertical; min-height: 78px; }
    .form-divider { grid-column: 1/-1; height: 1px; background: var(--border); margin: 5px 0; }
    .form-section {
      grid-column: 1/-1; font-size: 10px; font-weight: 700;
      letter-spacing: .09em; text-transform: uppercase; color: var(--accent2);
      display: flex; align-items: center; gap: 6px; margin-top: 3px;
    }
    .form-check { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: var(--muted); }
    .form-check input { width: 14px; height: 14px; accent-color: var(--accent); cursor: pointer; }
    .btn-submit {
      width: 100%; margin-top: 18px; padding: 11px; background: var(--accent);
      border: none; border-radius: 8px; color: #fff;
      font-size: 13px; font-weight: 700; cursor: pointer; font-family: 'DM Sans', sans-serif;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      
    }
    .btn-submit:hover { background: #7d52ff; }
    .btn-submit:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    /* MISC */
    .loading-row { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 44px; color: var(--muted2); font-size: 13px; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spin { animation: spin 1s linear infinite; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp .3s ease; }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }
    .toast { position: fixed; bottom: 22px; right: 22px; z-index: 99999; padding: 10px 17px; border-radius: 8px; font-size: 13px; font-weight: 600;  display: flex; align-items: center; gap: 7px; }
    .toast-ok  { background: rgba(34,197,94,.15);  border: 1px solid rgba(34,197,94,.35);  color: #4ade80; }
    .toast-err { background: rgba(239,68,68,.15);  border: 1px solid rgba(239,68,68,.35);  color: #f87171; }

    /* DASH COLS */
    .dash-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .recent-item { display: flex; align-items: center; gap: 10px; padding: 10px 18px; border-bottom: 1px solid var(--border); }
    .recent-item:last-child { border-bottom: none; }

    @media (max-width: 860px) {
      .app { grid-template-columns: 1fr; }
      .sidebar { display: none; }
      .page { padding: 18px 14px; }
      .topbar { padding: 0 14px; }
      .form-grid { grid-template-columns: 1fr; }
      .dash-cols { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-brand">
      <img src="assets/images/logo2.png" alt="VaiJogar" />
      <div>
        <div class="sb-brand-name">VaiJogar</div>
        <div class="sb-brand-sub">Backoffice</div>
      </div>
    </div>
    <div class="sb-section">
      <div class="sb-section-lbl">Principal</div>
      <button class="nav-item active" id="nav-dashboard"    onclick="mudarTab('dashboard')"><i class="bi bi-grid-1x2-fill"></i> Dashboard</button>
      <button class="nav-item"        id="nav-utilizadores" onclick="mudarTab('utilizadores')"><i class="bi bi-people-fill"></i> Utilizadores<span class="nav-badge" id="nbUsers" style="display:none"></span></button>
      <button class="nav-item"        id="nav-clubes"       onclick="mudarTab('clubes')"><i class="bi bi-building"></i> Clubes<span class="nav-badge" id="nbClubes" style="display:none"></span></button>
    </div>
    <div class="sb-section">
      <div class="sb-section-lbl">Financeiro</div>
      <button class="nav-item" id="nav-marcacoes" onclick="mudarTab('marcacoes')"><i class="bi bi-calendar-check-fill"></i> Marcações<span class="nav-badge" id="nbMarc" style="display:none"></span></button>
      <button class="nav-item" id="nav-planos"    onclick="mudarTab('planos')"><i class="bi bi-layers-fill"></i> Planos</button>
      <button class="nav-item" id="nav-horarios"  onclick="mudarTab('horarios')"><i class="bi bi-clock-fill"></i> Horários</button>
    </div>
    <div class="sb-section">
      <div class="sb-section-lbl">Site</div>
      <a class="nav-item" href="escolha.php" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Ver site</a>
      <a class="nav-item" href="mapa.php"   target="_blank"><i class="bi bi-map-fill"></i> Mapa</a>
    </div>
    <div class="sb-bottom">
      <div class="admin-profile">
        <div class="admin-avatar" id="adminInitials">A</div>
        <div><div class="admin-name" id="adminName">Administrador</div><div class="admin-role">Admin</div></div>
      </div>
      <button class="btn-logout" onclick="window.location.href='PHP/auth.php?action=logout&redirect=../index.php'"><i class="bi bi-box-arrow-right"></i> Terminar sessão</button>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-title" id="topbarTitle"><i class="bi bi-grid-1x2-fill"></i> Dashboard</div>
      <div class="topbar-right">
        <div class="search-bar">
          <i class="bi bi-search"></i>
          <input type="text" id="globalSearch" placeholder="Pesquisar..." oninput="onGlobalSearch()" />
        </div>
        <a class="topbar-btn" href="perfil.php"><i class="bi bi-person-fill"></i> Perfil</a>
      </div>
    </div>

    <!-- DASHBOARD -->
    <div class="tab-panel active" id="tab-dashboard">
      <div class="page">
        <div class="page-header">
          <div><div class="page-title">Dashboard</div><div class="page-subtitle">Visão geral da plataforma VaiJogar.</div></div>
        </div>
        <div class="stat-grid">
          <div class="stat-card fade-up">
            <div class="stat-card-top"><div class="stat-card-lbl">Utilizadores</div><div class="stat-card-icon icon-purple"><i class="bi bi-people-fill"></i></div></div>
            <div class="stat-card-num" id="dsTotal">—</div><div class="stat-card-sub">Total registados</div>
          </div>
          <div class="stat-card fade-up" style="animation-delay:.05s">
            <div class="stat-card-top"><div class="stat-card-lbl">Admins</div><div class="stat-card-icon icon-gold"><i class="bi bi-shield-lock-fill"></i></div></div>
            <div class="stat-card-num" id="dsAdmins">—</div><div class="stat-card-sub">Com acesso total</div>
          </div>
          <div class="stat-card fade-up" style="animation-delay:.08s">
            <div class="stat-card-top"><div class="stat-card-lbl">Futebol</div><div class="stat-card-icon icon-green"><i class="bi bi-dribbble"></i></div></div>
            <div class="stat-card-num" id="dsFutebol">—</div><div class="stat-card-sub">Clubes no mapa</div>
          </div>
          <div class="stat-card fade-up" style="animation-delay:.11s">
            <div class="stat-card-top"><div class="stat-card-lbl">Basquetebol</div><div class="stat-card-icon icon-gold"><i class="bi bi-record-circle"></i></div></div>
            <div class="stat-card-num" id="dsBasket">—</div><div class="stat-card-sub">Clubes no mapa</div>
          </div>
          <div class="stat-card fade-up" style="animation-delay:.14s">
            <div class="stat-card-top"><div class="stat-card-lbl">Voleibol</div><div class="stat-card-icon icon-blue"><i class="bi bi-circle"></i></div></div>
            <div class="stat-card-num" id="dsVolei">—</div><div class="stat-card-sub">Clubes no mapa</div>
          </div>
          <div class="stat-card fade-up" style="animation-delay:.17s">
            <div class="stat-card-top"><div class="stat-card-lbl">Receita</div><div class="stat-card-icon icon-teal"><i class="bi bi-currency-euro"></i></div></div>
            <div class="stat-card-num" id="dsReceita">—</div><div class="stat-card-sub">Marcações confirmadas</div>
          </div>
        </div>
        <div class="dash-cols">
          <div class="panel">
            <div class="panel-header">
              <div class="panel-title"><i class="bi bi-person-plus-fill"></i> Utilizadores recentes</div>
              <button class="btn btn-ghost btn-sm" onclick="mudarTab('utilizadores')">Ver todos</button>
            </div>
            <div id="dsRecentUsers"><div class="loading-row"><i class="bi bi-arrow-repeat spin"></i></div></div>
          </div>
          <div class="panel">
            <div class="panel-header">
              <div class="panel-title"><i class="bi bi-calendar-check-fill"></i> Marcações recentes</div>
              <button class="btn btn-ghost btn-sm" onclick="mudarTab('marcacoes')">Ver todas</button>
            </div>
            <div id="dsRecentBookings"><div class="loading-row"><i class="bi bi-arrow-repeat spin"></i></div></div>
          </div>
        </div>
      </div>
    </div>

    <!-- UTILIZADORES -->
    <div class="tab-panel" id="tab-utilizadores">
      <div class="page">
        <div class="page-header">
          <div><div class="page-title">Utilizadores</div><div class="page-subtitle">Gerir contas e permissões.</div></div>
        </div>
        <div class="panel">
          <div class="panel-header">
            <div class="panel-title"><i class="bi bi-people-fill"></i> Todos os utilizadores</div>
            <div class="panel-actions">
              <div class="inline-search"><i class="bi bi-search"></i><input type="text" id="searchUsers" placeholder="Nome ou email..." oninput="filtrarTabela()" /></div>
            </div>
          </div>
          <div id="loadingMsg" class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          <div class="tbl-wrap"><table id="usersTable" style="display:none"><thead><tr><th>Utilizador</th><th>Tipo</th><th>Membro desde</th><th>Ações</th></tr></thead><tbody id="usersBody"></tbody></table></div>
        </div>
      </div>
    </div>

    <!-- CLUBES -->
    <div class="tab-panel" id="tab-clubes">
      <div class="page">
        <div class="page-header">
          <div><div class="page-title">Clubes</div><div class="page-subtitle">Gerir clubes desportivos no mapa.</div></div>
          <button class="btn btn-primary" onclick="abrirModalClube(null)"><i class="bi bi-plus-lg"></i> Novo Clube</button>
        </div>
        <div class="panel">
          <div class="panel-header">
            <div class="filter-bar" id="filtrosClubes">
              <button class="filter-chip on"  onclick="filtrarModal('')">Todos</button>
              <button class="filter-chip"     onclick="filtrarModal('futebol')"><i class="bi bi-dribbble"></i> Futebol</button>
              <button class="filter-chip"     onclick="filtrarModal('basquetebol')"><i class="bi bi-record-circle"></i> Basquetebol</button>
              <button class="filter-chip"     onclick="filtrarModal('voleibol')"><i class="bi bi-circle"></i> Voleibol</button>
            </div>
            <div class="inline-search" style="margin-left:auto">
              <i class="bi bi-search"></i><input type="text" id="searchClubes" placeholder="Pesquisar clube..." oninput="pesquisarClubes()" />
            </div>
          </div>
          <div id="clubesGrid" class="clubs-grid"><div class="loading-row" style="grid-column:1/-1"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div></div>
        </div>
      </div>
    </div>

    <!-- MARCACOES -->
    <div class="tab-panel" id="tab-marcacoes">
      <div class="page">
        <div class="page-header">
          <div><div class="page-title">Marcações</div><div class="page-subtitle">Gerir reservas, próximos treinos e histórico.</div></div>
        </div>

        <!-- Stats -->
        <div class="panel" style="margin-bottom:16px">
          <div class="bk-stats">
            <div class="bk-stat"><div class="bk-stat-num" id="ams-total" style="color:var(--accent2)">—</div><div class="bk-stat-lbl">Total</div></div>
            <div class="bk-stat"><div class="bk-stat-num" id="ams-pend"  style="color:#fbbf24">—</div><div class="bk-stat-lbl">Pendentes</div></div>
            <div class="bk-stat"><div class="bk-stat-num" id="ams-conf"  style="color:#4ade80">—</div><div class="bk-stat-lbl">Confirmados</div></div>
            <div class="bk-stat"><div class="bk-stat-num" id="ams-canc"  style="color:#f87171">—</div><div class="bk-stat-lbl">Cancelados</div></div>
            <div class="bk-stat"><div class="bk-stat-num" id="ams-rev"   style="color:#2dd4bf">—</div><div class="bk-stat-lbl">Receita (€)</div></div>
          </div>
        </div>

        <!-- Sub-tabs -->
        <div style="display:flex;gap:8px;margin-bottom:16px">
          <button class="filter-chip on" id="mst-todas"   onclick="mudarSubTabMarc('todas')">Todas</button>
          <button class="filter-chip"   id="mst-proximos" onclick="mudarSubTabMarc('proximos')"><i class="bi bi-calendar-check"></i> Próximos Treinos</button>
          <button class="filter-chip"   id="mst-passadas" onclick="mudarSubTabMarc('passadas')"><i class="bi bi-clock-history"></i> Historial</button>
        </div>

        <!-- Todas -->
        <div class="panel" id="marcSubTodas">
          <div class="panel-header">
            <div class="filter-bar" id="filtrosMarcacoes">
              <button class="filter-chip on" id="fmTodos"  onclick="filtrarMarcAdmin('')">Todos</button>
              <button class="filter-chip"    id="fmPend"   onclick="filtrarMarcAdmin('pendente')">Pendentes</button>
              <button class="filter-chip"    id="fmConf"   onclick="filtrarMarcAdmin('confirmado')">Confirmados</button>
              <button class="filter-chip"    id="fmCanc"   onclick="filtrarMarcAdmin('cancelado')">Cancelados</button>
              <button class="filter-chip"    id="fmReemb"  onclick="filtrarMarcAdmin('reembolsado')">Reembolsados</button>
            </div>
            <div class="inline-search" style="margin-left:auto">
              <i class="bi bi-search"></i><input type="text" id="searchMarcAdmin" placeholder="Utilizador, clube, ref..." oninput="renderMarcAdmin()" />
            </div>
          </div>
          <div id="marcAdminLoading" class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          <div class="tbl-wrap" id="marcAdminTableWrap" style="display:none">
            <table>
              <thead><tr><th>#</th><th>Utilizador</th><th>Clube / Plano</th><th>Horário</th><th>Método</th><th>Valor</th><th>Referência</th><th>Estado</th><th>Data</th><th>Ações</th></tr></thead>
              <tbody id="marcAdminBody"></tbody>
            </table>
          </div>
          <div class="tbl-empty" id="marcAdminEmpty" style="display:none"><i class="bi bi-calendar-x"></i>Nenhuma marcação encontrada.</div>
        </div>

        <!-- Próximos Treinos -->
        <div class="panel" id="marcSubProximos" style="display:none">
          <div class="panel-header"><span class="panel-title"><i class="bi bi-calendar-check-fill"></i> Marcações Ativas — Próximos Treinos</span></div>
          <div id="marcProximosLista" style="padding:16px">
            <div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          </div>
        </div>

        <!-- Historial / Passadas -->
        <div class="panel" id="marcSubPassadas" style="display:none">
          <div class="panel-header"><span class="panel-title"><i class="bi bi-clock-history"></i> Historial de Marcações</span></div>
          <div id="marcPassadasLista" style="padding:16px">
            <div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          </div>
        </div>

      </div>
    </div>


    <!-- ══ TAB PLANOS ══════════════════════════════════════ -->
    <div class="tab-panel" id="tab-planos">
      <div class="page">
        <div class="page-header">
          <div><div class="page-title">Planos & Extras</div><div class="page-subtitle">Editar preços, limites de dias e extras do plano Premium.</div></div>
        </div>

        <!-- PLANOS -->
        <div class="panel" style="margin-bottom:20px">
          <div class="panel-header">
            <span class="panel-title"><i class="bi bi-layers-fill"></i> Planos de Treino</span>
          </div>
          <div id="planosLista" style="padding:16px">
            <div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          </div>
        </div>

        <!-- EXTRAS PREMIUM -->
        <div class="panel">
          <div class="panel-header" style="display:flex;justify-content:space-between;align-items:center">
            <span class="panel-title"><i class="bi bi-star-fill"></i> Extras Premium</span>
            <button class="btn btn-primary btn-sm" onclick="abrirNovoExtra()"><i class="bi bi-plus-lg"></i> Adicionar Extra</button>
          </div>
          <div id="extrasLista" style="padding:16px">
            <div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ TAB HORÁRIOS ════════════════════════════════════ -->
    <div class="tab-panel" id="tab-horarios">
      <div class="page">
        <div class="page-header">
          <div><div class="page-title">Horários de Treino</div><div class="page-subtitle">Editar dias e horas de treino por clube e escalão.</div></div>
        </div>
        <div class="panel">
          <div class="panel-header" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <span class="panel-title"><i class="bi bi-clock-fill"></i> Filtrar</span>
            <select class="form-select" id="horClubeFiltro" style="max-width:200px;padding:6px 10px" onchange="carregarHorariosAdmin()">
              <option value="">Todos os clubes</option>
            </select>
            <select class="form-select" id="horEscalaoFiltro" style="max-width:160px;padding:6px 10px" onchange="renderHorariosAdmin()">
              <option value="">Todos os escalões</option>
            </select>
          </div>
          <div id="horariosAdminLista" style="padding:16px">
            <div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /main -->
</div><!-- /app -->

<!-- MODAL CLUBE -->
<div class="modal-overlay" id="modalClube">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-head-title"><i class="bi bi-building"></i><span id="modalClubeTitle">Novo Clube</span></div>
      <button class="modal-close" onclick="fecharModalClube()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-body">
      <form id="formClube" onsubmit="guardarClube(event)">
        <input type="hidden" id="cId">
        <div class="form-grid">
          <div class="form-section"><i class="bi bi-info-circle-fill"></i> Informação Básica</div>
          <div class="form-row form-full"><label class="form-label">Nome do Clube *</label><input class="form-input" id="cNome" type="text" placeholder="ex: SL Benfica" required></div>
          <div class="form-row"><label class="form-label">Modalidade *</label><select class="form-select" id="cModalidade" required><option value="">Selecionar...</option><option value="futebol">⚽ Futebol</option><option value="basquetebol">🏀 Basquetebol</option><option value="voleibol">🏐 Voleibol</option></select></div>
          <div class="form-row"><label class="form-label">Divisão</label><input class="form-input" id="cDivisao" type="text" placeholder="ex: 1ª Liga"></div>
          <div class="form-row"><label class="form-label">Localização</label><input class="form-input" id="cLocalizacao" type="text" placeholder="ex: Lisboa"></div>
          <div class="form-row"><label class="form-label">Recinto / Estádio</label><input class="form-input" id="cRecinto" type="text" placeholder="ex: Estádio da Luz"></div>
          <div class="form-row form-full"><label class="form-label">Descrição</label><textarea class="form-textarea" id="cDescricao" placeholder="Breve descrição do clube..."></textarea></div>
          <div class="form-row"><label class="form-label">URL do Logo</label><input class="form-input" id="cImagem" type="url" placeholder="https://..."></div>
          <div class="form-row"><label class="form-label">Preço de Inscrição</label><input class="form-input" id="cPreco" type="text" placeholder="ex: A partir de €29.99/mês"></div>
          <div class="form-divider"></div>
          <div class="form-section"><i class="bi bi-telephone-fill"></i> Contactos</div>
          <div class="form-row"><label class="form-label">Telefone</label><input class="form-input" id="cTelefone" type="text" placeholder="+351 ..."></div>
          <div class="form-row"><label class="form-label">Email</label><input class="form-input" id="cEmail" type="email" placeholder="geral@clube.pt"></div>
          <div class="form-row"><label class="form-label">Website</label><input class="form-input" id="cWebsite" type="url" placeholder="https://..."></div>
          <div class="form-row"><label class="form-label">Facebook</label><input class="form-input" id="cFacebook" type="url" placeholder="https://facebook.com/..."></div>
          <div class="form-row"><label class="form-label">Instagram</label><input class="form-input" id="cInstagram" type="url" placeholder="https://instagram.com/..."></div>
          <div class="form-divider"></div>
          <div class="form-section"><i class="bi bi-geo-alt-fill"></i> Coordenadas GPS</div>
          <div class="form-row"><label class="form-label">Latitude</label><input class="form-input" id="cLat" type="number" step="any" placeholder="ex: 38.7527"></div>
          <div class="form-row"><label class="form-label">Longitude</label><input class="form-input" id="cLng" type="number" step="any" placeholder="ex: -9.1849"></div>
          <div class="form-divider"></div>
          <div class="form-row form-full"><label class="form-check"><input type="checkbox" id="cAtivo" checked> Clube ativo (visível no mapa)</label></div>
        </div>
        <button class="btn-submit" type="submit" id="btnGuardar"><i class="bi bi-floppy-fill"></i> Guardar Clube</button>
      </form>
    </div>
  </div>
</div>

<script>
  // ─── NAVIGATION ─────────────────────────────────────────
  const TAB_ICONS  = { dashboard:'bi-grid-1x2-fill', utilizadores:'bi-people-fill', clubes:'bi-building', marcacoes:'bi-calendar-check-fill', planos:'bi-layers-fill', horarios:'bi-clock-fill' };
  const TAB_LABELS = { dashboard:'Dashboard', utilizadores:'Utilizadores', clubes:'Clubes', marcacoes:'Marcações', planos:'Planos & Extras', horarios:'Horários de Treino' };

  function mudarTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-item[id^="nav-"]').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('nav-' + tab)?.classList.add('active');
    document.getElementById('topbarTitle').innerHTML = `<i class="bi ${TAB_ICONS[tab]}"></i> ${TAB_LABELS[tab]}`;
    if (tab === 'clubes'       && !window._clubesCarregados)    carregarClubes();
    if (tab === 'marcacoes'    && !window._marcAdminCarregadas) carregarMarcacoesAdmin();
    if (tab === 'planos'       && !window._planosCarregados)   carregarPlanos();
    if (tab === 'horarios'     && !window._horariosAdminCarregados) carregarHorariosAdmin();
  }

  function onGlobalSearch() {
    const q = document.getElementById('globalSearch').value;
    document.getElementById('searchUsers').value  = q;
    document.getElementById('searchClubes').value = q;
    filtrarTabela(); pesquisarClubes();
  }

  // ─── UTILIZADORES ───────────────────────────────────────
  let allUsers = [], currentUserId = null;

  async function carregarUtilizadores() {
    try {
      const r = await fetch('PHP/admin_api.php?action=users_list');
      const d = await r.json();
      if (!d.sucesso) { document.getElementById('loadingMsg').innerHTML = '<i class="bi bi-exclamation-triangle-fill" style="color:#f87171"></i> Erro ao carregar.'; return; }
      allUsers = d.utilizadores;
      renderTabela(allUsers);
      document.getElementById('dsTotal').textContent  = allUsers.length;
      document.getElementById('dsAdmins').textContent = allUsers.filter(u => u.role === 'admin').length;
      document.getElementById('loadingMsg').style.display = 'none';
      document.getElementById('usersTable').style.display = 'table';
      const nb = document.getElementById('nbUsers');
      nb.textContent = allUsers.length; nb.style.display = 'inline';
      renderRecentUsers(allUsers.slice(0,5));
    } catch(e) { document.getElementById('loadingMsg').innerHTML = '<i class="bi bi-wifi-off"></i> Erro de ligação.'; }
  }

  function renderTabela(users) {
    const tbody = document.getElementById('usersBody');
    if (!users.length) { tbody.innerHTML = `<tr><td colspan="4" class="tbl-empty"><i class="bi bi-search"></i>Nenhum utilizador encontrado.</td></tr>`; return; }
    tbody.innerHTML = users.map(u => {
      const isMe  = u.id == currentUserId;
      const ini   = u.nome.split(' ').map(n => n[0]).slice(0,2).join('').toUpperCase();
      const av    = (u.avatar && u.avatar !== 'default.png' && u.avatar.startsWith('data:')) ? `<img src="${u.avatar}">` : ini;
      const data  = new Date(u.criado_em).toLocaleDateString('pt-PT');
      const badge = u.role === 'admin'
        ? `<span class="badge badge-admin"><i class="bi bi-shield-lock-fill"></i> Admin</span>`
        : `<span class="badge badge-user"><i class="bi bi-person-fill"></i> User</span>`;
      const acoes = isMe
        ? `<span style="opacity:.4;font-size:11px;display:flex;align-items:center;gap:5px"><i class="bi bi-person-check-fill"></i> A tua conta</span>`
        : `<button class="btn btn-role btn-sm" onclick="mudarRole(${u.id},'${u.role}','${u.nome.replace(/'/g,"\\'")}')">
             ${u.role==='admin'?'<i class="bi bi-arrow-down-circle-fill"></i> Tornar user':'<i class="bi bi-arrow-up-circle-fill"></i> Tornar admin'}
           </button>
           <button class="btn btn-danger btn-sm" onclick="apagarUser(${u.id},'${u.nome.replace(/'/g,"\\'")}')">
             <i class="bi bi-trash3-fill"></i>
           </button>`;
      return `<tr id="row-${u.id}">
        <td><div class="user-cell"><div class="u-avatar">${av}</div><div><div class="u-name">${u.nome}</div><div class="u-email">${u.email}</div></div></div></td>
        <td>${badge}</td>
        <td style="color:var(--muted2);font-size:12px">${data}</td>
        <td><div style="display:flex;gap:6px;flex-wrap:wrap">${acoes}</div></td>
      </tr>`;
    }).join('');
  }

  function renderRecentUsers(users) {
    const el = document.getElementById('dsRecentUsers');
    if (!users.length) { el.innerHTML = '<div class="tbl-empty" style="padding:26px"><i class="bi bi-people"></i>Sem utilizadores.</div>'; return; }
    el.innerHTML = users.map(u => {
      const ini = u.nome.split(' ').map(n=>n[0]).slice(0,2).join('').toUpperCase();
      const av  = (u.avatar&&u.avatar!=='default.png'&&u.avatar.startsWith('data:')) ? `<img src="${u.avatar}">` : ini;
      return `<div class="recent-item">
        <div class="u-avatar">${av}</div>
        <div style="flex:1;min-width:0"><div class="u-name" style="font-size:12px">${u.nome}</div><div class="u-email">${u.email}</div></div>
        <span class="badge ${u.role==='admin'?'badge-admin':'badge-user'}">${u.role==='admin'?'Admin':'User'}</span>
      </div>`;
    }).join('');
  }

  function filtrarTabela() {
    const q = document.getElementById('searchUsers').value.toLowerCase();
    renderTabela(allUsers.filter(u => u.nome.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)));
  }

  async function mudarRole(userId, roleAtual, nome) {
    const novoRole = roleAtual === 'admin' ? 'user' : 'admin';
    if (!confirm(`Mudar "${nome}" para ${novoRole}?`)) return;
    const fd = new FormData(); fd.append('action','users_role'); fd.append('user_id',userId); fd.append('role',novoRole);
    const r = await fetch('PHP/admin_api.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.sucesso) carregarUtilizadores(); else toast('Erro: '+(d.mensagem||''),'err');
  }

  async function apagarUser(userId, nome) {
    if (!confirm(`Tens a certeza que queres apagar a conta de "${nome}"?\nEsta ação é irreversível!`)) return;
    const fd = new FormData(); fd.append('action','users_delete'); fd.append('user_id',userId);
    const r = await fetch('PHP/admin_api.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.sucesso) {
      document.getElementById('row-'+userId)?.remove();
      allUsers = allUsers.filter(u => u.id != userId);
      document.getElementById('dsTotal').textContent  = allUsers.length;
      document.getElementById('dsAdmins').textContent = allUsers.filter(u=>u.role==='admin').length;
    } else toast('Erro: '+(d.mensagem||''),'err');
  }

  // ─── CLUBES ─────────────────────────────────────────────
  let allClubes = [], modalAtual = '';
  const ICONS_MOD = { futebol:'bi-dribbble', basquetebol:'bi-record-circle', voleibol:'bi-circle' };

  async function carregarClubes() {
    window._clubesCarregados = true;
    const grid = document.getElementById('clubesGrid');
    grid.innerHTML = `<div class="loading-row" style="grid-column:1/-1"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>`;
    try {
      const r = await fetch('PHP/admin_api.php?action=clubes_list');
      const d = await r.json();
      if (!d.sucesso) { grid.innerHTML = `<div class="tbl-empty" style="grid-column:1/-1"><i class="bi bi-exclamation-triangle"></i>Erro ao carregar.</div>`; return; }
      allClubes = d.clubes;
      document.getElementById('dsFutebol').textContent = d.stats['futebol']     || 0;
      document.getElementById('dsBasket').textContent  = d.stats['basquetebol'] || 0;
      document.getElementById('dsVolei').textContent   = d.stats['voleibol']    || 0;
      const nb = document.getElementById('nbClubes');
      nb.textContent = allClubes.length; nb.style.display = 'inline';
      renderClubes(allClubes);
    } catch { grid.innerHTML = `<div class="tbl-empty" style="grid-column:1/-1"><i class="bi bi-wifi-off"></i>Erro de ligação.</div>`; }
  }

  function renderClubes(lista) {
    const grid = document.getElementById('clubesGrid');
    if (!lista.length) { grid.innerHTML = `<div class="tbl-empty" style="grid-column:1/-1"><i class="bi bi-search"></i>Nenhum clube encontrado.</div>`; return; }
    grid.innerHTML = lista.map(c => `
      <div class="club-card" id="ccard-${c.id}">
        <div class="club-card-head">
          <div class="club-logo">
            ${c.imagem_url ? `<img src="${c.imagem_url}" onerror="this.parentNode.innerHTML='<i class=\\'bi bi-shield-fill\\'></i>'">` : `<i class="bi ${ICONS_MOD[c.modalidade]||'bi-trophy'}"></i>`}
          </div>
          <div><div class="club-name">${c.nome}</div><div class="club-sub"><i class="bi bi-geo-alt"></i>${c.localizacao||'—'}</div></div>
        </div>
        <div class="club-chips">
          <span class="chip chip-mod"><i class="bi ${ICONS_MOD[c.modalidade]||'bi-trophy'}"></i> ${c.modalidade}</span>
          <span class="chip chip-div">${c.divisao||'—'}</span>
          <span class="badge ${c.ativo=='1'||c.ativo===true?'badge-on':'badge-off'}" style="font-size:9px">${c.ativo=='1'||c.ativo===true?'Ativo':'Inativo'}</span>
        </div>
        <div class="club-actions">
          <button class="btn btn-ghost btn-sm" onclick="abrirModalClube(${c.id})"><i class="bi bi-pencil-fill"></i> Editar</button>
          <button class="btn btn-ghost btn-sm" onclick="toggleClube(${c.id})" title="Ativar/Desativar"><i class="bi bi-toggle-on"></i></button>
          <button class="btn btn-danger btn-sm" onclick="apagarClube(${c.id},'${c.nome.replace(/'/g,"\\'")}')"><i class="bi bi-trash3-fill"></i></button>
        </div>
      </div>`).join('');
  }

  function filtrarModal(modal) {
    modalAtual = modal;
    document.querySelectorAll('#filtrosClubes .filter-chip').forEach(b => b.classList.remove('on'));
    event.target.closest('.filter-chip').classList.add('on');
    pesquisarClubes();
  }

  function pesquisarClubes() {
    const q = document.getElementById('searchClubes').value.toLowerCase();
    let lista = allClubes;
    if (modalAtual) lista = lista.filter(c => c.modalidade === modalAtual);
    if (q) lista = lista.filter(c => c.nome.toLowerCase().includes(q) || (c.localizacao||'').toLowerCase().includes(q));
    renderClubes(lista);
  }

  function abrirModalClube(id) {
    document.getElementById('formClube').reset();
    document.getElementById('cAtivo').checked = true;
    if (id) {
      const c = allClubes.find(x => x.id == id);
      if (!c) return;
      document.getElementById('modalClubeTitle').textContent = 'Editar Clube';
      document.getElementById('cId').value         = c.id;
      document.getElementById('cNome').value       = c.nome||'';
      document.getElementById('cModalidade').value = c.modalidade||'';
      document.getElementById('cDivisao').value    = c.divisao||'';
      document.getElementById('cLocalizacao').value= c.localizacao||'';
      document.getElementById('cRecinto').value    = c.recinto||'';
      document.getElementById('cDescricao').value  = c.descricao||'';
      document.getElementById('cImagem').value     = c.imagem_url||'';
      document.getElementById('cPreco').value      = c.inscricao_preco||'';
      document.getElementById('cTelefone').value   = c.telefone||'';
      document.getElementById('cEmail').value      = c.email||'';
      document.getElementById('cWebsite').value    = c.website||'';
      document.getElementById('cFacebook').value   = c.facebook||'';
      document.getElementById('cInstagram').value  = c.instagram||'';
      document.getElementById('cLat').value        = c.latitude||'';
      document.getElementById('cLng').value        = c.longitude||'';
      document.getElementById('cAtivo').checked    = c.ativo=='1'||c.ativo===true;
    } else {
      document.getElementById('modalClubeTitle').textContent = 'Novo Clube';
      document.getElementById('cId').value = '';
    }
    document.getElementById('modalClube').classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function fecharModalClube() { document.getElementById('modalClube').classList.remove('show'); document.body.style.overflow=''; }
  document.getElementById('modalClube').addEventListener('click', e => { if (e.target===document.getElementById('modalClube')) fecharModalClube(); });

  async function guardarClube(e) {
    e.preventDefault();
    const btn = document.getElementById('btnGuardar');
    btn.disabled = true; btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> A guardar...';
    const id = document.getElementById('cId').value;
    const fd = new FormData();
    fd.append('action', id?'clubes_update':'clubes_create');
    if (id) fd.append('id', id);
    fd.append('nome',          document.getElementById('cNome').value);
    fd.append('modalidade',    document.getElementById('cModalidade').value);
    fd.append('divisao',       document.getElementById('cDivisao').value);
    fd.append('localizacao',   document.getElementById('cLocalizacao').value);
    fd.append('recinto',       document.getElementById('cRecinto').value);
    fd.append('descricao',     document.getElementById('cDescricao').value);
    fd.append('imagem_url',    document.getElementById('cImagem').value);
    fd.append('inscricao_preco', document.getElementById('cPreco').value);
    fd.append('telefone',      document.getElementById('cTelefone').value);
    fd.append('email',         document.getElementById('cEmail').value);
    fd.append('website',       document.getElementById('cWebsite').value);
    fd.append('facebook',      document.getElementById('cFacebook').value);
    fd.append('instagram',     document.getElementById('cInstagram').value);
    fd.append('latitude',      document.getElementById('cLat').value);
    fd.append('longitude',     document.getElementById('cLng').value);
    if (document.getElementById('cAtivo').checked) fd.append('ativo','1');
    try {
      const r = await fetch('PHP/admin_api.php',{method:'POST',body:fd});
      const d = await r.json();
      if (d.sucesso) { fecharModalClube(); await carregarClubes(); toast(id?'Clube atualizado ✓':'Clube criado ✓','ok'); }
      else toast('Erro: '+d.mensagem,'err');
    } catch { toast('Erro de ligação','err'); }
    finally { btn.disabled=false; btn.innerHTML='<i class="bi bi-floppy-fill"></i> Guardar Clube'; }
  }

  async function toggleClube(id) {
    const fd = new FormData(); fd.append('action','clubes_toggle'); fd.append('id',id);
    const r = await fetch('PHP/admin_api.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.sucesso) { const c=allClubes.find(x=>x.id==id); if(c) c.ativo=d.ativo?'1':'0'; pesquisarClubes(); toast(d.ativo?'Ativado':'Desativado','ok'); }
  }

  async function apagarClube(id, nome) {
    if (!confirm(`Apagar "${nome}"? Ação irreversível!`)) return;
    const fd = new FormData(); fd.append('action','users_delete'); fd.append('id',id);
    const r = await fetch('PHP/admin_api.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.sucesso) { allClubes=allClubes.filter(c=>c.id!=id); document.getElementById('ccard-'+id)?.remove(); toast('Clube apagado','ok'); }
    else toast('Erro: '+d.mensagem,'err');
  }

  // ─── MARCAÇÕES ──────────────────────────────────────────
  let _allMarcAdmin = [], _filtroMarcAdmin = '';

  async function carregarMarcacoesAdmin() {
    window._marcAdminCarregadas = true;
    try {
      const r = await fetch('PHP/admin_api.php?action=marc_list');
      const d = await r.json();
      _allMarcAdmin = d.ok ? d.marcacoes : [];
    } catch { _allMarcAdmin = []; }
    renderMarcAdmin();
    renderRecentBookings(_allMarcAdmin.slice(0,5));
  }

  function filtrarMarcAdmin(status) {
    _filtroMarcAdmin = status;
    document.querySelectorAll('#filtrosMarcacoes .filter-chip').forEach(b => b.classList.remove('on'));
    const map = {'':'fmTodos',pendente:'fmPend',confirmado:'fmConf',cancelado:'fmCanc',reembolsado:'fmReemb'};
    document.getElementById(map[status]||'fmTodos')?.classList.add('on');
    renderMarcAdmin();
  }

  function renderMarcAdmin() {
    const loading   = document.getElementById('marcAdminLoading');
    const tableWrap = document.getElementById('marcAdminTableWrap');
    const empty     = document.getElementById('marcAdminEmpty');
    const body      = document.getElementById('marcAdminBody');
    const q         = document.getElementById('searchMarcAdmin').value.toLowerCase();
    loading.style.display = 'none';
    const all = _allMarcAdmin;
    document.getElementById('ams-total').textContent = all.length;
    document.getElementById('ams-pend').textContent  = all.filter(m=>m.status==='pendente').length;
    document.getElementById('ams-conf').textContent  = all.filter(m=>m.status==='confirmado').length;
    document.getElementById('ams-canc').textContent  = all.filter(m=>['cancelado','reembolsado'].includes(m.status)).length;
    const rev = all.filter(m=>m.status==='confirmado').reduce((s,m)=>s+parseFloat(m.preco||0),0);
    document.getElementById('ams-rev').textContent  = rev.toFixed(2);
    document.getElementById('dsReceita').textContent = rev.toFixed(0)+'€';
    const nb = document.getElementById('nbMarc');
    nb.textContent = all.length; nb.style.display = all.length?'inline':'none';
    let filtered = all.filter(m => {
      if (_filtroMarcAdmin && m.status !== _filtroMarcAdmin) return false;
      if (q) { const hay=(m.user_nome+m.user_email+m.clube_nome+m.referencia).toLowerCase(); if (!hay.includes(q)) return false; }
      return true;
    });
    if (!filtered.length) { tableWrap.style.display='none'; empty.style.display='block'; return; }
    tableWrap.style.display='block'; empty.style.display='none';
    const METODO = { mbway:'MB WAY', multibanco:'Multibanco', cartao:'Cartão' };
    const SC = {
      pendente:    { cls:'badge-pend',  icon:'bi-clock-fill',             label:'Pendente' },
      confirmado:  { cls:'badge-conf',  icon:'bi-check-circle-fill',      label:'Confirmado' },
      cancelado:   { cls:'badge-canc',  icon:'bi-x-circle-fill',          label:'Cancelado' },
      reembolsado: { cls:'badge-reemb', icon:'bi-arrow-counterclockwise', label:'Reembolsado' },
    };
    body.innerHTML = filtered.map(m => {
      const sc = SC[m.status]||SC.pendente;
      const acoes = [];
      if (m.status==='pendente') acoes.push(`<button class="btn btn-success btn-sm" onclick="adminAcaoMarc(${m.id},'confirmar')"><i class="bi bi-check-circle-fill"></i> Confirmar</button>`);
      if (['pendente','confirmado'].includes(m.status)) acoes.push(`<button class="btn btn-danger btn-sm" onclick="adminAcaoMarc(${m.id},'cancelar')"><i class="bi bi-x-circle-fill"></i> Cancelar</button>`);
      return `<tr>
        <td style="color:var(--muted2);font-size:11px;font-family:'DM Mono',monospace">#${m.id}</td>
        <td><div style="font-size:12px;font-weight:600">${m.user_nome||'—'}</div><div style="font-size:11px;color:var(--muted2)">${m.user_email||''}</div></td>
        <td><div style="font-weight:600;font-size:13px">${m.clube_nome}</div><div style="font-size:11px;color:var(--muted2)">${m.plano||''}${m.escalao?' · '+m.escalao:''}</div></td>
        <td style="font-size:12px;color:var(--muted2)">${m.dias||'—'}<br><span style="color:var(--muted)">${m.horario||''}</span></td>
        <td style="font-size:12px">${METODO[m.metodo]||m.metodo}</td>
        <td style="font-weight:700;color:var(--accent2)">${parseFloat(m.preco||0).toFixed(2)}€</td>
        <td><span class="ref-code">${m.referencia}</span></td>
        <td><span class="badge ${sc.cls}"><i class="bi ${sc.icon}"></i> ${sc.label}</span></td>
        <td style="font-size:11px;color:var(--muted2);white-space:nowrap">${m.data_formatada||''}</td>
        <td><div style="display:flex;gap:5px;flex-wrap:wrap">${acoes.join('')}</div></td>
      </tr>`;
    }).join('');
  }

  function renderRecentBookings(bookings) {
    const el = document.getElementById('dsRecentBookings');
    if (!bookings.length) { el.innerHTML='<div class="tbl-empty" style="padding:26px"><i class="bi bi-calendar-x"></i>Sem marcações.</div>'; return; }
    const SC   = { pendente:'badge-pend', confirmado:'badge-conf', cancelado:'badge-canc', reembolsado:'badge-reemb' };
    const SLBL = { pendente:'Pendente', confirmado:'Confirmado', cancelado:'Cancelado', reembolsado:'Reembolsado' };
    el.innerHTML = bookings.map(m =>
      `<div class="recent-item">
        <div style="flex:1;min-width:0">
          <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${m.clube_nome}</div>
          <div style="font-size:11px;color:var(--muted2)">${m.user_nome||'—'}</div>
        </div>
        <span style="font-weight:700;font-size:12px;color:var(--accent2);flex-shrink:0">${parseFloat(m.preco||0).toFixed(2)}€</span>
        <span class="badge ${SC[m.status]||'badge-pend'}" style="flex-shrink:0">${SLBL[m.status]||'?'}</span>
      </div>`
    ).join('');
  }

  async function adminAcaoMarc(id, acao) {
    if (!confirm(acao==='confirmar'?'Confirmar este pagamento?':'Cancelar esta marcação?')) return;
    try {
      const r = await fetch('PHP/admin_api.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'marc_'+acao,marcacao_id:id})});
      const d = await r.json();
      if (d.ok) { const m=_allMarcAdmin.find(x=>x.id==id); if(m) m.status=d.novo_status; renderMarcAdmin(); toast(acao==='confirmar'?'Pagamento confirmado ✓':'Marcação cancelada','ok'); }
      else toast('Erro: '+(d.erro||''),'err');
    } catch { toast('Erro de ligação','err'); }
  }

  // ─── TOAST ──────────────────────────────────────────────
  function toast(msg, tipo) {
    const t = document.createElement('div');
    t.className = 'toast '+(tipo==='ok'?'toast-ok':'toast-err');
    t.innerHTML = `<i class="bi ${tipo==='ok'?'bi-check-circle-fill':'bi-exclamation-circle-fill'}"></i>${msg}`;
    document.body.appendChild(t);
    setTimeout(()=>t.remove(),3500);
  }
  function mostrarToast(msg, tipo) { toast(msg, tipo==='success'?'ok':'err'); }

  // ─── INIT ────────────────────────────────────────────────
  async function init() {
    try {
      const r = await fetch('PHP/auth.php?action=session');
      const d = await r.json();
      if (!d.autenticado || d.utilizador.role !== 'admin') { window.location.href='index.php'; return; }
      currentUserId = d.utilizador.id;
      const nome = d.utilizador.nome || 'Administrador';
      document.getElementById('adminName').textContent    = nome;
      document.getElementById('adminInitials').textContent = nome.split(' ').map(n=>n[0]).slice(0,2).join('').toUpperCase();
    } catch { window.location.href='index.php'; return; }
    carregarUtilizadores();
    carregarMarcacoesAdmin();
    carregarClubes();
  }


  // ══ PLANOS ══════════════════════════════════════════════
  let _planos = [], _extras = [];

  async function carregarPlanos() {
    window._planosCarregados = true;
    const rP = await fetch('PHP/admin_api.php?action=planos_list').then(r=>r.json());
    _planos = rP.planos || [];
    _extras = rP.extras || [];
    renderPlanos();
    renderExtras();
  }

  function renderPlanos() {
    const el = document.getElementById('planosLista');
    if (!_planos.length) { el.innerHTML='<div class="tbl-empty"><i class="bi bi-layers"></i>Sem planos. Clique em "Novo Plano" para criar.</div>'; return; }
    el.innerHTML = `
      <div style="display:flex;justify-content:flex-end;padding:0 0 12px">
        <button class="btn btn-primary btn-sm" onclick="criarPlano()"><i class="bi bi-plus-lg"></i> Novo Plano</button>
      </div>` +
    _planos.map(p => `
      <div style="display:grid;grid-template-columns:1.2fr 110px 90px 160px auto auto;gap:10px;align-items:end;padding:14px 0;border-bottom:1px solid var(--border)">
        <div>
          <label style="font-size:10px;color:rgba(255,255,255,.4);display:block;margin-bottom:3px">NOME</label>
          <input class="form-input" type="text" value="${p.nome}" id="pp_nome_${p.id}" style="padding:6px 10px">
        </div>
        <div>
          <label style="font-size:10px;color:rgba(255,255,255,.4);display:block;margin-bottom:3px">PREÇO (€/mês)</label>
          <input class="form-input" type="number" step="0.01" min="0" value="${p.preco}" id="pp_preco_${p.id}" style="padding:6px 10px">
        </div>
        <div>
          <label style="font-size:10px;color:rgba(255,255,255,.4);display:block;margin-bottom:3px">MÁX. DIAS</label>
          <input class="form-input" type="number" min="1" max="7" value="${p.dias_maximos}" id="pp_dias_${p.id}" style="padding:6px 10px">
        </div>
        <div>
          <label style="font-size:10px;color:rgba(255,255,255,.4);display:block;margin-bottom:3px">SESSÕES/SEM</label>
          <input class="form-input" type="text" value="${p.sessoes_por_semana||''}" id="pp_sess_${p.id}" style="padding:6px 10px">
        </div>
        <button class="btn btn-primary btn-sm" onclick="guardarPlano(${p.id})"><i class="bi bi-floppy-fill"></i> Guardar</button>
        <button class="btn btn-ghost btn-sm" onclick="apagarPlano(${p.id})" style="color:#f87171"><i class="bi bi-trash3"></i></button>
      </div>
    `).join('');
  }

  async function criarPlano() {
    const nome  = prompt('Nome do plano (ex: Básico):');
    if (!nome) return;
    const preco = prompt('Preço mensal (€):');
    if (!preco || isNaN(preco)) return;
    const dias  = prompt('Máximo de dias por semana:', '1');
    const sess  = dias + ' dia' + (parseInt(dias)>1?'s':'') + '/semana';
    const fd = new FormData();
    fd.append('action','planos_create'); fd.append('nome',nome);
    fd.append('preco',preco); fd.append('dias_maximos',dias||1); fd.append('sessoes_por_semana',sess);
    const r = await fetch('PHP/admin_api.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.sucesso) { showToast('Plano criado!','ok'); carregarPlanos(); }
    else showToast(d.mensagem||'Erro ao criar','err');
  }

  async function guardarPlano(id) {
    const nome  = document.getElementById('pp_nome_'+id)?.value;
    const preco = document.getElementById('pp_preco_'+id)?.value;
    const dias  = document.getElementById('pp_dias_'+id)?.value;
    const sess  = document.getElementById('pp_sess_'+id)?.value;
    const fd = new FormData();
    fd.append('action','planos_update'); fd.append('id',id); fd.append('nome',nome||'');
    fd.append('preco',preco); fd.append('dias_maximos',dias); fd.append('sessoes_por_semana',sess);
    const r = await fetch('PHP/admin_api.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.sucesso) showToast('Plano atualizado!','ok');
    else showToast(d.mensagem||'Erro ao guardar','err');
  }

  async function apagarPlano(id) {
    if (!confirm('Apagar este plano?')) return;
    const fd = new FormData(); fd.append('action','planos_delete'); fd.append('id',id);
    const r = await fetch('PHP/admin_api.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.sucesso) { showToast('Plano apagado','ok'); carregarPlanos(); }
    else showToast(d.mensagem||'Erro','err');
  }

  function renderExtras() {
    const el = document.getElementById('extrasLista');
    if (!_extras.length) {
      el.innerHTML='<div class="tbl-empty" style="padding:20px 0"><i class="bi bi-star"></i>Sem extras. Clique em "Adicionar Extra" para criar.</div>';
      return;
    }
    el.innerHTML = `<table><thead><tr><th>Nome</th><th>Plano</th><th>Preço</th><th>Ações</th></tr></thead><tbody>` +
      _extras.map(e => `<tr>
        <td style="font-weight:600">${e.nome}</td>
        <td>${_planos.find(p=>p.id==e.plano_id)?.nome||'—'}</td>
        <td style="color:#f5a623;font-weight:700">€${parseFloat(e.preco).toFixed(2)}</td>
        <td><button class="btn btn-ghost btn-sm" style="color:#f87171" onclick="apagarExtra(${e.id})"><i class="bi bi-trash3"></i></button></td>
      </tr>`).join('') + '</tbody></table>';
  }

  function abrirNovoExtra() {
    const nome = prompt('Nome do extra (ex: Ginásio Completo):');
    if (!nome) return;
    const preco = prompt('Preço (€):');
    if (!preco || isNaN(preco)) return;
    const desc = prompt('Descrição (opcional):') || '';
    const planoOpts = _planos.map((p,i) => (i+1)+'. '+p.nome).join('\n');
    const idx = parseInt(prompt('Plano (número):\n' + planoOpts)) - 1;
    const plano_id = _planos[idx]?.id || _planos[_planos.length-1]?.id || 3;
    criarExtra(nome, plano_id, preco, desc);
  }

  async function criarExtra(nome, plano_id, preco, descricao) {
    const fd = new FormData();
    fd.append('action','extras_create'); fd.append('nome',nome);
    fd.append('plano_id',plano_id); fd.append('preco',preco); fd.append('descricao',descricao);
    const r = await fetch('PHP/admin_api.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.sucesso) { showToast('Extra criado!','ok'); carregarPlanos(); }
    else showToast(d.mensagem||'Erro','err');
  }

  async function apagarExtra(id) {
    if (!confirm('Apagar este extra?')) return;
    const fd = new FormData(); fd.append('action','extras_delete'); fd.append('id',id);
    const r = await fetch('PHP/admin_api.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.sucesso) { showToast('Extra apagado','ok'); carregarPlanos(); }
    else showToast(d.mensagem||'Erro','err');
  }

  // ══ HORÁRIOS ════════════════════════════════════════════
  let _horAdminData = [];

  async function carregarHorariosAdmin() {
    window._horariosAdminCarregados = true;
    const clubeId = document.getElementById('horClubeFiltro')?.value || '';
    const lista = document.getElementById('horariosAdminLista');
    lista.innerHTML = '<div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>';

    // Load clubes for filter if not yet
    const sel = document.getElementById('horClubeFiltro');
    if (sel && sel.options.length <= 1) {
      const rc = await fetch('PHP/admin_api.php?action=clubes_list').then(r=>r.json());
      (rc.clubes||[]).forEach(cl => {
        const o = document.createElement('option'); o.value=cl.id; o.textContent=cl.nome+' ('+cl.modalidade+')';
        sel.appendChild(o);
      });
    }

    const url = 'PHP/admin_api.php?action=horarios_list' + (clubeId?'&club_id='+clubeId:'');
    const r = await fetch(url).then(r=>r.json());
    _horAdminData = r.horarios || [];

    // Populate escalao filter
    const escSel = document.getElementById('horEscalaoFiltro');
    if (escSel) {
      const escs = [...new Set(_horAdminData.map(h=>h.escalao_nome))].filter(Boolean);
      escSel.innerHTML = '<option value="">Todos os escalões</option>' + escs.map(e=>`<option value="${e}">${e}</option>`).join('');
    }
    renderHorariosAdmin();
  }

  function renderHorariosAdmin() {
    const el = document.getElementById('horariosAdminLista');
    const escFiltro = document.getElementById('horEscalaoFiltro')?.value || '';
    const dados = escFiltro ? _horAdminData.filter(h=>h.escalao_nome===escFiltro) : _horAdminData;

    if (!dados.length) {
      el.innerHTML = '<div class="tbl-empty"><i class="bi bi-clock"></i>Sem horários. Selecione um clube.</div>'; return;
    }

    el.innerHTML = `<table>
      <thead><tr><th>Clube</th><th>Escalão</th><th>Dia</th><th>Início</th><th>Fim</th><th>Vagas</th><th>Ativo</th><th>Guardar</th></tr></thead>
      <tbody>` + dados.map(h => `<tr>
        <td style="font-size:12px;color:rgba(255,255,255,.6)">${h.clube_nome}</td>
        <td><span style="font-weight:600">${h.escalao_nome}</span></td>
        <td style="font-weight:600">${h.dia_semana}</td>
        <td><input class="form-input" type="time" value="${h.hora_inicio}" id="hor_hi_${h.id}" style="padding:5px 8px;width:110px"></td>
        <td><input class="form-input" type="time" value="${h.hora_fim||''}" id="hor_hf_${h.id}" style="padding:5px 8px;width:110px"></td>
        <td><input class="form-input" type="number" min="0" max="50" value="${h.vagas_disponiveis}" id="hor_vg_${h.id}" style="padding:5px 8px;width:70px"></td>
        <td><input type="checkbox" ${h.ativo?'checked':''} id="hor_at_${h.id}" style="accent-color:#7b2cff;width:16px;height:16px"></td>
        <td><button class="btn btn-primary btn-sm" onclick="guardarHorario(${h.id})"><i class="bi bi-floppy-fill"></i></button></td>
      </tr>`).join('') + '</tbody></table>';
  }

  async function guardarHorario(id) {
    const hi = document.getElementById('hor_hi_'+id)?.value;
    const hf = document.getElementById('hor_hf_'+id)?.value;
    const vg = document.getElementById('hor_vg_'+id)?.value;
    const at = document.getElementById('hor_at_'+id)?.checked ? 1 : 0;
    const fd = new FormData();
    fd.append('action','horarios_update'); fd.append('id',id);
    fd.append('hora_inicio',hi); fd.append('hora_fim',hf);
    fd.append('vagas_disponiveis',vg); fd.append('ativo',at);
    const r = await fetch('PHP/admin_api.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.sucesso) showToast('Horário guardado!','ok');
    else showToast(d.mensagem||'Erro','err');
  }

  // ══ SUB-TABS MARCAÇÕES ══════════════════════════════════
  function mudarSubTabMarc(sub) {
    document.querySelectorAll('[id^="mst-"]').forEach(b=>b.classList.remove('on'));
    document.getElementById('mst-'+sub)?.classList.add('on');
    document.getElementById('marcSubTodas').style.display   = sub==='todas'    ? '' : 'none';
    document.getElementById('marcSubProximos').style.display = sub==='proximos' ? '' : 'none';
    document.getElementById('marcSubPassadas').style.display = sub==='passadas' ? '' : 'none';
    if (sub==='proximos') carregarProximos();
    if (sub==='passadas') carregarPassadas();
  }

  async function carregarProximos() {
    const el = document.getElementById('marcProximosLista');
    el.innerHTML = '<div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>';
    const d = await fetch('PHP/admin_api.php?action=marc_proximos').then(r=>r.json());
    const marc = d.marcacoes || [];
    if (!marc.length) { el.innerHTML='<div class="tbl-empty"><i class="bi bi-calendar"></i>Sem marcações ativas.</div>'; return; }
    el.innerHTML = '<table><thead><tr><th>Utilizador</th><th>Clube</th><th>Plano / Escalão</th><th>Dias</th><th>Horário</th><th>Valor</th><th>Estado</th><th>Ações</th></tr></thead><tbody>' +
      marc.map(m => {
        const bc = m.status==='confirmado'?'<span style="color:#4ade80;font-size:11px;font-weight:700">● Confirmado</span>':'<span style="color:#fbbf24;font-size:11px;font-weight:700">● Pendente</span>';
        return `<tr>
          <td><div style="font-weight:600;font-size:13px">${m.user_nome}</div><div style="font-size:11px;color:rgba(255,255,255,.4)">${m.user_email}</div></td>
          <td>${m.clube_nome}</td>
          <td><div>${m.plano}</div><div style="font-size:11px;color:rgba(255,255,255,.4)">${m.escalao}</div></td>
          <td style="font-size:12px">${m.dias}</td>
          <td style="font-size:12px">${m.horario}</td>
          <td style="font-weight:700;color:#f5a623">€${parseFloat(m.preco||0).toFixed(2)}</td>
          <td>${bc}</td>
          <td style="display:flex;gap:6px">
            ${m.status==='pendente'?`<button class="btn btn-primary btn-sm" onclick="acaoMarcAdmin('confirmar',${m.id})"><i class="bi bi-check-lg"></i></button>`:''}
            <button class="btn btn-ghost btn-sm" style="color:#f87171" onclick="acaoMarcAdmin('cancelar',${m.id})"><i class="bi bi-x-lg"></i></button>
          </td>
        </tr>`;
      }).join('') + '</tbody></table>';
  }

  async function carregarPassadas() {
    const el = document.getElementById('marcPassadasLista');
    el.innerHTML = '<div class="loading-row"><i class="bi bi-arrow-repeat spin"></i> A carregar...</div>';
    const d = await fetch('PHP/admin_api.php?action=marc_passadas').then(r=>r.json());
    const marc = d.marcacoes || [];
    if (!marc.length) { el.innerHTML='<div class="tbl-empty"><i class="bi bi-clock-history"></i>Sem histórico de marcações.</div>'; return; }
    el.innerHTML = '<table><thead><tr><th>Utilizador</th><th>Clube</th><th>Plano</th><th>Dias</th><th>Valor</th><th>Estado</th><th>Motivo</th><th>Data</th></tr></thead><tbody>' +
      marc.map(m => {
        const bc = m.status==='reembolsado'
          ? '<span style="color:#2dd4bf;font-size:11px;font-weight:700">● Reembolsado</span>'
          : '<span style="color:#f87171;font-size:11px;font-weight:700">● Cancelado</span>';
        return `<tr>
          <td><div style="font-weight:600;font-size:13px">${m.user_nome}</div><div style="font-size:11px;color:rgba(255,255,255,.4)">${m.user_email}</div></td>
          <td>${m.clube_nome}</td>
          <td>${m.plano}</td>
          <td style="font-size:12px">${m.dias}</td>
          <td style="font-weight:700;color:rgba(255,255,255,.4)">€${parseFloat(m.preco||0).toFixed(2)}</td>
          <td>${bc}</td>
          <td style="font-size:11px;color:rgba(255,255,255,.4)">${m.cancelamento_motivo||'—'}</td>
          <td style="font-size:11px;color:rgba(255,255,255,.4)">${m.data_criacao}</td>
        </tr>`;
      }).join('') + '</tbody></table>';
  }

  // ══ TOAST ════════════════════════════════════════════════
  function showToast(msg, type='ok') {
    let t = document.getElementById('adminToast');
    if (!t) {
      t = document.createElement('div'); t.id='adminToast';
      t.style.cssText='position:fixed;bottom:28px;right:28px;padding:11px 18px;border-radius:10px;font-size:13px;font-weight:600;z-index:99999;transition:opacity .3s;pointer-events:none';
      document.body.appendChild(t);
    }
    t.textContent = msg;
    t.style.background = type==='ok' ? 'rgba(34,197,94,.15)' : 'rgba(239,68,68,.15)';
    t.style.border = type==='ok' ? '1px solid rgba(34,197,94,.3)' : '1px solid rgba(239,68,68,.3)';
    t.style.color = type==='ok' ? '#4ade80' : '#f87171';
    t.style.opacity = '1';
    clearTimeout(t._t);
    t._t = setTimeout(()=>t.style.opacity='0', 3000);
  }

  init();
</script>
<script src="assets/js/fullscreen.js"></script>
</body>
</html>

<?php
// Email: admin@vaijogar.pt | Password: password
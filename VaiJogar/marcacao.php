<?php
session_start();
require_once __DIR__ . '/PHP/csrf.php';
$csrf_token = csrf_gerar();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Marcar Treino — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    const CSRF_TOKEN             = "<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>";
    const EMAILJS_PUBLIC_KEY     = "PAbl8cIsdr_jn4lxB";
    const EMAILJS_SERVICE_ID     = "service_gahmyxk";
    const EMAILJS_TEMPLATE_NOTIF = "template_e01o2cf";
    emailjs.init(EMAILJS_PUBLIC_KEY);
  </script>
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Roboto',sans-serif;background:#0a0014;color:white;min-height:100vh}

    /* ── HEADER ── */
    .header-right{display:flex;align-items:center;gap:12px}
    .lang-selector-wrap{display:flex;align-items:center;gap:6px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;padding:5px 12px;cursor:pointer;position:relative}
    .lang-selector-wrap i{color:rgba(255,255,255,0.6);font-size:14px}
    .lang-btn-inner{background:none;border:none;color:white;font-size:13px;font-weight:700;cursor:pointer;padding:0;font-family:'Roboto',sans-serif}
    .lang-menu{display:none;position:absolute;top:calc(100% + 8px);right:0;background:rgba(20,5,50,0.97);border:1px solid rgba(255,255,255,0.15);border-radius:12px;overflow:hidden;min-width:140px;z-index:9999}
    .lang-menu.show{display:block}
    .lang-menu a{display:flex;align-items:center;gap:8px;padding:10px 16px;color:rgba(255,255,255,0.8);font-size:13px;cursor:pointer;transition:background .15s;text-decoration:none}
    .lang-menu a:hover{background:rgba(123,44,255,0.2);color:white}
    .user-menu{position:relative}
    .user-btn{display:inline-flex;align-items:center;gap:8px;padding:7px 16px;border-radius:10px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:white;font-size:13px;font-weight:600;cursor:pointer;font-family:'Roboto',sans-serif;transition:background .2s}
    .user-btn:hover{background:rgba(255,255,255,0.14)}
    .avatar-mini{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;overflow:hidden;flex-shrink:0;color:white}
    .avatar-mini img{width:100%;height:100%;object-fit:cover}
    .user-dropdown{display:none;position:absolute;top:calc(100% + 10px);right:0;background:rgba(20,5,50,0.97);border:1px solid rgba(255,255,255,0.12);border-radius:14px;overflow:hidden;min-width:200px;z-index:9999;}
    .user-dropdown.show{display:block}
    .dropdown-header{padding:14px 16px 10px;border-bottom:1px solid rgba(255,255,255,0.08)}
    .dropdown-header .d-nome{font-size:14px;font-weight:700}
    .dropdown-header .d-email{font-size:12px;color:rgba(255,255,255,0.4);margin-top:2px}
    .dropdown-item{display:flex;align-items:center;gap:10px;padding:11px 16px;color:rgba(255,255,255,0.75);font-size:13px;cursor:pointer;transition:background .15s;text-decoration:none;border:none;background:none;width:100%;font-family:'Roboto',sans-serif}
    .dropdown-item:hover{background:rgba(123,44,255,0.2);color:white}
    .dropdown-item i{font-size:15px;color:rgba(255,255,255,0.7);width:18px}
    .dropdown-item.danger{color:#f87171}
    .dropdown-item.danger i{color:#f87171}
    .dropdown-item.danger:hover{background:rgba(239,68,68,0.15)}
    .dropdown-divider{border:none;border-top:1px solid rgba(255,255,255,0.08);margin:4px 0}
    .admin-badge{font-size:10px;background:rgba(245,158,11,0.2);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);border-radius:6px;padding:1px 6px;font-weight:700}

    /* ── PAGE ── */
    .marc-page{max-width:900px;margin:0 auto;padding:100px 20px 80px}
    .btn-voltar{display:inline-flex;align-items:center;gap:7px;color:rgba(255,255,255,0.45);font-size:13px;font-weight:600;text-decoration:none;margin-bottom:28px;transition:color .2s;background:none;border:none;cursor:pointer}
    .btn-voltar:hover{color:white}

    /* ── HERO CLUBE ── */
    .clube-hero{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);border-radius:20px;padding:28px 32px;margin-bottom:28px;display:flex;align-items:center;gap:20px;position:relative;overflow:hidden}
    .clube-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 0% 0%,rgba(75,3,209,0.2),transparent 60%);pointer-events:none}
    .clube-logo{width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;}
    .clube-logo img{width:58px;height:58px;object-fit:contain}
    .clube-info{flex:1}
    .clube-nome{font-size:1.5rem;font-weight:900;margin-bottom:8px}
    .clube-badges{display:flex;flex-wrap:wrap;gap:7px}
    .badge-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700}
    .badge-mod{background:rgba(123,44,255,0.2);color:#c4b5fd;border:1px solid rgba(123,44,255,0.3)}
    .badge-div{background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.25)}
    .badge-loc{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.55);border:1px solid rgba(255,255,255,0.1)}

    /* ── STEPPER ── */
    .stepper{display:flex;align-items:center;gap:0;margin-bottom:36px}
    .step{display:flex;align-items:center;gap:10px;flex:1;position:relative}
    .step:not(:last-child)::after{content:'';position:absolute;left:calc(28px + 10px);right:0;top:14px;height:2px;background:rgba(255,255,255,0.1);z-index:0}
    .step.done:not(:last-child)::after{background:rgba(123,44,255,0.5)}
    .step-num{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;z-index:1;transition:all .3s}
    .step.active .step-num{background:rgba(255,255,255,0.15);}
    .step.done .step-num{background:rgba(34,197,94,0.2);border:1px solid rgba(34,197,94,0.4);color:#4ade80}
    .step.pending .step-num{background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.3)}
    .step-label{font-size:12px;font-weight:600;white-space:nowrap}
    .step.active .step-label{color:white}
    .step.done .step-label{color:#4ade80}
    .step.pending .step-label{color:rgba(255,255,255,0.3)}

    /* ── PAINEL DE PASSO ── */
    .step-panel{display:none;animation:fadeUp .35s ease}
    .step-panel.active{display:block}
    @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

    .panel-title{font-size:1rem;font-weight:800;margin-bottom:20px;color:#a78bfa;text-transform:uppercase;letter-spacing:.08em;display:flex;align-items:center;gap:8px}
    .panel-title i{font-size:1.1rem}

    /* ── PLANOS ── */
    .planos-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    .plano-card{background:rgba(255,255,255,0.04);border:2px solid rgba(255,255,255,0.08);border-radius:16px;padding:22px 18px;cursor:pointer;position:relative;text-align:center}
    .plano-card:hover{border-color:rgba(123,44,255,0.4);background:rgba(123,44,255,0.08);transform:translateY(-3px)}
    .plano-card.selected{border-color:rgba(255,255,255,0.7);background:rgba(123,44,255,0.15);}
    .plano-card.popular{border-color:rgba(245,158,11,0.4)}
    .plano-card.popular.selected{border-color:#f59e0b}
    .popular-tag{position:absolute;top:-10px;left:50%;transform:translateX(-50%);background:rgba(255,255,255,0.15);color:white;font-size:10px;font-weight:800;padding:3px 12px;border-radius:20px;white-space:nowrap}
    .plano-nome{font-size:14px;font-weight:800;margin-bottom:6px;color:white}
    .plano-preco{font-size:2rem;font-weight:900;color:rgba(255,255,255,0.7);line-height:1}
    .plano-preco span{font-size:14px;color:rgba(255,255,255,0.4);font-weight:400}
    .plano-popular .plano-preco{color:#f59e0b}
    .plano-desc{font-size:12px;color:rgba(255,255,255,0.4);margin:10px 0 14px}
    .plano-features{list-style:none;text-align:left;display:flex;flex-direction:column;gap:7px}
    .plano-features li{display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,0.7)}
    .plano-features li i{color:rgba(255,255,255,0.7);font-size:13px;flex-shrink:0}
    .plano-card.popular .plano-features li i{color:#f59e0b}
    .plano-check{width:22px;height:22px;border-radius:50%;background:rgba(123,44,255,0.2);border:2px solid rgba(123,44,255,0.4);position:absolute;top:14px;right:14px;display:flex;align-items:center;justify-content:center}
    .plano-card.selected .plano-check{background:#7b2cff;border-color:#7b2cff}
    .plano-card.selected .plano-check::after{content:'✓';font-size:11px;color:white;font-weight:800}

    /* ── DIAS ── */
    .dias-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:10px;margin-bottom:8px}
    .dia-btn{background:rgba(255,255,255,0.05);border:2px solid rgba(255,255,255,0.1);border-radius:14px;padding:16px 6px;cursor:pointer;transition:all .2s;text-align:center;color:rgba(255,255,255,0.5)}
    .dia-btn:hover{border-color:rgba(123,44,255,0.4);color:white;background:rgba(123,44,255,0.1)}
    .dia-btn.selected{background:rgba(123,44,255,0.2);border-color:rgba(255,255,255,0.7);color:white;}
    .dia-btn.disabled{opacity:.35;cursor:not-allowed;pointer-events:none}
    .dia-abrev{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em}
    .dia-num{font-size:18px;font-weight:900;margin-top:4px}
    .dia-mes{font-size:10px;opacity:.6;margin-top:2px}
    .dias-hint{font-size:12px;color:rgba(255,255,255,0.35);margin-top:8px}

    /* ── HORÁRIOS ── */
    .horarios-grupo{margin-bottom:24px}
    .grupo-label{font-size:12px;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;display:flex;align-items:center;gap:6px}
    .horarios-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
    .hora-btn{background:rgba(255,255,255,0.05);border:2px solid rgba(255,255,255,0.1);border-radius:12px;padding:14px 8px;cursor:pointer;transition:all .2s;text-align:center;color:rgba(255,255,255,0.6);font-size:14px;font-weight:700}
    .hora-btn:hover:not(.ocupado){border-color:rgba(123,44,255,0.4);color:white;background:rgba(123,44,255,0.1)}
    .hora-btn.selected{background:rgba(123,44,255,0.2);border-color:rgba(255,255,255,0.7);color:white;}
    .hora-btn.ocupado{opacity:.35;cursor:not-allowed;position:relative}
    .hora-badge{font-size:10px;color:rgba(255,255,255,0.35);margin-top:4px}
    .hora-btn.ocupado .hora-badge{color:#f87171}

    /* ── ESCALÕES ── */
    .escaloes-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
    .escalao-btn{background:rgba(255,255,255,0.05);border:2px solid rgba(255,255,255,0.1);border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;text-align:center;color:rgba(255,255,255,0.6)}
    .escalao-btn:hover{border-color:rgba(123,44,255,0.4);color:white;background:rgba(123,44,255,0.1)}
    .escalao-btn.selected{background:rgba(123,44,255,0.2);border-color:rgba(255,255,255,0.7);color:white}
    .escalao-nome{font-size:14px;font-weight:700}
    .escalao-idade{font-size:11px;opacity:.5;margin-top:4px}

    /* ── RESUMO ── */
    .resumo-box{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);border-radius:16px;padding:24px;margin-bottom:20px}
    .resumo-row{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(255,255,255,0.06)}
    .resumo-row:last-child{border:none}
    .resumo-label{font-size:13px;color:rgba(255,255,255,0.45);display:flex;align-items:center;gap:8px}
    .resumo-label i{color:rgba(255,255,255,0.7);font-size:14px}
    .resumo-val{font-size:14px;font-weight:700;color:white}
    .resumo-total{background:rgba(123,44,255,0.1);border:1px solid rgba(123,44,255,0.25);border-radius:12px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
    .resumo-total-label{font-size:13px;color:rgba(255,255,255,0.5)}
    .resumo-total-preco{font-size:1.6rem;font-weight:900;color:#fbbf24}

    /* ── PAGAMENTO ── */
    .metodos-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
    .metodo-card{background:rgba(255,255,255,0.05);border:2px solid rgba(255,255,255,0.1);border-radius:14px;padding:18px 14px;cursor:pointer;transition:all .2s;text-align:center;color:rgba(255,255,255,0.6)}
    .metodo-card:hover{border-color:rgba(123,44,255,0.4);color:white;background:rgba(123,44,255,0.08)}
    .metodo-card.selected{background:rgba(123,44,255,0.15);border-color:rgba(255,255,255,0.7);color:white}
    .metodo-icon{font-size:1.8rem;margin-bottom:8px;display:block}
    .metodo-nome{font-size:13px;font-weight:700}
    .metodo-desc{font-size:11px;opacity:.5;margin-top:4px}
    .ref-box{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);border-radius:12px;padding:16px;margin-bottom:20px;display:none}
    .ref-box.show{display:block}
    .ref-label{font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px}
    .ref-value{font-size:1.4rem;font-weight:900;letter-spacing:.12em;color:#a78bfa}
    .ref-entidade{font-size:13px;color:rgba(255,255,255,0.4);margin-top:6px}

    /* ── BOTÕES NAVEGAÇÃO ── */
    .nav-btns{display:flex;gap:12px;justify-content:flex-end;margin-top:28px}
    .btn-prev{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:11px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.7);font-size:14px;font-weight:700;cursor:pointer;font-family:'Roboto',sans-serif;transition:all .2s}
    .btn-prev:hover{background:rgba(255,255,255,0.12);color:white}
    .btn-next{display:inline-flex;align-items:center;gap:8px;padding:11px 28px;border-radius:11px;background:rgba(255,255,255,0.15);border:none;color:white;font-size:14px;font-weight:700;cursor:pointer;font-family:'Roboto',sans-serif;}
    .btn-next:hover{transform:translateY(-2px);}
    .btn-next:disabled{opacity:.4;cursor:not-allowed;transform:none}

    /* ── SUCESSO ── */
    .sucesso-wrap{text-align:center;padding:40px 20px;animation:fadeUp .5s ease}
    .sucesso-icon-big{font-size:5rem;margin-bottom:20px;filter:drop-shadow(0 0 20px rgba(74,222,128,0.5))}
    .sucesso-titulo{font-size:1.6rem;font-weight:900;margin-bottom:10px}
    .sucesso-sub{font-size:14px;color:rgba(255,255,255,0.5);line-height:1.7;margin-bottom:30px}
    .confirmacao-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);border-radius:16px;padding:22px;text-align:left;max-width:500px;margin:0 auto 28px}
    .conf-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:13px}
    .conf-row:last-child{border:none}
    .conf-label{color:rgba(255,255,255,0.4)}
    .conf-val{font-weight:700;color:white}
    .ref-destacada{font-size:1.1rem;color:#a78bfa;letter-spacing:.1em}
    .sucesso-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
    .btn-outline{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:11px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);color:white;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;font-family:'Roboto',sans-serif}
    .btn-outline:hover{background:rgba(255,255,255,0.12)}
    .btn-primary{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;border-radius:11px;background:rgba(255,255,255,0.15);border:none;color:white;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;font-family:'Roboto',sans-serif;}
    .btn-primary:hover{transform:translateY(-2px);}

    /* ── LOADING ── */
    .loading-wrap{text-align:center;padding:80px 20px;color:rgba(255,255,255,0.3)}
    .loading-wrap i{font-size:2.5rem;display:block;margin-bottom:14px;animation:spin 1s linear infinite}
    @keyframes spin{to{transform:rotate(360deg)}}
    @keyframes mbwayLoad{0%{width:0}50%{width:80%}100%{width:100%}}
    @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.1)}}

    @media(max-width:700px){
      .planos-grid{grid-template-columns:1fr}
      .dias-grid{grid-template-columns:repeat(4,1fr)}
      .horarios-grid{grid-template-columns:repeat(3,1fr)}
      .escaloes-grid{grid-template-columns:repeat(2,1fr)}
      .metodos-grid{grid-template-columns:1fr}
      .step-label{display:none}
      .clube-hero{flex-direction:column;text-align:center}
      .clube-badges{justify-content:center}
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo"/></a>
    </div>
    <nav class="header-nav">
      <a href="escolha.php"><i class="bi bi-grid-fill"></i> <span>Modalidades</span></a>
      <a href="mapa.php"><i class="bi bi-map-fill"></i> <span>Mapa</span></a>
      <a href="sobre.php"><i class="bi bi-info-circle-fill"></i> <span>Sobre</span></a>
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
          <i class="bi bi-chevron-down" style="font-size:11px;opacity:.6"></i>
        </button>
        <div class="user-dropdown" id="userDropdown">
          <div class="dropdown-header">
            <div class="d-nome" id="dropNome">—</div>
            <div class="d-email" id="dropEmail">—</div>
          </div>
          <a class="dropdown-item" href="perfil.php"><i class="bi bi-person-fill"></i> O meu perfil</a>
          <div id="adminItem" style="display:none">
            <a class="dropdown-item" href="admin.php"><i class="bi bi-shield-lock-fill"></i> Painel Admin <span class="admin-badge">ADMIN</span></a>
          </div>
          <hr class="dropdown-divider"/>
          <button class="dropdown-item danger" onclick="window.location.href='PHP/auth.php?action=logout&redirect=../index.php'">
            <i class="bi bi-box-arrow-right"></i> Sair da conta
          </button>
        </div>
      </div>
    </div>
  </header>

  <div class="marc-page">

    <!-- Loading -->
    <div class="loading-wrap" id="loadingWrap">
      <i class="bi bi-arrow-repeat"></i>
      <span>A carregar...</span>
    </div>

    <!-- Conteúdo principal -->
    <div id="mainContent" style="display:none">

      <!-- Voltar -->
      <button class="btn-voltar" id="btnVoltar" onclick="voltarClube()">
        <i class="bi bi-arrow-left"></i> Voltar ao clube
      </button>

      <!-- Hero clube -->
      <div class="clube-hero">
        <div class="clube-logo">
          <img id="clubeLogo" src="" alt="" onerror="this.parentElement.innerHTML='<i class=\'bi bi-shield-fill\' style=\'font-size:2rem;color:rgba(255,255,255,0.7);\'></i>'"/>
        </div>
        <div class="clube-info">
          <div class="clube-nome" id="clubeNome">—</div>
          <div class="clube-badges" id="clubeBadges"></div>
        </div>
      </div>

      <!-- Stepper -->
      <div class="stepper" id="stepper">
        <div class="step active" id="stp1">
          <div class="step-num">1</div>
          <span class="step-label">Plano</span>
        </div>
        <div class="step pending" id="stp2">
          <div class="step-num">2</div>
          <span class="step-label">Escalão</span>
        </div>
        <div class="step pending" id="stp3">
          <div class="step-num">3</div>
          <span class="step-label">Dias</span>
        </div>
        <div class="step pending" id="stp4">
          <div class="step-num">4</div>
          <span class="step-label">Horário</span>
        </div>
        <div class="step pending" id="stp5">
          <div class="step-num">5</div>
          <span class="step-label">Pagamento</span>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           PASSO 1 — PLANO
      ════════════════════════════════════ -->
      <div class="step-panel active" id="panel1">
        <div class="panel-title"><i class="bi bi-star-fill"></i>Escolhe o teu plano</div>
        <div class="planos-grid" id="planosGrid"></div>
        <div class="nav-btns">
          <button class="btn-next" id="btnNext1" onclick="irPasso(2)" disabled>
            Continuar <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           PASSO 2 — ESCALÃO
      ════════════════════════════════════ -->
      <div class="step-panel" id="panel2">
        <div class="panel-title"><i class="bi bi-people-fill"></i>Escolhe o teu escalão</div>
        <div class="escaloes-grid" id="escaloesGrid"></div>
        <div class="nav-btns">
          <button class="btn-prev" onclick="irPasso(1)"><i class="bi bi-arrow-left"></i> Anterior</button>
          <button class="btn-next" id="btnNext2" onclick="irPasso(3)" disabled>
            Continuar <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           PASSO 3 — DIAS
      ════════════════════════════════════ -->
      <div class="step-panel" id="panel3">
        <div class="panel-title"><i class="bi bi-calendar3"></i>Escolhe os dias de treino</div>
        <div class="dias-grid" id="diasGrid"></div>
        <p class="dias-hint" id="diasHint"></p>
        <div class="nav-btns">
          <button class="btn-prev" onclick="irPasso(2)"><i class="bi bi-arrow-left"></i> Anterior</button>
          <button class="btn-next" id="btnNext3" onclick="irPasso(4)" disabled>
            Continuar <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           PASSO 4 — HORÁRIO
      ════════════════════════════════════ -->
      <div class="step-panel" id="panel4">
        <div class="panel-title"><i class="bi bi-clock-fill"></i>Escolhe o horário de treino</div>
        <div id="horariosContainer"></div>
        <div class="nav-btns">
          <button class="btn-prev" onclick="irPasso(3)"><i class="bi bi-arrow-left"></i> Anterior</button>
          <button class="btn-next" id="btnNext4" onclick="irPasso(5)" disabled>
            Continuar <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           PASSO 5 — PAGAMENTO & RESUMO
      ════════════════════════════════════ -->
      <div class="step-panel" id="panel5">
        <div class="panel-title"><i class="bi bi-credit-card-fill"></i>Resumo e pagamento</div>

        <!-- Resumo -->
        <div class="resumo-box" id="resumoBox"></div>
        <div class="resumo-total">
          <span class="resumo-total-label">Total mensal</span>
          <span class="resumo-total-preco" id="totalPreco">—</span>
        </div>

        <!-- Método de pagamento -->
        <div class="panel-title" style="margin-bottom:16px"><i class="bi bi-wallet2"></i>Método de pagamento</div>
        <div class="metodos-grid">
          <div class="metodo-card" onclick="selecionarMetodo('mbway')">
            <span class="metodo-icon">📱</span>
            <div class="metodo-nome">MB WAY</div>
            <div class="metodo-desc">Pagamento imediato</div>
          </div>
          <div class="metodo-card" onclick="selecionarMetodo('multibanco')">
            <span class="metodo-icon">🏧</span>
            <div class="metodo-nome">Multibanco</div>
            <div class="metodo-desc">Referência ATM</div>
          </div>
          <div class="metodo-card" onclick="selecionarMetodo('cartao')">
            <span class="metodo-icon">💳</span>
            <div class="metodo-nome">Cartão</div>
            <div class="metodo-desc">Crédito / Débito</div>
          </div>
        </div>

        <!-- Dados MB WAY -->
        <div class="ref-box" id="boxMbway">
          <div class="ref-label">📱 Número de telemóvel MB WAY</div>
          <div style="position:relative;margin-top:4px">
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);font-size:14px;font-weight:600;pointer-events:none">+351</span>
            <input id="inputTel" type="tel" placeholder="9XX XXX XXX"
              style="width:100%;padding:12px 14px 12px 56px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:16px;font-family:inherit;outline:none;transition:border-color .2s"
              oninput="validarTelMbway(this);validarForm()" maxlength="11"/>
          </div>
          <div id="telErro" style="display:none;color:#f87171;font-size:12px;margin-top:6px"><i class="bi bi-exclamation-circle"></i> Número inválido. Use um número PT (ex: 912 345 678)</div>
          <div id="telOk" style="display:none;color:#4ade80;font-size:12px;margin-top:6px"><i class="bi bi-check-circle"></i> Número válido — será enviado pedido MB WAY</div>
          <div style="margin-top:12px;padding:10px 14px;background:rgba(0,0,0,0.2);border-radius:8px;font-size:12px;color:rgba(255,255,255,0.45)">
            <i class="bi bi-info-circle"></i> Receberá uma notificação na app MB WAY para autorizar o pagamento de <strong id="precoMbway" style="color:#a78bfa">—</strong>
          </div>
        </div>

        <!-- Referência Multibanco -->
        <div class="ref-box" id="boxMultibanco">
          <div class="ref-label" style="margin-bottom:14px">Referência gerada</div>
          <div style="display:flex;gap:20px;align-items:center;flex-wrap:wrap">
            <div>
              <div style="font-size:11px;color:rgba(255,255,255,.4)">Entidade</div>
              <div class="ref-value" style="font-size:1.1rem">21 368</div>
            </div>
            <div>
              <div style="font-size:11px;color:rgba(255,255,255,.4)">Referência</div>
              <div class="ref-value" id="mbRef">— — —</div>
            </div>
            <div>
              <div style="font-size:11px;color:rgba(255,255,255,.4)">Montante</div>
              <div class="ref-value" id="mbMontante">—</div>
            </div>
          </div>
          <div class="ref-entidade" style="margin-top:12px">⏰ Referência válida por 48 horas</div>
        </div>

        <!-- Cartão -->
        <div class="ref-box" id="boxCartao">
          <div class="ref-label">Dados do cartão</div>
          <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px">
            <input placeholder="Nome no cartão" style="width:100%;padding:11px 14px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:14px;font-family:inherit;outline:none" oninput="validarForm()"/>
            <input placeholder="XXXX XXXX XXXX XXXX" maxlength="19" id="numCartao"
              style="width:100%;padding:11px 14px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:14px;font-family:inherit;outline:none"
              oninput="formatarCartao(this);validarForm()"/>
            <div style="display:flex;gap:10px">
              <input placeholder="MM/AA" maxlength="5" style="flex:1;padding:11px 14px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:14px;font-family:inherit;outline:none" oninput="validarForm()"/>
              <input placeholder="CVV" maxlength="3" style="width:90px;padding:11px 14px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:14px;font-family:inherit;outline:none" oninput="validarForm()"/>
            </div>
          </div>
        </div>

        <div class="nav-btns">
          <button class="btn-prev" onclick="irPasso(4)"><i class="bi bi-arrow-left"></i> Anterior</button>
          <button class="btn-next" id="btnConfirmar" onclick="confirmarMarcacao()" disabled>
            <i class="bi bi-check-circle-fill"></i> Confirmar Marcação
          </button>
        </div>
      </div>

      <!-- ═══════════════════════════════════
           SUCESSO
      ════════════════════════════════════ -->
      <div class="step-panel" id="panelSucesso">
        <div class="sucesso-wrap">
          <div class="sucesso-icon-big">✅</div>
          <div class="sucesso-titulo">Marcação Confirmada!</div>
          <div class="sucesso-sub" id="sucessoSub"></div>
          <div class="confirmacao-card" id="confirmacaoCard"></div>
          <div class="sucesso-btns">
            <a class="btn-outline" id="btnVoltarClube2" href="#"><i class="bi bi-arrow-left"></i> Voltar ao Clube</a>
            <a class="btn-primary" href="perfil.php"><i class="bi bi-person-fill"></i> Ver Perfil</a>
          </div>
        </div>
      </div>

    </div><!-- /mainContent -->
  </div>

  <script src="assets/js/tradutor.js"></script>

  <script>
  // ═══════════════════════════════════════════
  //  ESTADO GLOBAL
  // ═══════════════════════════════════════════
  const urlP    = new URLSearchParams(location.search);
  const NOME    = urlP.get('nome')       || '';
  const MOD     = urlP.get('modalidade') || '';
  const DIV     = urlP.get('divisao')    || '';
  const RECINTO = urlP.get('recinto')    || '';

  let _clube   = null;
  let _user    = null;
  let _passo   = 1;

  // Seleções
  let selPlano   = null;  // { nome, preco, dias }
  let selEscalao = null;  // { nome, idade }
  let selDias    = [];    // ['Seg','Qua',...]
  let selHorario = null;  // '18:00'
  let selMetodo  = null;  // 'mbway'|'multibanco'|'cartao'

  // Planos disponíveis (gerados com base no preço do clube)
  let PLANOS = [];

  // Logos Wikimedia
  const LOGO_URLS = {
    "FC Porto":"https://upload.wikimedia.org/wikipedia/pt/thumb/3/3e/FC_Porto.svg/120px-FC_Porto.svg.png",
    "Sporting CP":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/77/Sporting_CP.svg/120px-Sporting_CP.svg.png",
    "SL Benfica":"https://upload.wikimedia.org/wikipedia/pt/thumb/d/d8/SL_Benfica_logo.svg/120px-SL_Benfica_logo.svg.png",
    "SC Braga":"https://upload.wikimedia.org/wikipedia/pt/thumb/0/07/SC_Braga.png/120px-SC_Braga.png",
    "Vitória SC":"https://upload.wikimedia.org/wikipedia/pt/thumb/3/3e/Vitoria_SC.svg/120px-Vitoria_SC.svg.png",
    "Estoril Praia":"https://upload.wikimedia.org/wikipedia/pt/thumb/5/50/GD_Estoril_Praia.png/120px-GD_Estoril_Praia.png",
    "SC Farense":"https://upload.wikimedia.org/wikipedia/pt/thumb/c/c1/SC_Farense.png/120px-SC_Farense.png",
    "Portimonense SAD":"https://upload.wikimedia.org/wikipedia/pt/thumb/4/4c/Portimonense_SC.png/120px-Portimonense_SC.png",
    "Leixões SC":"https://upload.wikimedia.org/wikipedia/pt/thumb/8/8a/Leix%C3%B5es_SC.png/120px-Leix%C3%B5es_SC.png",
    "Ovarense GAVEX":"https://upload.wikimedia.org/wikipedia/pt/thumb/a/a1/Ovarense.png/120px-Ovarense.png",
    "Sporting de Espinho":"https://upload.wikimedia.org/wikipedia/pt/thumb/c/c3/Sporting_Clube_de_Espinho.png/120px-Sporting_Clube_de_Espinho.png"
  };

  // Horários disponíveis por período — todos os slots possíveis
  const TODOS_HORARIOS = {
    'Manhã':   ['08:00','09:00','10:00','11:00'],
    'Tarde':   ['14:00','15:00','16:00','17:00'],
    'Noite':   ['18:00','18:30','19:00','19:30','20:00','21:00']
  };

  // Horários filtrados conforme o escalão selecionado:
  // Sub-7 / Sub-9 / Sub-11  → Manhã + Tarde (crianças não treinam à noite)
  // Sub-13 / Sub-15         → Tarde + início de Noite (até 19:30)
  // Sub-19 / Seniores / 18+ → todos os horários
  function horariosParaEscalao(escalao) {
    if (!escalao) return TODOS_HORARIOS;
    const idade = (escalao.idade || '').toLowerCase();
    if (/sub-[79]$|sub-11/.test(idade)) {
      return { 'Manhã': TODOS_HORARIOS['Manhã'], 'Tarde': TODOS_HORARIOS['Tarde'] };
    }
    if (/sub-1[35]/.test(idade)) {
      return { 'Tarde': TODOS_HORARIOS['Tarde'], 'Noite': ['18:00','18:30','19:00','19:30'] };
    }
    return TODOS_HORARIOS;
  }

  // Dias da semana seguintes (próximos 14 dias, excluindo domingo)
  function gerarDias() {
    const dias = [];
    const hoje = new Date();
    const abrevs = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
    const meses  = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    let count = 0, offset = 1;
    while (count < 14) {
      const d = new Date(hoje);
      d.setDate(hoje.getDate() + offset);
      offset++;
      if (d.getDay() === 0) continue; // sem domingo
      dias.push({
        abrev: abrevs[d.getDay()],
        num:   d.getDate(),
        mes:   meses[d.getMonth()],
        full:  abrevs[d.getDay()],
        date:  d
      });
      count++;
    }
    return dias;
  }

  // ═══════════════════════════════════════════
  //  INICIALIZAÇÃO
  // ═══════════════════════════════════════════
  async function init() {
    // Auth
    try {
      const r = await fetch('PHP/auth.php?action=session');
      const d = await r.json();
      if (!d.autenticado) { location.href = 'index.php'; return; }
      _user = d.utilizador;

      const av = document.getElementById('headerAvatar');
      if (_user.avatar?.startsWith('data:')) av.innerHTML = `<img src="${_user.avatar}"/>`;
      else av.textContent = _user.nome.split(' ').map(n=>n[0]).slice(0,2).join('').toUpperCase();
      document.getElementById('headerNome').textContent  = _user.nome.split(' ')[0];
      document.getElementById('dropNome').textContent    = _user.nome;
      document.getElementById('dropEmail').textContent   = _user.email;
      if (_user.role === 'admin') document.getElementById('adminItem').style.display = 'block';
    } catch(e) { location.href = 'index.php'; return; }

    // Carregar clube
    if (!NOME) { location.href = 'escolha.php'; return; }
    try {
      const r = await fetch(`PHP/clubes_api.php?action=clube?nome=${encodeURIComponent(NOME)}&modalidade=${encodeURIComponent(MOD)}`);
      const d = await r.json();
      _clube = d.encontrado ? d : { nome: NOME, modalidade: MOD, divisao: DIV, recinto: RECINTO, escaloes: [], horarios: [], inscricao_preco: '' };
    } catch(e) {
      _clube = { nome: NOME, modalidade: MOD, divisao: DIV, recinto: RECINTO, escaloes: [], horarios: [], inscricao_preco: '' };
    }

    renderHeroClube();
    gerarPlanos();
    renderPlanos();
    renderEscaloes();
    renderDias();
    renderHorarios();

    document.getElementById('loadingWrap').style.display  = 'none';
    document.getElementById('mainContent').style.display  = 'block';
  }

  // ═══════════════════════════════════════════
  //  HERO DO CLUBE
  // ═══════════════════════════════════════════
  function renderHeroClube() {
    document.title = (_clube.nome || NOME) + ' — Marcar Treino — VaiJogar';
    const logoSrc = LOGO_URLS[_clube.nome] || '';
    const img = document.getElementById('clubeLogo');
    if (logoSrc) img.src = logoSrc;
    else img.parentElement.innerHTML = '<i class="bi bi-shield-fill" style="font-size:2rem;color:rgba(255,255,255,0.7);"></i>';

    document.getElementById('clubeNome').textContent = _clube.nome || NOME;

    const icons = { futebol:'bi-dribbble', basquetebol:'bi-record-circle', voleibol:'bi-circle' };
    const mod   = _clube.modalidade || MOD;
    const modNome = mod.charAt(0).toUpperCase() + mod.slice(1);
    document.getElementById('clubeBadges').innerHTML = `
      ${mod ? `<span class="badge-pill badge-mod"><i class="bi ${icons[mod]||'bi-trophy'}"></i>${modNome}</span>` : ''}
      ${(_clube.divisao||DIV) ? `<span class="badge-pill badge-div"><i class="bi bi-trophy-fill"></i>${_clube.divisao||DIV}</span>` : ''}
      ${(_clube.recinto||RECINTO) ? `<span class="badge-pill badge-loc"><i class="bi bi-geo-fill"></i>${_clube.recinto||RECINTO}</span>` : ''}
    `;

    // Botão voltar
    document.getElementById('btnVoltarClube2').href =
      `clube.php?nome=${encodeURIComponent(NOME)}&modalidade=${encodeURIComponent(MOD)}&divisao=${encodeURIComponent(DIV)}&recinto=${encodeURIComponent(RECINTO)}`;
  }

  function voltarClube() {
    history.back();
  }

  // ═══════════════════════════════════════════
  //  PLANOS
  // ═══════════════════════════════════════════
  function gerarPlanos() {
    // Extrair preço base do clube
    const precoStr = _clube.inscricao_preco || '20EUR/mes';
    const match = precoStr.match(/(\d+)/);
    const base = match ? parseInt(match[1]) : 20;

    PLANOS = [
      {
        id: 'basico', nome: 'Básico', preco: base,
        diasSemana: 1, popular: false,
        desc: '1x por semana',
        features: ['1 treino por semana','Acesso ao balneário','Acompanhamento do treinador','Seguro desportivo']
      },
      {
        id: 'standard', nome: 'Standard', preco: Math.round(base * 1.4),
        diasSemana: 3, popular: true,
        desc: '3x por semana',
        features: ['3 treinos por semana','Acesso ao balneário','Acompanhamento do treinador','Seguro desportivo','Avaliação mensal']
      },
      {
        id: 'premium', nome: 'Premium', preco: Math.round(base * 1.9),
        diasSemana: 5, popular: false,
        desc: '5x por semana',
        features: ['5 treinos por semana','Acesso ao balneário','Treinador dedicado','Seguro desportivo','Avaliação semanal','Nutricionista']
      }
    ];
  }

  function renderPlanos() {
    const g = document.getElementById('planosGrid');
    g.innerHTML = PLANOS.map(p => `
      <div class="plano-card ${p.popular?'popular':''}" onclick="selecionarPlano('${p.id}')" id="plano_${p.id}">
        ${p.popular ? '<span class="popular-tag">⭐ Mais popular</span>' : ''}
        <div class="plano-check" id="check_${p.id}"></div>
        <div class="plano-nome">${p.nome}</div>
        <div class="plano-preco">${p.preco}<span>€/mês</span></div>
        <div class="plano-desc">${p.desc}</div>
        <ul class="plano-features">
          ${p.features.map(f=>`<li><i class="bi bi-check-circle-fill"></i>${f}</li>`).join('')}
        </ul>
      </div>
    `).join('');
  }

  function selecionarPlano(id) {
    selPlano = PLANOS.find(p => p.id === id);
    document.querySelectorAll('.plano-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('plano_' + id).classList.add('selected');
    document.getElementById('btnNext1').disabled = false;

    // Limpar dias selecionados que excedam o novo limite do plano
    if (selDias.length > selPlano.diasSemana) {
      selDias = [];
      document.querySelectorAll('.dia-btn').forEach(b => b.classList.remove('selected'));
      document.getElementById('btnNext3') && (document.getElementById('btnNext3').disabled = true);
    }
    atualizarHintDias();
  }

  // ═══════════════════════════════════════════
  //  ESCALÕES
  // ═══════════════════════════════════════════
  function renderEscaloes() {
    const escaloes = (_clube.escaloes && _clube.escaloes.length)
      ? _clube.escaloes
      : [
          {nome:'Traquinas',idade:'Sub-7'},{nome:'Benjamins',idade:'Sub-9'},
          {nome:'Infantis',idade:'Sub-11'},{nome:'Iniciados',idade:'Sub-13'},
          {nome:'Juvenis',idade:'Sub-15'},{nome:'Juniores',idade:'Sub-19'},
          {nome:'Seniores',idade:'18+'}
        ];

    document.getElementById('escaloesGrid').innerHTML = escaloes.map((e,i) => `
      <div class="escalao-btn" onclick="selecionarEscalao(${i})" id="esc_${i}">
        <div class="escalao-nome">${e.nome}</div>
        <div class="escalao-idade">${e.idade}</div>
      </div>
    `).join('');
  }

  function selecionarEscalao(i) {
    const escaloes = (_clube.escaloes && _clube.escaloes.length)
      ? _clube.escaloes
      : [{nome:'Traquinas',idade:'Sub-7'},{nome:'Benjamins',idade:'Sub-9'},{nome:'Infantis',idade:'Sub-11'},{nome:'Iniciados',idade:'Sub-13'},{nome:'Juvenis',idade:'Sub-15'},{nome:'Juniores',idade:'Sub-19'},{nome:'Seniores',idade:'18+'}];
    selEscalao = escaloes[i];
    document.querySelectorAll('.escalao-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('esc_' + i).classList.add('selected');
    document.getElementById('btnNext2').disabled = false;

    // Rerender horários filtrados pelo novo escalão e limpar seleção anterior
    selHorario = null;
    document.getElementById('btnNext4') && (document.getElementById('btnNext4').disabled = true);
    renderHorarios();
  }

  // ═══════════════════════════════════════════
  //  DIAS
  // ═══════════════════════════════════════════
  const _dias = gerarDias();

  function renderDias() {
    document.getElementById('diasGrid').innerHTML = _dias.map((d,i) => `
      <div class="dia-btn" onclick="toggleDia(${i})" id="dia_${i}">
        <div class="dia-abrev">${d.abrev}</div>
        <div class="dia-num">${d.num}</div>
        <div class="dia-mes">${d.mes}</div>
      </div>
    `).join('');
    atualizarHintDias();
  }

  function toggleDia(i) {
    const max = selPlano ? selPlano.diasSemana : 3;
    const btn = document.getElementById('dia_' + i);
    const key = _dias[i].abrev + _dias[i].num;

    if (btn.classList.contains('selected')) {
      btn.classList.remove('selected');
      selDias = selDias.filter(k => k !== key);
    } else {
      if (selDias.length >= max) return; // limite atingido
      btn.classList.add('selected');
      selDias.push(key);
    }
    document.getElementById('btnNext3').disabled = selDias.length === 0;
    atualizarHintDias();
  }

  function atualizarHintDias() {
    const max = selPlano ? selPlano.diasSemana : '—';
    document.getElementById('diasHint').textContent =
      `Seleciona até ${max} dia${max>1?'s':''} de treino. ${selDias.length > 0 ? selDias.length + ' selecionado' + (selDias.length>1?'s':'') : ''}`;
  }

  // ═══════════════════════════════════════════
  //  HORÁRIOS
  // ═══════════════════════════════════════════
  function renderHorarios() {
    const ocupadas = ['09:00','16:00','19:30'];
    const horarios = horariosParaEscalao(selEscalao);
    const c = document.getElementById('horariosContainer');

    const notaEscalao = selEscalao
      ? `<p style="font-size:12px;color:rgba(255,255,255,0.4);margin-bottom:16px;background:rgba(123,44,255,0.08);border:1px solid rgba(123,44,255,0.2);border-radius:10px;padding:10px 14px;"><i class="bi bi-info-circle-fill" style="color:#a78bfa;margin-right:6px;"></i>Horários disponíveis para o escalão <strong style="color:#c4b5fd;">${selEscalao.nome} (${selEscalao.idade})</strong></p>`
      : '';

    c.innerHTML = notaEscalao + Object.entries(horarios).map(([periodo, horas]) => `
      <div class="horarios-grupo">
        <div class="grupo-label">
          <i class="bi bi-${periodo==='Manhã'?'sunrise':periodo==='Tarde'?'sun':'moon-stars'}-fill"></i>
          ${periodo}
        </div>
        <div class="horarios-grid">
          ${horas.map(h => {
            const ocup = ocupadas.includes(h);
            return `<div class="hora-btn ${ocup?'ocupado':''}" onclick="${ocup?'':` selecionarHorario('${h}')`}" id="hora_${h.replace(':','_')}">
              ${h}
              <div class="hora-badge">${ocup?'Ocupado':'Disponível'}</div>
            </div>`;
          }).join('')}
        </div>
      </div>
    `).join('');
  }

  function selecionarHorario(h) {
    selHorario = h;
    document.querySelectorAll('.hora-btn:not(.ocupado)').forEach(b => b.classList.remove('selected'));
    document.getElementById('hora_' + h.replace(':','_')).classList.add('selected');
    document.getElementById('btnNext4').disabled = false;
  }

  // ═══════════════════════════════════════════
  //  PAGAMENTO
  // ═══════════════════════════════════════════
  function selecionarMetodo(m) {
    selMetodo = m;
    document.querySelectorAll('.metodo-card').forEach(c => c.classList.remove('selected'));
    event.currentTarget.classList.add('selected');

    document.getElementById('boxMbway').classList.toggle('show', m === 'mbway');
    document.getElementById('boxMultibanco').classList.toggle('show', m === 'multibanco');
    document.getElementById('boxCartao').classList.toggle('show', m === 'cartao');

    if (m === 'multibanco') {
      // Gerar referência aleatória
      const ref = Array.from({length:3}, () => Math.floor(100 + Math.random()*900)).join(' ');
      document.getElementById('mbRef').textContent = ref;
      document.getElementById('mbMontante').textContent = (selPlano?.preco || '—') + '€';
    }
    validarForm();
  }

  function formatarCartao(input) {
    let v = input.value.replace(/\D/g,'').substring(0,16);
    input.value = v.replace(/(.{4})/g,'$1 ').trim();
  }

  function validarForm() {
    let ok = !!selMetodo;
    if (selMetodo === 'mbway') {
      const tel = document.getElementById('inputTel')?.value.trim();
      ok = ok && tel && tel.length >= 9;
    }
    if (selMetodo === 'cartao') {
      const num = document.getElementById('numCartao')?.value.replace(/\s/g,'');
      ok = ok && num && num.length === 16;
    }
    document.getElementById('btnConfirmar').disabled = !ok;
  }

  // ═══════════════════════════════════════════
  //  NAVEGAÇÃO ENTRE PASSOS
  // ═══════════════════════════════════════════
  function irPasso(n) {
    // Atualizar resumo ao chegar ao passo 5
    if (n === 5) renderResumo();

    // Esconder todos os painéis
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel' + n).classList.add('active');

    // Atualizar stepper
    for (let i = 1; i <= 5; i++) {
      const s = document.getElementById('stp' + i);
      s.className = 'step ' + (i < n ? 'done' : i === n ? 'active' : 'pending');
      const num = s.querySelector('.step-num');
      if (i < n) num.innerHTML = '<i class="bi bi-check" style="font-size:13px"></i>';
      else num.textContent = i;
    }

    _passo = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // ═══════════════════════════════════════════
  //  RESUMO
  // ═══════════════════════════════════════════
  function renderResumo() {
    const diasStr = selDias.join(', ') || '—';
    document.getElementById('resumoBox').innerHTML = `
      <div class="resumo-row">
        <span class="resumo-label"><i class="bi bi-shield-fill"></i>Clube</span>
        <span class="resumo-val">${_clube.nome || NOME}</span>
      </div>
      <div class="resumo-row">
        <span class="resumo-label"><i class="bi bi-star-fill"></i>Plano</span>
        <span class="resumo-val">${selPlano?.nome || '—'} — ${selPlano?.desc || ''}</span>
      </div>
      <div class="resumo-row">
        <span class="resumo-label"><i class="bi bi-people-fill"></i>Escalão</span>
        <span class="resumo-val">${selEscalao?.nome || '—'} (${selEscalao?.idade || '—'})</span>
      </div>
      <div class="resumo-row">
        <span class="resumo-label"><i class="bi bi-calendar3"></i>Dias</span>
        <span class="resumo-val">${diasStr}</span>
      </div>
      <div class="resumo-row">
        <span class="resumo-label"><i class="bi bi-clock-fill"></i>Horário</span>
        <span class="resumo-val">${selHorario || '—'}</span>
      </div>
    `;
    document.getElementById('totalPreco').textContent = (selPlano?.preco || '—') + '€/mês';
  }

  // ═══════════════════════════════════════════
  //  VALIDAÇÃO TELEMÓVEL MB WAY
  // ═══════════════════════════════════════════
  function validarTelMbway(input) {
    // Formatar: remover tudo exceto dígitos, máx 9
    let v = input.value.replace(/\D/g, '').substring(0, 9);
    // Formatar com espaços: 9XX XXX XXX
    if (v.length > 6) input.value = v.slice(0,3) + ' ' + v.slice(3,6) + ' ' + v.slice(6);
    else if (v.length > 3) input.value = v.slice(0,3) + ' ' + v.slice(3);
    else input.value = v;

    const digits = input.value.replace(/\D/g, '');
    const valido = /^9[1236]\d{7}$/.test(digits);
    document.getElementById('telErro').style.display = (!digits || valido) ? 'none' : 'flex';
    document.getElementById('telOk').style.display  = valido ? 'flex' : 'none';
    // Update input border color
    input.style.borderColor = digits.length === 9
      ? (valido ? 'rgba(74,222,128,0.5)' : 'rgba(248,113,113,0.5)')
      : 'rgba(255,255,255,0.15)';
    // Mostrar preço
    if (selPlano?.preco) document.getElementById('precoMbway').textContent = selPlano.preco + '€/mês';
  }

  // ═══════════════════════════════════════════
  //  MODAL MBWAY - loading overlay
  // ═══════════════════════════════════════════
  function mostrarLoadingMbway(show, msg = '') {
    let overlay = document.getElementById('mbwayOverlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'mbwayOverlay';
      overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:20px';
      overlay.innerHTML = `
        <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:2.5rem;">📱</div>
        <div id="mbwayMsg" style="color:white;font-size:16px;font-weight:600;text-align:center;max-width:300px"></div>
        <div style="width:200px;height:4px;background:rgba(255,255,255,0.1);border-radius:2px;overflow:hidden">
          <div id="mbwayBar" style="height:100%;background:rgba(255,255,255,0.3);border-radius:2px"></div>
        </div>`;
      document.body.appendChild(overlay);
    }
    document.getElementById('mbwayMsg').textContent = msg;
    overlay.style.display = show ? 'flex' : 'none';
  }

  // ═══════════════════════════════════════════
  //  CONFIRMAR MARCAÇÃO (com MB WAY real)
  // ═══════════════════════════════════════════
  async function confirmarMarcacao() {
    const btn = document.getElementById('btnConfirmar');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i> A processar...';

    const metodoNomes = { mbway:'MB WAY', multibanco:'Multibanco', cartao:'Cartão de Crédito' };
    const diasStr = selDias.join(', ');
    const nomeClubeF = _clube?.nome || NOME;

    try {
      // ── PASSO 1: Iniciar pagamento no backend ──────────────────────
      let marcacaoData = null;
      let endpoint = 'PHP/pagamentos.php?action=iniciar';
      let payload = {
        clube_nome: nomeClubeF,
        club_id:    _clube?.id || _clube?.club_id || 0,
        plano:      selPlano?.nome,
        plano_id:   selPlano?.id || 0,
        escalao:    selEscalao?.nome,
        escalao_id: selEscalao?.id || 0,
        dias:       diasStr,
        horario:    selHorario,
        metodo:     selMetodo,
        preco:      selPlano?.preco,
        ref:        'VJ' + Date.now().toString().slice(-7)
      };

      if (selMetodo === 'mbway') {
        endpoint = 'PHP/pagamentos.php?action=iniciar';
        const tel = document.getElementById('inputTel').value.replace(/\D/g, '');
        payload.telefone = tel;
        payload.clube_nome = nomeClubeF;

        // Mostrar overlay MB WAY
        mostrarLoadingMbway(true, 'A enviar pedido MB WAY para +351 ' + tel.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3') + '...');
      }

      const resp = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF_TOKEN },
        body: JSON.stringify(payload)
      });
      marcacaoData = await resp.json();

      if (!marcacaoData.ok) {
        mostrarLoadingMbway(false);
        alert('Erro: ' + (marcacaoData.erro || 'Não foi possível processar o pagamento'));
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Confirmar Marcação';
        return;
      }

      // ── PASSO 2: Simular autorização MB WAY (aguardar utilizador) ──
      if (selMetodo === 'mbway') {
        document.getElementById('mbwayMsg').textContent = 'Aguardando autorização na app MB WAY...';
        await new Promise(r => setTimeout(r, 2500));

        // Confirmar pagamento no backend
        document.getElementById('mbwayMsg').textContent = 'Pagamento autorizado! A confirmar marcação...';
        await new Promise(r => setTimeout(r, 800));

        const confResp = await fetch('PHP/pagamentos.php?action=confirmar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF_TOKEN },
          body: JSON.stringify({ action: 'confirmar', marcacao_id: marcacaoData.marcacao_id, csrf_token: CSRF_TOKEN })
        });
        const confData = await confResp.json();
        marcacaoData = { ...marcacaoData, ...confData };

        mostrarLoadingMbway(false);
      }

      const ref = marcacaoData.ref || payload.ref || ('VJ' + Date.now().toString().slice(-7));

      // ── PASSO 3: Enviar email de confirmação via EmailJS ───────────
      if (marcacaoData.email_user || marcacaoData.email) {
        try {
          await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_NOTIF, {
            to_email:   marcacaoData.email_user || marcacaoData.email,
            to_name:    marcacaoData.nome_user  || marcacaoData.nome || 'Atleta',
            clube:      nomeClubeF,
            plano:      selPlano?.nome || '—',
            escalao:    selEscalao?.nome || '—',
            dias:       diasStr,
            horario:    selHorario || '—',
            metodo:     metodoNomes[selMetodo],
            preco:      (selPlano?.preco || '0') + '€/mês',
            referencia: ref,
            data:       new Date().toLocaleDateString('pt-PT', {day:'2-digit',month:'long',year:'numeric'}),
            message:    'A tua marcação foi confirmada com sucesso! Referência: ' + ref
          });
        } catch(emailErr) {
          console.warn('Email não enviado:', emailErr);
          // Não bloquear o fluxo se o email falhar
        }
      }

      // ── PASSO 4: Mostrar sucesso ───────────────────────────────────
      document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
      document.getElementById('stepper').style.display = 'none';
      document.getElementById('panelSucesso').classList.add('active');

      const emailEnviado = marcacaoData.email_user || marcacaoData.email;
      document.getElementById('sucessoSub').innerHTML =
        `A tua marcação no <strong>${nomeClubeF}</strong> foi confirmada com sucesso!${emailEnviado ? '<br><i class="bi bi-envelope-check-fill" style="color:#4ade80"></i> Email de confirmação enviado para <strong>' + emailEnviado + '</strong>' : ''}`;

      document.getElementById('confirmacaoCard').innerHTML = `
        <div class="conf-row"><span class="conf-label"><i class="bi bi-shield-fill"></i> Clube</span><span class="conf-val">${nomeClubeF}</span></div>
        <div class="conf-row"><span class="conf-label"><i class="bi bi-star-fill"></i> Plano</span><span class="conf-val">${selPlano?.nome} — ${selPlano?.preco}€/mês</span></div>
        <div class="conf-row"><span class="conf-label"><i class="bi bi-people-fill"></i> Escalão</span><span class="conf-val">${selEscalao?.nome} (${selEscalao?.idade})</span></div>
        <div class="conf-row"><span class="conf-label"><i class="bi bi-calendar3"></i> Dias</span><span class="conf-val">${diasStr}</span></div>
        <div class="conf-row"><span class="conf-label"><i class="bi bi-clock-fill"></i> Horário</span><span class="conf-val">${selHorario}</span></div>
        <div class="conf-row"><span class="conf-label"><i class="bi bi-wallet2"></i> Pagamento</span><span class="conf-val">${metodoNomes[selMetodo]}</span></div>
        <div class="conf-row"><span class="conf-label"><i class="bi bi-hash"></i> Referência</span><span class="conf-val ref-destacada">${ref}</span></div>
        <div class="conf-row" style="margin-top:8px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.08)">
          <span class="conf-label" style="color:#4ade80"><i class="bi bi-check-circle-fill"></i> Estado</span>
          <span class="conf-val" style="color:#4ade80;font-weight:700">Confirmado</span>
        </div>
      `;

    } catch(e) {
      mostrarLoadingMbway(false);
      console.error('Erro ao confirmar marcação:', e);
      alert('Ocorreu um erro inesperado. Por favor tente novamente.');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Confirmar Marcação';
      return;
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // ═══════════════════════════════════════════
  //  HEADER DROPDOWN
  // ═══════════════════════════════════════════
  document.getElementById('userBtn').addEventListener('click', e => {
    e.stopPropagation();
    document.getElementById('userDropdown').classList.toggle('show');
  });
  document.addEventListener('click', () => {
    document.getElementById('userDropdown')?.classList.remove('show');
    document.getElementById('langMenu')?.classList.remove('show');
  });
  document.getElementById('langWrap')?.addEventListener('click', e => {
    e.stopPropagation();
    document.getElementById('langMenu').classList.toggle('show');
  });

  // Iniciar
  init();
  
  </script>
</body>
</html>
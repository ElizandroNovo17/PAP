<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Clube — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Roboto',sans-serif;background:#0a0014;color:white;min-height:100vh}

    /* HEADER */
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

    /* PAGE */
    .clube-page{max-width:860px;margin:0 auto;padding:100px 20px 60px}

    /* BACK */
    .btn-voltar{display:inline-flex;align-items:center;gap:7px;color:rgba(255,255,255,0.45);font-size:13px;font-weight:600;text-decoration:none;margin-bottom:24px;transition:color .2s}
    .btn-voltar:hover{color:white}

    /* HERO */
    .hero{
      position:relative;overflow:hidden;
      border-radius:20px;padding:32px;margin-bottom:20px;
      background:rgba(255,255,255,0.04);
      border:1px solid rgba(255,255,255,0.09);
    }
    .hero::before{
      content:'';position:absolute;inset:0;pointer-events:none;
      background:radial-gradient(ellipse at 0% 0%,rgba(75,3,209,0.18),transparent 60%);
    }
    .hero-inner{display:flex;align-items:center;gap:24px;position:relative}
    .hero-logo{
      width:96px;height:96px;border-radius:50%;flex-shrink:0;
      background:rgba(255,255,255,0.06);
      border:2px solid rgba(123,44,255,0.5);
      display:flex;align-items:center;justify-content:center;overflow:hidden;
      
    }
    .hero-logo img{width:68px;height:68px;object-fit:contain}
    .hero-info{flex:1}
    .hero-nome{font-size:1.75rem;font-weight:900;margin-bottom:10px;line-height:1.1}
    .hero-badges{display:flex;flex-wrap:wrap;gap:7px}
    .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.03em}
    .badge-mod{background:rgba(123,44,255,0.2);color:#c4b5fd;border:1px solid rgba(123,44,255,0.3)}
    .badge-div{background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.25)}
    .badge-rec{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.55);border:1px solid rgba(255,255,255,0.1)}

    /* GRID */
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .full{grid-column:1/-1}

    /* CARD */
    .card{
      background:rgba(255,255,255,0.04);
      border:1px solid rgba(255,255,255,0.08);
      border-radius:16px;padding:22px 22px 20px;
      animation:fadeUp .4s ease both;
    }
    @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
    .card:nth-child(1){animation-delay:.05s}
    .card:nth-child(2){animation-delay:.1s}
    .card:nth-child(3){animation-delay:.15s}
    .card:nth-child(4){animation-delay:.2s}
    .card:nth-child(5){animation-delay:.25s}

    .card-title{
      font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
      color:rgba(255,255,255,0.7);margin-bottom:16px;
      display:flex;align-items:center;gap:7px;
    }
    .card-title i{font-size:13px}

    /* DESCRIÇÃO */
    .descricao-txt{font-size:14px;color:rgba(255,255,255,0.7);line-height:1.75}

    /* CONTACTOS */
    .contacto-list{display:flex;flex-direction:column;gap:10px}
    .contacto-row{display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,0.7)}
    .contacto-row i{color:rgba(255,255,255,0.7);font-size:15px;width:18px;flex-shrink:0}
    .contacto-row a{color:#a78bfa;text-decoration:none;transition:color .15s}
    .contacto-row a:hover{color:white;text-decoration:underline}

    /* REDES */
    .redes{display:flex;gap:8px;flex-wrap:wrap}
    .rede-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:9px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.7);transition:all .2s}
    .rede-btn:hover{background:rgba(255,255,255,0.1);color:white}

    /* HORÁRIOS */
    .horario-list{display:flex;flex-direction:column;gap:7px}
    /* horario-row merged above */
    .horario-dia{font-size:13px;font-weight:600;color:rgba(255,255,255,0.6);display:flex;align-items:center;gap:6px}
    .horario-hora{font-size:13px;font-weight:700;color:#a78bfa}

    /* ESCALÕES */
    .escaloes-grid{display:flex;flex-wrap:wrap;gap:8px}
    .escalao{
      background:rgba(75,3,209,0.15);border:1px solid rgba(123,44,255,0.25);
      border-radius:10px;padding:9px 14px;
      display:flex;flex-direction:column;gap:2px;min-width:90px;
    }
    .escalao-nome{font-size:13px;font-weight:700;color:white}
    .escalao-idade{font-size:11px;color:rgba(255,255,255,0.4)}
    .vagas-badge{font-size:10px;font-weight:700;padding:2px 7px;border-radius:5px;margin-top:4px;display:inline-block}
    .vagas-ok  {background:rgba(34,197,94,0.12);color:#4ade80;border:1px solid rgba(34,197,94,0.2)}
    .vagas-low {background:rgba(245,158,11,0.12);color:#fbbf24;border:1px solid rgba(245,158,11,0.2)}
    .vagas-zero{background:rgba(239,68,68,0.1); color:#f87171;border:1px solid rgba(239,68,68,0.2)}
    .horario-row{display:flex;align-items:flex-start;justify-content:space-between;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:9px;padding:10px 14px;gap:12px}
    .horario-dia{font-size:13px;font-weight:700;color:rgba(255,255,255,0.8);display:flex;align-items:center;gap:6px;flex-shrink:0;min-width:70px}
    .horario-hora{font-size:12px;font-weight:500;color:#a78bfa;text-align:right;line-height:1.6}

    /* INSCRIÇÃO */
    .inscricao-box{
      background:rgba(75,3,209,0.1);border:1px solid rgba(123,44,255,0.2);
      border-radius:12px;padding:14px 16px;font-size:13px;
      color:rgba(255,255,255,0.7);line-height:1.7;margin-bottom:14px;
    }
    .preco-tag{
      display:inline-flex;align-items:center;gap:6px;
      background:rgba(245,158,11,0.15);color:#fbbf24;
      border:1px solid rgba(245,158,11,0.25);
      border-radius:9px;padding:6px 14px;font-size:14px;font-weight:700;
      margin-bottom:14px;
    }
    .btn-inscrever{
      display:inline-flex;align-items:center;gap:8px;
      background:rgba(255,255,255,0.15);
      color:white;border:none;border-radius:11px;
      padding:11px 22px;font-size:14px;font-weight:700;
      cursor:pointer;font-family:'Roboto',sans-serif;
      text-decoration:none;
      
    }
    

    /* OUTRAS MODALIDADES */
    .outras-mod-section{margin-bottom:20px}
    .outras-mod-titulo{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,0.7);margin-bottom:12px;display:flex;align-items:center;gap:7px}
    .outras-mod-grid{display:flex;flex-wrap:wrap;gap:10px}
    .mod-card{display:inline-flex;align-items:center;gap:10px;padding:12px 18px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:700;border:1px solid;transition:all .2s;cursor:pointer}
    .mod-card.futebol{background:rgba(34,197,94,.08);border-color:rgba(34,197,94,.25);color:#4ade80}
    .mod-card.futebol:hover{background:rgba(34,197,94,.15);transform:translateY(-2px)}
    .mod-card.basquetebol{background:rgba(245,158,11,.08);border-color:rgba(245,158,11,.25);color:#fbbf24}
    .mod-card.basquetebol:hover{background:rgba(245,158,11,.15);transform:translateY(-2px)}
    .mod-card.voleibol{background:rgba(59,130,246,.08);border-color:rgba(59,130,246,.25);color:#60a5fa}
    .mod-card.voleibol:hover{background:rgba(59,130,246,.15);transform:translateY(-2px)}
    .mod-card.atual{opacity:.5;cursor:default;pointer-events:none}
    .mod-card-info{display:flex;flex-direction:column;gap:2px}
    .mod-card-nome{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.04em}
    .mod-card-div{font-size:11px;font-weight:400;opacity:.75}

    /* SEM DADOS */
    .sem-dados{text-align:center;padding:36px 20px;color:rgba(255,255,255,0.3);font-size:13px}
    .sem-dados i{display:block;font-size:2rem;margin-bottom:10px;opacity:.3}

    /* LOADING */
    .loading{text-align:center;padding:100px 20px;color:rgba(255,255,255,0.3)}
    .loading i{font-size:2.5rem;display:block;margin-bottom:14px;animation:spin 1s linear infinite}
    @keyframes spin{to{transform:rotate(360deg)}}

    @media(max-width:600px){
      .grid{grid-template-columns:1fr}
      .hero-inner{flex-direction:column;text-align:center}
      .hero-badges{justify-content:center}
    }
  </style>
  <style>
    /* ── MODAL MARCAÇÃO ─────────────────────────────── */
    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:99999;align-items:center;justify-content:center;}
    .modal-overlay.show{display:flex;}
    .modal-box{background:rgba(12,4,30,.97);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:32px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;}
    @keyframes modalIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    .modal-title{font-size:1.2rem;font-weight:800;margin-bottom:20px;display:flex;align-items:center;gap:10px;}
    .modal-close{position:absolute;top:18px;right:18px;background:none;border:none;color:rgba(255,255,255,.5);font-size:1.4rem;cursor:pointer;line-height:1;}
    .modal-box{position:relative;}
    .form-group{margin-bottom:16px;}
    .form-group label{display:block;font-size:12px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;}
    .form-group input,.form-group select{width:100%;padding:11px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:white;font-size:14px;font-family:inherit;outline:none;transition:border .2s;}
    .form-group input:focus,.form-group select:focus{border-color:rgba(123,44,255,.6);}
    .form-group select option{background:#0c041e;color:white;}
    .vagas-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:700;margin-top:6px;}
    .vagas-ok{background:rgba(34,197,94,.15);color:#4ade80;border:1px solid rgba(34,197,94,.25);}
    .vagas-low{background:rgba(245,158,11,.15);color:#fbbf24;border:1px solid rgba(245,158,11,.25);}
    .vagas-zero{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.25);}
    .pagamento-box{background:rgba(75,3,209,.12);border:1px solid rgba(123,44,255,.25);border-radius:12px;padding:16px;margin:16px 0;}
    .pagamento-titulo{font-size:12px;font-weight:700;color:#a78bfa;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;}
    .metodo-radio{display:flex;flex-direction:column;gap:8px;}
    .metodo-radio label{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:9px;border:1px solid rgba(255,255,255,.08);cursor:pointer;transition:all .15s;font-size:13px;}
    .metodo-radio label:hover{background:rgba(123,44,255,.12);border-color:rgba(123,44,255,.3);}
    .metodo-radio input[type=radio]{accent-color:rgba(255,255,255,0.7);}
    .resumo-preco{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-top:1px solid rgba(255,255,255,.08);margin-top:12px;}
    .resumo-preco .label{font-size:12px;color:rgba(255,255,255,.5);}
    .resumo-preco .valor{font-size:1.2rem;font-weight:800;color:#fbbf24;}
    .btn-confirmar{width:100%;padding:14px;background:rgba(255,255,255,0.15);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;}
    .btn-confirmar:hover:not(:disabled){transform:translateY(-2px);}
    .btn-confirmar:disabled{opacity:.5;cursor:not-allowed;}
    .sucesso-box{text-align:center;padding:20px 0;}
    .sucesso-icon{font-size:3rem;margin-bottom:12px;}
    .sucesso-titulo{font-size:1.1rem;font-weight:800;margin-bottom:8px;}
    .sucesso-desc{font-size:13px;color:rgba(255,255,255,.6);line-height:1.6;}
    .btn-abrir-inscricao{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.15);color:white;border:none;border-radius:11px;padding:11px 22px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;text-decoration:none;}
    .btn-abrir-inscricao:hover{transform:translateY(-2px);}
  </style>
</head>
<body>

  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo"/></a>
    </div>
    <nav class="header-nav">
      <a href="escolha.php"><i class="bi bi-grid-fill"></i> <span data-i18n="modalidades">Modalidades</span></a>
      <a href="mapa.php"><i class="bi bi-map-fill"></i> <span data-i18n="nav_map">Mapa</span></a>
      <a href="sobre.php"><i class="bi bi-info-circle-fill"></i> <span data-i18n="nav_about">Sobre</span></a>
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
          <a class="dropdown-item" href="perfil.php"><i class="bi bi-person-fill"></i> <span data-i18n="profile">O meu perfil</span></a>
          <div id="adminItem" style="display:none">
            <a class="dropdown-item" href="admin.php"><i class="bi bi-shield-lock-fill"></i> <span data-i18n="admin_panel">Painel Admin</span> <span class="admin-badge">ADMIN</span></a>
          </div>
          <hr class="dropdown-divider"/>
          <button class="dropdown-item danger" onclick="window.location.href='PHP/auth.php?action=logout&redirect=../index.php'">
            <i class="bi bi-box-arrow-right"></i> <span data-i18n="logout">Sair da conta</span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <div class="clube-page">
    <div class="loading" id="loadingMsg">
      <i class="bi bi-arrow-repeat"></i>
      <span data-i18n="clube_loading">A carregar...</span>
    </div>
    <div id="conteudo" style="display:none"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script src="assets/js/tradutor.js"></script>
  <script src="assets/js/fullscreen.js"></script>
  <script>
    // ── Parâmetros URL ─────────────────────────────────────────
    const p   = new URLSearchParams(location.search);
    const NOME      = p.get('nome')       || '';
    const MOD       = p.get('modalidade') || '';
    const DIV       = p.get('divisao')    || '';
    const RECINTO   = p.get('recinto')    || '';
    const LOGO      = p.get('logo')       || '';

    // ── Mapa de logos Wikimedia (sem precisar de ficheiros locais) ──
    const LOGO_URLS = {
      "FC Porto":"https://upload.wikimedia.org/wikipedia/pt/thumb/3/3e/FC_Porto.svg/120px-FC_Porto.svg.png",
      "Sporting CP":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/77/Sporting_CP.svg/120px-Sporting_CP.svg.png",
      "SL Benfica":"https://upload.wikimedia.org/wikipedia/pt/thumb/d/d8/SL_Benfica_logo.svg/120px-SL_Benfica_logo.svg.png",
      "SC Braga":"https://upload.wikimedia.org/wikipedia/pt/thumb/0/07/SC_Braga.png/120px-SC_Braga.png",
      "Vitória SC":"https://upload.wikimedia.org/wikipedia/pt/thumb/3/3e/Vitoria_SC.svg/120px-Vitoria_SC.svg.png",
      "Gil Vicente":"https://upload.wikimedia.org/wikipedia/pt/thumb/4/4b/GilVicenteFC.png/120px-GilVicenteFC.png",
      "FC Famalicão":"https://upload.wikimedia.org/wikipedia/pt/thumb/c/c1/FC_Famalic%C3%A3o.png/120px-FC_Famalic%C3%A3o.png",
      "Moreirense":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/70/Moreirense_FC.png/120px-Moreirense_FC.png",
      "Estoril Praia":"https://upload.wikimedia.org/wikipedia/pt/thumb/5/50/GD_Estoril_Praia.png/120px-GD_Estoril_Praia.png",
      "FC Alverca":"https://upload.wikimedia.org/wikipedia/pt/thumb/2/2e/FC_Alverca.png/120px-FC_Alverca.png",
      "Rio Ave":"https://upload.wikimedia.org/wikipedia/pt/thumb/e/e9/Rio_Ave_FC_logo.png/120px-Rio_Ave_FC_logo.png",
      "Nacional":"https://upload.wikimedia.org/wikipedia/pt/thumb/f/f1/CD_Nacional.png/120px-CD_Nacional.png",
      "Santa Clara":"https://upload.wikimedia.org/wikipedia/pt/thumb/f/f0/CD_Santa_Clara.png/120px-CD_Santa_Clara.png",
      "Estrela da Amadora":"https://upload.wikimedia.org/wikipedia/pt/thumb/5/5d/Estrela_da_Amadora.png/120px-Estrela_da_Amadora.png",
      "Casa Pia AC":"https://upload.wikimedia.org/wikipedia/pt/thumb/a/af/Casa_Pia_AC.png/120px-Casa_Pia_AC.png",
      "FC Arouca":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/71/FC_Arouca.png/120px-FC_Arouca.png",
      "CD Tondela":"https://upload.wikimedia.org/wikipedia/pt/thumb/0/09/CD_Tondela.png/120px-CD_Tondela.png",
      "AFS Futebol SAD":"https://upload.wikimedia.org/wikipedia/pt/thumb/5/5d/AVS_FC.png/120px-AVS_FC.png",
      "Marítimo":"https://upload.wikimedia.org/wikipedia/pt/thumb/8/8c/CS_Mar%C3%ADtimo.png/120px-CS_Mar%C3%ADtimo.png",
      "Sporting CP B":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/77/Sporting_CP.svg/120px-Sporting_CP.svg.png",
      "Académico de Viseu":"https://upload.wikimedia.org/wikipedia/pt/thumb/6/62/Acad%C3%A9mico_de_Viseu_FC.png/120px-Acad%C3%A9mico_de_Viseu_FC.png",
      "GD Chaves":"https://upload.wikimedia.org/wikipedia/pt/thumb/e/e9/GD_Chaves.png/120px-GD_Chaves.png",
      "Vizela":"https://upload.wikimedia.org/wikipedia/pt/thumb/9/9e/FC_Vizela.png/120px-FC_Vizela.png",
      "UD Leiria":"https://upload.wikimedia.org/wikipedia/pt/thumb/8/8d/UD_Leiria.png/120px-UD_Leiria.png",
      "SC Farense":"https://upload.wikimedia.org/wikipedia/pt/thumb/c/c1/SC_Farense.png/120px-SC_Farense.png",
      "SL Benfica B":"https://upload.wikimedia.org/wikipedia/pt/thumb/d/d8/SL_Benfica_logo.svg/120px-SL_Benfica_logo.svg.png",
      "FC Porto B":"https://upload.wikimedia.org/wikipedia/pt/thumb/3/3e/FC_Porto.svg/120px-FC_Porto.svg.png",
      "CD Feirense":"https://upload.wikimedia.org/wikipedia/pt/thumb/5/52/CD_Feirense.png/120px-CD_Feirense.png",
      "Portimonense SAD":"https://upload.wikimedia.org/wikipedia/pt/thumb/4/4c/Portimonense_SC.png/120px-Portimonense_SC.png",
      "UD Oliveirense":"https://upload.wikimedia.org/wikipedia/pt/thumb/a/a4/UD_Oliveirense.png/120px-UD_Oliveirense.png",
      "FC Paços de Ferreira":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/7f/FC_Pa%C3%A7os_de_Ferreira.png/120px-FC_Pa%C3%A7os_de_Ferreira.png",
      "Leixões SC":"https://upload.wikimedia.org/wikipedia/pt/thumb/8/8a/Leix%C3%B5es_SC.png/120px-Leix%C3%B5es_SC.png",
      "Varzim SC":"https://upload.wikimedia.org/wikipedia/pt/thumb/0/06/Varzim_SC.png/120px-Varzim_SC.png",
      "CD Trofense":"https://upload.wikimedia.org/wikipedia/pt/thumb/a/aa/CD_Trofense.png/120px-CD_Trofense.png",
      "SC Braga B":"https://upload.wikimedia.org/wikipedia/pt/thumb/0/07/SC_Braga.png/120px-SC_Braga.png",
      "AD Fafe":"https://upload.wikimedia.org/wikipedia/pt/thumb/0/04/AD_Fafe.png/120px-AD_Fafe.png",
      "CD Mafra":"https://upload.wikimedia.org/wikipedia/pt/thumb/d/d3/CD_Mafra.png/120px-CD_Mafra.png",
      "Amora FC":"https://upload.wikimedia.org/wikipedia/pt/thumb/2/2a/Amora_FC.png/120px-Amora_FC.png",
      "CF Os Belenenses":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/73/CF_Belenenses.png/120px-CF_Belenenses.png",
      "Académica OAF":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/71/Acad%C3%A9mica_de_Coimbra.png/120px-Acad%C3%A9mica_de_Coimbra.png",
      "SC Covilhã":"https://upload.wikimedia.org/wikipedia/pt/thumb/3/3d/SC_Covilh%C3%A3.png/120px-SC_Covilh%C3%A3.png",
      "Lusitano GC Évora":"https://upload.wikimedia.org/wikipedia/pt/thumb/f/fb/Lusitano_GCE.png/120px-Lusitano_GCE.png",
      "Ovarense GAVEX":"https://upload.wikimedia.org/wikipedia/pt/thumb/a/a1/Ovarense.png/120px-Ovarense.png",
      "Imortal LUZiGÁS":"https://upload.wikimedia.org/wikipedia/pt/thumb/5/5f/Imortal_DC.png/120px-Imortal_DC.png",
      "CA Queluz":"https://upload.wikimedia.org/wikipedia/pt/thumb/4/4f/CA_Queluz.png/120px-CA_Queluz.png",
      "CP Esgueira":"https://upload.wikimedia.org/wikipedia/pt/thumb/9/9f/CP_Esgueira.png/120px-CP_Esgueira.png",
      "SC Vasco da Gama":"https://upload.wikimedia.org/wikipedia/pt/thumb/b/b1/SC_Vasco_da_Gama_Seixal.png/120px-SC_Vasco_da_Gama_Seixal.png",
      "Galitos Barreiro":"https://upload.wikimedia.org/wikipedia/pt/thumb/1/1f/Galitos_Barreiro.png/120px-Galitos_Barreiro.png",
      "Sporting de Espinho":"https://upload.wikimedia.org/wikipedia/pt/thumb/c/c3/Sporting_Clube_de_Espinho.png/120px-Sporting_Clube_de_Espinho.png",
      "Académica de Espinho":"https://upload.wikimedia.org/wikipedia/pt/thumb/8/88/Acad%C3%A9mica_de_Espinho.png/120px-Acad%C3%A9mica_de_Espinho.png",
      "Castêlo da Maia GC":"https://upload.wikimedia.org/wikipedia/pt/thumb/b/b5/Castelo_da_Maia_GC.png/120px-Castelo_da_Maia_GC.png",
      "Belenenses":"https://upload.wikimedia.org/wikipedia/pt/thumb/7/73/CF_Belenenses.png/120px-CF_Belenenses.png"
    };
    // Resolve logo: primeiro tenta LOGO_URLS pelo nome, depois Wikipedia, depois fallback escudo
    function resolveLogoSrc(nome, imagem_url) {
      if (imagem_url && imagem_url.trim()) return imagem_url.trim();
      return LOGO_URLS[nome] || null;
    }

    // ── EmailJS config ────────────────────────────────────────────
    const EMAILJS_PUBLIC_KEY = "PAbl8cIsdr_jn4lxB";
    const EMAILJS_SERVICE_ID = "service_gahmyxk";
    const EMAILJS_TEMPLATE   = "template_e01o2cf"; // reutiliza template existente
    emailjs.init(EMAILJS_PUBLIC_KEY);

    // ── Dados de vagas por escalão (simulados) ─────────────────
    const vagasPorEscalao = {
      "Traquinas":  { total: 20, ocupadas: Math.floor(Math.random()*10) },
      "Benjamins":  { total: 20, ocupadas: Math.floor(Math.random()*15) },
      "Infantis":   { total: 18, ocupadas: Math.floor(Math.random()*14) },
      "Iniciados":  { total: 18, ocupadas: Math.floor(Math.random()*16) },
      "Juvenis":    { total: 16, ocupadas: Math.floor(Math.random()*14) },
      "Juniores":   { total: 16, ocupadas: Math.floor(Math.random()*15) },
      "Seniores":   { total: 25, ocupadas: Math.floor(Math.random()*20) },
      "Minis":      { total: 20, ocupadas: Math.floor(Math.random()*10) },
      "Cadetes":    { total: 18, ocupadas: Math.floor(Math.random()*14) },
      "Infantis":   { total: 20, ocupadas: Math.floor(Math.random()*12) },
    };
    function getVagas(nomeEscalao) {
      const e = vagasPorEscalao[nomeEscalao] || { total:15, ocupadas: Math.floor(Math.random()*10) };
      return Math.max(0, e.total - e.ocupadas);
    }
    function vagasBadge(vagas) {
      if (vagas === 0) return `<span class="vagas-badge vagas-zero"><i class="bi bi-x-circle-fill"></i> Sem vagas</span>`;
      if (vagas <= 3)  return `<span class="vagas-badge vagas-low"><i class="bi bi-exclamation-circle-fill"></i> ${vagas} vaga${vagas>1?'s':''} disponível${vagas>1?'s':''}</span>`;
      return `<span class="vagas-badge vagas-ok"><i class="bi bi-check-circle-fill"></i> ${vagas} vagas disponíveis</span>`;
    }

    // ── Modal de Inscrição ─────────────────────────────────────
    let _clubeModal = null;
    function abrirModalInscricao(clube) {
      _clubeModal = clube;
      const escaloes = clube.escaloes?.length
        ? clube.escaloes
        : [{nome:"Seniores",idade:"18+",vagas_livres:15}];

      const optsEscaloes = escaloes.map(e => {
        // Use real DB vagas_livres if available, else fallback
        const v = e.vagas_livres !== undefined ? parseInt(e.vagas_livres) : getVagas(e.nome);
        return `<option value="${e.nome}" data-id="${e.id||''}" data-vagas="${v}" ${v<=0?'disabled':''}>${e.nome} (${e.idade})${v<=0?' — Sem vagas':''}</option>`;
      }).join('');

      const preco = clube.inscricao_preco || '25€/mês';

      document.getElementById('modalConteudo').innerHTML = `
        <div class="modal-title"><i class="bi bi-calendar-plus-fill" style="color:#7b2cff"></i> Inscrição — ${clube.nome}</div>

        <div class="form-group">
          <label>Nome completo</label>
          <input type="text" id="inscNome" placeholder="O teu nome" required/>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="inscEmail" placeholder="o.teu@email.com" required/>
        </div>
        <div class="form-group">
          <label>Telefone</label>
          <input type="tel" id="inscTel" placeholder="+351 9XX XXX XXX"/>
        </div>
        <div class="form-group">
          <label>Escalão</label>
          <select id="inscEscalao" onchange="atualizarVagas()">
            <option value="">Selecionar escalão...</option>
            ${optsEscaloes}
          </select>
          <div id="vagasInfo"></div>
        </div>

        <div class="pagamento-box">
          <div class="pagamento-titulo"><i class="bi bi-credit-card-fill"></i> Pagamento (simulado)</div>
          <div class="metodo-radio">
            <label><input type="radio" name="metodo" value="mbway" checked> <i class="bi bi-phone-fill"></i> MB WAY</label>
            <label><input type="radio" name="metodo" value="multibanco"> <i class="bi bi-bank"></i> Multibanco</label>
            <label><input type="radio" name="metodo" value="cartao"> <i class="bi bi-credit-card"></i> Cartão de crédito</label>
          </div>
          <div class="resumo-preco">
            <span class="label">Mensalidade</span>
            <span class="valor">${preco}</span>
          </div>
        </div>

        <button class="btn-confirmar" id="btnConfirmar" onclick="confirmarInscricao()">
          <i class="bi bi-check-circle-fill"></i> Confirmar Inscrição
        </button>
      `;

      document.getElementById('modalInscricao').classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function atualizarVagas() {
      const sel  = document.getElementById('inscEscalao');
      const nome = sel.value;
      const info = document.getElementById('vagasInfo');
      const btn  = document.getElementById('btnConfirmar');
      if (!nome) { info.innerHTML=''; return; }
      const vagas = parseInt(sel.selectedOptions[0]?.dataset.vagas || 5);
      info.innerHTML = vagasBadge(vagas);
      btn.disabled   = vagas === 0;
    }

    function fecharModal() {
      document.getElementById('modalInscricao').classList.remove('show');
      document.body.style.overflow = '';
    }

    async function confirmarInscricao() {
      const nome    = document.getElementById('inscNome')?.value.trim();
      const email   = document.getElementById('inscEmail')?.value.trim();
      const tel     = document.getElementById('inscTel')?.value.trim();
      const escalao = document.getElementById('inscEscalao')?.value;
      const metodo  = document.querySelector('input[name=metodo]:checked')?.value;

      if (!nome || !email || !escalao) {
        alert('Por favor preenche o nome, email e escalão.');
        return;
      }

      const btn = document.getElementById('btnConfirmar');
      btn.disabled   = true;
      btn.innerHTML  = '<i class="bi bi-arrow-repeat" style="animation:spin 1s linear infinite"></i> A processar...';

      // Simula processamento do pagamento (1.5s)
      await new Promise(r => setTimeout(r, 1500));

      const clube    = _clubeModal;
      const preco    = clube.inscricao_preco || '25€/mês';
      const metodoNome = {mbway:'MB WAY',multibanco:'Multibanco',cartao:'Cartão de Crédito'}[metodo] || metodo;
      const ref      = 'VJ' + Date.now().toString().slice(-6);

      // Envia email via EmailJS
      try {
        await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE, {
          to_name:    nome,
          to_email:   email,
          message:    `Inscrição confirmada no clube ${clube.nome}\n` +
                      `Escalão: ${escalao}\n` +
                      `Método de pagamento: ${metodoNome}\n` +
                      `Mensalidade: ${preco}\n` +
                      `Referência: ${ref}\n` +
                      `Telefone: ${tel || 'Não indicado'}\n\n` +
                      `O clube irá contactá-lo brevemente para confirmar a inscrição.`
        });
      } catch(e) { console.warn('EmailJS:', e); }

      // Mostra ecrã de sucesso
      document.getElementById('modalConteudo').innerHTML = `
        <div class="sucesso-box">
          <div class="sucesso-icon">✅</div>
          <div class="sucesso-titulo">Inscrição confirmada!</div>
          <div class="sucesso-desc">
            Olá <strong>${nome}</strong>, a tua inscrição no <strong>${clube.nome}</strong>
            foi registada com sucesso.<br><br>
            <strong>Escalão:</strong> ${escalao}<br>
            <strong>Método:</strong> ${metodoNome}<br>
            <strong>Mensalidade:</strong> ${preco}<br>
            <strong>Referência:</strong> <code style="background:rgba(123,44,255,.2);padding:2px 6px;border-radius:4px">${ref}</code><br><br>
            Um email de confirmação foi enviado para <strong>${email}</strong>.<br>
            O clube irá contactá-lo brevemente.
          </div>
          <br>
          <button class="btn-confirmar" onclick="fecharModal()" style="margin-top:8px">
            <i class="bi bi-x-circle"></i> Fechar
          </button>
        </div>
      `;
    }

    // Fechar modal ao clicar fora
    document.getElementById('modalInscricao')?.addEventListener('click', e => {
      if (e.target === document.getElementById('modalInscricao')) fecharModal();
    });

    // ── Header utilizador ──────────────────────────────────────
    async function initUser() {
      try {
        const d = await (await fetch('PHP/auth.php?action=session')).json();
        if (!d.autenticado) { location.href='index.php'; return; }
        const u = d.utilizador;
        const av = document.getElementById('headerAvatar');
        if (u.avatar?.startsWith('data:')) av.innerHTML=`<img src="${u.avatar}"/>`;
        else av.textContent=u.nome.split(' ').map(n=>n[0]).slice(0,2).join('').toUpperCase();
        document.getElementById('headerNome').textContent = u.nome.split(' ')[0];
        document.getElementById('dropNome').textContent   = u.nome;
        document.getElementById('dropEmail').textContent  = u.email;
        if (u.role==='admin') document.getElementById('adminItem').style.display='block';
      } catch(e){ location.href='index.php'; }
    }

    document.getElementById('userBtn').addEventListener('click', e=>{
      e.stopPropagation();
      document.getElementById('userDropdown').classList.toggle('show');
    });
    document.addEventListener('click', e=>{
      if (!document.getElementById('langWrap')?.contains(e.target))
        document.getElementById('langMenu')?.classList.remove('show');
      document.getElementById('userDropdown').classList.remove('show');
    });

    // ── Ícone modalidade ────────────────────────────────────────
    const ICONS = { futebol:'bi-dribbble', basquetebol:'bi-record-circle', voleibol:'bi-circle' };
    const MOD_LABEL = { futebol:'Futebol', basquetebol:'Basquetebol', voleibol:'Voleibol' };
    const MOD_ICON  = { futebol:'bi-dribbble', basquetebol:'bi-record-circle', voleibol:'bi-circle' };

    // ── Carrega outras modalidades do mesmo clube ───────────────
    async function carregarOutrasModalidades(nome, modAtual) {
      const secao = document.getElementById('outrasModalidades');
      if (!secao) return;
      try {
        const url = `PHP/clubes_api.php?action=modalidades&nome=${encodeURIComponent(nome)}&modalidade=${encodeURIComponent(modAtual)}`;
        const d   = await (await fetch(url)).json();
        const mods = d.modalidades || [];

        if (mods.length <= 1) { secao.style.display = 'none'; return; }

        const cards = mods.map(m => {
          const modKey = m.modalidade.toLowerCase();
          const icon   = MOD_ICON[modKey] || 'bi-trophy';
          const label  = MOD_LABEL[modKey] || m.modalidade;
          const isAtual = m.atual;
          const href   = `clube.php?nome=${encodeURIComponent(nome)}&modalidade=${encodeURIComponent(m.modalidade)}&divisao=${encodeURIComponent(m.divisao||'')}`;
          return `
            <a class="mod-card ${modKey}${isAtual?' atual':''}" ${isAtual?'':'href="'+href+'"'} title="${isAtual?'Modalidade atual':'Ver '+label}">
              <i class="bi ${icon}" style="font-size:1.4rem"></i>
              <div class="mod-card-info">
                <span class="mod-card-nome">${label}</span>
                <span class="mod-card-div">${m.divisao || '—'}</span>
              </div>
              ${isAtual?'<i class="bi bi-check-circle-fill" style="margin-left:4px;font-size:12px"></i>':''}
            </a>`;
        }).join('');

        secao.innerHTML = `
          <div class="outras-mod-titulo"><i class="bi bi-grid-3x3-gap-fill"></i> Este clube noutras modalidades</div>
          <div class="outras-mod-grid">${cards}</div>
        `;
        secao.style.display = 'block';
      } catch(e) {
        secao.style.display = 'none';
      }
    }

    // ── Renderiza com dados da BD ──────────────────────────────
    // ── Helper: obtém tradução do dicionário atual ─────────────
    function t(key, fallback) {
      return (window._dict && window._dict[key]) ? window._dict[key] : (fallback || key);
    }

    // ── Estado atual do clube carregado ────────────────────────
    let _clubeAtual   = null;
    let _modoAtual    = 'clube'; // 'clube' ou 'semDados'

    // ── Renderiza com dados da BD ──────────────────────────────
    function render(c) {
      _clubeAtual = c;
      _modoAtual  = 'clube';
      document.title = c.nome + ' — VaiJogar';
      const voltar = document.referrer ? 'javascript:history.back()' : 'mapa.php';

      // Horários
      let horariosHtml = `<div class="sem-dados"><i class="bi bi-clock"></i>${t('clube_sem_horarios','Sem horários registados')}</div>`;
      if (c.horarios?.length) {
        horariosHtml = '<div class="horario-list">' + c.horarios.map(h=>`
          <div class="horario-row">
            <span class="horario-dia"><i class="bi bi-calendar3"></i>${h.dia}</span>
            <span class="horario-hora" style="font-size:12px;letter-spacing:.01em">${h.horas}</span>
          </div>`).join('') + '</div>';
      }

      // Escalões
      let escaloesHtml = `<div class="sem-dados"><i class="bi bi-people"></i>${t('clube_sem_escaloes','Sem escalões registados')}</div>`;
      if (c.escaloes?.length) {
        escaloesHtml = '<div class="escaloes-grid">' + c.escaloes.map(e=>{
          const livres = parseInt(e.vagas_livres) || 0;
          const vagaClass = livres > 10 ? 'vagas-ok' : livres > 0 ? 'vagas-low' : 'vagas-zero';
          const vagaTxt = livres <= 0 ? 'Esgotado' : livres + ' vagas';
          return `<div class="escalao">
            <span class="escalao-nome">${e.nome}</span>
            <span class="escalao-idade">${e.idade}</span>
            <span class="vagas-badge ${vagaClass}">${vagaTxt}</span>
          </div>`;
        }).join('') + '</div>';
      }

      // Contactos
      let contactosHtml = '';
      if (c.localizacao) contactosHtml += `<div class="contacto-row"><i class="bi bi-geo-alt-fill"></i>${c.localizacao}</div>`;
      if (c.telefone) contactosHtml += `<div class="contacto-row"><i class="bi bi-telephone-fill"></i><a href="tel:${c.telefone}">${c.telefone}</a></div>`;
      if (c.email)    contactosHtml += `<div class="contacto-row"><i class="bi bi-envelope-fill"></i><a href="mailto:${c.email}">${c.email}</a></div>`;
      if (c.website)  contactosHtml += `<div class="contacto-row"><i class="bi bi-globe2"></i><a href="${c.website}" target="_blank">${c.website}</a></div>`;
      if (!contactosHtml) contactosHtml = `<div class="sem-dados" style="padding:10px 0"><i class="bi bi-telephone-slash"></i>${t('clube_sem_contactos','Sem contactos registados')}</div>`;

      // Redes sociais
      let redesHtml = '';
      if (c.facebook)  redesHtml += `<a class="rede-btn" href="${c.facebook}"  target="_blank"><i class="bi bi-facebook"></i>Facebook</a>`;
      if (c.instagram) redesHtml += `<a class="rede-btn" href="${c.instagram}" target="_blank"><i class="bi bi-instagram"></i>Instagram</a>`;

      // Inscrição — usa campos traduzidos se existirem
      const lang = window._lang || 'pt';
      const inscricaoTexto = c['inscricao_info_' + lang] || c.inscricao_info || '';
      let inscricaoHtml = '';
      if (inscricaoTexto) inscricaoHtml += `<div class="inscricao-box">${inscricaoTexto}</div>`;
      if (c.inscricao_preco) inscricaoHtml += `<div class="preco-tag"><i class="bi bi-tag-fill"></i>${c.inscricao_preco}</div><br>`;
      const emailInsc = c.inscricao_contacto || c.email;
      if (emailInsc) inscricaoHtml += `<a class="btn-inscrever" href="mailto:${emailInsc}" style="margin-right:10px"><i class="bi bi-envelope-fill"></i>${t('clube_contactar_inscricao','Contactar')}</a>`;
      // Botão de marcação com vagas
      inscricaoHtml += `<button class="btn-abrir-inscricao" onclick="abrirModalInscricao(${JSON.stringify(c).replace(/\`/g,'\\`')})"><i class="bi bi-calendar-plus-fill"></i> Marcar Inscrição</button>`;
      if (!inscricaoHtml) inscricaoHtml = `<div class="sem-dados"><i class="bi bi-person-slash"></i>${t('clube_sem_inscricao','Sem informações de inscrição')}</div>`;

      // Descrição — usa campo traduzido se existir
      const descricaoTexto = c['descricao_' + lang] || c.descricao || '';

      const modKey = c.modalidade || MOD;
      const modNome = t(modKey, modKey.charAt(0).toUpperCase() + modKey.slice(1));

      document.getElementById('conteudo').innerHTML = `
        <a class="btn-voltar" href="${voltar}"><i class="bi bi-arrow-left"></i> ${t('clube_voltar','Voltar')}</a>

        <div class="hero">
          <div class="hero-inner">
            <div class="hero-logo">
              <img id="heroLogoImg" src="${resolveLogoSrc(c.nome, c.imagem_url) || ''}" alt="${c.nome}"
                   onerror="this.parentElement.innerHTML='<i class=\\'bi bi-shield-fill\\' style=\\'font-size:2.5rem;color:rgba(255,255,255,0.7);\\'></i>'"
                   style="${resolveLogoSrc(c.nome, c.imagem_url) ? '' : 'display:none'}"/>
              ${!resolveLogoSrc(c.nome, c.imagem_url) ? `<i class="bi bi-shield-fill" id="heroShield" style="font-size:2.5rem;color:rgba(255,255,255,0.7);"></i>` : ''}
            </div>
            <div class="hero-info">
              <div class="hero-nome">${c.nome}</div>
              <div class="hero-badges">
                <span class="badge badge-mod"><i class="bi ${ICONS[modKey]||'bi-trophy'}"></i>${modNome}</span>
                <span class="badge badge-div"><i class="bi bi-trophy-fill"></i>${c.divisao||DIV}</span>
                ${(c.recinto||RECINTO)?`<span class="badge badge-rec"><i class="bi bi-geo-fill"></i>${c.recinto||RECINTO}</span>`:''}
              </div>
            </div>
            ${redesHtml?`<div style="display:flex;flex-direction:column;gap:6px">${redesHtml}</div>`:''}
          </div>
        </div>

        <div class="outras-mod-section" id="outrasModalidades" style="display:none"></div>

        <div class="grid">
          ${descricaoTexto?`
          <div class="card full">
            <div class="card-title"><i class="bi bi-info-circle-fill"></i>${t('clube_sobre','Sobre o clube')}</div>
            <p class="descricao-txt">${descricaoTexto}</p>
          </div>`:''}

          <div class="card full">
  <div class="card-title"><i class="bi bi-dumbbell"></i>Marcação de Treinos</div>
  <a href="booking-simples.php?clube=${c.nome}&modalidade=${c.modalidade||MOD}" style="display:inline-flex; align-items:center; gap:8px; background:rgba(34,197,94,0.7); color:white; padding:11px 22px; border-radius:11px; text-decoration:none; font-weight:700;">
    <i class="bi bi-calendar-check"></i> Marcar Treino
  </a>
</div>

          <div class="card">
            <div class="card-title"><i class="bi bi-clock-fill"></i>${t('clube_horarios','Horários de treino')}</div>
            ${horariosHtml}
          </div>

          <div class="card">
            <div class="card-title"><i class="bi bi-people-fill"></i>${t('clube_escaloes','Escalões')}</div>
            ${escaloesHtml}
          </div>

          <div class="card full">
            <div class="card-title"><i class="bi bi-telephone-fill"></i>${t('clube_contactos','Contactos')}</div>
            <div class="contacto-list">${contactosHtml}</div>
          </div>
        </div>
      `;

      document.getElementById('loadingMsg').style.display = 'none';
      document.getElementById('conteudo').style.display   = 'block';

      // Carrega outras modalidades do mesmo clube
      carregarOutrasModalidades(c.nome, c.modalidade || MOD);
    }

    // ── Renderiza quando clube não está na BD ──────────────────
    function renderSemDados() {
      _modoAtual = 'semDados';
      const voltar = document.referrer ? 'javascript:history.back()' : 'mapa.php';
      const modKey = MOD;
      const modNome = t(modKey, modKey.charAt(0).toUpperCase() + modKey.slice(1));

      document.title = (NOME||'Clube') + ' — VaiJogar';
      document.getElementById('conteudo').innerHTML = `
        <a class="btn-voltar" href="${voltar}"><i class="bi bi-arrow-left"></i> ${t('clube_voltar','Voltar')}</a>

        <div class="hero">
          <div class="hero-inner">
            <div class="hero-logo">
              <img src="${resolveLogoSrc(NOME, LOGO) || ''}" alt="${NOME}"
                   onerror="this.parentElement.innerHTML='<i class=\\'bi bi-shield-fill\\' style=\\'font-size:2.5rem;color:rgba(255,255,255,0.7);\\'></i>'"
                   style="${resolveLogoSrc(NOME, LOGO) ? '' : 'display:none'}"/>
              ${!resolveLogoSrc(NOME, LOGO) ? `<i class="bi bi-shield-fill" style="font-size:2.5rem;color:rgba(255,255,255,0.7);"></i>` : ''}
            </div>
            <div class="hero-info">
              <div class="hero-nome">${NOME}</div>
              <div class="hero-badges">
                ${MOD?`<span class="badge badge-mod"><i class="bi ${ICONS[modKey]||'bi-trophy'}"></i>${modNome}</span>`:''}
                ${DIV?`<span class="badge badge-div"><i class="bi bi-trophy-fill"></i>${DIV}</span>`:''}
                ${RECINTO?`<span class="badge badge-rec"><i class="bi bi-geo-fill"></i>${RECINTO}</span>`:''}
              </div>
            </div>
          </div>
        </div>

        <div class="card full" style="text-align:center;padding:40px 20px">
          <i class="bi bi-database-slash" style="font-size:2.5rem;color:rgba(255,255,255,0.2);display:block;margin-bottom:14px"></i>
          <p style="color:rgba(255,255,255,0.4);font-size:14px;margin-bottom:20px;line-height:1.6">
            ${t('clube_sem_dados_desc','Este clube ainda não tem informações detalhadas registadas. Um administrador pode adicionar os detalhes no painel de administração.')}
          </p>
          <a href="${voltar}" class="btn-inscrever" style="display:inline-flex">
            <i class="bi bi-arrow-left"></i> ${t('clube_voltar_mapa','Voltar ao mapa')}
          </a>
        </div>
      `;

      document.getElementById('loadingMsg').style.display = 'none';
      document.getElementById('conteudo').style.display   = 'block';
    }

    // ── Re-renderiza quando língua muda ────────────────────────
    document.addEventListener('langChanged', () => {
      if (_modoAtual === 'clube' && _clubeAtual)  render(_clubeAtual);
      else if (_modoAtual === 'semDados')          renderSemDados();
    });

    // ── Carrega da BD ──────────────────────────────────────────
    async function carregar() {
      if (!NOME) { renderSemDados(); return; }
      try {
        const url = `PHP/clubes_api.php?action=clube&nome=${encodeURIComponent(NOME)}&modalidade=${encodeURIComponent(MOD)}`;
        const d   = await (await fetch(url)).json();
        if (d.encontrado) render(d); else renderSemDados();
      } catch(e) { renderSemDados(); }
    }

    initUser();
    carregar();
  </script>

  <!-- ── MODAL MARCAÇÃO/INSCRIÇÃO ──────────────────────────── -->
  <div class="modal-overlay" id="modalInscricao">
    <div class="modal-box">
      <button class="modal-close" onclick="fecharModal()">&times;</button>
      <div id="modalConteudo"></div>
    </div>
  </div>

</body>
</html>
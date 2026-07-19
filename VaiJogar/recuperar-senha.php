<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title data-i18n-title="recuperar_title">Recuperar Palavra-passe — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    const EMAILJS_PUBLIC_KEY  = "PAbl8cIsdr_jn4lxB";
    const EMAILJS_SERVICE_ID  = "service_gahmyxk";
    const EMAILJS_TEMPLATE_ID = "template_86hqtap";
    emailjs.init(EMAILJS_PUBLIC_KEY);
  </script>
  <style>
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
      background: rgba(20,5,50,0.97); 
      border: 1px solid rgba(255,255,255,0.15); border-radius: 12px;
      overflow: hidden; min-width: 140px; z-index: 999;
    }
    .lang-menu.show { display: block; }
    .lang-menu a {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 16px; color: rgba(255,255,255,0.8);
      font-size: 13px; cursor: pointer; transition: background .15s;
      text-decoration: none;
    }
    .lang-menu a:hover { background: rgba(123,44,255,0.2); color: white; }
    @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

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
    .header-nav a { text-decoration: none !important; }
  </style>
</head>
<body>

  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo" /></a>
    </div>
    <nav class="header-nav">
      <a href="index.php"><i class="bi bi-house-fill"></i> <span data-i18n="nav_home">Início</span></a>
      <a href="sobre.php"><i class="bi bi-info-circle"></i> <span data-i18n="nav_about">Sobre</span></a>
    </nav>
    <div class="lang-selector-wrap" id="langWrap">
      <i class="bi bi-globe"></i>
      <button class="lang-btn-inner" id="currentLangBtn">
        <span id="currentLangText">PT</span>
      </button>
      <div class="lang-menu" id="langMenu">
        <a data-lang="pt"><i class="bi bi-translate"></i> Português</a>
        <a data-lang="en"><i class="bi bi-translate"></i> English</a>
        <a data-lang="es"><i class="bi bi-translate"></i> Español</a>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="caixa-login">
      <div style="text-align:center;margin-bottom:20px;">
        <div style="width:56px;height:56px;border-radius:16px;background:rgba(123,44,255,0.2);border:1px solid rgba(123,44,255,0.4);display:inline-flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--purple-glow);margin-bottom:14px;">
          <i class="bi bi-key-fill"></i>
        </div>
        <h2 style="margin:0 0 6px 0;" data-i18n="recuperar_titulo">Recuperar Palavra-passe</h2>
        <p style="color:rgba(255,255,255,0.55);font-size:14px;margin:0;" data-i18n="recuperar_desc">Introduz o teu email e receberás um link para redefinir a palavra-passe.</p>
      </div>

      <div id="msgBox" style="display:none;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:14px;text-align:center;"></div>

      <div class="grupo-input">
        <i class="bi bi-envelope-fill" style="margin-right:8px;color:#fff;"></i>
        <input type="email" id="emailInput" placeholder="O teu email" data-i18n-placeholder="recuperar_placeholder_email" required />
      </div>

      <button id="submitBtn" class="botao-entrar" onclick="enviarRecuperacao()" style="display:flex;align-items:center;justify-content:center;gap:8px;">
        <i class="bi bi-send-fill"></i> <span data-i18n="recuperar_btn">Enviar link de recuperação</span>
      </button>

      <p class="Criar-conta" style="margin-top:18px;">
        <span data-i18n="recuperar_lembrou">Lembrou-se da palavra-passe?</span>
        <a href="index.php" data-i18n="recuperar_voltar">Voltar ao login</a>
      </p>
    </div>
  </div>

  <div id="intro"><img src="assets/images/logo2.png" alt="Logo"></div>

  <script>
    // Função t() de segurança para mensagens
    function t(key, fallback) { return fallback || key; }

    async function enviarRecuperacao() {
      const email = document.getElementById("emailInput").value.trim();
      const btn   = document.getElementById("submitBtn");
      msgBox("", false, false);

      if (!email) { msgBox(t("msg_recovery_enter_email","Introduz o teu email."), false); return; }

      btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin .8s linear infinite;display:inline-block;"></i> A enviar...';
      btn.disabled = true;

      try {
        const fd = new FormData();
        fd.append("email", email);
        fd.append("action","recuperar");
      const r = await fetch("PHP/auth.php", { method:"POST", body:fd });
        const d = await r.json();

        if (!d.sucesso || !d.token) {
          msgBox(t("msg_recovery_sent","Se este email estiver registado, receberás um link em breve. Verifica também a pasta de spam."), true);
          resetBtn(); return;
        }

        const base = window.location.href.replace("recuperar-senha.php", "");
        const resetLink = base + "reset-password.php?token=" + d.token;

        await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, {
          to_email:   d.email,
          to_name:    d.nome,
          reset_link: resetLink,
          site_name:  "VaiJogar"
        });

        msgBox(t("msg_recovery_sent","Se este email estiver registado, receberás um link em breve. Verifica também a pasta de spam."), true);
      } catch(err) {
        console.error(err);
        msgBox(t("err_email_send","Erro ao enviar o email. Tenta novamente."), false);
      }
      resetBtn();
    }

    function resetBtn() {
      const btn = document.getElementById("submitBtn");
      btn.innerHTML = '<i class="bi bi-send-fill"></i> <span data-i18n="recuperar_btn">Enviar link de recuperação</span>';
      btn.disabled = false;
    }

    function msgBox(texto, sucesso, show = true) {
      const box = document.getElementById("msgBox");
      if (!show) { box.style.display = "none"; return; }
      box.innerHTML = `<i class="bi ${sucesso ? 'bi-check-circle-fill' : 'bi-x-circle-fill'}" style="margin-right:6px;"></i>${texto}`;
      box.style.background = sucesso ? "rgba(34,197,94,0.12)"  : "rgba(239,68,68,0.12)";
      box.style.border     = sucesso ? "1px solid rgba(34,197,94,0.3)" : "1px solid rgba(239,68,68,0.3)";
      box.style.color      = sucesso ? "#6dffaa" : "#ff8a8a";
      box.style.display    = "block";
    }

    document.addEventListener("DOMContentLoaded", () => {
      // Tradução e seletor de língua geridos pelo tradutor.js

      // Partículas
      const canvas = document.createElement("canvas"); canvas.id="particles"; document.body.appendChild(canvas);
      const ctx=canvas.getContext("2d"); let w,h;
      function resize(){ w=canvas.width=window.innerWidth; h=canvas.height=window.innerHeight; }
      window.addEventListener("resize",resize); resize();
      const pts=Array.from({length:60},()=>({x:Math.random()*w,y:Math.random()*h,r:Math.random()*2+1,dx:(Math.random()-.5)*.4,dy:(Math.random()-.5)*.4}));
      function draw(){ ctx.clearRect(0,0,w,h); pts.forEach(p=>{p.x+=p.dx;p.y+=p.dy;if(p.x<0||p.x>w)p.dx*=-1;if(p.y<0||p.y>h)p.dy*=-1;ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle="rgba(150,90,255,0.6)";ctx.fill();}); requestAnimationFrame(draw); }
      draw();
      document.addEventListener("mousemove",e=>{ document.body.style.setProperty("--mx",`${e.clientX}px`); document.body.style.setProperty("--my",`${e.clientY}px`); });

      const sports=["futebol","basquete","volei"];
      let activeSport=localStorage.getItem("sportTheme")||"futebol";
      document.body.classList.add(activeSport);
      let idx=sports.indexOf(activeSport);
      setInterval(()=>{ document.body.classList.remove(...sports); idx=(idx+1)%sports.length; document.body.classList.add(sports[idx]); localStorage.setItem("sportTheme",sports[idx]); },12000);
    });
  </script>

  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <img src="assets/images/logo2.png" alt="VaiJogar" />
        <p data-i18n="footer_desc">Plataforma de geolocalização de clubes desportivos em Portugal.</p>
      </div>
      <div class="footer-links">
        <h4 data-i18n="footer_nav_title">Navegação</h4>
        <a href="index.php"><i class="bi bi-house-fill"></i> <span data-i18n="nav_home">Início</span></a>
        <a href="escolha.php"><i class="bi bi-grid-fill"></i> <span data-i18n="nav_sports">Modalidades</span></a>
        <a href="sobre.php"><i class="bi bi-info-circle-fill"></i> <span data-i18n="nav_about">Sobre</span></a>
        <a href="perfil.php"><i class="bi bi-person-fill"></i> <span data-i18n="footer_perfil">Perfil</span></a>
      </div>
      <div class="footer-sports">
        <h4 data-i18n="footer_sports_title">Modalidades</h4>
        <a href="mapa.php"><i class="bi bi-dribbble"></i> <span data-i18n="footer_football">Futebol</span></a>
        <a href="basket.php"><i class="bi bi-record-circle"></i> <span data-i18n="footer_basket">Basquetebol</span></a>
        <a href="volei.php"><i class="bi bi-circle"></i> <span data-i18n="footer_volley">Voleibol</span></a>
      </div>
    </div>
    <div class="footer-bottom">
      <span data-i18n="footer_rights">© 2026 VaiJogar — Todos os direitos reservados.</span>
      <span>Desenvolvido por <a href="#">Elizandro Novo</a> · PAP 2025/2026</span>
    </div>
  </footer>

  <button class="fullscreen-btn" title="Tela cheia" onclick="toggleFullscreen()">
    <i class="bi bi-fullscreen"></i>
  </button>
  <script src="assets/js/tradutor.js"></script>
  <script>
    // Força reaplicação da tradução depois de tudo carregado
    window.addEventListener("load", () => {
      const lang = localStorage.getItem("siteLang") || "pt";
      if (lang === "pt") return;
      fetch("lang/" + lang + ".json")
        .then(r => r.json())
        .then(dict => {
          document.querySelectorAll("[data-i18n]").forEach(el => {
            const k = el.getAttribute("data-i18n");
            if (!dict[k]) return;
            const hasIcon = el.querySelector("i, svg");
            if (hasIcon) {
              Array.from(el.childNodes).forEach(node => {
                if (node.nodeType === 3 && node.textContent.trim()) node.textContent = " " + dict[k];
              });
            } else {
              el.innerText = dict[k];
            }
          });
          document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
            const k = el.getAttribute("data-i18n-placeholder");
            if (dict[k]) el.placeholder = dict[k];
          });
          const titleEl = document.querySelector("[data-i18n-title]");
          if (titleEl) {
            const k = titleEl.getAttribute("data-i18n-title");
            if (dict[k]) document.title = dict[k];
          }
        }).catch(() => {});
    });
  </script>
  <script src="assets/js/fullscreen.js"></script>
</body>
</html>
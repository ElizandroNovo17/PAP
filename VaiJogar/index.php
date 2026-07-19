<?php
session_start();
require_once __DIR__ . '/PHP/csrf.php';
$csrf_token = csrf_gerar();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title data-i18n="login_title">Iniciar Sessão — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    const CSRF_TOKEN               = "<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>";
    const EMAILJS_PUBLIC_KEY       = "PAbl8cIsdr_jn4lxB";
    const EMAILJS_SERVICE_ID       = "service_gahmyxk";
    const EMAILJS_TEMPLATE_WELCOME = "template_e01o2cf";
    emailjs.init(EMAILJS_PUBLIC_KEY);
    const GOOGLE_CLIENT_ID = "915645442918-44a3b8u3c7ohjcsv76s55svv5b9rqn4o.apps.googleusercontent.com";

    window.addEventListener("load", () => {
      if (typeof google === "undefined") return;
      google.accounts.id.initialize({
        client_id: GOOGLE_CLIENT_ID,
        callback: handleGoogleLogin,
        ux_mode: "popup"
      });
      const currentLang = localStorage.getItem("vj_lang") || "pt";
      const localeMap = { pt:"pt-PT", en:"en", es:"es" };
      google.accounts.id.renderButton(
        document.getElementById("googleBtnContainer"),
        { theme:"outline", size:"large", width:308, text:"continue_with", locale: localeMap[localStorage.getItem("siteLang")||"pt"]||"pt-PT" }
      );
    });

    async function handleGoogleLogin(response) {
      const err = document.getElementById("loginError");
      try {
        const fd = new FormData();
        fd.append("credential", response.credential);
        const r = await fetch("PHP/google_login.php", { method:"POST", body:fd });
        const d = await r.json();
        if (d.sucesso) {
          if (d.novo) {
            try {
              await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_WELCOME, {
                to_email: d.utilizador.email, to_name: d.utilizador.nome,
                nome: d.utilizador.nome,
                assunto: "Bem-vindo ao VaiJogar! ⚽",
                mensagem: "A tua conta foi criada com sucesso via Google!\nJá podes explorar clubes de futebol, basquetebol e voleibol por todo Portugal."
              });
            } catch(ex) { console.warn("EmailJS:", ex); }
          }
          const _next = new URLSearchParams(window.location.search).get('next');
          window.location.href = _next ? _next : (d.utilizador.role === "admin" ? "admin.php" : "escolha.php");
        } else {
          err.textContent = d.mensagem || "Erro ao entrar com Google.";
          err.style.display = "block";
        }
      } catch(ex) { err.textContent = "Erro de ligação."; err.style.display = "block"; }
    }
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
      <a href="index.php" style="color:var(--purple-glow);font-weight:700;"><i class="bi bi-house-fill"></i> <span data-i18n="menu_home">Início</span></a>
      <a href="sobre.php"><i class="bi bi-info-circle"></i> <span data-i18n="menu_about">Sobre</span></a>
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
      <h2 data-i18n="login_heading">Faça o seu login</h2>

      <div id="loginError" style="display:none; background:rgba(255,50,50,0.15); border:1px solid rgba(255,50,50,0.4); border-radius:10px; padding:10px; margin-bottom:15px; color:#ff8a8a; font-size:14px;"></div>

      <form id="loginForm">
        <div class="grupo-input">
          <i class="bi bi-envelope-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="email" id="loginEmail" placeholder="Email" data-i18n-placeholder="username_or_email" required />
        </div>
        <div class="grupo-input password-container">
          <i class="bi bi-lock-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="password" id="loginPassword" placeholder="Palavra-passe" data-i18n-placeholder="password" required />
          <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
        </div>
        <div class="opcoes">
          <label><input type="checkbox" checked /> <span data-i18n="remember_password">Lembrar palavra-passe</span></label>
          <p><a href="recuperar-senha.php" data-i18n="forgot_password">Esqueci-me da palavra-passe</a></p>
        </div>
        <button type="submit" class="botao-entrar" id="loginBtn" data-i18n="login_button">Entrar</button>
      </form>

            <div style="display:flex;align-items:center;gap:10px;margin:14px 0;color:rgba(255,255,255,0.35);font-size:13px;">
        <div style="flex:1;height:1px;background:rgba(255,255,255,0.12);"></div>
        <span data-i18n="or_divider">ou</span>
        <div style="flex:1;height:1px;background:rgba(255,255,255,0.12);"></div>
      </div>
      <div id="googleBtnContainer" style="display:flex;justify-content:center;margin-bottom:10px;"></div>

      <p class="Criar-conta">
        <span data-i18n="no_account">Não tem conta?</span>
        <a href="register.php" data-i18n="create_account">Crie uma conta</a>
      </p>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", async () => {
      // Verificar sessão
      try {
        const r = await fetch("PHP/auth.php?action=session");
        const d = await r.json();
        if (d.autenticado) {
          const next = new URLSearchParams(window.location.search).get('next');
          if (next) { window.location.href = next; return; }
          const _next = new URLSearchParams(window.location.search).get('next');
          window.location.href = _next ? _next : (d.utilizador.role === "admin" ? "admin.php" : "escolha.php"); return; }
      } catch(e) {}

      // Tradução gerida pelo tradutor.js

      // Toggle password
      document.querySelectorAll(".toggle-password").forEach(btn => {
        btn.addEventListener("click", () => {
          const inp = btn.previousElementSibling;
          inp.type = inp.type === "password" ? "text" : "password";
          btn.querySelector("i").classList.toggle("bi-eye");
          btn.querySelector("i").classList.toggle("bi-eye-slash");
        });
      });

      // Login
      document.getElementById("loginForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const email = document.getElementById("loginEmail").value.trim();
        const pass  = document.getElementById("loginPassword").value;
        const err   = document.getElementById("loginError");
        const btn   = document.getElementById("loginBtn");

        btn.textContent = "A entrar..."; btn.disabled = true;
        err.style.display = "none";

        try {
          const fd = new FormData();
          fd.append("email", email); fd.append("password", pass);
          fd.append("action","login");
          fd.append("csrf_token", CSRF_TOKEN);
      const r = await fetch("PHP/auth.php", { method:"POST", body:fd });
          const d = await r.json();
          if (d.sucesso) {
            const _next = new URLSearchParams(window.location.search).get('next');
          window.location.href = _next ? _next : (d.utilizador.role === "admin" ? "admin.php" : "escolha.php");
          } else {
            err.textContent = d.mensagem; err.style.display = "block";
            btn.textContent = "Entrar"; btn.disabled = false;
          }
        } catch(ex) {
          err.textContent = "Erro de ligação ao servidor."; err.style.display = "block";
          btn.textContent = "Entrar"; btn.disabled = false;
        }
      });

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
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
          <img src="assets/images/logo2.png" alt="VaiJogar" style="height:42px;width:auto;filter:drop-shadow(0 0 8px rgba(123,44,255,0.5))" />
          <span style="font-size:20px;font-weight:900;color:white;letter-spacing:-0.5px">VaiJogar</span>
        </div>
        <p>Plataforma de geolocalização de clubes desportivos em Portugal.</p>
      </div>
      <div class="footer-links">
        <h4>Navegação</h4>
        <a href="index.php"><i class="bi bi-house-fill"></i> Início</a>
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

  <button class="fullscreen-btn" title="Tela cheia" onclick="toggleFullscreen()">
    <i class="bi bi-fullscreen"></i>
  </button>
  <script src="assets/js/tradutor.js"></script>
  <script src="assets/js/fullscreen.js"></script>
</body>
</html>
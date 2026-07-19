<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title data-i18n="create_account">Criar Conta — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    const EMAILJS_PUBLIC_KEY       = "PAbl8cIsdr_jn4lxB";
    const EMAILJS_SERVICE_ID       = "service_gahmyxk";
    const EMAILJS_TEMPLATE_WELCOME = "template_e01o2cf";
    emailjs.init(EMAILJS_PUBLIC_KEY);
  </script>
  <style>
    .pass-req { margin:8px 0 14px; padding:12px 14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:10px; font-size:12px; }
    .req-item { display:flex; align-items:center; gap:7px; padding:3px 0; color:rgba(255,255,255,0.45); transition:color .2s; }
    .req-item i { font-size:13px; }
    .req-item.ok   { color:#6dffaa; }
    .req-item.fail { color:#ff8a8a; }
    .lang-selector-wrap { display:flex; align-items:center; gap:6px; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15); border-radius:10px; padding:5px 12px; cursor:pointer; position:relative; }
    .lang-selector-wrap i { color:rgba(255,255,255,0.6); font-size:14px; }
    .lang-btn-inner { background:none !important; border:none !important; color:white !important; font-size:13px; font-weight:700; cursor:pointer; padding:0 !important; font-family:'Roboto',sans-serif; width:auto !important;   }
    .lang-menu { display:none; position:absolute; top:calc(100% + 8px); right:0; background:rgba(20,5,50,0.97);  border:1px solid rgba(255,255,255,0.15); border-radius:12px; overflow:hidden; min-width:140px; z-index:9999; }
    .lang-menu.show { display:block; }
    .lang-menu a { display:flex; align-items:center; gap:8px; padding:10px 16px; color:rgba(255,255,255,0.8); font-size:13px; cursor:pointer; transition:background .15s; text-decoration:none; }
    .lang-menu a:hover { background:rgba(123,44,255,0.2); color:white; }
    .header-nav a { text-decoration:none !important; }
    .site-footer { position:relative; z-index:10; background:rgba(10,2,30,0.85);  border-top:1px solid rgba(123,44,255,0.25); padding:32px 40px 20px; color:rgba(255,255,255,0.55); font-family:"Roboto",sans-serif; font-size:13px; }
    .footer-inner { max-width:1100px; margin:0 auto; display:flex; flex-wrap:wrap; gap:28px; justify-content:space-between; align-items:flex-start; }
    .footer-brand { display:flex; flex-direction:column; gap:8px; }
    .footer-brand img { height:36px; opacity:.9; }
    .footer-brand p { margin:0; font-size:12px; max-width:220px; line-height:1.6; }
    .footer-links, .footer-sports { display:flex; flex-direction:column; gap:8px; }
    .footer-links h4, .footer-sports h4 { margin:0 0 6px; font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--purple-glow); }
    .footer-links a, .footer-sports a { color:rgba(255,255,255,0.5); text-decoration:none; font-size:13px; transition:color .2s; display:flex; align-items:center; gap:6px; }
    .footer-links a:hover, .footer-sports a:hover { color:white; }
    .footer-bottom { max-width:1100px; margin:20px auto 0; padding-top:16px; border-top:1px solid rgba(255,255,255,0.07); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; font-size:12px; }
    .footer-bottom span { color:rgba(255,255,255,0.3); }
    .footer-bottom a { color:rgba(123,44,255,0.8); text-decoration:none; }
    .footer-bottom a:hover { color:var(--purple-glow); }
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
      <button class="lang-btn-inner" id="currentLangBtn"><span id="currentLangText">PT</span></button>
      <div class="lang-menu" id="langMenu">
        <a data-lang="pt"><i class="bi bi-translate"></i> Português</a>
        <a data-lang="en"><i class="bi bi-translate"></i> English</a>
        <a data-lang="es"><i class="bi bi-translate"></i> Español</a>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="caixa-login">
      <h2 data-i18n="create_account">Crie a sua conta</h2>

      <div id="regError"   style="display:none;background:rgba(255,50,50,0.15);border:1px solid rgba(255,50,50,0.4);border-radius:10px;padding:10px;margin-bottom:15px;color:#ff8a8a;font-size:14px;"></div>
      <div id="regSuccess" style="display:none;background:rgba(50,255,120,0.12);border:1px solid rgba(50,255,120,0.35);border-radius:10px;padding:10px;margin-bottom:15px;color:#6dffaa;font-size:14px;"></div>

      <div id="registerForm">
        <div class="grupo-input">
          <i class="bi bi-person-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="text" id="regNome" placeholder="Nome completo" data-i18n-placeholder="full_name" required />
        </div>
        <div class="grupo-input">
          <i class="bi bi-envelope-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="email" id="regEmail" placeholder="Email" data-i18n-placeholder="email" required />
        </div>
        <div class="grupo-input password-container">
          <i class="bi bi-lock-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="password" id="regPass" placeholder="Palavra-passe" data-i18n-placeholder="password" required />
          <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <div class="pass-req">
          <div class="req-item" id="req-len"><i class="bi bi-circle"></i> <span data-i18n="req_min">Mínimo 8 caracteres</span></div>
          <div class="req-item" id="req-upper"><i class="bi bi-circle"></i> <span data-i18n="req_upper">Pelo menos 1 letra maiúscula</span></div>
          <div class="req-item" id="req-num"><i class="bi bi-circle"></i> <span data-i18n="req_num">Pelo menos 1 número</span></div>
          <div class="req-item" id="req-special"><i class="bi bi-circle"></i> <span data-i18n="req_special">Pelo menos 1 carácter especial (!@#$...)</span></div>
        </div>

        <div class="grupo-input password-container">
          <i class="bi bi-lock-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="password" id="regPassConf" placeholder="Confirmar palavra-passe" data-i18n-placeholder="confirm_password" required />
          <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <button id="regBtn" data-i18n="create_btn" onclick="criarConta()">Criar conta</button>
      </div>

      <p class="Criar-conta">
        <span data-i18n="already_have_account">Já tem conta?</span>
        <a href="index.php" data-i18n="login_here">Faça login</a>
      </p>
    </div>
  </div>

  <div id="intro"><img src="assets/images/logo2.png" alt="Logo"></div>

  <script>
    // Validação em tempo real
    const regras = {
      "req-len":     p => p.length >= 8,
      "req-upper":   p => /[A-Z]/.test(p),
      "req-num":     p => /[0-9]/.test(p),
      "req-special": p => /[!@#$%^&*()\-_=+\[\]{};:'",.<>?\/\\|]/.test(p)
    };

    document.getElementById("regPass").addEventListener("input", function() {
      const p = this.value;
      for (const [id, fn] of Object.entries(regras)) {
        const el = document.getElementById(id);
        if (!el) continue;
        const ok = fn(p);
        el.classList.toggle("ok",   ok);
        el.classList.toggle("fail", p.length > 0 && !ok);
        el.querySelector("i").className = ok
          ? "bi bi-check-circle-fill"
          : (p.length > 0 ? "bi bi-x-circle-fill" : "bi bi-circle");
      }
    });

    function validarPassword(p) {
      if (p.length < 8)                                        return "A palavra-passe deve ter pelo menos 8 caracteres.";
      if (!/[A-Z]/.test(p))                                   return "A palavra-passe deve ter pelo menos uma letra maiúscula.";
      if (!/[0-9]/.test(p))                                   return "A palavra-passe deve ter pelo menos um número.";
      if (!/[!@#$%^&*()\-_=+\[\]{};:'",.<>?\/\\|]/.test(p)) return "A palavra-passe deve ter pelo menos um carácter especial (!@#$...).";
      return null;
    }

    async function criarConta() {
      const nome  = document.getElementById("regNome").value.trim();
      const email = document.getElementById("regEmail").value.trim();
      const pass  = document.getElementById("regPass").value;
      const conf  = document.getElementById("regPassConf").value;
      const err   = document.getElementById("regError");
      const suc   = document.getElementById("regSuccess");
      const btn   = document.getElementById("regBtn");

      err.style.display = "none";
      suc.style.display = "none";

      if (!nome)  { mostrarErro("Preenche o nome."); return; }
      if (!email) { mostrarErro("Preenche o email."); return; }

      const erroPass = validarPassword(pass);
      if (erroPass) { mostrarErro(erroPass); return; }
      if (pass !== conf) { mostrarErro("As palavras-passe não coincidem."); return; }

      btn.textContent = "A criar...";
      btn.disabled = true;

      try {
        const fd = new FormData();
        fd.append("nome", nome);
        fd.append("email", email);
        fd.append("password", pass);
        fd.append("confirmar_password", conf);

        fd.append("action","register");
        const r    = await fetch("PHP/auth.php", { method: "POST", body: fd });
        const text = await r.text();
        let d;
        try { d = JSON.parse(text); }
        catch(e) { throw new Error("Servidor devolveu: " + text.substring(0, 150)); }

        if (d.sucesso) {
          try {
            await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_WELCOME, {
              to_email: email, to_name: nome, nome: nome,
              assunto:  "Bem-vindo ao VaiJogar! ⚽",
              mensagem: "A tua conta foi criada com sucesso!\nJá podes explorar clubes de futebol, basquetebol e voleibol por todo Portugal."
            });
          } catch(emailErr) { console.warn("EmailJS:", emailErr); }

          suc.innerHTML = '<i class="bi bi-check-circle-fill" style="margin-right:6px;"></i>Conta criada com sucesso! A redirecionar...';
          suc.style.display = "block";
          setTimeout(() => window.location.href = "index.php", 2000);
        } else {
          mostrarErro(d.mensagem || "Erro ao criar conta.");
          btn.textContent = "Criar conta";
          btn.disabled = false;
        }
      } catch(ex) {
        console.error(ex);
        mostrarErro("Erro de ligação ao servidor.");
        btn.textContent = "Criar conta";
        btn.disabled = false;
      }
    }

    function mostrarErro(msg) {
      const el = document.getElementById("regError");
      el.innerHTML = '<i class="bi bi-x-circle-fill" style="margin-right:6px;"></i>' + msg;
      el.style.display = "block";
    }

    document.addEventListener("DOMContentLoaded", () => {
      // Toggle password
      document.querySelectorAll(".toggle-password").forEach(btn => {
        btn.addEventListener("click", () => {
          const inp = btn.previousElementSibling;
          inp.type = inp.type === "password" ? "text" : "password";
          btn.querySelector("i").classList.toggle("bi-eye");
          btn.querySelector("i").classList.toggle("bi-eye-slash");
        });
      });

      // Partículas
      const canvas = document.createElement("canvas");
      canvas.id = "particles";
      document.body.appendChild(canvas);
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

  <button class="fullscreen-btn" title="Tela cheia"
    onclick="toggleFullscreen()">
    <i class="bi bi-fullscreen"></i>
  </button>
  <script src="assets/js/tradutor.js"></script>
  <script src="assets/js/fullscreen.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nova Palavra-passe — VaiJogar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <script src="assets/js/tradutor.js"></script>
</head>
<body>

  <header class="header">
    <div class="header-logo">
      <a href="index.php"><img src="assets/images/logo2.png" alt="Logo" /></a>
    </div>
    <nav class="header-nav">
      <a href="index.php"><i class="bi bi-house-fill"></i> Início</a>
      <a href="sobre.php"><i class="bi bi-info-circle"></i> Sobre</a>
    </nav>
  </header>

  <div class="container">
    <div class="caixa-login">

      <div style="text-align:center;margin-bottom:20px;">
        <div style="
          width:56px;height:56px;border-radius:16px;
          background:rgba(123,44,255,0.2);border:1px solid rgba(123,44,255,0.4);
          display:inline-flex;align-items:center;justify-content:center;
          font-size:1.4rem;color:var(--purple-glow);margin-bottom:14px;
        "><i class="bi bi-shield-lock-fill"></i></div>
        <h2 style="margin:0 0 6px 0;">Nova Palavra-passe</h2>
        <p style="color:rgba(255,255,255,0.55);font-size:14px;margin:0;">
          Escolhe uma nova palavra-passe para a tua conta.
        </p>
      </div>

      <div id="msgBox" style="display:none;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-size:14px;text-align:center;"></div>

      <div id="formArea">
        <div class="grupo-input password-container">
          <i class="bi bi-lock-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="password" id="novaSenha" placeholder="Nova palavra-passe" required />
          <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
        </div>
        <div class="grupo-input password-container">
          <i class="bi bi-lock-fill" style="margin-right:8px;color:#fff;"></i>
          <input type="password" id="confirmarSenha" placeholder="Confirmar palavra-passe" required />
          <button type="button" class="toggle-password"><i class="bi bi-eye"></i></button>
        </div>

        <button id="submitBtn" class="botao-entrar" onclick="redefinirSenha()">
          <i class="bi bi-check-circle-fill"></i> Redefinir palavra-passe
        </button>
      </div>

      <p class="Criar-conta" style="margin-top:18px;">
        <a href="index.php"><i class="bi bi-arrow-left"></i> Voltar ao login</a>
      </p>
    </div>
  </div>

  <script>
    // Obter token do URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get("token");

    // Se não houver token, mostrar erro
    if (!token) {
      document.getElementById("formArea").style.display = "none";
      msgBox(t("err_invalid_link","Link inválido ou expirado. Pede uma nova recuperação."), false);
    }

    async function redefinirSenha() {
      const nova      = document.getElementById("novaSenha").value;
      const confirmar = document.getElementById("confirmarSenha").value;
      const btn       = document.getElementById("submitBtn");

      msgBox("", false, false);

      if (nova.length < 8)                  { msgBox(t("err_pass_min","A palavra-passe deve ter pelo menos 8 caracteres."), false); return; }
      if (!/[A-Z]/.test(nova))              { msgBox(t("err_pass_upper","A palavra-passe deve ter pelo menos uma letra maiúscula."), false); return; }
      if (!/[0-9]/.test(nova))              { msgBox(t("err_pass_number","A palavra-passe deve ter pelo menos um número."), false); return; }
      
      if (nova !== confirmar) { msgBox(t("err_pass_match","As palavras-passe não coincidem."), false); return; }

      btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation:spin .8s linear infinite;display:inline-block;"></i> A guardar...';
      btn.disabled = true;

      try {
        const fd = new FormData();
        fd.append("token",    token);
        fd.append("password", nova);

        fd.append("action","reset");
      const r = await fetch("PHP/auth.php", { method: "POST", body: fd });
        const d = await r.json();

        if (d.sucesso) {
          document.getElementById("formArea").style.display = "none";
          msgBox(t("msg_pass_changed","Palavra-passe alterada com sucesso! A redirecionar..."), true);
          setTimeout(() => window.location.href = "index.php", 2500);
        } else {
          msgBox(d.mensagem || t("msg_pass_error","Erro ao redefinir. O link pode ter expirado."), false);
          btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Redefinir palavra-passe';
          btn.disabled = false;
        }
      } catch(err) {
        msgBox(t("err_server","Erro de ligação ao servidor."), false);
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Redefinir palavra-passe';
        btn.disabled = false;
      }
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
    document.addEventListener("DOMContentLoaded", () => {
      const canvas = document.createElement("canvas"); canvas.id = "particles"; document.body.appendChild(canvas);
      const ctx = canvas.getContext("2d"); let w, h;
      function resize() { w = canvas.width = window.innerWidth; h = canvas.height = window.innerHeight; }
      window.addEventListener("resize", resize); resize();
      const pts = Array.from({length:50}, () => ({x:Math.random()*w,y:Math.random()*h,r:Math.random()*2+1,dx:(Math.random()-.5)*.4,dy:(Math.random()-.5)*.4}));
      function draw() {
        ctx.clearRect(0,0,w,h);
        pts.forEach(p => {
          p.x+=p.dx; p.y+=p.dy;
          if(p.x<0||p.x>w) p.dx*=-1; if(p.y<0||p.y>h) p.dy*=-1;
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
  <style>
    @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
    .botao-entrar { display:flex; align-items:center; justify-content:center; gap:8px; }
    .header-nav a { text-decoration: none !important; }
  </style>
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

</body>
</html>
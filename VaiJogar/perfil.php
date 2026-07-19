<?php
session_start();
require_once 'PHP/conexao.php';
if (empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$uid  = (int)$_SESSION['user_id'];
$nome = $_SESSION['user_nome'] ?? 'Utilizador';
$initials = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ', trim($nome)), 0, 2)));
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Perfil — VaiJogar</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#07060f;--surface:#0f0d1e;--surface2:#151228;
  --border:rgba(255,255,255,.07);--border2:rgba(255,255,255,.13);
  --accent:#6c3fff;--accent2:#9b6dff;--accent-dim:rgba(108,63,255,.14);
  --text:#ede9ff;--muted:rgba(237,233,255,.5);--muted2:rgba(237,233,255,.28);
  --green:#22c55e;--red:#ef4444;--gold:#f4a623;
  --sidebar-w:232px;
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px}

/* ── LAYOUT ── */
.app{display:grid;grid-template-columns:var(--sidebar-w) 1fr;min-height:100vh}

/* ── SIDEBAR ── */
.sidebar{background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto;z-index:100}
.sb-brand{padding:20px 16px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px;flex-shrink:0}
.sb-brand-name{font-size:14px;font-weight:800}
.sb-brand-sub{font-size:10px;color:var(--accent2);font-weight:600;letter-spacing:.1em;text-transform:uppercase;margin-top:1px}
.sb-avatar-wrap{padding:16px 10px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;flex-shrink:0}
.user-avatar{width:36px;height:36px;border-radius:9px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0}
.user-name{font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-role-badge{font-size:10px;color:var(--accent2);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-top:1px}
.sb-section{padding:14px 10px 6px}
.sb-section-lbl{font-size:9px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--muted2);padding:0 8px;margin-bottom:4px}
.nav-item{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;color:var(--muted);font-size:13px;font-weight:500;cursor:pointer;border:1px solid transparent;background:none;width:100%;text-align:left;font-family:'DM Sans',sans-serif;text-decoration:none;transition:all .15s}
.nav-item i{font-size:14px;width:17px;flex-shrink:0}
.nav-item:hover{color:var(--text);background:rgba(255,255,255,.045)}
.nav-item.active{color:var(--text);background:var(--accent-dim);border-color:rgba(108,63,255,.22)}
.nav-item.active i{color:var(--accent2)}
.nav-item.danger{color:#f87171}
.nav-item.danger:hover{background:rgba(239,68,68,.08)}
.sb-bottom{margin-top:auto;padding:12px 10px;border-top:1px solid var(--border)}

/* ── MAIN ── */
.main{display:flex;flex-direction:column;min-height:100vh}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:56px;border-bottom:1px solid var(--border);background:rgba(7,6,15,.9);position:sticky;top:0;z-index:50;gap:14px;flex-shrink:0}
.topbar-title{font-size:14px;font-weight:700;display:flex;align-items:center;gap:9px}
.topbar-title i{color:var(--accent2)}
.topbar-btn{display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid var(--border2);background:var(--surface2);color:var(--muted);text-decoration:none;font-family:'DM Sans',sans-serif;transition:all .15s}
.topbar-btn:hover{color:var(--text);background:rgba(255,255,255,.06)}

/* ── PAGE ── */
.page{padding:26px 28px;flex:1}

/* ── PANEL ── */
.panel{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:20px}
.panel-header{padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px}
.panel-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);display:flex;align-items:center;gap:7px}
.panel-title i{color:var(--accent2)}
.panel-body{padding:24px 20px}

/* ── FORM ── */
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);margin-bottom:6px}
.form-input{width:100%;padding:10px 13px;background:var(--surface2);border:1px solid var(--border2);border-radius:8px;color:var(--text);font-family:'DM Sans',sans-serif;font-size:13px;outline:none;transition:border-color .15s}
.form-input:focus{border-color:rgba(108,63,255,.5);background:rgba(108,63,255,.06)}
.form-input:disabled{opacity:.4;cursor:not-allowed}
.form-input::placeholder{color:var(--muted2)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-hint{font-size:11px;color:var(--muted2);margin-top:4px}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;transition:all .15s}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:#5930e5}
.btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--muted)}
.btn-ghost:hover{background:rgba(255,255,255,.06);color:var(--text)}

/* ── ALERT ── */
.alert{display:none;padding:10px 14px;border-radius:8px;font-size:12px;font-weight:600;margin-bottom:14px;align-items:center;gap:8px}
.alert.ok{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80;display:flex}
.alert.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;display:flex}

/* ── PASSWORD STRENGTH ── */
.pass-rules{margin-top:8px;display:grid;grid-template-columns:1fr 1fr;gap:4px}
.pass-rule{font-size:11px;color:var(--muted2);display:flex;align-items:center;gap:5px;transition:color .2s}
.pass-rule.ok{color:var(--green)}
.pass-rule i{font-size:10px}

/* ── TOAST ── */
.toast{position:fixed;bottom:24px;right:24px;padding:12px 18px;border-radius:10px;font-size:13px;font-weight:600;z-index:999;display:none;gap:8px;align-items:center;max-width:320px}
.toast.ok{background:#052e16;border:1px solid rgba(34,197,94,.3);color:#4ade80;display:flex}
.toast.err{background:#2d0a0a;border:1px solid rgba(239,68,68,.3);color:#f87171;display:flex}

@media(max-width:768px){.app{grid-template-columns:1fr}.sidebar{display:none}.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-brand">
      <div style="width:30px;height:30px;border-radius:8px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff">VJ</div>
      <div><div class="sb-brand-name">VaiJogar</div><div class="sb-brand-sub">Perfil</div></div>
    </div>

    <div class="sb-avatar-wrap">
      <div class="user-avatar" id="sbAvatar"><?php echo htmlspecialchars($initials ?: 'VJ'); ?></div>
      <div style="min-width:0">
        <div class="user-name" id="sbNome"><?php echo htmlspecialchars($nome); ?></div>
        <div class="user-role-badge" id="sbRole">Utilizador</div>
      </div>
    </div>

    <div class="sb-section">
      <div class="sb-section-lbl">Conta</div>
      <button class="nav-item active" data-tab="info"><i class="bi bi-person"></i> Informações</button>
      <button class="nav-item" data-tab="email"><i class="bi bi-envelope"></i> Email</button>
      <button class="nav-item" data-tab="password"><i class="bi bi-lock"></i> Password</button>
      <a href="marcacoes.php" class="nav-item"><i class="bi bi-calendar-check"></i> Marcações</a>
    </div>

    <div class="sb-section">
      <div class="sb-section-lbl">Navegação</div>
      <a href="escolha.php" class="nav-item"><i class="bi bi-grid"></i> Modalidades</a>
      <a href="mapa.php" class="nav-item"><i class="bi bi-map"></i> Mapa</a>
    </div>

    <div class="sb-bottom">
      <button class="nav-item danger" onclick="logout()"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">
    <div class="topbar">
      <div class="topbar-title" id="pageTitle"><i class="bi bi-person"></i> Meu Perfil</div>
      <div style="display:flex;gap:8px">
        <a href="escolha.php" class="topbar-btn"><i class="bi bi-arrow-left"></i> Voltar ao Site</a>
      </div>
    </div>

    <div class="page">

      <!-- INFO TAB -->
      <div id="tabInfo">
        <div class="panel">
          <div class="panel-header">
            <div class="panel-title"><i class="bi bi-info-circle"></i> Informações Pessoais</div>
          </div>
          <div class="panel-body">
            <div id="alertInfo" class="alert"></div>
            <form id="formInfo">
              <div class="form-group">
                <label class="form-label">Nome Completo</label>
                <input type="text" id="infoName" class="form-input" placeholder="Teu nome" required>
              </div>
              <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="infoEmail" class="form-input" disabled>
                <div class="form-hint">Para alterar o email usa a secção "Email".</div>
              </div>
              <div class="form-group">
                <label class="form-label">Telefone</label>
                <input type="tel" id="infoTelefone" class="form-input" placeholder="+351 912 345 678">
              </div>
              <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill"></i> Guardar</button>
            </form>
          </div>
        </div>
      </div>

      <!-- EMAIL TAB -->
      <div id="tabEmail" style="display:none">
        <div class="panel">
          <div class="panel-header">
            <div class="panel-title"><i class="bi bi-envelope"></i> Alterar Email</div>
          </div>
          <div class="panel-body">
            <div id="alertEmail" class="alert"></div>
            <form id="formEmail">
              <div class="form-group">
                <label class="form-label">Novo Email</label>
                <input type="email" id="emailNew" class="form-input" placeholder="novo@exemplo.com" required>
              </div>
              <div class="form-group">
                <label class="form-label">Password Atual (confirmação)</label>
                <input type="password" id="emailPass" class="form-input" placeholder="••••••••" required>
              </div>
              <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill"></i> Alterar Email</button>
            </form>
          </div>
        </div>
      </div>

      <!-- PASSWORD TAB -->
      <div id="tabPassword" style="display:none">
        <div class="panel">
          <div class="panel-header">
            <div class="panel-title"><i class="bi bi-lock"></i> Alterar Password</div>
          </div>
          <div class="panel-body">
            <div id="alertPass" class="alert"></div>
            <form id="formPassword">
              <div class="form-group">
                <label class="form-label">Password Atual</label>
                <input type="password" id="passOld" class="form-input" placeholder="••••••••" required>
              </div>
              <div class="form-group">
                <label class="form-label">Nova Password</label>
                <input type="password" id="passNew" class="form-input" placeholder="••••••••" required>
                <div class="pass-rules">
                  <div class="pass-rule" id="r1"><i class="bi bi-circle"></i> 8+ caracteres</div>
                  <div class="pass-rule" id="r2"><i class="bi bi-circle"></i> Maiúscula</div>
                  <div class="pass-rule" id="r3"><i class="bi bi-circle"></i> Número</div>
                  <div class="pass-rule" id="r4"><i class="bi bi-circle"></i> Carácter especial</div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Confirmar Nova Password</label>
                <input type="password" id="passConfirm" class="form-input" placeholder="••••••••" required>
              </div>
              <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill"></i> Alterar Password</button>
            </form>
          </div>
        </div>
      </div>

    </div><!-- /page -->
  </div><!-- /main -->
</div><!-- /app -->

<div class="toast" id="toast"></div>

<script src="assets/js/tradutor.js"></script>
<script>
const user_id = <?php echo $uid ?: '0'; ?>;
if (!user_id) location.href = 'index.php';

// ── Tab switching ─────────────────────────────────────────────
const TABS = {
  info:     { icon:'person',   title:'Meu Perfil' },
  email:    { icon:'envelope', title:'Alterar Email' },
  password: { icon:'lock',     title:'Alterar Password' },
};

document.querySelectorAll('.nav-item[data-tab]').forEach(btn => {
  btn.addEventListener('click', () => {
    const tab = btn.dataset.tab;
    document.querySelectorAll('.nav-item[data-tab]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    Object.keys(TABS).forEach(k => {
      document.getElementById('tab' + k.charAt(0).toUpperCase() + k.slice(1)).style.display = k === tab ? 'block' : 'none';
    });
    const cfg = TABS[tab];
    document.getElementById('pageTitle').innerHTML = `<i class="bi bi-${cfg.icon}"></i> ${cfg.title}`;
  });
});

// ── Check tab from URL ────────────────────────────────────────
const urlTab = new URLSearchParams(location.search).get('tab');
if (urlTab && TABS[urlTab]) {
  document.querySelector(`[data-tab="${urlTab}"]`)?.click();
}

// ── Carregar Perfil ───────────────────────────────────────────
async function carregarPerfil() {
  try {
    const r = await fetch('PHP/auth.php?action=profile');
    const d = await r.json();
    if (!d.sucesso && !d.ok) return;
    const u = d.utilizador || d.user;
    if (!u) return;
    document.getElementById('sbNome').textContent  = u.nome  || '—';
    document.getElementById('sbRole').textContent  = u.role === 'admin' ? 'Admin' : 'Utilizador';
    const letters = (u.nome || 'VJ').split(' ').map(n => n[0]).join('').toUpperCase().slice(0,2);
    document.getElementById('sbAvatar').textContent = letters;
    const n = document.getElementById('infoName');
    const e = document.getElementById('infoEmail');
    const t = document.getElementById('infoTelefone');
    if (n) n.value = u.nome    || '';
    if (e) e.value = u.email   || '';
    if (t) t.value = u.telefone || '';
  } catch(e) { console.error(e); }
}

// ── Alert helper ──────────────────────────────────────────────
function showAlert(id, msg, type) {
  const el = document.getElementById(id);
  if (!el) return;
  el.className = 'alert ' + type;
  el.innerHTML = `<i class="bi bi-${type==='ok'?'check-circle':'exclamation-circle'}"></i> ${msg}`;
  clearTimeout(el._t);
  el._t = setTimeout(() => el.style.display='none', 4000);
}

function showToast(msg, type) {
  const el = document.getElementById('toast');
  el.className = 'toast ' + type;
  el.innerHTML = `<i class="bi bi-${type==='ok'?'check-circle':'exclamation-circle'}"></i> ${msg}`;
  clearTimeout(el._t);
  el._t = setTimeout(() => el.className='toast', 3500);
}

// ── Form: Informações ─────────────────────────────────────────
document.getElementById('formInfo').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData();
  fd.append('nome',     document.getElementById('infoName').value);
  fd.append('telefone', document.getElementById('infoTelefone').value);
  const r = await fetch('PHP/auth.php?action=update_profile', { method:'POST', body:fd });
  const d = await r.json();
  if (d.sucesso || d.ok) { showAlert('alertInfo','Perfil atualizado com sucesso!','ok'); carregarPerfil(); }
  else showAlert('alertInfo', d.mensagem || d.erro || 'Erro ao guardar', 'err');
});

// ── Form: Email ───────────────────────────────────────────────
document.getElementById('formEmail').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData();
  fd.append('email',    document.getElementById('emailNew').value);
  fd.append('password', document.getElementById('emailPass').value);
  const r = await fetch('PHP/auth.php?action=change_email', { method:'POST', body:fd });
  const d = await r.json();
  if (d.sucesso || d.ok) { showAlert('alertEmail','Email alterado com sucesso!','ok'); carregarPerfil(); document.getElementById('formEmail').reset(); }
  else showAlert('alertEmail', d.mensagem || d.erro || 'Erro ao alterar email', 'err');
});

// ── Form: Password com validação visual ───────────────────────
document.getElementById('passNew').addEventListener('input', function() {
  const v = this.value;
  const rules = [
    { id:'r1', ok: v.length >= 8 },
    { id:'r2', ok: /[A-Z]/.test(v) },
    { id:'r3', ok: /[0-9]/.test(v) },
    { id:'r4', ok: /[!@#$%^&*()\-_=+\[\]{};:'",.<>?\/\\|]/.test(v) },
  ];
  rules.forEach(r => {
    const el = document.getElementById(r.id);
    el.className = 'pass-rule' + (r.ok ? ' ok' : '');
    el.querySelector('i').className = r.ok ? 'bi bi-check-circle-fill' : 'bi bi-circle';
  });
});

document.getElementById('formPassword').addEventListener('submit', async e => {
  e.preventDefault();
  const nova    = document.getElementById('passNew').value;
  const confirma= document.getElementById('passConfirm').value;
  if (nova !== confirma) { showAlert('alertPass','As passwords não coincidem.','err'); return; }
  const fd = new FormData();
  fd.append('password_atual', document.getElementById('passOld').value);
  fd.append('password_nova',  nova);
  fd.append('confirmar',      confirma);
  const r = await fetch('PHP/auth.php?action=change_password', { method:'POST', body:fd });
  const d = await r.json();
  if (d.sucesso || d.ok) { showAlert('alertPass','Password alterada com sucesso!','ok'); document.getElementById('formPassword').reset(); }
  else showAlert('alertPass', d.mensagem || d.erro || 'Erro ao alterar password', 'err');
});

// ── Logout ────────────────────────────────────────────────────
function logout() {
  fetch('PHP/auth.php?action=logout').then(() => location.href = 'index.php');
}

carregarPerfil();
</script>
</body>
</html>
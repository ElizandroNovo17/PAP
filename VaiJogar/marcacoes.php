<?php
session_start();
require_once 'PHP/conexao.php';
require_once 'PHP/csrf.php';
if (empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$uid  = (int)$_SESSION['user_id'];
$nome = $_SESSION['user_nome'] ?? 'Utilizador';
$initials = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ', $nome), 0, 2)));
$csrf_token = csrf_gerar();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Minhas Marcações — VaiJogar</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#07060f;--surface:#0f0d1e;--surface2:#151228;
  --border:rgba(255,255,255,.07);--border2:rgba(255,255,255,.13);
  --accent:#6c3fff;--accent2:#9b6dff;--accent-dim:rgba(108,63,255,.14);
  --gold:#f4a623;--green:#22c55e;--red:#ef4444;--blue:#3b82f6;--orange:#f97316;
  --text:#ede9ff;--muted:rgba(237,233,255,.5);--muted2:rgba(237,233,255,.28);
  --sidebar-w:232px;
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;line-height:1.5}

/* ── LAYOUT ── */
.app{display:grid;grid-template-columns:var(--sidebar-w) 1fr;min-height:100vh}

/* ── SIDEBAR ── */
.sidebar{background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto;z-index:100}
.sb-brand{padding:20px 16px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px}
.sb-brand-name{font-size:14px;font-weight:800;letter-spacing:-.01em}
.sb-brand-sub{font-size:10px;color:var(--accent2);font-weight:600;letter-spacing:.1em;text-transform:uppercase;margin-top:1px}
.sb-section{padding:18px 10px 6px}
.sb-section-lbl{font-size:9px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--muted2);padding:0 8px;margin-bottom:4px}
.nav-item{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;color:var(--muted);font-size:13px;font-weight:500;cursor:pointer;border:1px solid transparent;background:none;width:100%;text-align:left;font-family:'DM Sans',sans-serif;text-decoration:none;transition:all .15s}
.nav-item i{font-size:14px;width:17px;flex-shrink:0}
.nav-item:hover{color:var(--text);background:rgba(255,255,255,.045)}
.nav-item.active{color:var(--text);background:var(--accent-dim);border-color:rgba(108,63,255,.22)}
.nav-item.active i{color:var(--accent2)}
.nav-item.danger{color:#f87171}
.nav-item.danger:hover{background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.2)}
.sb-bottom{margin-top:auto;padding:14px 10px;border-top:1px solid var(--border)}
.user-profile{display:flex;align-items:center;gap:10px;padding:8px 8px 12px}
.user-avatar{width:32px;height:32px;border-radius:8px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0}
.user-name{font-size:12px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-role{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em}

/* ── MAIN ── */
.main{display:flex;flex-direction:column;min-height:100vh;overflow:hidden}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:56px;border-bottom:1px solid var(--border);background:rgba(7,6,15,.9);position:sticky;top:0;z-index:50;gap:14px;flex-shrink:0}
.topbar-title{font-size:14px;font-weight:700;display:flex;align-items:center;gap:9px}
.topbar-title i{color:var(--accent2)}
.topbar-right{display:flex;align-items:center;gap:8px}
.topbar-btn{display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid var(--border2);background:var(--surface2);color:var(--muted);text-decoration:none;font-family:'DM Sans',sans-serif;transition:all .15s}
.topbar-btn:hover{color:var(--text);background:rgba(255,255,255,.06)}

/* ── PAGE ── */
.page{padding:26px 28px;flex:1}
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:14px;flex-wrap:wrap}
.page-title{font-size:20px;font-weight:800;letter-spacing:-.3px}
.page-subtitle{font-size:12px;color:var(--muted);margin-top:3px}

/* ── STAT GRID ── */
.stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:24px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:16px 18px}
.stat-card-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.stat-card-lbl{font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.07em}
.stat-card-icon{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:14px}
.stat-card-num{font-size:26px;font-weight:800;letter-spacing:-1px;line-height:1}
.stat-card-sub{font-size:10px;color:var(--muted2);margin-top:4px}
.icon-purple{background:rgba(108,63,255,.18);color:var(--accent2)}
.icon-gold{background:rgba(244,166,35,.18);color:var(--gold)}
.icon-green{background:rgba(34,197,94,.18);color:#4ade80}
.icon-red{background:rgba(239,68,68,.18);color:#f87171}

/* ── PANEL ── */
.panel{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:20px}
.panel-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border);gap:12px;flex-wrap:wrap}
.panel-title{font-size:12px;font-weight:700;display:flex;align-items:center;gap:7px;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.panel-title i{color:var(--accent2)}
.panel-actions{display:flex;gap:8px;align-items:center}

/* ── FILTER BAR ── */
.filter-bar{display:flex;align-items:center;gap:10px;padding:12px 18px;border-bottom:1px solid var(--border);flex-wrap:wrap}
.filter-select{background:var(--surface2);border:1px solid var(--border2);border-radius:7px;color:var(--text);font-size:12px;padding:6px 10px;font-family:'DM Sans',sans-serif;cursor:pointer;outline:none}
.filter-select:focus{border-color:rgba(108,63,255,.45)}

/* ── TABLE ── */
.tbl-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead th{padding:9px 16px;text-align:left;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted2);border-bottom:1px solid var(--border);white-space:nowrap}
tbody tr{transition:background .1s}
tbody tr:hover{background:rgba(255,255,255,.025)}
tbody td{padding:12px 16px;font-size:13px;border-bottom:1px solid var(--border);vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
.tbl-empty{text-align:center;padding:48px;color:var(--muted2)}
.tbl-empty i{font-size:2rem;display:block;margin-bottom:10px;opacity:.25}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;padding:3px 8px;border-radius:5px;letter-spacing:.03em;white-space:nowrap}
.badge-confirmado{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.25)}
.badge-pendente{background:rgba(244,166,35,.12);color:var(--gold);border:1px solid rgba(244,166,35,.25)}
.badge-cancelado{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2)}
.badge-reembolsado{background:rgba(100,116,139,.1);color:#94a3b8;border:1px solid rgba(100,116,139,.2)}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;transition:all .15s;text-decoration:none}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:#5930e5;color:#fff}
.btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--muted)}
.btn-ghost:hover{background:rgba(255,255,255,.06);color:var(--text)}
.btn-danger{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}
.btn-danger:hover{background:rgba(239,68,68,.2)}
.btn-pdf{background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.25);color:#60a5fa}
.btn-pdf:hover{background:rgba(59,130,246,.2);color:#93c5fd}

/* ── DETAIL CELL ── */
.detail-cell{display:flex;flex-direction:column;gap:2px}
.detail-main{font-size:13px;font-weight:600}
.detail-sub{font-size:11px;color:var(--muted)}

/* ── TOAST ── */
.toast{position:fixed;bottom:24px;right:24px;padding:12px 18px;border-radius:10px;font-size:13px;font-weight:600;z-index:999;display:none;gap:8px;align-items:center}
.toast.ok{background:#052e16;border:1px solid rgba(34,197,94,.3);color:#4ade80;display:flex}
.toast.err{background:#2d0a0a;border:1px solid rgba(239,68,68,.3);color:#f87171;display:flex}

/* ── SKELETON ── */
.skel{animation:skelAnim 1.2s infinite;background:linear-gradient(90deg,var(--surface2) 25%,rgba(255,255,255,.04) 50%,var(--surface2) 75%);background-size:400% 100%;border-radius:6px;height:16px}
@keyframes skelAnim{0%{background-position:100% 50%}100%{background-position:0 50%}}

/* ── MODAL ── */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);display:none;align-items:center;justify-content:center;z-index:200}
.modal-overlay.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:14px;width:420px;max-width:90vw;padding:24px}
.modal-title{font-size:15px;font-weight:700;margin-bottom:12px;display:flex;align-items:center;gap:8px}
.modal-title i{color:var(--red)}
.modal-body{font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:20px}
.modal-actions{display:flex;gap:10px;justify-content:flex-end}
textarea.form-input{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:7px;color:var(--text);font-size:12px;padding:10px;font-family:'DM Sans',sans-serif;resize:vertical;min-height:80px;outline:none;margin-top:10px}
textarea.form-input:focus{border-color:rgba(108,63,255,.45)}

@media(max-width:768px){.app{grid-template-columns:1fr}.sidebar{display:none}}
</style>
</head>
<body>
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-brand">
      <div style="width:30px;height:30px;border-radius:8px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800">VJ</div>
      <div><div class="sb-brand-name">VaiJogar</div><div class="sb-brand-sub">Perfil</div></div>
    </div>

    <div class="sb-section">
      <div class="sb-section-lbl">Conta</div>
      <a href="perfil.php" class="nav-item"><i class="bi bi-person"></i> Informações</a>
      <a href="perfil.php?tab=email" class="nav-item"><i class="bi bi-envelope"></i> Email</a>
      <a href="perfil.php?tab=password" class="nav-item"><i class="bi bi-lock"></i> Password</a>
      <a href="marcacoes.php" class="nav-item active"><i class="bi bi-calendar-check"></i> Marcações</a>
    </div>

    <div class="sb-section">
      <div class="sb-section-lbl">Navegação</div>
      <a href="escolha.php" class="nav-item"><i class="bi bi-grid"></i> Modalidades</a>
      <a href="mapa.php" class="nav-item"><i class="bi bi-map"></i> Mapa</a>
    </div>

    <div class="sb-bottom">
      <div class="user-profile">
        <div class="user-avatar" id="sbAvatar"><?php echo htmlspecialchars($initials); ?></div>
        <div>
          <div class="user-name" id="sbNome"><?php echo htmlspecialchars($nome); ?></div>
          <div class="user-role">Utilizador</div>
        </div>
      </div>
      <button class="nav-item danger" onclick="logout()"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-title"><i class="bi bi-calendar-check"></i> Minhas Marcações</div>
      <div class="topbar-right">
        <a href="escolha.php" class="topbar-btn"><i class="bi bi-arrow-left"></i> Voltar ao Site</a>
      </div>
    </div>

    <!-- PAGE -->
    <div class="page">

      <!-- STATS -->
      <div class="stat-grid" id="statsGrid">
        <div class="stat-card"><div class="stat-card-top"><span class="stat-card-lbl">Total</span><span class="stat-card-icon icon-purple"><i class="bi bi-calendar-check"></i></span></div><div class="stat-card-num" id="statTotal">—</div><div class="stat-card-sub">marcações</div></div>
        <div class="stat-card"><div class="stat-card-top"><span class="stat-card-lbl">Ativas</span><span class="stat-card-icon icon-green"><i class="bi bi-check-circle"></i></span></div><div class="stat-card-num" id="statAtivas">—</div><div class="stat-card-sub">confirmadas</div></div>
        <div class="stat-card"><div class="stat-card-top"><span class="stat-card-lbl">Canceladas</span><span class="stat-card-icon icon-red"><i class="bi bi-x-circle"></i></span></div><div class="stat-card-num" id="statCanc">—</div><div class="stat-card-sub">canceladas</div></div>
        <div class="stat-card"><div class="stat-card-top"><span class="stat-card-lbl">Gasto</span><span class="stat-card-icon icon-gold"><i class="bi bi-currency-euro"></i></span></div><div class="stat-card-num" id="statGasto">—</div><div class="stat-card-sub">€ total/mês</div></div>
      </div>

      <!-- TABELA -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title"><i class="bi bi-list-ul"></i> Histórico de Marcações</div>
          <div class="panel-actions">
            <select class="filter-select" id="filterStatus" onchange="filtrar()">
              <option value="">Todos os estados</option>
              <option value="confirmado">Confirmado</option>
              <option value="pendente">Pendente</option>
              <option value="cancelado">Cancelado</option>
              <option value="reembolsado">Reembolsado</option>
            </select>
          </div>
        </div>
        <div class="tbl-wrap">
          <table>
            <thead>
              <tr>
                <th>Clube</th>
                <th>Plano / Escalão</th>
                <th>Dias & Horário</th>
                <th>Valor</th>
                <th>Estado</th>
                <th>Data</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody id="tblBody">
              <tr><td colspan="7" class="tbl-empty"><i class="bi bi-hourglass-split"></i>A carregar...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

    </div><!-- /page -->
  </div><!-- /main -->
</div><!-- /app -->

<!-- MODAL CANCELAR -->
<div class="modal-overlay" id="modalCanc">
  <div class="modal">
    <div class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Cancelar Marcação</div>
    <div class="modal-body">
      Tens a certeza que queres cancelar esta marcação? Esta ação não pode ser revertida.
      <textarea class="form-input" id="cancelMotivo" placeholder="Motivo do cancelamento (opcional)..."></textarea>
    </div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="fecharModal()">Voltar</button>
      <button class="btn btn-danger" onclick="confirmarCancelamento()"><i class="bi bi-x-circle"></i> Cancelar Marcação</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
const CSRF_TOKEN = "<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>";
let _marcacoes = [];
let _cancelId  = null;

// ── Carregar ─────────────────────────────────────────────────
async function carregar() {
  try {
    const r = await fetch('PHP/pagamentos.php?action=listar');
    const d = await r.json();
    _marcacoes = d.marcacoes || [];
    actualizarStats();
    renderTabela(_marcacoes);
  } catch(e) {
    document.getElementById('tblBody').innerHTML =
      '<tr><td colspan="7" class="tbl-empty"><i class="bi bi-exclamation-circle"></i>Erro ao carregar</td></tr>';
  }
}

function actualizarStats() {
  const total  = _marcacoes.length;
  const ativas = _marcacoes.filter(m => m.status === 'confirmado').length;
  const canc   = _marcacoes.filter(m => m.status === 'cancelado').length;
  const gasto  = _marcacoes.filter(m => m.status === 'confirmado')
                            .reduce((s, m) => s + parseFloat(m.preco || 0), 0);
  document.getElementById('statTotal').textContent  = total;
  document.getElementById('statAtivas').textContent = ativas;
  document.getElementById('statCanc').textContent   = canc;
  document.getElementById('statGasto').textContent  = '€' + gasto.toFixed(2);
}

function filtrar() {
  const f = document.getElementById('filterStatus').value;
  renderTabela(f ? _marcacoes.filter(m => m.status === f) : _marcacoes);
}

function renderTabela(lista) {
  const tb = document.getElementById('tblBody');
  if (!lista.length) {
    tb.innerHTML = '<tr><td colspan="7" class="tbl-empty"><i class="bi bi-inbox"></i>Nenhuma marcação encontrada</td></tr>';
    return;
  }

  tb.innerHTML = lista.map(m => {
    const badgeClass = {
      confirmado: 'badge-confirmado', pendente: 'badge-pendente',
      cancelado: 'badge-cancelado', reembolsado: 'badge-reembolsado'
    }[m.status] || 'badge-pendente';

    const statusLabel = { confirmado: '✓ Confirmado', pendente: '⏳ Pendente',
                          cancelado: '✗ Cancelado', reembolsado: '↩ Reembolsado' }[m.status] || m.status;

    const podeCanc = m.status === 'confirmado' || m.status === 'pendente';

    return `<tr>
      <td>
        <div class="detail-cell">
          <span class="detail-main">${esc(m.clube_nome)}</span>
          <span class="detail-sub" style="font-family:'DM Mono',monospace;font-size:10px;color:var(--accent2)">${esc(m.referencia)}</span>
        </div>
      </td>
      <td>
        <div class="detail-cell">
          <span class="detail-main">${esc(m.plano || '—')}</span>
          <span class="detail-sub">${esc(m.escalao || '—')}</span>
        </div>
      </td>
      <td>
        <div class="detail-cell">
          <span class="detail-main">${esc(m.dias || '—')}</span>
          <span class="detail-sub"><i class="bi bi-clock" style="font-size:10px"></i> ${esc(m.horario || '—')}</span>
        </div>
      </td>
      <td style="font-weight:700;color:var(--gold)">€${parseFloat(m.preco||0).toFixed(2)}<span style="font-size:10px;color:var(--muted);font-weight:400">/mês</span></td>
      <td><span class="badge ${badgeClass}">${statusLabel}</span></td>
      <td>
        <div class="detail-cell">
          <span class="detail-main">${esc(m.data_formatada || '—')}</span>
        </div>
      </td>
      <td>
        <div style="display:flex;gap:6px;align-items:center">
          <a href="gerar_pdf_marcacao.php?id=${m.id}" target="_blank" class="btn btn-pdf btn-sm" title="Ver PDF">
            <i class="bi bi-file-earmark-pdf"></i> PDF
          </a>
          ${podeCanc ? `<button class="btn btn-danger" style="padding:5px 10px;font-size:11px" onclick="abrirModal(${m.id})" title="Cancelar">
            <i class="bi bi-x"></i>
          </button>` : ''}
        </div>
      </td>
    </tr>`;
  }).join('');
}

function esc(s) {
  if (!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ── Modal cancelar ────────────────────────────────────────────
function abrirModal(id) {
  _cancelId = id;
  document.getElementById('cancelMotivo').value = '';
  document.getElementById('modalCanc').classList.add('open');
}
function fecharModal() {
  document.getElementById('modalCanc').classList.remove('open');
  _cancelId = null;
}

async function confirmarCancelamento() {
  if (!_cancelId) return;
  const motivo = document.getElementById('cancelMotivo').value;
  try {
    const r = await fetch('PHP/pagamentos.php?action=cancelar', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF_TOKEN },
      body: JSON.stringify({ marcacao_id: _cancelId, motivo, csrf_token: CSRF_TOKEN })
    });
    const d = await r.json();
    fecharModal();
    if (d.ok || d.sucesso) { showToast('Marcação cancelada.', 'ok'); carregar(); }
    else showToast(d.erro || d.mensagem || 'Erro ao cancelar', 'err');
  } catch(e) { showToast('Erro de ligação', 'err'); }
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg, type) {
  const el = document.getElementById('toast');
  el.className = 'toast ' + type;
  el.innerHTML = `<i class="bi bi-${type==='ok'?'check-circle':'exclamation-circle'}"></i> ${msg}`;
  clearTimeout(el._t);
  el._t = setTimeout(() => el.className = 'toast', 3500);
}

function logout() {
  fetch('PHP/auth.php?action=logout').then(() => location.href = 'index.php');
}

carregar();
</script>
</body>
</html>
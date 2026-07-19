<?php
session_start();
require_once 'PHP/conexao.php';

if (empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$clube   = trim($_GET['clube']   ?? 'Clube');
$plano   = trim($_GET['plano']   ?? 'Plano');
$escalao = trim($_GET['escalao'] ?? '');
$dias    = trim($_GET['dias']    ?? '');
$hora         = trim($_GET['hora']         ?? '');
$preco        = (float)($_GET['preco']        ?? 0);
$horario_ids  = trim($_GET['horario_ids']     ?? '');
$escalao_id_get = (int)($_GET['escalao_id']  ?? 0);

// ── Resolver IDs ──────────────────────────────────────────────
$club_id = 0; $plano_id = null; $esc_id = null;

$sc = $conn->prepare("SELECT id FROM clubes WHERE nome=? LIMIT 1");
$sc->bind_param('s', $clube); $sc->execute();
$club_id = ($sc->get_result()->fetch_assoc())['id'] ?? 0;

if ($plano) {
    $sp = $conn->prepare("SELECT id FROM planos WHERE nome=? AND ativo=1 LIMIT 1");
    $sp->bind_param('s', $plano); $sp->execute();
    $plano_id = ($sp->get_result()->fetch_assoc())['id'] ?? null;
}
if ($escalao && $club_id) {
    $se = $conn->prepare("SELECT id FROM escaloes WHERE nome=? AND club_id=? LIMIT 1");
    $se->bind_param('si', $escalao, $club_id); $se->execute();
    $esc_id = ($se->get_result()->fetch_assoc())['id'] ?? null;
}
// fallback ao ID direto se vier no GET
if (!$esc_id && $escalao_id_get) $esc_id = $escalao_id_get;

// ── Gerar referência e gravar ─────────────────────────────────
$ref = 'VJ' . strtoupper(substr(md5(uniqid($uid, true)), 0, 10));
$marc_id = 0;
if ($club_id) {
    $sm = $conn->prepare("
        INSERT INTO marcacoes
            (user_id,club_id,plano_id,escalao_id,referencia,
             metodo,preco,dias,horario,status,criado_em)
        VALUES (?,?,?,?,?,'mbway',?,?,?,'confirmado',NOW())
    ");
    $sm->bind_param('iiissdss', $uid, $club_id, $plano_id, $esc_id, $ref, $preco, $dias, $hora);
    if ($sm->execute()) {
        $marc_id = $conn->insert_id;

        // ── Decrementar vagas do escalão ─────────────────────
        if ($esc_id) {
            $conn->query("UPDATE escaloes
                          SET vagas_ocupadas = vagas_ocupadas + 1
                          WHERE id = $esc_id AND vagas_ocupadas < vagas_totais");
        }

        // ── Decrementar vagas de CADA horário selecionado ────
        // horario_ids = "12,15,18" (um por dia selecionado)
        if ($horario_ids) {
            $ids = array_filter(array_map('intval', explode(',', $horario_ids)));
            foreach ($ids as $hid) {
                if ($hid > 0) {
                    $conn->query("UPDATE horarios
                                  SET vagas_disponiveis = vagas_disponiveis - 1
                                  WHERE id = $hid AND vagas_disponiveis > 0");
                }
            }
        }
    }
}

$nome_user = $_SESSION['user_nome'] ?? 'Utilizador';
$initials  = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ', trim($nome_user)), 0, 2)));
$hora_emissao = date('d/m/Y \à\s H:i');
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Marcação Confirmada — VaiJogar</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#07060f;--surface:#0f0d1e;--surface2:#151228;
  --border:rgba(255,255,255,.07);--border2:rgba(255,255,255,.13);
  --accent:#6c3fff;--accent2:#9b6dff;--accent-dim:rgba(108,63,255,.14);
  --text:#ede9ff;--muted:rgba(237,233,255,.5);--muted2:rgba(237,233,255,.28);
  --green:#22c55e;--gold:#f4a623;
  --sidebar-w:232px;
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px}

.app{display:grid;grid-template-columns:var(--sidebar-w) 1fr;min-height:100vh}

/* ── SIDEBAR ── */
.sidebar{background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto;z-index:100}
.sb-brand{padding:20px 16px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px;flex-shrink:0}
.sb-brand-name{font-size:14px;font-weight:800}
.sb-brand-sub{font-size:10px;color:var(--accent2);font-weight:600;letter-spacing:.1em;text-transform:uppercase;margin-top:1px}
.sb-avatar-wrap{padding:16px 10px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;flex-shrink:0}
.user-avatar{width:36px;height:36px;border-radius:9px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0}
.user-name{font-size:13px;font-weight:700}
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
.topbar{display:flex;align-items:center;justify-content:space-between;padding:0 28px;height:56px;border-bottom:1px solid var(--border);background:rgba(7,6,15,.9);position:sticky;top:0;z-index:50;gap:14px;flex-shrink:0}
.topbar-title{font-size:14px;font-weight:700;display:flex;align-items:center;gap:9px}
.topbar-title i{color:var(--accent2)}
.topbar-btn{display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid var(--border2);background:var(--surface2);color:var(--muted);text-decoration:none;font-family:'DM Sans',sans-serif;transition:all .15s}
.topbar-btn:hover{color:var(--text);background:rgba(255,255,255,.06)}

/* ── PAGE ── */
.page{padding:26px 28px;flex:1;max-width:860px;margin:0 auto;width:100%}

/* ── SUCCESS BANNER ── */
.success-banner{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:12px;padding:24px 28px;display:flex;align-items:center;gap:20px;margin-bottom:24px}
.success-icon{width:52px;height:52px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.success-icon i{font-size:24px;color:#fff}
.success-title{font-size:18px;font-weight:800;color:#4ade80;margin-bottom:3px}
.success-sub{font-size:13px;color:var(--muted)}

/* ── PANEL ── */
.panel{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:16px}
.panel-header{padding:13px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.panel-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);display:flex;align-items:center;gap:7px}
.panel-title i{color:var(--accent2);font-size:13px}
.panel-body{padding:0}

/* ── INFO ROWS ── */
.info-row{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);gap:16px}
.info-row:last-child{border-bottom:none}
.info-key{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);font-weight:500;flex-shrink:0}
.info-key i{font-size:13px;color:var(--muted2)}
.info-val{font-size:13px;font-weight:600;text-align:right;word-break:break-word}
.info-val.gold{color:var(--gold);font-size:16px;font-weight:800}
.info-val.green{color:#4ade80}

/* ── REFERÊNCIA ── */
.ref-panel{background:linear-gradient(135deg,rgba(108,63,255,.12),rgba(155,109,255,.06));border:1px solid rgba(108,63,255,.25);border-radius:10px;padding:24px;text-align:center;margin-bottom:16px}
.ref-label{font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--muted2);margin-bottom:10px}
.ref-code{font-family:'DM Mono',monospace;font-size:24px;font-weight:700;color:var(--accent2);letter-spacing:.1em;word-break:break-all}
.ref-date{font-size:11px;color:var(--muted2);margin-top:8px}

/* ── STEPS ── */
.steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px}
.step{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:14px 16px;display:flex;align-items:flex-start;gap:10px}
.step-num{width:24px;height:24px;border-radius:6px;background:var(--accent-dim);border:1px solid rgba(108,63,255,.3);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--accent2);flex-shrink:0}
.step-text{font-size:12px;color:var(--muted);line-height:1.5}

/* ── ACTIONS ── */
.actions{display:flex;gap:12px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;transition:all .15s;text-decoration:none}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:#5930e5;color:#fff}
.btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--muted)}
.btn-ghost:hover{background:rgba(255,255,255,.06);color:var(--text)}
.btn-pdf{background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.25);color:#60a5fa}
.btn-pdf:hover{background:rgba(59,130,246,.2)}

@media(max-width:768px){.app{grid-template-columns:1fr}.sidebar{display:none}.page{padding:20px 16px}}
</style>
</head>
<body>
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-brand">
      <div style="width:30px;height:30px;border-radius:8px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff">VJ</div>
      <div><div class="sb-brand-name">VaiJogar</div><div class="sb-brand-sub">Marcação</div></div>
    </div>

    <div class="sb-avatar-wrap">
      <div class="user-avatar"><?php echo htmlspecialchars($initials ?: 'VJ'); ?></div>
      <div>
        <div class="user-name"><?php echo htmlspecialchars($nome_user); ?></div>
        <div class="user-role-badge">Utilizador</div>
      </div>
    </div>

    <div class="sb-section">
      <div class="sb-section-lbl">Navegação</div>
      <a href="escolha.php" class="nav-item"><i class="bi bi-grid"></i> Modalidades</a>
      <a href="mapa.php" class="nav-item"><i class="bi bi-map"></i> Mapa</a>
      <a href="marcacoes.php" class="nav-item"><i class="bi bi-calendar-check"></i> Marcações</a>
      <a href="perfil.php" class="nav-item"><i class="bi bi-person"></i> Perfil</a>
    </div>

    <div class="sb-bottom">
      <button class="nav-item danger" onclick="fetch('PHP/auth.php?action=logout').then(()=>location.href='index.php')">
        <i class="bi bi-box-arrow-right"></i> Logout
      </button>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">
    <div class="topbar">
      <div class="topbar-title"><i class="bi bi-check-circle"></i> Marcação Confirmada</div>
      <div style="display:flex;gap:8px">
        <a href="mapa.php" class="topbar-btn"><i class="bi bi-map"></i> Voltar ao Mapa</a>
      </div>
    </div>

    <div class="page">

      <!-- BANNER ── -->
      <div class="success-banner">
        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
        <div>
          <div class="success-title">Marcação Confirmada!</div>
          <div class="success-sub">O teu treino foi agendado com sucesso. Guarda a referência abaixo.</div>
        </div>
      </div>

      <!-- REFERÊNCIA ── -->
      <div class="ref-panel">
        <div class="ref-label">Número de Referência</div>
        <div class="ref-code"><?php echo htmlspecialchars($ref); ?></div>
        <div class="ref-date">Emitido em <?php echo $hora_emissao; ?></div>
      </div>

      <!-- DETALHES ── -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title"><i class="bi bi-info-circle"></i> Detalhes da Marcação</div>
        </div>
        <div class="panel-body">
          <div class="info-row">
            <span class="info-key"><i class="bi bi-building"></i> Clube</span>
            <span class="info-val"><?php echo htmlspecialchars($clube); ?></span>
          </div>
          <div class="info-row">
            <span class="info-key"><i class="bi bi-layers"></i> Plano</span>
            <span class="info-val"><?php echo htmlspecialchars($plano); ?></span>
          </div>
          <?php if($escalao): ?>
          <div class="info-row">
            <span class="info-key"><i class="bi bi-people"></i> Escalão</span>
            <span class="info-val"><?php echo htmlspecialchars($escalao); ?></span>
          </div>
          <?php endif; ?>
          <div class="info-row">
            <span class="info-key"><i class="bi bi-calendar3"></i> Dias</span>
            <span class="info-val"><?php echo htmlspecialchars($dias); ?></span>
          </div>
          <div class="info-row">
            <span class="info-key"><i class="bi bi-clock"></i> Horário</span>
            <span class="info-val"><?php echo htmlspecialchars($hora); ?></span>
          </div>
          <div class="info-row">
            <span class="info-key"><i class="bi bi-currency-euro"></i> Valor</span>
            <span class="info-val gold">€<?php echo number_format($preco, 2, ',', '.'); ?>/mês</span>
          </div>
          <div class="info-row">
            <span class="info-key"><i class="bi bi-phone"></i> Método</span>
            <span class="info-val green"><i class="bi bi-check-circle-fill"></i> MB WAY</span>
          </div>
        </div>
      </div>

      <!-- PRÓXIMOS PASSOS ── -->
      <div class="steps">
        <div class="step">
          <div class="step-num">1</div>
          <div class="step-text">Guarda o número de referência para apresentar no clube</div>
        </div>
        <div class="step">
          <div class="step-num">2</div>
          <div class="step-text">Aparece no primeiro treino com o equipamento adequado</div>
        </div>
        <div class="step">
          <div class="step-num">3</div>
          <div class="step-text">Consulta as tuas marcações no perfil a qualquer momento</div>
        </div>
        <div class="step">
          <div class="step-num">4</div>
          <div class="step-text">Podes cancelar a marcação com até 24h de antecedência</div>
        </div>
      </div>

      <!-- AÇÕES ── -->
      <div class="actions">
        <a href="mapa.php" class="btn btn-primary"><i class="bi bi-map"></i> Voltar ao Mapa</a>
        <a href="marcacoes.php" class="btn btn-ghost"><i class="bi bi-calendar-check"></i> Ver Marcações</a>
        <?php if($club_id): ?>
          <?php
            // Get the marcacao id just inserted
            $last = $conn->query("SELECT id FROM marcacoes WHERE user_id=$uid AND referencia='".addslashes($ref)."' LIMIT 1")->fetch_assoc();
            $mid = $last['id'] ?? 0;
          ?>
          <?php if($mid): ?>
          <a href="gerar_pdf_marcacao.php?id=<?php echo $mid; ?>" target="_blank" class="btn btn-pdf">
            <i class="bi bi-file-earmark-pdf"></i> Descarregar PDF
          </a>
          <?php endif; ?>
        <?php endif; ?>
      </div>

    </div><!-- /page -->
  </div><!-- /main -->
</div><!-- /app -->
</body>
</html>
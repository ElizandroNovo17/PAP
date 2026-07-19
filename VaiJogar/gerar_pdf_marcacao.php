<?php
session_start();
require_once 'PHP/conexao.php';

if (empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$uid = (int)$_SESSION['user_id'];
$mid = (int)($_GET['id'] ?? 0);

if (!$mid) { http_response_code(400); echo 'ID inválido'; exit; }

// Buscar marcação (só do próprio utilizador)
$stmt = $conn->prepare("
    SELECT m.id, m.referencia, m.status, m.metodo, m.preco,
           m.dias, m.horario, m.criado_em,
           DATE_FORMAT(m.criado_em,'%d/%m/%Y às %H:%i') AS data_fmt,
           m.cancelamento_motivo, m.cancelado_em,
           c.nome AS clube_nome, c.localizacao, c.recinto,
           c.telefone AS clube_tel, c.email AS clube_email,
           c.modalidade, c.divisao,
           p.nome AS plano_nome, p.preco AS plano_preco,
           e.nome AS escalao_nome, e.idade AS escalao_idade,
           u.nome AS user_nome, u.email AS user_email
    FROM marcacoes m
    JOIN clubes       c ON c.id    = m.club_id
    LEFT JOIN planos  p ON p.id    = m.plano_id
    LEFT JOIN escaloes e ON e.id   = m.escalao_id
    JOIN utilizadores u ON u.id_utilizador = m.user_id
    WHERE m.id = ? AND m.user_id = ?
    LIMIT 1
");
$stmt->bind_param('ii', $mid, $uid);
$stmt->execute();
$m = $stmt->get_result()->fetch_assoc();

if (!$m) { http_response_code(404); echo 'Marcação não encontrada.'; exit; }

$statusColor = [
  'confirmado'  => ['#052e16', '#4ade80', '#16a34a'],
  'pendente'    => ['#2d1f00', '#fbbf24', '#d97706'],
  'cancelado'   => ['#2d0a0a', '#f87171', '#dc2626'],
  'reembolsado' => ['#1a1f2e', '#94a3b8', '#64748b'],
][$m['status']] ?? ['#1a1a2e', '#9b6dff', '#6c3fff'];

$statusLabel = [
  'confirmado'  => '✓ Confirmado',
  'pendente'    => '⏳ Pendente',
  'cancelado'   => '✗ Cancelado',
  'reembolsado' => '↩ Reembolsado',
][$m['status']] ?? ucfirst($m['status']);

$modalidadeIcon = ['futebol' => '⚽', 'basquetebol' => '🏀', 'voleibol' => '🏐'][$m['modalidade']] ?? '🏟';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Comprovativo de Marcação — <?php echo htmlspecialchars($m['referencia']); ?></title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#07060f;color:#ede9ff;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding:32px 16px}

.toolbar{width:100%;max-width:680px;display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.toolbar-left{display:flex;gap:10px}
.btn{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;transition:all .15s;text-decoration:none}
.btn-back{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);color:rgba(237,233,255,.7)}
.btn-back:hover{background:rgba(255,255,255,.12);color:#ede9ff}
.btn-pdf{background:#6c3fff;color:#fff}
.btn-pdf:hover{background:#5930e5}

/* ── COMPROVATIVO ── */
.doc{width:100%;max-width:680px;background:#fff;color:#1a1a2e;border-radius:16px;overflow:hidden;box-shadow:0 24px 80px rgba(0,0,0,.6)}

/* Header do documento */
.doc-header{background:linear-gradient(135deg,#6c3fff 0%,#9b6dff 100%);padding:32px 36px;display:flex;justify-content:space-between;align-items:flex-start}
.doc-brand{color:#fff}
.doc-brand-name{font-size:26px;font-weight:800;letter-spacing:-.5px}
.doc-brand-sub{font-size:12px;opacity:.75;margin-top:2px;letter-spacing:.05em}
.doc-status-wrap{text-align:right}
.doc-status{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3)}

/* Ref banner */
.doc-ref{background:#f8f7ff;padding:18px 36px;display:flex;justify-content:space-between;align-items:center;border-bottom:2px solid #ede9ff}
.ref-label{font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6b7280}
.ref-code{font-family:'DM Mono',monospace;font-size:20px;font-weight:700;color:#6c3fff;letter-spacing:.08em}
.ref-date{font-size:12px;color:#6b7280;text-align:right}

/* Body */
.doc-body{padding:30px 36px}

/* Section title */
.sec-title{font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#9ca3af;margin-bottom:12px;display:flex;align-items:center;gap:7px}
.sec-title::after{content:'';flex:1;height:1px;background:#e5e7eb}

/* Info grid */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;margin-bottom:24px}
.info-cell{padding:14px 18px;border-bottom:1px solid #e5e7eb;border-right:1px solid #e5e7eb}
.info-cell:nth-child(even){border-right:none}
.info-cell:nth-last-child(-n+2){border-bottom:none}
.info-cell.full{grid-column:1/-1;border-right:none}
.info-cell.full:last-child{border-bottom:none}
.info-key{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#9ca3af;margin-bottom:5px}
.info-val{font-size:14px;font-weight:600;color:#111827;line-height:1.4}
.info-val.mono{font-family:'DM Mono',monospace;color:#6c3fff}
.info-val.price{font-size:20px;font-weight:800;color:#059669}

/* Club card */
.club-card{background:linear-gradient(135deg,#f8f7ff,#ede9ff);border:1px solid #ddd6fe;border-radius:10px;padding:18px;margin-bottom:24px;display:flex;align-items:center;gap:16px}
.club-icon{width:50px;height:50px;border-radius:12px;background:#6c3fff;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0}
.club-name{font-size:18px;font-weight:800;color:#1a1a2e;line-height:1.2}
.club-meta{font-size:12px;color:#6b7280;margin-top:4px;display:flex;gap:10px;flex-wrap:wrap}
.club-meta span{display:flex;align-items:center;gap:4px}

/* Schedule strip */
.schedule{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px 18px;margin-bottom:24px}
.schedule-row{display:flex;align-items:center;gap:12px;font-size:13px;color:#065f46}
.schedule-row + .schedule-row{margin-top:10px;padding-top:10px;border-top:1px solid #bbf7d0}
.schedule-icon{width:30px;height:30px;border-radius:7px;background:rgba(34,197,94,.15);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.schedule-label{font-size:10px;color:#6b7280;font-weight:600;letter-spacing:.06em;text-transform:uppercase}
.schedule-val{font-weight:700;color:#065f46;margin-top:2px}

/* Footer */
.doc-footer{background:#f8f7ff;padding:20px 36px;border-top:2px solid #ede9ff;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
.doc-footer-note{font-size:11px;color:#9ca3af;line-height:1.5}
.doc-footer-logo{font-size:16px;font-weight:800;color:#6c3fff;letter-spacing:-.3px}

/* Print */
@media print{
  body{background:#fff;padding:0}
  .toolbar{display:none}
  .doc{box-shadow:none;border-radius:0}
}
</style>
</head>
<body>

<!-- TOOLBAR (não imprime) -->
<div class="toolbar">
  <div class="toolbar-left">
    <a href="marcacoes.php" class="btn btn-back"><i class="bi bi-arrow-left"></i> Voltar</a>
  </div>
  <button class="btn btn-pdf" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir / Guardar PDF</button>
</div>

<!-- COMPROVATIVO -->
<div class="doc" id="docPrint">

  <!-- HEADER -->
  <div class="doc-header">
    <div class="doc-brand">
      <div class="doc-brand-name">VaiJogar</div>
      <div class="doc-brand-sub">Comprovativo de Marcação de Treino</div>
    </div>
    <div class="doc-status-wrap">
      <div class="doc-status"><?php echo $statusLabel; ?></div>
      <div style="font-size:11px;color:rgba(255,255,255,.6);margin-top:6px">Emitido em <?php echo date('d/m/Y H:i'); ?></div>
    </div>
  </div>

  <!-- REFERÊNCIA -->
  <div class="doc-ref">
    <div>
      <div class="ref-label">Número de Referência</div>
      <div class="ref-code"><?php echo htmlspecialchars($m['referencia']); ?></div>
    </div>
    <div class="ref-date">
      <div class="ref-label">Data da Marcação</div>
      <div style="font-size:13px;font-weight:600;color:#374151;margin-top:4px"><?php echo htmlspecialchars($m['data_fmt']); ?></div>
    </div>
  </div>

  <!-- BODY -->
  <div class="doc-body">

    <!-- CLUBE -->
    <div class="sec-title"><i class="bi bi-building" style="font-size:11px;color:#9ca3af"></i> Clube Desportivo</div>
    <div class="club-card">
      <div class="club-icon"><?php echo $modalidadeIcon; ?></div>
      <div>
        <div class="club-name"><?php echo htmlspecialchars($m['clube_nome']); ?></div>
        <div class="club-meta">
          <?php if($m['modalidade']): ?><span><i class="bi bi-tag-fill" style="color:#6c3fff"></i> <?php echo ucfirst(htmlspecialchars($m['modalidade'])); ?></span><?php endif; ?>
          <?php if($m['divisao']): ?><span><i class="bi bi-trophy-fill" style="color:#f59e0b"></i> <?php echo htmlspecialchars($m['divisao']); ?></span><?php endif; ?>
          <?php if($m['recinto']): ?><span><i class="bi bi-geo-alt-fill" style="color:#ef4444"></i> <?php echo htmlspecialchars($m['recinto']); ?></span><?php endif; ?>
          <?php if($m['localizacao']): ?><span><i class="bi bi-map-fill" style="color:#3b82f6"></i> <?php echo htmlspecialchars($m['localizacao']); ?></span><?php endif; ?>
        </div>
      </div>
    </div>

    <!-- DETALHES DA MARCAÇÃO -->
    <div class="sec-title"><i class="bi bi-info-circle" style="font-size:11px;color:#9ca3af"></i> Detalhes da Marcação</div>
    <div class="info-grid">
      <div class="info-cell">
        <div class="info-key">Plano</div>
        <div class="info-val"><?php echo htmlspecialchars($m['plano_nome'] ?? '—'); ?></div>
      </div>
      <div class="info-cell">
        <div class="info-key">Escalão</div>
        <div class="info-val"><?php echo htmlspecialchars($m['escalao_nome'] ?? '—'); ?><?php if($m['escalao_idade']): ?> <span style="font-size:11px;color:#9ca3af;font-weight:400">(<?php echo htmlspecialchars($m['escalao_idade']); ?>)</span><?php endif; ?></div>
      </div>
      <div class="info-cell">
        <div class="info-key">Método de Pagamento</div>
        <div class="info-val"><?php echo strtoupper(htmlspecialchars($m['metodo'] ?? 'mbway')); ?></div>
      </div>
      <div class="info-cell">
        <div class="info-key">Valor Mensal</div>
        <div class="info-val price">€<?php echo number_format((float)$m['preco'], 2, ',', '.'); ?></div>
      </div>
      <?php if($m['cancelamento_motivo']): ?>
      <div class="info-cell full">
        <div class="info-key">Motivo de Cancelamento</div>
        <div class="info-val" style="color:#ef4444"><?php echo htmlspecialchars($m['cancelamento_motivo']); ?></div>
      </div>
      <?php endif; ?>
    </div>

    <!-- HORÁRIOS -->
    <div class="sec-title"><i class="bi bi-calendar3" style="font-size:11px;color:#9ca3af"></i> Horário dos Treinos</div>
    <div class="schedule">
      <div class="schedule-row">
        <div class="schedule-icon"><i class="bi bi-calendar-week" style="color:#16a34a"></i></div>
        <div>
          <div class="schedule-label">Dias da Semana</div>
          <div class="schedule-val"><?php echo htmlspecialchars($m['dias'] ?? '—'); ?></div>
        </div>
      </div>
      <div class="schedule-row">
        <div class="schedule-icon"><i class="bi bi-clock" style="color:#16a34a"></i></div>
        <div>
          <div class="schedule-label">Horário</div>
          <div class="schedule-val"><?php echo htmlspecialchars($m['horario'] ?? '—'); ?></div>
        </div>
      </div>
    </div>

    <!-- UTILIZADOR -->
    <div class="sec-title"><i class="bi bi-person" style="font-size:11px;color:#9ca3af"></i> Dados do Utilizador</div>
    <div class="info-grid">
      <div class="info-cell">
        <div class="info-key">Nome</div>
        <div class="info-val"><?php echo htmlspecialchars($m['user_nome']); ?></div>
      </div>
      <div class="info-cell">
        <div class="info-key">Email</div>
        <div class="info-val"><?php echo htmlspecialchars($m['user_email']); ?></div>
      </div>
    </div>

  </div><!-- /doc-body -->

  <!-- FOOTER -->
  <div class="doc-footer">
    <div class="doc-footer-note">
      Este documento é um comprovativo oficial de marcação na plataforma VaiJogar.<br>
      Apresente esta referência ao clube para confirmar a sua inscrição.
    </div>
    <div class="doc-footer-logo">VaiJogar</div>
  </div>

</div><!-- /doc -->

</body>
</html>
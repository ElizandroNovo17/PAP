<?php
require_once 'PHP/conexao.php';

$clube_nome = $_GET['clube']      ?? '';
$modalidade = $_GET['modalidade'] ?? '';

$stmt = $conn->prepare("SELECT * FROM clubes WHERE nome = ? AND (? = '' OR modalidade = ?) LIMIT 1");
$stmt->bind_param("sss", $clube_nome, $modalidade, $modalidade);
$stmt->execute();
$clube   = $stmt->get_result()->fetch_assoc();
$club_id = $clube['id'] ?? 1;

$planos = $conn->query("SELECT * FROM planos WHERE ativo = 1 ORDER BY preco ASC")->fetch_all(MYSQLI_ASSOC);

$stmt2 = $conn->prepare("
    SELECT e.*, (e.vagas_totais - e.vagas_ocupadas) AS vagas_livres
    FROM escaloes e WHERE e.club_id = ? ORDER BY e.id ASC
");
$stmt2->bind_param("i", $club_id);
$stmt2->execute();
$escaloes = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$planos_js   = json_encode($planos);
$escaloes_js = json_encode($escaloes);
$club_id_js  = json_encode($club_id);
$clube_js    = json_encode($clube);

// Logo resolution
$LOGO_URLS = [
  "SL Benfica"          => "https://upload.wikimedia.org/wikipedia/pt/thumb/d/d8/SL_Benfica_logo.svg/120px-SL_Benfica_logo.svg.png",
  "Estrela da Amadora"  => "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5d/Estrela_da_Amadora.png/120px-Estrela_da_Amadora.png",
  "CF Os Belenenses"    => "https://upload.wikimedia.org/wikipedia/pt/thumb/7/73/CF_Belenenses.png/120px-CF_Belenenses.png",
  "Casa Pia AC"         => "https://upload.wikimedia.org/wikipedia/pt/thumb/a/af/Casa_Pia_AC.png/120px-Casa_Pia_AC.png",
  "Estoril Praia"       => "https://upload.wikimedia.org/wikipedia/pt/thumb/5/50/GD_Estoril_Praia.png/120px-GD_Estoril_Praia.png",
  "GD Estoril-Praia B"  => "https://upload.wikimedia.org/wikipedia/pt/thumb/5/50/GD_Estoril_Praia.png/120px-GD_Estoril_Praia.png",
  "SC Farense"          => "https://upload.wikimedia.org/wikipedia/pt/thumb/c/c1/SC_Farense.png/120px-SC_Farense.png",
];
$logo_src = $clube['imagem_url'] ?? ($LOGO_URLS[$clube_nome] ?? '');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marcação — <?php echo htmlspecialchars($clube_nome); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --bg:      #07000e;
  --surface: #0f0720;
  --surface2:#140a28;
  --border:  rgba(110,50,240,0.15);
  --border2: rgba(110,50,240,0.35);
  --purple:  #6e32f0;
  --purple2: #9d6fff;
  --gold:    #e8a020;
  --green:   #22c55e;
  --red:     #ef4444;
  --text:    #ede8ff;
  --muted:   rgba(237,232,255,0.38);
  --muted2:  rgba(237,232,255,0.6);
}

html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);overflow:hidden}

/* ── SHELL ── */
.shell{height:100vh;display:grid;grid-template-rows:64px 1fr}

/* ── TOPBAR ── */
.topbar{
  display:flex;align-items:center;justify-content:space-between;
  padding:0 24px;border-bottom:1px solid var(--border);
  background:rgba(10,3,22,0.98);z-index:10;gap:16px;
}
.topbar-left{display:flex;align-items:center;gap:14px}
.btn-back{
  display:inline-flex;align-items:center;gap:6px;
  font-size:13px;color:var(--muted2);text-decoration:none;
  padding:6px 12px;border-radius:8px;border:1px solid var(--border);
  transition:all .18s;background:none;cursor:pointer;
  font-family:'DM Sans',sans-serif;
}
.btn-back:hover{color:var(--text);border-color:var(--border2)}

/* Logo do clube na topbar */
.clube-logo-wrap{
  width:36px;height:36px;border-radius:10px;
  background:rgba(110,50,240,0.15);border:1px solid var(--border2);
  display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;
}
.clube-logo-wrap img{width:28px;height:28px;object-fit:contain}
.clube-logo-wrap i{font-size:18px;color:var(--purple2)}

.clube-info{display:flex;flex-direction:column;gap:2px}
.clube-nome{font-weight:700;font-size:15px;line-height:1.2}
.clube-sub{font-size:11px;color:var(--muted2);display:flex;align-items:center;gap:6px}
.clube-sub span{display:flex;align-items:center;gap:3px}
.badge-topbar{
  font-size:10px;font-weight:700;padding:2px 7px;border-radius:5px;letter-spacing:.03em;
}
.badge-mod{background:rgba(110,50,240,0.2);color:var(--purple2);border:1px solid var(--border2)}
.badge-div{background:rgba(232,160,32,0.15);color:var(--gold);border:1px solid rgba(232,160,32,0.25)}

.topbar-right{font-size:11px;color:var(--muted);letter-spacing:.08em;text-transform:uppercase}

/* ── MAIN 5 COLUNAS ── */
.main{
  display:grid;
  grid-template-columns:220px 220px 1fr 1fr 280px;
  height:100%;overflow:hidden;
}

/* ── COLUNA ── */
.col{
  border-right:1px solid var(--border);
  display:flex;flex-direction:column;overflow:hidden;min-width:0;
}
.col:last-child{border-right:none}

.col-head{
  padding:16px 16px 12px;
  border-bottom:1px solid var(--border);flex-shrink:0;
  background:rgba(255,255,255,0.015);
}
.col-label{
  font-size:10px;font-weight:700;letter-spacing:.13em;
  text-transform:uppercase;color:var(--purple2);
  display:flex;align-items:center;gap:5px;margin-bottom:4px;
}
.col-hint{
  font-size:11px;color:var(--muted);min-height:14px;
  transition:color .2s;line-height:1.4;
}
.col-hint.ok   {color:var(--green)}
.col-hint.limit{color:var(--gold)}
.col-hint.warn {color:var(--red)}

.col-body{
  flex:1;overflow-y:auto;padding:10px;
  display:flex;flex-direction:column;gap:7px;
}
.col-body::-webkit-scrollbar{width:3px}
.col-body::-webkit-scrollbar-thumb{background:var(--border2);border-radius:4px}

/* ── PLANO CARD ── */
.plano-card{
  border:1px solid var(--border);border-radius:12px;
  padding:14px 15px;cursor:pointer;transition:border-color .18s,background .18s;
  display:flex;align-items:center;gap:12px;
}
.plano-card:hover{border-color:var(--border2);background:rgba(110,50,240,0.06)}
.plano-card.sel{border-color:var(--purple);background:rgba(110,50,240,0.13);box-shadow:0 0 0 1px rgba(110,50,240,0.2)}
.plano-info{flex:1;min-width:0}
.plano-nome{font-size:14px;font-weight:700;margin-bottom:3px}
.plano-preco{font-family:'DM Mono',monospace;font-size:19px;font-weight:500;color:var(--gold);line-height:1.2}
.plano-meta{font-size:11px;color:var(--muted);margin-top:3px}
.radio{
  width:18px;height:18px;border-radius:50%;
  border:1.5px solid var(--border2);flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  transition:all .18s;
}
.plano-card.sel .radio{background:var(--purple);border-color:var(--purple)}
.radio-dot{width:6px;height:6px;border-radius:50%;background:white;display:none}
.plano-card.sel .radio-dot{display:block}

/* ── ESCALÃO CARD ── */
.esc-card{
  border:1px solid var(--border);border-radius:12px;
  padding:11px 13px;cursor:pointer;transition:border-color .18s,background .18s;
  display:flex;align-items:center;justify-content:space-between;gap:8px;
}
.esc-card:hover{border-color:var(--border2);background:rgba(110,50,240,0.06)}
.esc-card.sel{border-color:var(--purple);background:rgba(110,50,240,0.13)}
.esc-card.esgotado{opacity:.35;cursor:not-allowed}
.esc-nome{font-size:13px;font-weight:600}
.esc-idade{font-size:10px;color:var(--muted);margin-top:1px}
.esc-vagas{font-size:10px;padding:3px 8px;border-radius:5px;font-weight:700;flex-shrink:0}
.esc-vagas.ok {background:rgba(34,197,94,0.12);color:var(--green)}
.esc-vagas.low{background:rgba(232,160,32,0.12);color:var(--gold)}
.esc-vagas.no {background:rgba(239,68,68,0.1);color:var(--red)}

/* ── DIA BTN ── */
.dia-btn{
  border:1px solid var(--border);border-radius:10px;
  padding:11px 13px;cursor:pointer;transition:border-color .18s,background .18s;
  display:flex;align-items:center;justify-content:space-between;
  font-size:13px;font-weight:500;color:var(--muted2);
}
.dia-btn:hover:not(.locked){border-color:var(--border2);color:var(--text);background:rgba(110,50,240,0.06)}
.dia-btn.sel{border-color:var(--purple);background:rgba(110,50,240,0.13);color:var(--text)}
.dia-btn.locked{opacity:.28;cursor:not-allowed}
.dia-chk{
  width:16px;height:16px;border-radius:4px;
  border:1.5px solid var(--border2);font-size:10px;
  display:flex;align-items:center;justify-content:center;
  transition:all .18s;flex-shrink:0;
}
.dia-btn.sel .dia-chk{background:var(--purple);border-color:var(--purple);color:white}

/* ── HORA BTN ── */
.hora-btn{
  border:1px solid var(--border);border-radius:10px;
  padding:11px 13px;cursor:pointer;transition:border-color .18s,background .18s;
  display:flex;align-items:center;justify-content:space-between;color:var(--muted2);
}
.hora-btn:hover:not(.sem-vagas){border-color:var(--border2);color:var(--text);background:rgba(110,50,240,0.06)}
.hora-btn.sel{border-color:var(--purple);background:rgba(110,50,240,0.13);color:var(--text)}
.hora-btn.sem-vagas{opacity:.3;cursor:not-allowed}
.hora-time{font-family:'DM Mono',monospace;font-size:15px;font-weight:500}
.hora-fim{font-size:10px;color:var(--muted);margin-top:1px}
.hora-vagas-badge{font-size:10px;padding:3px 8px;border-radius:5px;font-weight:700}
.hora-vagas-badge.ok {background:rgba(34,197,94,0.12);color:var(--green)}
.hora-vagas-badge.low{background:rgba(232,160,32,0.12);color:var(--gold)}
.hora-vagas-badge.no {background:rgba(239,68,68,0.1);color:var(--red)}

/* ── AVISO ── */
.aviso{
  font-size:12px;color:var(--red);
  background:rgba(239,68,68,0.08);
  border:1px solid rgba(239,68,68,0.2);
  border-radius:8px;padding:9px 12px;
  display:none;
}

/* ── EMPTY ── */
.empty{
  text-align:center;padding:28px 16px;
  color:var(--muted);font-size:12px;line-height:1.6;
}
.empty i{display:block;font-size:22px;margin-bottom:8px;opacity:.35}

/* ── RESUMO ── */
.resumo-col{background:var(--surface);display:flex;flex-direction:column}

/* mini hero no resumo */
.resumo-hero{
  padding:16px 18px 14px;
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:12px;
  background:rgba(110,50,240,0.06);
  flex-shrink:0;
}
.resumo-logo{
  width:44px;height:44px;border-radius:12px;
  background:rgba(110,50,240,0.15);border:1px solid var(--border2);
  display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;
}
.resumo-logo img{width:34px;height:34px;object-fit:contain}
.resumo-logo i{font-size:20px;color:var(--purple2)}
.resumo-clube-nome{font-size:14px;font-weight:700;line-height:1.3}
.resumo-clube-mod{font-size:11px;color:var(--muted2);margin-top:2px}

.resumo-body{flex:1;padding:14px 18px;display:flex;flex-direction:column;gap:0;overflow-y:auto}
.r-row{
  display:flex;justify-content:space-between;align-items:flex-start;
  padding:11px 0;border-bottom:1px solid var(--border);gap:10px;
}
.r-row:last-of-type{border-bottom:none}
.r-key{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;padding-top:2px;flex-shrink:0}
.r-val{font-size:13px;font-weight:600;text-align:right;word-break:break-word}
.r-val.em{color:var(--muted);font-weight:400;font-style:italic}
.r-preco-wrap{margin-top:auto;padding-top:18px}
.r-preco-label{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px}
.r-preco{font-family:'DM Mono',monospace;font-size:28px;font-weight:500;color:var(--gold);line-height:1}

.resumo-footer{padding:14px 18px;border-top:1px solid var(--border);flex-shrink:0}
.btn-confirmar{
  width:100%;padding:13px;border:none;border-radius:10px;
  background:var(--purple);color:white;
  font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;
  cursor:pointer;transition:opacity .2s;
  display:flex;align-items:center;justify-content:center;gap:7px;
}
.btn-confirmar:hover:not(:disabled){opacity:.85}
.btn-confirmar:disabled{opacity:.3;cursor:not-allowed}
.spinner{width:15px;height:15px;border:2px solid rgba(255,255,255,0.2);border-top-color:white;border-radius:50%;animation:spin .7s linear infinite;display:none}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes fadeIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}

/* ── MOBILE ── */
@media(max-width:960px){
  html,body{overflow:auto}
  .main{grid-template-columns:1fr;grid-template-rows:auto;height:auto}
  .col{border-right:none;border-bottom:1px solid var(--border);overflow:visible}
  .col-body{overflow:visible;max-height:none}
}
</style>
</head>
<body>
<div class="shell">

  <!-- TOPBAR com logo do clube -->
  <div class="topbar">
    <div class="topbar-left">
      <button class="btn-back" onclick="history.back()">
        <i class="bi bi-arrow-left"></i> Voltar
      </button>
      <div class="clube-logo-wrap" id="topbarLogo">
        <?php if($logo_src): ?>
          <img src="<?php echo htmlspecialchars($logo_src); ?>"
               alt="<?php echo htmlspecialchars($clube_nome); ?>"
               onerror="this.parentElement.innerHTML='<i class=\'bi bi-shield-fill\'></i>'">
        <?php else: ?>
          <i class="bi bi-shield-fill"></i>
        <?php endif; ?>
      </div>
      <div class="clube-info">
        <div class="clube-nome"><?php echo htmlspecialchars($clube_nome ?: 'Clube'); ?></div>
        <div class="clube-sub">
          <?php if($modalidade): ?><span class="badge-topbar badge-mod"><?php echo htmlspecialchars(ucfirst($modalidade)); ?></span><?php endif; ?>
          <?php if($clube['divisao'] ?? ''): ?><span class="badge-topbar badge-div"><?php echo htmlspecialchars($clube['divisao']); ?></span><?php endif; ?>
          <?php if($clube['recinto'] ?? ''): ?><span style="font-size:11px;color:var(--muted)"><i class="bi bi-geo-fill"></i> <?php echo htmlspecialchars($clube['recinto']); ?></span><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="topbar-right">Marcação de Treino</div>
  </div>

  <div class="main">

    <!-- COL 1: PLANO -->
    <div class="col">
      <div class="col-head">
        <div class="col-label"><i class="bi bi-layers"></i> Plano</div>
        <div class="col-hint" id="hintPlano">Escolhe um plano</div>
      </div>
      <div class="col-body">
        <?php foreach($planos as $i => $p): ?>
        <div class="plano-card" id="plano<?php echo $i; ?>" onclick="selecionarPlano(<?php echo $i; ?>)">
          <div class="plano-info">
            <div class="plano-nome"><?php echo htmlspecialchars($p['nome']); ?></div>
            <div class="plano-preco">€<?php echo number_format($p['preco'],2); ?></div>
            <div class="plano-meta"><?php echo $p['sessoes_por_semana']; ?> · máx <?php echo (int)$p['dias_maximos']; ?> dia<?php echo $p['dias_maximos']>1?'s':''; ?></div>
          </div>
          <div class="radio"><div class="radio-dot"></div></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- COL 2: ESCALÃO -->
    <div class="col">
      <div class="col-head">
        <div class="col-label"><i class="bi bi-people"></i> Escalão</div>
        <div class="col-hint" id="hintEscalao">Escolhe o escalão</div>
      </div>
      <div class="col-body">
        <?php if(empty($escaloes)): ?>
          <div class="empty"><i class="bi bi-people"></i>Sem escalões para este clube</div>
        <?php else: foreach($escaloes as $i => $e):
          $livres    = (int)$e['vagas_livres'];
          $esgotado  = $livres <= 0;
          $vagaClass = $livres > 10 ? 'ok' : ($livres > 0 ? 'low' : 'no');
          $vagaTxt   = $esgotado ? 'Esgotado' : $livres . ' vagas';
        ?>
        <div class="esc-card <?php echo $esgotado?'esgotado':''; ?>" id="esc<?php echo $i; ?>"
             onclick="<?php echo $esgotado?'':'selecionarEscalao('.$i.')'; ?>">
          <div>
            <div class="esc-nome"><?php echo htmlspecialchars($e['nome']); ?></div>
            <div class="esc-idade"><?php echo htmlspecialchars($e['idade'] ?? ''); ?></div>
          </div>
          <span class="esc-vagas <?php echo $vagaClass; ?>"><?php echo $vagaTxt; ?></span>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- COL 3: DIAS -->
    <div class="col">
      <div class="col-head">
        <div class="col-label"><i class="bi bi-calendar3"></i> Dias</div>
        <div class="col-hint" id="hintDias">Escolhe plano e escalão primeiro</div>
      </div>
      <div class="col-body" id="diasBody">
        <div class="empty"><i class="bi bi-calendar3"></i>Seleciona um escalão para ver os dias disponíveis</div>
      </div>
    </div>

    <!-- COL 4: HORÁRIO -->
    <div class="col">
      <div class="col-head">
        <div class="col-label"><i class="bi bi-clock"></i> Horário</div>
        <div class="col-hint" id="hintHora">Seleciona um dia primeiro</div>
      </div>
      <div class="col-body" id="horasBody">
        <div class="empty"><i class="bi bi-clock"></i>Seleciona os dias para ver os horários</div>
      </div>
    </div>

    <!-- COL 5: RESUMO -->
    <div class="col resumo-col">
      <!-- Mini hero do clube -->
      <div class="resumo-hero">
        <div class="resumo-logo">
          <?php if($logo_src): ?>
            <img src="<?php echo htmlspecialchars($logo_src); ?>" alt="<?php echo htmlspecialchars($clube_nome); ?>"
                 onerror="this.parentElement.innerHTML='<i class=\'bi bi-shield-fill\'></i>'">
          <?php else: ?>
            <i class="bi bi-shield-fill"></i>
          <?php endif; ?>
        </div>
        <div>
          <div class="resumo-clube-nome"><?php echo htmlspecialchars($clube_nome ?: '—'); ?></div>
          <div class="resumo-clube-mod">
            <?php if($modalidade): ?><span style="color:var(--purple2)"><?php echo htmlspecialchars(ucfirst($modalidade)); ?></span><?php endif; ?>
            <?php if($clube['divisao'] ?? ''): ?> · <?php echo htmlspecialchars($clube['divisao']); ?><?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-head" style="background:none">
        <div class="col-label"><i class="bi bi-receipt"></i> Resumo</div>
        <div class="col-hint">Confirma os detalhes</div>
      </div>

      <div class="resumo-body">
        <div class="r-row">
          <span class="r-key">Plano</span>
          <span class="r-val em" id="rPlano">—</span>
        </div>
        <div class="r-row">
          <span class="r-key">Escalão</span>
          <span class="r-val em" id="rEscalao">—</span>
        </div>
        <div class="r-row">
          <span class="r-key">Dias</span>
          <span class="r-val em" id="rDias">—</span>
        </div>
        <div class="r-row">
          <span class="r-key">Horário</span>
          <span class="r-val em" id="rHora">—</span>
        </div>
        <div class="r-preco-wrap">
          <div class="r-preco-label">Total / mês</div>
          <div class="r-preco" id="rPreco">— €</div>
        </div>
      </div>

      <div class="resumo-footer">
        <button class="btn-confirmar" id="btnConfirmar" disabled onclick="confirmar()">
          <div class="spinner" id="spinner"></div>
          <i class="bi bi-check-circle" id="btnIcon"></i>
          <span id="btnTxt">Confirmar Marcação</span>
        </button>
      </div>
    </div>

  </div>
</div>

<script>
const PLANOS   = <?php echo $planos_js; ?>;
const ESCALOES = <?php echo $escaloes_js; ?>;
const CLUB_ID  = <?php echo $club_id_js; ?>;
const CLUBE_OBJ= <?php echo $clube_js; ?>;
const CLUBE    = <?php echo json_encode($clube_nome); ?>;

let sel = { plano:null, escalao:null, dias:[], horario:null, horario_id:null };
let horariosPorDia = {};

function selecionarPlano(i) {
  sel.plano = PLANOS[i];
  sel.dias = []; sel.horario = null; sel.horario_id = null;
  renderHoras([]);
  document.querySelectorAll('.plano-card').forEach((c,j) => c.classList.toggle('sel', j===i));
  document.getElementById('hintPlano').textContent = sel.plano.nome + ' · €' + parseFloat(sel.plano.preco).toFixed(2) + '/mês';
  document.getElementById('hintPlano').className = 'col-hint ok';
  atualizarHintDias();
  atualizar();
}

function selecionarEscalao(i) {
  sel.escalao = ESCALOES[i];
  sel.dias = []; sel.horario = null; sel.horario_id = null;
  document.querySelectorAll('.esc-card').forEach((c,j) => c.classList.toggle('sel', j===i));
  document.getElementById('hintEscalao').textContent = sel.escalao.nome + (sel.escalao.idade ? ' · ' + sel.escalao.idade : '');
  document.getElementById('hintEscalao').className = 'col-hint ok';
  carregarDias();
  atualizar();
}

async function carregarDias() {
  const body = document.getElementById('diasBody');
  body.innerHTML = '<div class="empty"><i class="bi bi-hourglass"></i>A carregar...</div>';
  try {
    const r = await fetch(`PHP/clubes_api.php?action=horarios&club_id=${CLUB_ID}&escalao_id=${sel.escalao.id}`);
    const data = await r.json();
    horariosPorDia = {};
    const lista = data.horarios || data || [];
    lista.forEach(h => {
      if (!horariosPorDia[h.dia_semana]) horariosPorDia[h.dia_semana] = [];
      horariosPorDia[h.dia_semana].push(h);
    });
    renderDias();
  } catch(e) {
    body.innerHTML = '<div class="empty"><i class="bi bi-exclamation-circle"></i>Erro ao carregar horários</div>';
  }
}

const ORDEM_DIAS = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'];

function renderDias() {
  const body = document.getElementById('diasBody');
  const dias = ORDEM_DIAS.filter(d => horariosPorDia[d]);
  if (!dias.length) {
    body.innerHTML = '<div class="empty"><i class="bi bi-calendar-x"></i>Sem horários disponíveis para este escalão</div>';
    return;
  }
  let html = '<div class="aviso" id="avisoDias"></div>';
  dias.forEach(dia => {
    const temVagas = horariosPorDia[dia].some(h => h.vagas_disponiveis > 0);
    const isSel    = sel.dias.includes(dia);
    const isLocked = !isSel && sel.dias.length >= (sel.plano?.dias_maximos || 99);
    html += `<div class="dia-btn ${isSel?'sel':''} ${isLocked||!temVagas?'locked':''}"
      id="dia_${dia}" onclick="toggleDia('${dia}')">
      <span>${dia}</span>
      <div class="dia-chk">${isSel?'<i class="bi bi-check"></i>':''}</div>
    </div>`;
  });
  body.innerHTML = html;
  atualizarHintDias();
}

function toggleDia(dia) {
  if (!sel.plano) { mostrarAviso('avisoDias','Escolhe primeiro um plano.'); return; }
  const max = sel.plano.dias_maximos;
  if (sel.dias.includes(dia)) {
    sel.dias = sel.dias.filter(d => d !== dia);
    sel.horario = null; sel.horario_id = null;
  } else {
    if (sel.dias.length >= max) {
      mostrarAviso('avisoDias','O plano ' + sel.plano.nome + ' permite no máximo ' + max + ' dia' + (max>1?'s':'') + '.');
      return;
    }
    sel.dias.push(dia);
  }
  renderDias();
  if (sel.dias.length === 1)      renderHoras(horariosPorDia[sel.dias[0]] || []);
  else if (sel.dias.length === 0) renderHoras([]);
  else                            renderHoras(horariosPorDia[sel.dias[0]] || [], true);
  atualizar();
}

function renderHoras(horas, multiDia=false) {
  const body = document.getElementById('horasBody');
  sel.horario = null; sel.horario_id = null;
  if (!horas.length && sel.dias.length === 0) {
    body.innerHTML = '<div class="empty"><i class="bi bi-clock"></i>Seleciona os dias para ver os horários</div>';
    document.getElementById('hintHora').textContent = 'Seleciona um dia primeiro';
    document.getElementById('hintHora').className = 'col-hint';
    return;
  }
  if (!horas.length) {
    body.innerHTML = '<div class="empty"><i class="bi bi-clock-history"></i>Sem horários para este dia</div>';
    return;
  }
  let html = multiDia ? `<div style="font-size:11px;color:var(--muted);padding:2px 0 6px;">Horários do primeiro dia selecionado</div>` : '';
  horas.forEach(h => {
    const v = parseInt(h.vagas_disponiveis);
    const semVagas   = v <= 0;
    const vagaClass  = v > 5 ? 'ok' : (v > 0 ? 'low' : 'no');
    const vagaTxt    = semVagas ? 'Sem vagas' : v + ' vaga' + (v!==1?'s':'');
    html += `<div class="hora-btn ${semVagas?'sem-vagas':''}" id="hora_${h.id}"
      onclick="${semVagas?'':'selecionarHora('+h.id+',\''+h.hora_inicio+'\',\''+h.hora_fim+'\')'}">
      <div>
        <div class="hora-time">${h.hora_inicio.slice(0,5)}</div>
        <div class="hora-fim">até ${h.hora_fim.slice(0,5)}</div>
      </div>
      <span class="hora-vagas-badge ${vagaClass}">${vagaTxt}</span>
    </div>`;
  });
  body.innerHTML = html;
  document.getElementById('hintHora').textContent = 'Escolhe o horário';
  document.getElementById('hintHora').className = 'col-hint';
}

function selecionarHora(id, inicio, fim) {
  document.querySelectorAll('.hora-btn').forEach(b => b.classList.remove('sel'));
  document.getElementById('hora_' + id).classList.add('sel');
  sel.horario    = inicio.slice(0,5) + ' – ' + fim.slice(0,5);
  sel.horario_id = id;
  document.getElementById('hintHora').textContent = sel.horario;
  document.getElementById('hintHora').className = 'col-hint ok';
  atualizar();
}

function atualizarHintDias() {
  const h = document.getElementById('hintDias');
  if (!sel.plano || !sel.escalao) {
    h.textContent = 'Escolhe plano e escalão primeiro';
    h.className = 'col-hint'; return;
  }
  const s = sel.dias.length, max = parseInt(sel.plano.dias_maximos);
  const rest = max - s;
  h.textContent = s + ' de ' + max + ' dia' + (max>1?'s':'') + (rest>0?' · falta'+(rest>1?'m':'')+' '+rest : ' · completo');
  h.className = 'col-hint' + (rest===0?' limit':'');
}

function mostrarAviso(id, msg) {
  const el = document.getElementById(id);
  if (!el) return;
  el.textContent = msg; el.style.display = 'block';
  clearTimeout(el._t);
  el._t = setTimeout(() => el.style.display='none', 3000);
}

function atualizar() {
  atualizarHintDias();
  set('rPlano',   sel.plano?.nome,           !sel.plano);
  set('rEscalao', sel.escalao?.nome,         !sel.escalao);
  set('rDias',    sel.dias.join(', ')||null, sel.dias.length===0);
  set('rHora',    sel.horario,               !sel.horario);
  document.getElementById('rPreco').textContent = sel.plano ? '€ '+parseFloat(sel.plano.preco).toFixed(2) : '— €';
  document.getElementById('btnConfirmar').disabled = !(sel.plano && sel.escalao && sel.dias.length > 0 && sel.horario);
}

function set(id, val, empty) {
  const el = document.getElementById(id);
  el.textContent = val || '—';
  el.className = 'r-val' + (empty?' em':'');
}

async function confirmar() {
  const btn = document.getElementById('btnConfirmar');
  btn.disabled = true;
  document.getElementById('spinner').style.display = 'block';
  document.getElementById('btnIcon').style.display = 'none';
  document.getElementById('btnTxt').textContent = 'A processar...';

  // Recolher os IDs de horário de cada dia selecionado
  // (o primeiro dia usa sel.horario_id; os restantes usam o mesmo horário relativo)
  const horarioIds = sel.dias.map(dia => {
    const horasDia = horariosPorDia[dia] || [];
    // Usar o horário selecionado se o dia for o primeiro, ou o mesmo slot horário
    const match = horasDia.find(h => h.id === sel.horario_id)
                || horasDia.find(h => h.hora_inicio === (horariosPorDia[sel.dias[0]]?.find(x => x.id === sel.horario_id)?.hora_inicio))
                || horasDia[0];
    return match ? match.id : '';
  }).filter(Boolean);

  const params = new URLSearchParams({
    clube:      CLUBE,
    plano:      sel.plano?.nome,
    escalao:    sel.escalao?.nome,
    dias:       sel.dias.join(', '),
    hora:       sel.horario,
    preco:      parseFloat(sel.plano?.preco).toFixed(2),
    horario_ids: horarioIds.join(','),
    escalao_id:  sel.escalao?.id || ''
  });
  window.location.href = 'booking-confirmacao.php?' + params;
}
</script>
</body>
</html>
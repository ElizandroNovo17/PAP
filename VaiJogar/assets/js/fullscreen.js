// =============================================
// fullscreen.js — Gestão global do fullscreen
// Incluir em TODAS as páginas antes do </body>
// =============================================

(function () {

  // ── Sai do fullscreen ao navegar para outra página ─────────
  window.addEventListener("beforeunload", () => {
    if (document.fullscreenElement) {
      document.exitFullscreen().catch(() => {});
    }
  });

  // ── Atualiza o ícone do botão conforme o estado ────────────
  document.addEventListener("fullscreenchange", () => {
    const btn  = document.querySelector(".fullscreen-btn i");
    if (!btn) return;
    if (document.fullscreenElement) {
      btn.className = "bi bi-fullscreen-exit";
    } else {
      btn.className = "bi bi-fullscreen";
    }
  });

  // ── Função global para o onclick dos botões ───────────────
  window.toggleFullscreen = function () {
    if (document.fullscreenElement) {
      document.exitFullscreen().catch(() => {});
    } else {
      document.documentElement.requestFullscreen().catch(() => {});
    }
  };

})();
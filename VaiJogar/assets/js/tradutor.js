// ====================== SISTEMA DE TRADUÇÃO ======================
(function() {
  function initTradutor() {
    const langWrap = document.getElementById("langWrap");
    const langMenu = document.getElementById("langMenu");
    const langText = document.getElementById("currentLangText");
    
    if (!langWrap || !langMenu || !langText) {
      console.warn("Elementos do tradutor não encontrados");
      return;
    }
    
    const saved = localStorage.getItem("siteLang") || "pt";

    async function loadLang(lang) {
      try {
        const dict = await (await fetch("lang/" + lang + ".json")).json();

        // ── Expõe globalmente para outros scripts usarem ──
        window._dict = dict;
        window._lang = lang;

        document.querySelectorAll("[data-i18n]").forEach(el => {
          const k = el.getAttribute("data-i18n");
          if (dict[k]) el.innerText = dict[k];
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
        
        langText.textContent = lang.toUpperCase();
        localStorage.setItem("siteLang", lang);

        // ── Dispara evento para páginas que precisam re-renderizar ──
        document.dispatchEvent(new CustomEvent("langChanged", { detail: { lang, dict } }));
        
        if (typeof google !== "undefined" && google.accounts) {
          const localeMap = { pt: "pt-PT", en: "en", es: "es" };
          const btnContainer = document.getElementById("googleBtnContainer");
          if (btnContainer) {
            btnContainer.innerHTML = "";
            google.accounts.id.renderButton(btnContainer, { 
              theme: "outline", size: "large", width: 308, 
              text: "continue_with", locale: localeMap[lang] || "pt-PT" 
            });
          }
        }
      } catch (e) {
        console.error("Erro ao carregar idioma:", e);
      }
    }

    loadLang(saved);

    langWrap.addEventListener("click", e => {
      e.stopPropagation();
      langMenu.classList.toggle("show");
    });

    document.querySelectorAll("#langMenu a").forEach(a => {
      a.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        loadLang(a.dataset.lang);
        langMenu.classList.remove("show");
      });
    });

    document.addEventListener("click", (e) => {
      if (!langWrap.contains(e.target)) langMenu.classList.remove("show");
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initTradutor);
  } else {
    initTradutor();
  }
})();
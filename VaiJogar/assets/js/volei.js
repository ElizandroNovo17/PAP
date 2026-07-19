// ====================== VARIÁVEIS GLOBAIS ======================
const map = L.map("map", { zoomControl: false }).setView([39.5, -8], 7);
L.control.zoom({ position: "bottomright" }).addTo(map);

let clubesVisiveis = [];


// Base layers
const baseLayers = {
    streets: L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"),
    dark: L.tileLayer("https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png"),
    satellite: L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"),
    topo: L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png"),
    hybrid: L.tileLayer("https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}", { subdomains: ["mt0", "mt1", "mt2", "mt3"] }),
    minimal: L.tileLayer("https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png")
};

baseLayers.streets.addTo(map);

// ====================== INPUTS E SUGESTÕES ======================
const placeInput = document.getElementById("place-search");
const placeSuggestions = document.getElementById("place-suggestions");
const searchInput = document.getElementById("search-input");
const suggestions = document.getElementById("suggestions");

// ====================== DIVISÕES E CLUBES ======================
const layersDivisoes = {
    div1: L.layerGroup(),
    div2: L.layerGroup(),
    div3: L.layerGroup()
};

const markersMap = new Map();
document.getElementById("btn-localizacao")?.addEventListener("click", () => {
    navigator.geolocation.getCurrentPosition(pos => {
        userLatLng = [pos.coords.latitude, pos.coords.longitude];

        if (userMarker) map.removeLayer(userMarker);

        userMarker = L.marker(userLatLng, {
            icon: L.icon({
                iconUrl: "assets/images/localizacao.png",
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            })
        }).addTo(map).bindPopup("<strong>Tu estás aqui</strong>").openPopup();

        map.flyTo(userLatLng, 14);
    });
});

let userLatLng = null;
let linhaRota = null;
let placeMarker = null;
let placePolygon = null;
let placeTimeout = null;

// ====================== CLUBES ======================

// 🥇 1ª DIVISÃO – VOLEIBOL MASCULINO
const div1Clubes = [
    { nome: "SL Benfica", coords: [38.7527, -9.1849], logo: "benfica.png", campo: "Pavilhão Fidelidade" },
    { nome: "Sporting CP", coords: [38.7614, -9.1604], logo: "sporting.png", campo: "Pavilhão João Rocha" },
    { nome: "Sporting de Espinho", coords: [41.0072, -8.6410], logo: "espinho.png", campo: "Pavilhão Arquiteto Jerónimo Reis" },
    { nome: "Académica de Espinho", coords: [41.0072, -8.6410], logo: "aespinho.png", campo: "Pavilhão Nave Polivalente" },
    { nome: "Castêlo da Maia GC", coords: [41.2676, -8.6174], logo: "maia.png", campo: "Pavilhão Municipal de Castêlo da Maia" },
    { nome: "Leixões SC", coords: [41.1856, -8.6894], logo: "leixoes.png", campo: "Pavilhão Ilídio Ramos" },
    { nome: "Vitória SC", coords: [41.4425, -8.2962], logo: "vitoria.png", campo: "Pavilhão Unidade Vimaranense" },
    { nome: "VC Viana", coords: [41.6932, -8.8329], logo: "viana.png", campo: "Pavilhão Municipal de Santa Maria Maior" },
    { nome: "Ala de Gondomar", coords: [41.1446, -8.5326], logo: "gondomar.png", campo: "Pavilhão Municipal de Gondomar" },
    { nome: "Académica São Mamede", coords: [32.6507, -16.9084], logo: "mamede.png", campo: "Pavilhão Bartolomeu Perestrelo" },
    { nome: "Clube Atlântico da Madalena", coords: [38.7036, -9.1799], logo: "madalena.png", campo: "Pavilhão da Madalena" },
    { nome: "Santo Tirso", coords: [41.3429, -8.4775], logo: "santotirso.png", campo: "Pavilhão Municipal de Santo Tirso" }
];

// 🥈 2ª DIVISÃO – VOLEIBOL MASCULINO
const div2Clubes = [
    { nome: "Esmoriz GC", coords: [40.9577, -8.6275], logo: "esmoriz.png", campo: "Pavilhão Gimnodesportivo de Esmoriz" },
    { nome: "GC Vilacondense", coords: [41.3520, -8.7431], logo: "vilacondense.png", campo: "Pavilhão Municipal de Vila do Conde" },
    { nome: "GDC Gueifães", coords: [41.2565, -8.6147], logo: "gueifaes.png", campo: "Pavilhão de Gueifães" },
    { nome: "SO Marinhense", coords: [39.7476, -8.9323], logo: "marinhense.png", campo: "Pavilhão Municipal da Marinha Grande" },
    { nome: "SC Caldas", coords: [39.4036, -9.1387], logo: "caldas.png", campo: "Pavilhão Rainha D. Leonor" },
    { nome: "CV Oeiras", coords: [38.6979, -9.3086], logo: "cvo.png", campo: "Pavilhão do Jamor" },
    { nome: "CN Ginástica", coords: [38.6599, -9.2050], logo: "ginastica.png", campo: "Pavilhão da Escola Alfredo da Silva" },
    { nome: "AC Albufeira", coords: [37.0897, -8.2503], logo: "albufeira.png", campo: "Pavilhão Desportivo de Albufeira" }
];


// 🥉 3ª DIVISÃO – VOLEIBOL MASCULINO
const div3Grupo = [
    { nome: "VC Braga", coords: [41.5456, -8.4268], logo: "braga.png", campo: "Pavilhão Gimnodesportivo de Braga" },
    { nome: "AA Coimbra", coords: [40.2033, -8.4103], logo: "coimbra.png", campo: "Pavilhão Multidesportos de Coimbra" },
    { nome: "Figueira VC", coords: [40.1506, -8.8618], logo: "figueira.png", campo: "Pavilhão Galamba Marques" },
    { nome: "Lousã VC", coords: [40.1167, -8.2490], logo: "lousa.png", campo: "Pavilhão Municipal da Lousã" },
    { nome: "Amares Volei", coords: [41.6305, -8.3517], logo: "amares.png", campo: "Pavilhão Municipal de Amares" },
    { nome: "Académica Volei Setúbal", coords: [38.5244, -8.8882], logo: "setubal.png", campo: "Pavilhão Municipal de Setúbal" }
];


// Lista de todos os clubes
const todosOsClubes = [...div1Clubes, ...div2Clubes, ...div3Grupo];

// ====================== FUNÇÕES AUXILIARES ======================
function clubIcon(logo) {
    return L.divIcon({
        className: "club-div-icon",
        html: `
      <div class="club-circle">
        <img src="${logo}" />
      </div>
    `,
        iconSize: [50, 50],
        iconAnchor: [25, 25],
        popupAnchor: [0, -25]
    });
}


function distanciaKm(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

// ====================== FUNÇÃO PARA MARCADORES ======================
function criarMarkerClube(clube, layer) {
    const marker = L.marker(clube.coords, { icon: clubIcon(clube.logo) })
        .bindPopup("") // conteúdo vazio por enquanto
        .on("popupopen", e => {
            const popup = e.popup;
            let txt = "📏 Distância: indisponível";
            if (userLatLng) {
                const km = distanciaKm(userLatLng[0], userLatLng[1], clube.coords[0], clube.coords[1]).toFixed(1);
                txt = `📏 Distância: <strong>${km} km</strong>`;
            }
            popup.setContent(`<strong>${clube.nome}</strong><br>🏟 ${clube.campo}<br>${txt}`);
        });
    marker.addTo(layer);
    markersMap.set(clube.nome.toLowerCase(), marker);
    return marker;
}


// ====================== FUNÇÕES MOSTRAR CLUBES ======================
function mostrarDivisao(clubes, layer, containerId) {

    // Limpa apenas as outras layers
    Object.values(layersDivisoes).forEach(l => {
        map.removeLayer(l);
        l.clearLayers();
    });


    clubesVisiveis = clubes;

    const container = document.getElementById(containerId);
    container.replaceChildren();

    clubes.forEach(c => {
        criarMarkerClube(c, layer);

        const div = document.createElement("div");
        div.innerHTML = `<img src="${c.logo}" width="20"> ${c.nome}`;

        div.onclick = () => {
            map.flyTo(c.coords, 14);
            markersMap.get(c.nome.toLowerCase())?.openPopup();
            criarRotaAte(c.coords);
        };

        container.appendChild(div);
    });

    layer.addTo(map);
}

function mostrarTodosClubes() {
    Object.values(layersDivisoes).forEach(l => {
        map.removeLayer(l);
        l.clearLayers();
    });

    [["div1", div1Clubes], ["div2", div2Clubes], ["div3", div3Grupo]].forEach(([layerKey, clubes]) => {
        const layer = layersDivisoes[layerKey];
        const container = document.getElementById(layerKey + "-list");
        container.replaceChildren();

        clubes.forEach(c => {
            criarMarkerClube(c, layer);

            const div = document.createElement("div");
            div.innerHTML = `<img src="${c.logo}" width="20"> ${c.nome}`;
            div.onclick = () => {
                map.flyTo(c.coords, 14);
                markersMap.get(c.nome.toLowerCase())?.openPopup();
                criarRotaAte(c.coords);
            };
            container.appendChild(div);
        });

        layer.addTo(map);
    });

    clubesVisiveis = todosOsClubes;
}


// ====================== EVENTOS ======================

// Dropdown divisões
document.querySelectorAll(".dropdown .dropbtn").forEach(btn => {
    btn.addEventListener("click", () => {
        const dropdown = btn.closest(".dropdown");
        document.querySelectorAll(".dropdown").forEach(d => { if (d !== dropdown) d.classList.remove("active"); });
        dropdown.classList.toggle("active");

        if (btn.textContent.includes("1ª")) mostrarDivisao(div1Clubes, layersDivisoes.div1, "div1-list");
        if (btn.textContent.includes("2ª")) mostrarDivisao(div2Clubes, layersDivisoes.div2, "div2-list");
        if (btn.textContent.includes("3ª")) mostrarDivisao(div3Grupo, layersDivisoes.div3, "div3-list");
    });
});

// Mostrar todos
document.getElementById("btn-todos").addEventListener("click", mostrarTodosClubes);

// Localização
document.getElementById("btn-localizacao")?.addEventListener("click", () => {
    navigator.geolocation.getCurrentPosition(pos => {
        userLatLng = [pos.coords.latitude, pos.coords.longitude];
        L.marker(userLatLng, {
            icon: L.icon({ iconUrl: "assets/images/localizacao.png", iconSize: [30, 30], iconAnchor: [15, 15] })
        }).addTo(map).bindPopup("<strong>Tu estás aqui</strong>").openPopup();
        map.flyTo(userLatLng, 14);
    });
});

// Base layers
document.querySelectorAll("#map-modes button").forEach(btn => {
    btn.addEventListener("click", () => {
        Object.values(baseLayers).forEach(layer => map.removeLayer(layer));
        baseLayers[btn.dataset.map]?.addTo(map);
        document.querySelectorAll("#map-modes button").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
    });
});

// ====================== PESQUISA CLUBES ======================
searchInput.addEventListener("input", () => {
    const texto = searchInput.value.trim().toLowerCase();
    suggestions.innerHTML = "";

    if (texto.length < 2) return;

    const resultados = clubesVisiveis.filter(c =>
        c.nome.toLowerCase().includes(texto)
    );


    resultados.forEach(c => {
        let distTxt = "";
        if (userLatLng) {
            const km = distanciaKm(
                userLatLng[0],
                userLatLng[1],
                c.coords[0],
                c.coords[1]
            ).toFixed(1);
            distTxt = ` <small>(${km} km)</small>`;
        }

        const div = document.createElement("div");
        div.innerHTML = `<img src="${c.logo}" width="20"> ${c.nome}${distTxt}`;
        div.onclick = () => {
            map.flyTo(c.coords, 14);
            markersMap.get(c.nome.toLowerCase())?.openPopup();

            criarRotaAte(c.coords); // <<< ROTA NA PESQUISA

            suggestions.classList.remove("active");
            searchInput.value = "";
        };

        suggestions.appendChild(div);
    });

    suggestions.classList.toggle("active", resultados.length > 0);
});

// ====================== PESQUISA CIDADES ======================
// ====================== PESQUISA CIDADES / DISTRITOS ======================
placeInput.addEventListener("input", () => {
    const q = placeInput.value.trim();
    placeSuggestions.innerHTML = "";

    if (q.length < 2) {
        placeSuggestions.style.display = "none";
        return;
    }

    clearTimeout(placeTimeout);
    placeTimeout = setTimeout(async () => {
        try {
            const url =
                "https://nominatim.openstreetmap.org/search" +
                `?q=${encodeURIComponent(q)}` +
                "&countrycodes=pt" +
                "&format=json" +
                "&addressdetails=1" +
                "&limit=8";


            const res = await fetch(url, {
                headers: {
                    "Accept": "application/json"
                }
            });

            const data = await res.json();

            placeSuggestions.innerHTML = "";

            data.forEach(p => {
                const a = p.address || {};

                const nome =
                    a.city ||
                    a.town ||
                    a.village ||
                    a.suburb ||
                    a.hamlet ||
                    a.locality ||
                    a.neighbourhood ||
                    a.municipality ||
                    p.display_name.split(",")[0];

                const distrito =
                    a.state_district || a.state || "";

                const label = distrito
                    ? `${nome} (${distrito})`
                    : nome;

                const div = document.createElement("div");
                div.textContent = label;

                div.onclick = () => {
                    if (placeMarker) map.removeLayer(placeMarker);

                    placeMarker = L.marker([p.lat, p.lon])
                        .addTo(map)
                        .bindPopup(`<strong>${label}</strong>`)
                        .openPopup();

                    map.flyTo([p.lat, p.lon], 13);

                    placeSuggestions.innerHTML = "";
                    placeSuggestions.style.display = "none";
                    placeInput.value = "";
                };

                placeSuggestions.appendChild(div);
            });

            placeSuggestions.style.display = data.length ? "block" : "none";
        } catch (err) {
            console.error("Erro na pesquisa de cidades:", err);
        }
    }, 400);
});


// Fechar sugestões ao clicar fora
document.addEventListener("click", e => {
    if (!searchInput.contains(e.target)) {
        suggestions.classList.remove("active");
    }
    if (!placeInput.contains(e.target)) {
        placeSuggestions.classList.remove("active");
    }
});
document.addEventListener("click", e => {
    if (!e.target.closest(".dropdown")) {
        document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("active"));
    }
});
let rotaAtual = null;
const btn = document.createElement("button");
btn.textContent = "❌ Limpar rota";

btn.onclick = () => {

    if (rotaAtual) map.removeControl(rotaAtual);

};
document.body.appendChild(btn);
window.addEventListener("load", () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const userLatLng = [pos.coords.latitude, pos.coords.longitude];
                // Coloque aqui o código para exibir marcador e centralizar o mapa
                if (userMarker) map.removeLayer(userMarker);
                userMarker = L.marker(userLatLng, {
                    icon: L.icon({
                        iconUrl: "assets/images/localizacao.png",
                        iconSize: [30, 30],
                        iconAnchor: [15, 15],
                    }),
                })
                    .addTo(map)
                    .bindPopup("<strong>Tu estás aqui</strong>")
                    .openPopup();

                map.flyTo(userLatLng, 14);
            },
            (err) => {
                console.warn("Permissão negada ou erro na geolocalização:", err.message);
                // Pode colocar um fallback, tipo centralizar em Portugal
            }
        );
    } else {
        console.warn("Geolocalização não suportada pelo navegador.");
    }
});
function criarRotaAte(coordsDestino) {
    if (!userLatLng) return;

    if (rotaAtual) {
        map.removeControl(rotaAtual);
    }

    rotaAtual = L.Routing.control({
        waypoints: [
            L.latLng(userLatLng[0], userLatLng[1]),
            L.latLng(coordsDestino[0], coordsDestino[1])
        ],
        lineOptions: {
            styles: [{ color: "purple", opacity: 0.8, weight: 5 }]
        },
        createMarker: () => null,
        routeWhileDragging: false,
        showAlternatives: false,
        fitSelectedRoutes: true,
        addWaypoints: false,
        show: false
    }).addTo(map);
}
// Os clubes só aparecem quando o utilizador clicar numa divisão ou em "Mostrar todos"
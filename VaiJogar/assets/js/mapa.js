// ====================== MAPA VAIJJOGAR ======================
// Modalidades: Futebol, Basquetebol, Voleibol
// Ao abrir: pede localização e mostra clubes mais próximos
// ============================================================

const map = L.map("map", { zoomControl: false }).setView([39.5, -8], 7);
L.control.zoom({ position: "bottomright" }).addTo(map);

// ====================== BASE LAYERS ======================
const baseLayers = {
  streets:  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"),
  dark:     L.tileLayer("https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png"),
  satellite:L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"),
  topo:     L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png"),
  hybrid:   L.tileLayer("https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}", { subdomains: ["mt0","mt1","mt2","mt3"] }),
  minimal:  L.tileLayer("https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png")
};
baseLayers.streets.addTo(map);

// ====================== ESTADO ======================
let userLatLng        = null;
let linhaRota         = null;
let placeMarker       = null;
let placePolygon      = null;
let placeTimeout      = null;
let userMarker        = null;
let modalidadeAtual   = "futebol";
let clubesVisiveis    = [];
let clubesEstaoVisiveis = false;

const markersMap = new Map();
const layerAtual = L.layerGroup().addTo(map);

// ====================== DADOS — FUTEBOL ======================
const futebol = {
  div1: [
    { nome: "FC Porto",           coords: [41.1621,-8.6216],  logo: "porto.png",           recinto: "Estádio do Dragão" },
    { nome: "Sporting CP",        coords: [38.7614,-9.1604],  logo: "sporting.png",         recinto: "Estádio José Alvalade" },
    { nome: "SL Benfica",         coords: [38.7527,-9.1849],  logo: "benfica.png",          recinto: "Estádio da Luz" },
    { nome: "SC Braga",           coords: [41.5456,-8.4268],  logo: "braga.png",            recinto: "Estádio Municipal de Braga" },
    { nome: "Vitória SC",         coords: [41.4425,-8.2962],  logo: "vitoria.png",          recinto: "D. Afonso Henriques" },
    { nome: "Gil Vicente",        coords: [41.5317,-8.6151],  logo: "gilvicente.png",       recinto: "Cidade de Barcelos" },
    { nome: "FC Famalicão",       coords: [41.4071,-8.5198],  logo: "famalicao.png",        recinto: "22 de Junho" },
    { nome: "Moreirense",         coords: [41.3796,-8.3399],  logo: "moreirense.png",       recinto: "Comendador Joaquim de Almeida Freitas" },
    { nome: "Estoril Praia",      coords: [38.7057,-9.3977],  logo: "estoril.png",          recinto: "António Coimbra da Mota" },
    { nome: "FC Alverca",         coords: [38.8989,-9.0386],  logo: "alverca.png",          recinto: "Estádio FC Alverca" },
    { nome: "Rio Ave",            coords: [41.3499,-8.7390],  logo: "rioave.png",           recinto: "Estádio dos Arcos" },
    { nome: "Nacional",           coords: [32.6666,-16.9245], logo: "nacional.png",         recinto: "Estádio da Madeira" },
    { nome: "Santa Clara",        coords: [37.7710,-25.3126], logo: "santaclara.png",       recinto: "Estádio de São Miguel" },
    { nome: "Estrela da Amadora", coords: [38.7596,-9.2247],  logo: "estreladaamadora.png", recinto: "Estádio José Gomes" },
    { nome: "Casa Pia AC",        coords: [38.7347,-9.1681],  logo: "casapia.png",          recinto: "Pina Manique" },
    { nome: "FC Arouca",          coords: [40.9262,-8.2449],  logo: "arouca.png",           recinto: "Estádio Municipal de Arouca" },
    { nome: "CD Tondela",         coords: [40.5256,-8.2837],  logo: "tondela.png",          recinto: "João Cardoso" },
    { nome: "AFS Futebol SAD",    coords: [40.6560,-7.9129],  logo: "avs.png",              recinto: "Estádio do Fontelo" }
  ],
  div2: [
    { nome: "Marítimo",           coords: [32.6652,-16.9253], logo: "maritimo.png",         recinto: "Estádio dos Barreiros" },
    { nome: "Sporting CP B",      coords: [38.7419,-8.9609],  logo: "sportingb.png",        recinto: "Academia de Alcochete" },
    { nome: "Académico de Viseu", coords: [40.6560,-7.9129],  logo: "acviseu.png",          recinto: "Estádio do Fontelo" },
    { nome: "GD Chaves",          coords: [41.7416,-7.4689],  logo: "chaves.png",           recinto: "Municipal de Chaves" },
    { nome: "Vizela",             coords: [41.3829,-8.3065],  logo: "vizela.png",           recinto: "Estádio do Vizela" },
    { nome: "UD Leiria",          coords: [39.7472,-8.8077],  logo: "udleiria.png",         recinto: "Magalhães Pessoa" },
    { nome: "SC União Torreense", coords: [39.0926,-9.2609],  logo: "uniaotorrense.png",    recinto: "Manuel Marques" },
    { nome: "FC Penafiel",        coords: [41.1477,-8.3920],  logo: "penafiel.png",         recinto: "Municipal de Penafiel" },
    { nome: "FC Felgueiras 1932", coords: [41.2836,-8.2972],  logo: "felgueiras.png",       recinto: "Machado de Matos" },
    { nome: "SC Farense",         coords: [37.0194,-7.9350],  logo: "farense.png",          recinto: "São Luís" },
    { nome: "SL Benfica B",       coords: [38.6401,-9.1015],  logo: "benfica.png",          recinto: "Benfica Campus (Seixal)" },
    { nome: "FC Porto B",         coords: [41.0911,-8.6249],  logo: "porto.png",            recinto: "Centro de Treinos do Olival" },
    { nome: "CD Feirense",        coords: [40.9578,-8.6260],  logo: "feirense.png",         recinto: "Marcolino de Castro" },
    { nome: "Portimonense SAD",   coords: [37.1366,-8.5341],  logo: "portimonense.png",     recinto: "Municipal de Portimão" },
    { nome: "UD Oliveirense",     coords: [40.8380,-8.5100],  logo: "oliveirense.png",      recinto: "Carlos Osório" },
    { nome: "FC Paços de Ferreira",coords:[41.3369,-8.2491],  logo: "paco.png",             recinto: "Capital do Móvel" },
    { nome: "Leixões SC",         coords: [41.1806,-8.6846],  logo: "leixoes.png",          recinto: "Estádio do Mar" },
    { nome: "Lusitânia FC Lourosa",coords:[40.9840,-8.4966],  logo: "lusitania.png",        recinto: "Estádio do Lusitânia" }
  ],
  div3: [
    { nome: "Varzim SC",          coords: [41.3834,-8.7636],  logo: "varzim.png",           recinto: "Estádio do Varzim SC" },
    { nome: "CD Trofense",        coords: [41.3393,-8.5606],  logo: "trofense.png",         recinto: "Estádio do Trofense" },
    { nome: "SC Braga B",         coords: [41.5456,-8.4268],  logo: "braga.png",            recinto: "Cidade Desportiva SC Braga" },
    { nome: "AD Fafe",            coords: [41.4510,-8.1683],  logo: "adfafe.png",           recinto: "Estádio Municipal de Fafe" },
    { nome: "FC Paredes",         coords: [41.2049,-8.3315],  logo: "paredes.png",          recinto: "Estádio Cidade de Paredes" },
    { nome: "Amarante FC",        coords: [41.2722,-8.0826],  logo: "amarante.png",         recinto: "Estádio Municipal de Amarante" },
    { nome: "CD Mafra",           coords: [38.9376,-9.3272],  logo: "mafra.png",            recinto: "Estádio Municipal de Mafra" },
    { nome: "Amora FC",           coords: [38.6207,-9.1151],  logo: "amora.png",            recinto: "Estádio da Medideira" },
    { nome: "CF Os Belenenses",   coords: [38.7068,-9.2146],  logo: "belenenses.png",       recinto: "Estádio do Restelo" },
    { nome: "Académica OAF",      coords: [40.2033,-8.4103],  logo: "aac.png",              recinto: "Estádio Cidade de Coimbra" },
    { nome: "SC Covilhã",         coords: [40.2833,-7.5019],  logo: "covilha.png",          recinto: "Estádio Municipal José Santos Pinto" },
    { nome: "Lusitano GC Évora",  coords: [38.5714,-7.9097],  logo: "lusitano.png",         recinto: "Campo Estrela" }
  ]
};

// ====================== DADOS — BASQUETEBOL ======================
const basquetebol = {
  div1: [
    { nome: "Sporting CP",        coords: [38.7614,-9.1604],  logo: "sporting.png",   recinto: "Pavilhão João Rocha" },
    { nome: "SL Benfica",         coords: [38.7527,-9.1849],  logo: "benfica.png",    recinto: "Pavilhão Fidelidade" },
    { nome: "FC Porto",           coords: [41.1621,-8.6216],  logo: "porto.png",      recinto: "Dragão Arena" },
    { nome: "Ovarense GAVEX",     coords: [40.8616,-8.6256],  logo: "ovarense.png",   recinto: "Arena de Ovar" },
    { nome: "UD Oliveirense",     coords: [40.8380,-8.5100],  logo: "oliveirense.png",recinto: "Pavilhão Dr. Salvador Machado" },
    { nome: "Imortal LUZiGÁS",   coords: [37.0897,-8.2503],  logo: "imortal.png",    recinto: "Pavilhão de Albufeira" },
    { nome: "CA Queluz",          coords: [38.7548,-9.2431],  logo: "queluz.png",     recinto: "Pavilhão Henrique Miranda" },
    { nome: "CP Esgueira",        coords: [40.6415,-8.6499],  logo: "esgueira.png",   recinto: "Pavilhão de Esgueira" },
    { nome: "SC Braga",           coords: [41.5456,-8.4268],  logo: "braga.png",      recinto: "Pavilhão Gimnodesportivo de Braga" },
    { nome: "Vitória SC",         coords: [41.4425,-8.2962],  logo: "vitoria.png",    recinto: "Pavilhão Unidade Vimaranense" },
    { nome: "SC Vasco da Gama",   coords: [38.5903,-9.0769],  logo: "vasco.png",      recinto: "Pavilhão Vasco da Gama" },
    { nome: "Galitos Barreiro",   coords: [38.6523,-9.0759],  logo: "galitos.png",    recinto: "Pavilhão do Galitos" }
  ],
  div2: [
    { nome: "Illiabum Clube",     coords: [40.6019,-8.6700],  logo: "illiabum.png",   recinto: "Pavilhão Capitão Adriano Nordeste" },
    { nome: "Sangalhos DC",       coords: [40.4869,-8.4696],  logo: "sangalhos.png",  recinto: "Pavilhão de Sangalhos" },
    { nome: "Maia Basket",        coords: [41.2357,-8.6199],  logo: "maia.png",       recinto: "Pavilhão Municipal da Maia" },
    { nome: "Guifões SC",         coords: [41.2049,-8.6656],  logo: "guifoes.png",    recinto: "Pavilhão de Guifões" },
    { nome: "CAB Madeira",        coords: [32.6669,-16.9241], logo: "madeira.png",    recinto: "Pavilhão do Funchal" },
    { nome: "SC Lusitânia",       coords: [38.6561,-27.2167], logo: "lusitania.png",  recinto: "Pavilhão Municipal de Angra" },
    { nome: "Gaeirense Basket",   coords: [39.3534,-9.1577],  logo: "gaeirense.png",  recinto: "Pavilhão das Gaeiras" },
    { nome: "Belenenses",         coords: [38.7036,-9.2057],  logo: "belenenses.png", recinto: "Pavilhão Acácio Rosa" }
  ],
  div3: [
    { nome: "Académica de Coimbra",coords:[40.2033,-8.4103],  logo: "coimbra.png",    recinto: "Pavilhão Multidesportos" },
    { nome: "Ginásio Figueirense", coords:[40.1506,-8.8618],  logo: "figueirense.png",recinto: "Pavilhão Galamba Marques" },
    { nome: "Basket Santo André",  coords:[38.0600,-8.7820],  logo: "andre.png",      recinto: "Pavilhão Municipal de Santo André" },
    { nome: "Viana Basket",        coords:[41.6932,-8.8329],  logo: "viana.png",      recinto: "Pavilhão Municipal de Viana" },
    { nome: "Física de Torres Vedras",coords:[39.0915,-9.2586],logo:"vedras.png",     recinto: "Pavilhão da Física" }
  ]
};

// ====================== DADOS — VOLEIBOL ======================
const voleibol = {
  div1: [
    { nome: "SL Benfica",             coords: [38.7527,-9.1849],  logo: "benfica.png",   recinto: "Pavilhão Fidelidade" },
    { nome: "Sporting CP",            coords: [38.7614,-9.1604],  logo: "sporting.png",  recinto: "Pavilhão João Rocha" },
    { nome: "Sporting de Espinho",    coords: [41.0072,-8.6410],  logo: "espinho.png",   recinto: "Pavilhão Arquiteto Jerónimo Reis" },
    { nome: "Académica de Espinho",   coords: [41.0072,-8.6410],  logo: "aespinho.png",  recinto: "Pavilhão Nave Polivalente" },
    { nome: "Castêlo da Maia GC",     coords: [41.2676,-8.6174],  logo: "maia.png",      recinto: "Pavilhão Municipal de Castêlo da Maia" },
    { nome: "Leixões SC",             coords: [41.1856,-8.6894],  logo: "leixoes.png",   recinto: "Pavilhão Ilídio Ramos" },
    { nome: "Vitória SC",             coords: [41.4425,-8.2962],  logo: "vitoria.png",   recinto: "Pavilhão Unidade Vimaranense" },
    { nome: "VC Viana",               coords: [41.6932,-8.8329],  logo: "viana.png",     recinto: "Pavilhão Municipal de Santa Maria Maior" },
    { nome: "Ala de Gondomar",        coords: [41.1446,-8.5326],  logo: "gondomar.png",  recinto: "Pavilhão Municipal de Gondomar" },
    { nome: "Académica São Mamede",   coords: [32.6507,-16.9084], logo: "mamede.png",    recinto: "Pavilhão Bartolomeu Perestrelo" },
    { nome: "Clube Atlântico Madalena",coords:[38.7036,-9.1799],  logo: "madalena.png",  recinto: "Pavilhão da Madalena" },
    { nome: "Santo Tirso",            coords: [41.3429,-8.4775],  logo: "santotirso.png",recinto: "Pavilhão Municipal de Santo Tirso" }
  ],
  div2: [
    { nome: "Esmoriz GC",             coords: [40.9577,-8.6275],  logo: "esmoriz.png",       recinto: "Pavilhão Gimnodesportivo de Esmoriz" },
    { nome: "GC Vilacondense",        coords: [41.3520,-8.7431],  logo: "vilacondense.png",  recinto: "Pavilhão Municipal de Vila do Conde" },
    { nome: "GDC Gueifães",           coords: [41.2565,-8.6147],  logo: "gueifaes.png",      recinto: "Pavilhão de Gueifães" },
    { nome: "SO Marinhense",          coords: [39.7476,-8.9323],  logo: "marinhense.png",    recinto: "Pavilhão Municipal da Marinha Grande" },
    { nome: "SC Caldas",              coords: [39.4036,-9.1387],  logo: "caldas.png",        recinto: "Pavilhão Rainha D. Leonor" },
    { nome: "CV Oeiras",              coords: [38.6979,-9.3086],  logo: "cvo.png",           recinto: "Pavilhão do Jamor" },
    { nome: "CN Ginástica",           coords: [38.6599,-9.2050],  logo: "ginastica.png",     recinto: "Pavilhão da Escola Alfredo da Silva" },
    { nome: "AC Albufeira",           coords: [37.0897,-8.2503],  logo: "albufeira.png",     recinto: "Pavilhão Desportivo de Albufeira" }
  ],
  div3: [
    { nome: "VC Braga",               coords: [41.5456,-8.4268],  logo: "braga.png",         recinto: "Pavilhão Gimnodesportivo de Braga" },
    { nome: "AA Coimbra",             coords: [40.2033,-8.4103],  logo: "coimbra.png",        recinto: "Pavilhão Multidesportos de Coimbra" },
    { nome: "Figueira VC",            coords: [40.1506,-8.8618],  logo: "figueira.png",       recinto: "Pavilhão Galamba Marques" },
    { nome: "Lousã VC",               coords: [40.1167,-8.2490],  logo: "lousa.png",          recinto: "Pavilhão Municipal da Lousã" },
    { nome: "Amares Volei",           coords: [41.6305,-8.3517],  logo: "amares.png",         recinto: "Pavilhão Municipal de Amares" },
    { nome: "Académica Volei Setúbal",coords: [38.5244,-8.8882],  logo: "setubal.png",        recinto: "Pavilhão Municipal de Setúbal" }
  ],
  div4: [
    { nome: "VC Sintra",              coords: [38.8007,-9.3839],  logo: "sintra.png",         recinto: "Pavilhão Municipal de Sintra" },
    { nome: "GD Carcavelos",          coords: [38.6928,-9.3364],  logo: "carcavelos.png",     recinto: "Pavilhão de Carcavelos" },
    { nome: "Juventude de Évora VC",  coords: [38.5714,-7.9097],  logo: "evora.png",          recinto: "Pavilhão Municipal de Évora" },
    { nome: "VC Setúbal",             coords: [38.5244,-8.8882],  logo: "setubalvc.png",      recinto: "Pavilhão António Capucho" },
    { nome: "GD Sesimbra VC",         coords: [38.4440,-9.1009],  logo: "sesimbra.png",       recinto: "Pavilhão Municipal de Sesimbra" },
    { nome: "VC Torres Novas",        coords: [39.4804,-8.5402],  logo: "torresnovas.png",    recinto: "Pavilhão Municipal de Torres Novas" },
    { nome: "CR Boavista VC",         coords: [38.7330,-9.1620],  logo: "boavistavc.png",     recinto: "Pavilhão do Boavista" }
  ]
};

// ====================== DADOS — FUTEBOL REGIONAL (div4) ======================
futebol.div4 = [
  // Grande Lisboa
  { nome: "Sintrense FC",           coords: [38.7991,-9.3857],  logo: "sintrense.png",      recinto: "Estádio Municipal de Sintra" },
  { nome: "GD Estoril-Praia B",    coords: [38.7057,-9.3977],  logo: "estoril.png",        recinto: "Complexo Desportivo do Estoril" },
  { nome: "CD Carcavelos",          coords: [38.6928,-9.3364],  logo: "carcavelos.png",     recinto: "Campo do Carcavelos" },
  { nome: "SC Algesirense",         coords: [38.7400,-9.1600],  logo: "alges.png",          recinto: "Campo de Algés" },
  { nome: "FC Alverca B",           coords: [38.8989,-9.0386],  logo: "alverca.png",        recinto: "Complexo do Alverca" },
  { nome: "GD Mafra B",             coords: [38.9376,-9.3272],  logo: "mafra.png",          recinto: "Municipal de Mafra" },
  { nome: "SC Lourel",              coords: [38.7700,-9.3800],  logo: "lourel.png",         recinto: "Campo do Lourel" },
  { nome: "AD Camarate",            coords: [38.8110,-9.1200],  logo: "camarate.png",       recinto: "Campo da Camarate" },
  { nome: "GS Loures",              coords: [38.8330,-9.1680],  logo: "loures.png",         recinto: "Estádio Municipal de Loures" },
  { nome: "CD Odivelas",            coords: [38.7920,-9.1850],  logo: "odivelas.png",       recinto: "Campo da Odivelas" },
  { nome: "FC Sacavém",             coords: [38.8020,-9.1060],  logo: "sacavem.png",        recinto: "Campo de Sacavém" },
  { nome: "SU Sintrense",           coords: [38.8100,-9.3760],  logo: "susintrense.png",    recinto: "Campo da União de Sintra" },
  // Setúbal / Sesimbra
  { nome: "GD Sesimbra",            coords: [38.4440,-9.1009],  logo: "sesimbra.png",       recinto: "Campo Municipal de Sesimbra" },
  { nome: "AD Seixal",              coords: [38.6440,-9.1000],  logo: "seixal.png",         recinto: "Campo do Seixal" },
  { nome: "SC Pinhalnovense",        coords: [38.9791,-8.9148],  logo: "pinhalnovo.png",     recinto: "Campo do Pinhal Novo" },
  { nome: "CD Palmela",             coords: [38.5650,-8.9000],  logo: "palmela.png",        recinto: "Campo Municipal de Palmela" },
  { nome: "FC Barreiro",            coords: [38.6600,-9.0720],  logo: "barreiro.png",       recinto: "Estádio do Barreiro" },
  // Porto / Norte
  { nome: "Vila Fria FC",           coords: [41.4720,-8.4100],  logo: "vilafria.png",       recinto: "Campo de Vila Fria" },
  { nome: "SC Caldas",              coords: [39.4036,-9.1387],  logo: "caldas.png",         recinto: "Estádio do Caldas SC" },
  { nome: "CD Miramar",             coords: [41.0900,-8.6760],  logo: "miramar.png",        recinto: "Campo de Miramar" },
  { nome: "SC Espinho",             coords: [41.0119,-8.6400],  logo: "espinho.png",        recinto: "Estádio Municipal de Espinho" },
  { nome: "FC São João de Ver",     coords: [40.9840,-8.5300],  logo: "sjoaover.png",       recinto: "Campo de São João de Ver" },
  { nome: "GD Gondomar",            coords: [41.1446,-8.5326],  logo: "gondomar.png",       recinto: "Campo Municipal de Gondomar" },
  { nome: "SC Salgueiros",          coords: [41.1700,-8.6000],  logo: "salgueiros.png",     recinto: "Campo do Salgueiros" },
  { nome: "FC Pedras Rubras",       coords: [41.2400,-8.6900],  logo: "pedrasrubras.png",   recinto: "Campo de Pedras Rubras" },
  { nome: "AD Canelas",             coords: [41.0780,-8.5700],  logo: "canelas.png",        recinto: "Campo de Canelas" },
  { nome: "CD Penafiel B",          coords: [41.1477,-8.3920],  logo: "penafiel.png",       recinto: "Campo do Penafiel" },
  { nome: "SC Real",                coords: [41.5900,-8.5200],  logo: "screal.png",         recinto: "Campo de Real" },
  // Centro / Coimbra
  { nome: "AD Oliveirinha",         coords: [40.5900,-8.4600],  logo: "oliveirinha.png",    recinto: "Campo da Oliveirinha" },
  { nome: "Atlético de Cacia",      coords: [40.6850,-8.5700],  logo: "cacia.png",          recinto: "Campo de Cacia" },
  { nome: "SC Pombal",              coords: [39.9150,-8.6300],  logo: "pombal.png",         recinto: "Campo Municipal de Pombal" },
  { nome: "CD Ílhavo",              coords: [40.6000,-8.6600],  logo: "ilhavo.png",         recinto: "Campo Municipal de Ílhavo" },
  { nome: "GD Bairrada",            coords: [40.4500,-8.4400],  logo: "bairrada.png",       recinto: "Campo da Bairrada" },
  // Algarve
  { nome: "SC Olhanense",           coords: [37.0250,-7.8400],  logo: "olhanense.png",      recinto: "Estádio José Arcanjo" },
  { nome: "Louletano DC",           coords: [37.1360,-8.0200],  logo: "louletano.png",      recinto: "Campo do Louletano" },
  { nome: "CD Lagos",               coords: [37.1020,-8.6750],  logo: "lagos.png",          recinto: "Campo Municipal de Lagos" },
  { nome: "AD Lagoa",               coords: [37.1350,-8.4600],  logo: "lagoa.png",          recinto: "Campo Municipal de Lagoa" },
  // Alentejo
  { nome: "GD Beja",                coords: [38.0160,-7.8650],  logo: "beja.png",           recinto: "Campo Municipal de Beja" },
  { nome: "Lusitano FC Évora",      coords: [38.5714,-7.9097],  logo: "lusitano.png",       recinto: "Campo do Lusitano" },
  { nome: "CD Vilafranquense",      coords: [38.9500,-8.9800],  logo: "vilafranquense.png", recinto: "Campo Municipal de Vila Franca" },
  // Madeira / Açores
  { nome: "CS Marítimo B",          coords: [32.6652,-16.9253], logo: "maritimo.png",       recinto: "Estádio dos Barreiros" },
  { nome: "CD Operário de Lagoa",   coords: [37.7500,-25.4900], logo: "operario.png",       recinto: "Campo do Lagoa" }
];

// ====================== DADOS — BASQUETEBOL REGIONAL (div4) ======================
basquetebol.div4 = [
  { nome: "CB Sintra",              coords: [38.8007,-9.3839],  logo: "sintra.png",         recinto: "Pavilhão Municipal de Sintra" },
  { nome: "CB Carcavelos",          coords: [38.6928,-9.3364],  logo: "carcavelos.png",     recinto: "Pavilhão de Carcavelos" },
  { nome: "GD Sesimbra Basket",     coords: [38.4440,-9.1009],  logo: "sesimbra.png",       recinto: "Pavilhão Municipal de Sesimbra" },
  { nome: "AD Seixal Basket",       coords: [38.6440,-9.1000],  logo: "seixal.png",         recinto: "Pavilhão do Seixal" },
  { nome: "Basket Almada",          coords: [38.6800,-9.1600],  logo: "almada.png",         recinto: "Pavilhão Municipal de Almada" },
  { nome: "CB Torres Vedras",       coords: [39.0915,-9.2586],  logo: "vedras.png",         recinto: "Pavilhão de Torres Vedras" },
  { nome: "CD Entroncamento Basket",coords: [39.4670,-8.4680],  logo: "entroncamento.png",  recinto: "Pavilhão Municipal do Entroncamento" },
  { nome: "CB Vila Nova Gaia",      coords: [41.1330,-8.6100],  logo: "gaia.png",           recinto: "Pavilhão Municipal de Gaia" },
  { nome: "SC Espinho Basket",      coords: [41.0119,-8.6400],  logo: "espinho.png",        recinto: "Pavilhão de Espinho" },
  { nome: "CB Felgueiras",          coords: [41.3600,-8.2000],  logo: "felgueiras.png",     recinto: "Pavilhão Municipal de Felgueiras" },
  { nome: "AD Oliveira do Bairro",  coords: [40.5000,-8.4900],  logo: "oliveirabairro.png", recinto: "Pavilhão Municipal de Oliveira do Bairro" },
  { nome: "CB Viseu",               coords: [40.6560,-7.9129],  logo: "viseu.png",          recinto: "Pavilhão Municipal de Viseu" },
  { nome: "CB Évora",               coords: [38.5714,-7.9097],  logo: "evora.png",          recinto: "Pavilhão Municipal de Évora" },
  { nome: "Basket Portimão",        coords: [37.1366,-8.5341],  logo: "portimao.png",       recinto: "Pavilhão Desportivo de Portimão" },
  { nome: "CB Faro",                coords: [37.0200,-7.9350],  logo: "faro.png",           recinto: "Pavilhão Municipal de Faro" }
];

const dados = { futebol, basquetebol, voleibol };

// ====================== LABELS DAS DIVISÕES ======================
const labelsDiv = {
  futebol:     { div1: "1ª Divisão Nacional", div2: "2ª Divisão Nacional", div3: "3ª Divisão Nacional",    div4: "Distrital / Regional" },
  basquetebol: { div1: "Liga Portuguesa",     div2: "2ª Divisão",          div3: "3ª Divisão / Regional",  div4: "Distrital / Regional" },
  voleibol:    { div1: "Liga A",              div2: "Liga B",               div3: "2ª Divisão Nacional",    div4: "Distrital / Regional" }
};

// ====================== UTILITÁRIOS ======================
function distanciaKm(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// URLs das logos — Wikimedia /thumb/ (carregam sem problemas no browser)
// ── Logos via zerozero.pt (futebol) + Wikimedia (basket/volei) ──
// zerozero.pt usa IDs numéricos por equipa — URLs diretas e estáveis
const logoUrls = {
  // ── FUTEBOL — zerozero.pt ──────────────────────────────────
  "FC Porto":              "https://cdn.zerozero.pt/equipa.100.png",
  "Sporting CP":           "https://cdn.zerozero.pt/equipa.16.png",
  "SL Benfica":            "https://cdn.zerozero.pt/equipa.13.png",
  "SC Braga":              "https://cdn.zerozero.pt/equipa.120.png",
  "Vitória SC":            "https://cdn.zerozero.pt/equipa.157.png",
  "Gil Vicente":           "https://cdn.zerozero.pt/equipa.181.png",
  "FC Famalicão":          "https://cdn.zerozero.pt/equipa.168.png",
  "Moreirense":            "https://cdn.zerozero.pt/equipa.182.png",
  "Estoril Praia":         "https://cdn.zerozero.pt/equipa.154.png",
  "FC Alverca":            "https://cdn.zerozero.pt/equipa.4992.png",
  "Rio Ave":               "https://cdn.zerozero.pt/equipa.107.png",
  "Nacional":              "https://cdn.zerozero.pt/equipa.104.png",
  "Santa Clara":           "https://cdn.zerozero.pt/equipa.253.png",
  "Estrela da Amadora":    "https://cdn.zerozero.pt/equipa.14.png",
  "Casa Pia AC":           "https://cdn.zerozero.pt/equipa.163.png",
  "FC Arouca":             "https://cdn.zerozero.pt/equipa.235.png",
  "CD Tondela":            "https://cdn.zerozero.pt/equipa.246.png",
  "AFS Futebol SAD":       "https://cdn.zerozero.pt/equipa.9444.png",
  "Marítimo":              "https://cdn.zerozero.pt/equipa.103.png",
  "Sporting CP B":         "https://cdn.zerozero.pt/equipa.16.png",
  "Académico de Viseu":    "https://cdn.zerozero.pt/equipa.341.png",
  "GD Chaves":             "https://cdn.zerozero.pt/equipa.174.png",
  "Vizela":                "https://cdn.zerozero.pt/equipa.255.png",
  "UD Leiria":             "https://cdn.zerozero.pt/equipa.117.png",
  "SC União Torreense":    "https://cdn.zerozero.pt/equipa.1119.png",
  "FC Penafiel":           "https://cdn.zerozero.pt/equipa.143.png",
  "FC Felgueiras 1932":    "https://cdn.zerozero.pt/equipa.362.png",
  "SC Farense":            "https://cdn.zerozero.pt/equipa.155.png",
  "SL Benfica B":          "https://cdn.zerozero.pt/equipa.13.png",
  "FC Porto B":            "https://cdn.zerozero.pt/equipa.100.png",
  "CD Feirense":           "https://cdn.zerozero.pt/equipa.176.png",
  "Portimonense SAD":      "https://cdn.zerozero.pt/equipa.188.png",
  "UD Oliveirense":        "https://cdn.zerozero.pt/equipa.186.png",
  "FC Paços de Ferreira":  "https://cdn.zerozero.pt/equipa.141.png",
  "Leixões SC":            "https://cdn.zerozero.pt/equipa.118.png",
  "Lusitânia FC Lourosa":  "https://upload.wikimedia.org/wikipedia/pt/thumb/5/53/Lusi_FC.png/120px-Lusi_FC.png",
  "Varzim SC":             "https://cdn.zerozero.pt/equipa.156.png",
  "CD Trofense":           "https://cdn.zerozero.pt/equipa.153.png",
  "SC Braga B":            "https://cdn.zerozero.pt/equipa.120.png",
  "AD Fafe":               "https://cdn.zerozero.pt/equipa.175.png",
  "FC Paredes":            "https://cdn.zerozero.pt/equipa.1132.png",
  "Amarante FC":           "https://cdn.zerozero.pt/equipa.1274.png",
  "CD Mafra":              "https://cdn.zerozero.pt/equipa.1064.png",
  "Amora FC":              "https://cdn.zerozero.pt/equipa.1238.png",
  "CF Os Belenenses":      "https://cdn.zerozero.pt/equipa.17.png",
  "Académica OAF":         "https://cdn.zerozero.pt/equipa.102.png",
  "SC Covilhã":            "https://cdn.zerozero.pt/equipa.172.png",
  "Lusitano GC Évora":     "https://cdn.zerozero.pt/equipa.1148.png",
  "SU 1º Dezembro": "https://upload.wikimedia.org/wikipedia/pt/thumb/3/3d/SU_1%C2%BA_Dezembro.png/120px-SU_1%C2%BA_Dezembro.png",
  "Caldas SC": "https://upload.wikimedia.org/wikipedia/pt/thumb/6/65/SC_Caldas.png/120px-SC_Caldas.png",
  "Anadia FC": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5f/Anadia_FC.png/120px-Anadia_FC.png",
  "AD Sanjoanense": "https://upload.wikimedia.org/wikipedia/pt/thumb/c/c4/AD_Sanjoanense.png/120px-AD_Sanjoanense.png",
  "Atlético CP": "https://upload.wikimedia.org/wikipedia/pt/thumb/e/e8/Atl%C3%A9tico_CP.png/120px-Atl%C3%A9tico_CP.png",
  "AD Marco 09": "https://upload.wikimedia.org/wikipedia/pt/thumb/f/f0/AD_Marco.png/120px-AD_Marco.png",
  "SC São João de Ver": "https://upload.wikimedia.org/wikipedia/pt/thumb/a/a5/SC_S%C3%A3o_Jo%C3%A3o_de_Ver.png/120px-SC_S%C3%A3o_Jo%C3%A3o_de_Ver.png",
  "SG Sacavenense": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5b/SG_Sacavenense.png/120px-SG_Sacavenense.png",
  "GD Bragança": "https://upload.wikimedia.org/wikipedia/pt/thumb/0/0a/GD_Bragan%C3%A7a.png/120px-GD_Bragan%C3%A7a.png",
  "SC Mirandela": "https://upload.wikimedia.org/wikipedia/pt/thumb/9/9e/SC_Mirandela.png/120px-SC_Mirandela.png",
  "SC Espinho": "https://upload.wikimedia.org/wikipedia/pt/thumb/2/22/SC_Espinho.png/120px-SC_Espinho.png",
  "UD Santarém": "https://upload.wikimedia.org/wikipedia/pt/thumb/4/41/UD_Santar%C3%A9m.png/120px-UD_Santar%C3%A9m.png",
  "FC Barreirense": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5b/FC_Barreirense.png/120px-FC_Barreirense.png",
  "SC Olhanense": "https://upload.wikimedia.org/wikipedia/pt/thumb/2/20/SC_Olhanense.png/120px-SC_Olhanense.png",
  "Louletano DC": "https://upload.wikimedia.org/wikipedia/pt/thumb/3/36/Louletano_DC.png/120px-Louletano_DC.png",
  "GD Beja": "https://upload.wikimedia.org/wikipedia/pt/thumb/8/8d/GD_Beja.png/120px-GD_Beja.png",
  "UD Vilafranquense": "https://upload.wikimedia.org/wikipedia/pt/thumb/8/83/UD_Vilafranquense.png/120px-UD_Vilafranquense.png",
  "Ovarense GAVEX": "https://upload.wikimedia.org/wikipedia/pt/thumb/a/a1/Ovarense.png/120px-Ovarense.png",
  "Imortal LUZiGÁS": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5f/Imortal_DC.png/120px-Imortal_DC.png",
  "CA Queluz": "https://upload.wikimedia.org/wikipedia/pt/thumb/4/4f/CA_Queluz.png/120px-CA_Queluz.png",
  "CP Esgueira": "https://upload.wikimedia.org/wikipedia/pt/thumb/9/9f/CP_Esgueira.png/120px-CP_Esgueira.png",
  "SC Vasco da Gama": "https://upload.wikimedia.org/wikipedia/pt/thumb/b/b1/SC_Vasco_da_Gama_Seixal.png/120px-SC_Vasco_da_Gama_Seixal.png",
  "Galitos Barreiro": "https://upload.wikimedia.org/wikipedia/pt/thumb/1/1f/Galitos_Barreiro.png/120px-Galitos_Barreiro.png",
  "Illiabum Clube": "https://upload.wikimedia.org/wikipedia/pt/thumb/7/71/Illiabum_Clube.png/120px-Illiabum_Clube.png",
  "Sangalhos DC": "https://upload.wikimedia.org/wikipedia/pt/thumb/3/38/DC_Sangalhos.png/120px-DC_Sangalhos.png",
  "Maia Basket": "https://upload.wikimedia.org/wikipedia/pt/thumb/b/b5/Castelo_da_Maia_GC.png/120px-Castelo_da_Maia_GC.png",
  "Guifões SC": "https://upload.wikimedia.org/wikipedia/pt/thumb/6/65/Guif%C3%B5es_SC.png/120px-Guif%C3%B5es_SC.png",
  "CAB Madeira": "https://upload.wikimedia.org/wikipedia/pt/thumb/1/14/CAB_Madeira.png/120px-CAB_Madeira.png",
  "SC Lusitânia": "https://upload.wikimedia.org/wikipedia/pt/thumb/f/f6/SC_Lusit%C3%A2nia_Angra.png/120px-SC_Lusit%C3%A2nia_Angra.png",
  "Gaeirense Basket": "https://upload.wikimedia.org/wikipedia/pt/thumb/0/07/Gaeirense_BC.png/120px-Gaeirense_BC.png",
  "Belenenses": "https://upload.wikimedia.org/wikipedia/pt/thumb/7/73/CF_Belenenses.png/120px-CF_Belenenses.png",
  "Académica de Coimbra": "https://upload.wikimedia.org/wikipedia/pt/thumb/7/71/Acad%C3%A9mica_de_Coimbra.png/120px-Acad%C3%A9mica_de_Coimbra.png",
  "Ginásio Figueirense": "https://upload.wikimedia.org/wikipedia/pt/thumb/6/64/Gin%C3%A1sio_Clube_Figueirense.png/120px-Gin%C3%A1sio_Clube_Figueirense.png",
  "Basket Santo André": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5a/Basket_Santo_Andr%C3%A9.png/120px-Basket_Santo_Andr%C3%A9.png",
  "Viana Basket": "https://upload.wikimedia.org/wikipedia/pt/thumb/c/c9/Viana_Basket.png/120px-Viana_Basket.png",
  "Sporting de Espinho": "https://upload.wikimedia.org/wikipedia/pt/thumb/c/c3/Sporting_Clube_de_Espinho.png/120px-Sporting_Clube_de_Espinho.png",
  "Académica de Espinho": "https://upload.wikimedia.org/wikipedia/pt/thumb/8/88/Acad%C3%A9mica_de_Espinho.png/120px-Acad%C3%A9mica_de_Espinho.png",
  "Castêlo da Maia GC": "https://upload.wikimedia.org/wikipedia/pt/thumb/b/b5/Castelo_da_Maia_GC.png/120px-Castelo_da_Maia_GC.png",
  "VC Viana": "https://upload.wikimedia.org/wikipedia/pt/thumb/c/c9/Viana_Basket.png/120px-Viana_Basket.png",
  "Ala de Gondomar": "https://upload.wikimedia.org/wikipedia/pt/thumb/e/ef/GD_Gondomar.png/120px-GD_Gondomar.png",
  "Académica São Mamede": "https://upload.wikimedia.org/wikipedia/pt/thumb/9/9d/Acad%C3%A9mica_de_S%C3%A3o_Mamede.png/120px-Acad%C3%A9mica_de_S%C3%A3o_Mamede.png",
  "Clube Atlântico Madalena": "https://upload.wikimedia.org/wikipedia/pt/thumb/a/a2/Clube_Atl%C3%A2ntico_da_Madalena.png/120px-Clube_Atl%C3%A2ntico_da_Madalena.png",
  "Santo Tirso": "https://upload.wikimedia.org/wikipedia/pt/thumb/d/d0/SC_Santo_Tirso.png/120px-SC_Santo_Tirso.png",
  "Esmoriz GC": "https://upload.wikimedia.org/wikipedia/pt/thumb/b/b9/Esmoriz_GC.png/120px-Esmoriz_GC.png",
  "GC Vilacondense": "https://upload.wikimedia.org/wikipedia/pt/thumb/1/1c/GC_Vilacondense.png/120px-GC_Vilacondense.png",
  "GDC Gueifães": "https://upload.wikimedia.org/wikipedia/pt/thumb/2/2e/GDC_Gueif%C3%A3es.png/120px-GDC_Gueif%C3%A3es.png",
  "SO Marinhense": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/57/SO_Marinhense.png/120px-SO_Marinhense.png",
  "SC Caldas": "https://upload.wikimedia.org/wikipedia/pt/thumb/6/65/SC_Caldas.png/120px-SC_Caldas.png",
  "CV Oeiras": "https://upload.wikimedia.org/wikipedia/pt/thumb/9/99/CV_Oeiras.png/120px-CV_Oeiras.png",
  "CN Ginástica": "https://upload.wikimedia.org/wikipedia/pt/thumb/3/3f/CN_Gin%C3%A1stica.png/120px-CN_Gin%C3%A1stica.png",
  "AC Albufeira": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5b/AC_Albufeira.png/120px-AC_Albufeira.png",
  "VC Braga": "https://upload.wikimedia.org/wikipedia/pt/thumb/0/07/SC_Braga.png/120px-SC_Braga.png",
  "AA Coimbra": "https://upload.wikimedia.org/wikipedia/pt/thumb/7/71/Acad%C3%A9mica_de_Coimbra.png/120px-Acad%C3%A9mica_de_Coimbra.png",
  "Figueira VC": "https://upload.wikimedia.org/wikipedia/pt/thumb/6/6f/Figueira_VC.png/120px-Figueira_VC.png",
  "Lousã VC": "https://upload.wikimedia.org/wikipedia/pt/thumb/6/6a/Lous%C3%A3_VC.png/120px-Lous%C3%A3_VC.png",
  "Amares Volei": "https://upload.wikimedia.org/wikipedia/pt/thumb/9/9f/Amares_Volei.png/120px-Amares_Volei.png",
  "Académica Volei Setúbal": "https://upload.wikimedia.org/wikipedia/pt/thumb/2/2a/Acad%C3%A9mica_Vol%C3%AAi_Set%C3%BAbal.png/120px-Acad%C3%A9mica_Vol%C3%AAi_Set%C3%BAbal.png",
  "Física de Torres Vedras": "https://upload.wikimedia.org/wikipedia/pt/thumb/5/5f/AD_F%C3%ADsica.png/120px-AD_F%C3%ADsica.png",

  // ── Liga Portugal — URLs diretas Wikimedia ──────────────────
  "FC Porto":             "https://cdn-img.staticzz.com/img/logos/equipas/9_imgbank_1728921003.png",
  "Sporting CP":          "https://cdn-img.staticzz.com/img/logos/equipas/16_imgbank_1741687081.png",
  "SL Benfica":           "https://cdn-img.staticzz.com/img/logos/equipas/4_imgbank_1683238034.png",
  "SC Braga":             "https://upload.wikimedia.org/wikipedia/pt/thumb/d/d5/Sporting_Clube_de_Braga.png/200px-Sporting_Clube_de_Braga.png",
  "Vitória SC":           "https://upload.wikimedia.org/wikipedia/pt/thumb/f/f7/Vit%C3%B3ria_SC.png/200px-Vit%C3%B3ria_SC.png",
  "Gil Vicente":          "https://upload.wikimedia.org/wikipedia/pt/thumb/2/27/Gil_Vicente_FC.png/200px-Gil_Vicente_FC.png",
  "FC Famalicão":         "https://upload.wikimedia.org/wikipedia/pt/thumb/9/9e/FC_Famalic%C3%A3o.png/200px-FC_Famalic%C3%A3o.png",
  "Moreirense":           "https://upload.wikimedia.org/wikipedia/pt/thumb/7/7d/Moreirense_FC.png/200px-Moreirense_FC.png",
  "Estoril Praia":        "https://cdn-img.staticzz.com/img/logos/equipas/1734_imgbank_1682584220.png",
  "FC Alverca":           "https://upload.wikimedia.org/wikipedia/pt/thumb/5/57/FC_Alverca.png/200px-FC_Alverca.png",
  "Rio Ave":              "https://upload.wikimedia.org/wikipedia/pt/thumb/5/58/Rio_Ave_FC.png/200px-Rio_Ave_FC.png",
  "Nacional":             "https://upload.wikimedia.org/wikipedia/pt/thumb/6/6b/CD_Nacional.png/200px-CD_Nacional.png",
  "Santa Clara":          "https://upload.wikimedia.org/wikipedia/pt/thumb/e/eb/CD_Santa_Clara.png/200px-CD_Santa_Clara.png",
  "Estrela da Amadora":   "https://cdn-img.staticzz.com/img/logos/equipas/253884_imgbank_1755880629.png",
  "Casa Pia AC":          "https://cdn-img.staticzz.com/img/logos/equipas/2412_imgbank_1695724045.png",
  "FC Arouca":            "https://upload.wikimedia.org/wikipedia/pt/thumb/6/65/FC_Arouca.png/200px-FC_Arouca.png",
  "AFS Futebol SAD":      "https://upload.wikimedia.org/wikipedia/pt/thumb/4/4a/AVS_Futebol_SAD.png/200px-AVS_Futebol_SAD.png",
  "Marítimo":             "https://upload.wikimedia.org/wikipedia/pt/thumb/d/d3/CS_Mar%C3%ADtimo.png/200px-CS_Mar%C3%ADtimo.png",
  "CF Os Belenenses":     "https://cdn-img.staticzz.com/img/logos/equipas/3_imgbank_1682589777.png",
  "Belenenses":           "https://cdn-img.staticzz.com/img/logos/equipas/3_imgbank_1682589777.png",
  "GD Carcavelos":        "https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png",
  "CD Carcavelos":        "https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png",
  "CB Carcavelos":        "https://cdn-img.staticzz.com/img/logos/equipas/11193_imgbank.png",
  "GD Estoril-Praia B":   "https://cdn-img.staticzz.com/img/logos/equipas/1734_imgbank_1682584220.png",
  "SC Lourel":            "https://cdn-img.staticzz.com/img/logos/equipas/3958_imgbank_1748614610.png",
  "Sintrense FC":         "https://cdn-img.staticzz.com/img/logos/equipas/3655_imgbank_1683305451.png",
  "SU Sintrense":         "https://cdn-img.staticzz.com/img/logos/equipas/3655_imgbank_1683305451.png",
};

function getIniciais(nome) {
  return nome.split(" ")
    .filter(w => w.length > 1 && !["de","do","da","dos","das","e","FC","SC","CD","UD","AD","GD","CP","AC","VC"].includes(w))
    .slice(0,2).map(w=>w[0]).join("").toUpperCase() || nome.slice(0,2).toUpperCase();
}

function clubIcon(logoUrl, iniciais) {
  const inner = logoUrl
    ? `<div class="club-circle" style="background-image:url('${logoUrl}');background-size:80%;background-repeat:no-repeat;background-position:center;"></div>`
    : `<div class="club-circle"><span style="font-size:11px;font-weight:700;color:white;line-height:1;">${iniciais}</span></div>`;
  return L.divIcon({
    className: "club-div-icon",
    html: inner,
    iconSize: [42, 42],
    iconAnchor: [21, 21],
    popupAnchor: [0, -22]
  });
}

// ====================== CRIAR MARKER ======================
function criarMarker(clube) {
  const iniciais   = getIniciais(clube.nome);
  const urlDirecto = logoUrls[clube.nome] || null;
  const marker     = L.marker(clube.coords, { icon: clubIcon(urlDirecto, iniciais) });

  // Se não tem URL direto, tenta Wikipedia API
  if (!urlDirecto) {
    fetch(`https://pt.wikipedia.org/w/api.php?action=query&titles=${encodeURIComponent(clube.nome)}&prop=pageimages&pithumbsize=120&format=json&origin=*&redirects=1`)
      .then(r => r.json())
      .then(d => {
        const pages = Object.values(d?.query?.pages || {});
        const url   = pages[0]?.thumbnail?.source;
        if (url) marker.setIcon(clubIcon(url, iniciais));
      }).catch(()=>{});
  }

  marker.on("click", () => {
    let txt = "";
    if (userLatLng) {
      const km = distanciaKm(userLatLng[0], userLatLng[1], clube.coords[0], clube.coords[1]).toFixed(1);
      txt = `<br><small>📍 ${km} km de distância</small>`;
    }
    // Determina divisão do clube para passar à página de detalhe
    function getDivClube() {
      const mod = modalidadeAtual;
      if ((dados[mod].div1||[]).find(c => c.nome === clube.nome)) return labelsDiv[mod].div1;
      if ((dados[mod].div2||[]).find(c => c.nome === clube.nome)) return labelsDiv[mod].div2;
      if ((dados[mod].div3||[]).find(c => c.nome === clube.nome)) return labelsDiv[mod].div3;
      return labelsDiv[mod].div4;
    }
    const divNome = getDivClube();
    const urlClube = `clube.php?nome=${encodeURIComponent(clube.nome)}&modalidade=${encodeURIComponent(modalidadeAtual)}&divisao=${encodeURIComponent(divNome)}&recinto=${encodeURIComponent(clube.recinto)}&logo=${encodeURIComponent(clube.logo)}`;
    marker.bindPopup(`
      <strong>${clube.nome}</strong>
      <br>🏟 ${clube.recinto}${txt}
      <br><a href="${urlClube}" style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;padding:5px 12px;background:rgba(255,255,255,0.15);color:white;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid rgba(255,255,255,0.25);">
        <i class="bi bi-info-circle-fill"></i> ${window._dict?.ver_mais || 'Ver mais'}
      </a>
    `).openPopup();
  });
  marker.addTo(layerAtual);
  markersMap.set(clube.nome.toLowerCase(), marker);
  return marker;
}

// ====================== POPULAR SIDEBAR — CLUBES PRÓXIMOS ======================
function popularProximosSidebar(mod) {
  const container = document.getElementById("proximos-list");
  if (!container) return;

  if (!userLatLng) {
    container.innerHTML = `<div class="proximos-loading"><i class="bi bi-compass" style="font-size:22px;opacity:.4;"></i><br><span>A aguardar localização...</span></div>`;
    return;
  }

  const todos = [...(dados[mod].div1||[]), ...(dados[mod].div2||[]), ...(dados[mod].div3||[]), ...(dados[mod].div4||[])];

  // Determina a divisão de cada clube
  function getDivisao(clube) {
    if ((dados[mod].div1||[]).find(c => c.nome === clube.nome)) return labelsDiv[mod].div1;
    if ((dados[mod].div2||[]).find(c => c.nome === clube.nome)) return labelsDiv[mod].div2;
    if ((dados[mod].div3||[]).find(c => c.nome === clube.nome)) return labelsDiv[mod].div3;
    return labelsDiv[mod].div4;
  }

  const comDist = todos.map(c => ({
    ...c,
    dist: distanciaKm(userLatLng[0], userLatLng[1], c.coords[0], c.coords[1]),
    divisao: getDivisao(c)
  })).sort((a, b) => a.dist - b.dist);

  container.innerHTML = "";
  comDist.slice(0, 10).forEach(c => {
    const item = document.createElement("div");
    item.className = "proximo-item";
    item.innerHTML = `
      <img src="${logoUrls[c.nome] || ''}" alt="${c.nome}" onerror="this.style.display='none'" style="${logoUrls[c.nome] ? '' : 'display:none'}">
      <div class="pi-info">
        <div class="pi-nome">${c.nome}</div>
        <div class="pi-div">${c.divisao}</div>
      </div>
      <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
        <div class="pi-dist">${c.dist.toFixed(1)} km</div>
        <a href="clube.php?nome=${encodeURIComponent(c.nome)}&modalidade=${encodeURIComponent(mod)}&divisao=${encodeURIComponent(c.divisao)}&recinto=${encodeURIComponent(c.recinto)}&logo=${encodeURIComponent(c.logo)}"
           style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.75);text-decoration:none;white-space:nowrap;"
           onclick="event.stopPropagation()">
          ${window._dict?.ver_mais || "Ver mais"} →
        </a>
      </div>
    `;
    item.onclick = () => {
      map.flyTo(c.coords, 14);
      setTimeout(() => markersMap.get(c.nome.toLowerCase())?.openPopup(), 900);
      criarRotaAte(c.coords);
    };
    container.appendChild(item);
  });
}

// ====================== CARREGAR MODALIDADE ======================
function carregarModalidade(mod) {
  modalidadeAtual = mod;
  clubesEstaoVisiveis = false;
  layerAtual.clearLayers();
  markersMap.clear();

  // Criar todos os markers
  const todos = [...(dados[mod].div1||[]), ...(dados[mod].div2||[]), ...(dados[mod].div3||[]), ...(dados[mod].div4||[])];
  todos.forEach(c => criarMarker(c));
  clubesVisiveis = todos;
  clubesEstaoVisiveis = true;

  // Popular sidebar com clubes mais próximos
  popularProximosSidebar(mod);

  // Se tiver localização, centra nos mais próximos
  if (userLatLng) {
    mostrarMaisProximos(mod);
  }
}

// ====================== MAIS PRÓXIMOS ======================
function mostrarMaisProximos(mod) {
  const todos = [...(dados[mod].div1||[]), ...(dados[mod].div2||[]), ...(dados[mod].div3||[]), ...(dados[mod].div4||[])];
  const comDist = todos.map(c => ({
    ...c,
    dist: distanciaKm(userLatLng[0], userLatLng[1], c.coords[0], c.coords[1])
  })).sort((a, b) => a.dist - b.dist);

  // Centra o mapa no clube mais próximo
  if (comDist.length > 0) {
    const maisProximo = comDist[0];
    map.flyTo(maisProximo.coords, 11, { duration: 1.5 });

    // Abre popup do mais próximo com distância
    setTimeout(() => {
      const marker = markersMap.get(maisProximo.nome.toLowerCase());
      if (marker) {
        const divNomeProx = (dados[mod].div1||[]).find(c=>c.nome===maisProximo.nome) ? labelsDiv[mod].div1
          : (dados[mod].div2||[]).find(c=>c.nome===maisProximo.nome) ? labelsDiv[mod].div2
          : (dados[mod].div3||[]).find(c=>c.nome===maisProximo.nome) ? labelsDiv[mod].div3 : labelsDiv[mod].div4;
        const urlClubeProx = `clube.php?nome=${encodeURIComponent(maisProximo.nome)}&modalidade=${encodeURIComponent(mod)}&divisao=${encodeURIComponent(divNomeProx)}&recinto=${encodeURIComponent(maisProximo.recinto)}&logo=${encodeURIComponent(maisProximo.logo)}`;
        marker.bindPopup(`
          <strong>${maisProximo.nome}</strong>
          <br>🏟 ${maisProximo.recinto}
          <br><small>📍 ${maisProximo.dist.toFixed(1)} km de distância</small>
          <br><a href="${urlClubeProx}" style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;padding:5px 12px;background:rgba(255,255,255,0.15);color:white;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid rgba(255,255,255,0.25);">
            <i class="bi bi-info-circle-fill"></i> ${window._dict?.ver_mais || 'Ver mais'}
          </a>
        `).openPopup();
      }
    }, 1800);
  }
}

// ====================== LOCALIZAÇÃO DO UTILIZADOR ======================
function pedirLocalizacao() {
  if (!navigator.geolocation) return;
  navigator.geolocation.getCurrentPosition(pos => {
    userLatLng = [pos.coords.latitude, pos.coords.longitude];

    if (userMarker) map.removeLayer(userMarker);
    userMarker = L.marker(userLatLng, {
      icon: L.divIcon({
        className: "",
        html: `<div style="width:16px;height:16px;background:#5a2bcf;border:2px solid white;border-radius:50%;"></div>`,
        iconSize: [16,16], iconAnchor: [8,8]
      })
    }).addTo(map).bindPopup("📍 A tua localização").openPopup();

    // Atualiza sidebar com clubes próximos e centra mapa
    popularProximosSidebar(modalidadeAtual);
    mostrarMaisProximos(modalidadeAtual);
  }, () => {
    // Utilizador recusou — mantém vista de Portugal e mostra estado sem localização
    map.setView([39.5, -8], 7);
    const container = document.getElementById("proximos-list");
    if (container) container.innerHTML = `<div class="proximos-loading"><i class="bi bi-geo-alt" style="font-size:22px;opacity:.4;"></i><br><span>Localização não disponível.<br>Clica em "Minha localização".</span></div>`;
  });
}

// ====================== MOSTRAR/ESCONDER TODOS ======================
function mostrarTodosClubes() {
  if (clubesEstaoVisiveis) {
    layerAtual.clearLayers();
    markersMap.clear();
    ["div1-list","div2-list","div3-list"].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.innerHTML = "";
    });
    clubesVisiveis = [];
    clubesEstaoVisiveis = false;
  } else {
    carregarModalidade(modalidadeAtual);
  }
}

// ====================== ROTA ======================
let routingControl = null;
function criarRotaAte(destCoords) {
  if (!userLatLng) return;
  if (routingControl) { map.removeControl(routingControl); routingControl = null; }
  if (linhaRota) { map.removeLayer(linhaRota); linhaRota = null; }
  routingControl = L.Routing.control({
    waypoints: [L.latLng(userLatLng), L.latLng(destCoords)],
    routeWhileDragging: false,
    showAlternatives: false,
    serviceUrl: 'https://router.project-osrm.org/route/v1',
    lineOptions: { styles: [{ color: "#5a2bcf", weight: 3, opacity: 1 }] },
    createMarker: () => null,
    show: false,
    addWaypoints: false,
    fitSelectedRoutes: false
  }).addTo(map);
}

// ====================== PESQUISA DE LOCAL ======================
const placeInput   = document.getElementById("place-search");
const placeSugg    = document.getElementById("place-suggestions");
const searchInput  = document.getElementById("search-input");
const suggestions  = document.getElementById("suggestions");

placeInput?.addEventListener("input", () => {
  clearTimeout(placeTimeout);
  const q = placeInput.value.trim();
  if (q.length < 3) { placeSugg.innerHTML = ""; placeSugg.classList.remove("active"); return; }
  placeTimeout = setTimeout(async () => {
    try {
      const res  = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=5&countrycodes=pt`);
      const data = await res.json();
      placeSugg.innerHTML = "";
      data.forEach(p => {
        const d = document.createElement("div");
        d.textContent = p.display_name;
        d.onclick = async () => {
          placeSugg.innerHTML = ""; placeSugg.classList.remove("active");
          placeInput.value = p.display_name;
          const lat = parseFloat(p.lat), lon = parseFloat(p.lon);
          map.flyTo([lat, lon], 13);
          if (placeMarker) map.removeLayer(placeMarker);
          if (placePolygon) map.removeLayer(placePolygon);
          placeMarker = L.marker([lat, lon]).addTo(map).bindPopup(p.display_name).openPopup();
          if (p.boundingbox) {
            const bb = p.boundingbox.map(Number);
            placePolygon = L.rectangle([[bb[0],bb[2]],[bb[1],bb[3]]], { color:"#5a2bcf", weight:1, fillOpacity:0.04 }).addTo(map);
          }
        };
        placeSugg.appendChild(d);
      });
      placeSugg.classList.toggle("active", data.length > 0);
    } catch {}
  }, 350);
});

searchInput?.addEventListener("input", () => {
  const q = searchInput.value.trim().toLowerCase();
  const todos = [...(dados[modalidadeAtual].div1||[]), ...(dados[modalidadeAtual].div2||[]), ...(dados[modalidadeAtual].div3||[]), ...(dados[modalidadeAtual].div4||[])];
  const resultados = todos.filter(c => c.nome.toLowerCase().includes(q));
  suggestions.innerHTML = "";
  resultados.slice(0, 6).forEach(c => {
    const d = document.createElement("div");
    let distTxt = "";
    if (userLatLng) {
      const km = distanciaKm(userLatLng[0], userLatLng[1], c.coords[0], c.coords[1]).toFixed(1);
      distTxt = ` — ${km} km`;
    }
    const logoSrc = logoUrls[c.nome] || '';
    const divNomeSugg = (() => {
      const mod = modalidadeAtual;
      if ((dados[mod].div1||[]).find(x => x.nome === c.nome)) return labelsDiv[mod].div1;
      if ((dados[mod].div2||[]).find(x => x.nome === c.nome)) return labelsDiv[mod].div2;
      if ((dados[mod].div3||[]).find(x => x.nome === c.nome)) return labelsDiv[mod].div3;
      return labelsDiv[mod].div4;
    })();
    d.className = 'suggestion-item';
    d.innerHTML = `
      <div class="sugg-logo">
        ${logoSrc
          ? `<img src="${logoSrc}" alt="${c.nome}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
          : ''}
        <div class="sugg-iniciais" style="${logoSrc ? 'display:none' : ''}">${c.nome.split(' ').filter(w=>w.length>2).slice(0,2).map(w=>w[0]).join('').toUpperCase()||c.nome.slice(0,2).toUpperCase()}</div>
      </div>
      <div class="sugg-info">
        <div class="sugg-nome">${c.nome}</div>
        <div class="sugg-div">${divNomeSugg}</div>
      </div>
      ${distTxt ? `<div class="sugg-dist">${distTxt.replace(' — ','')}</div>` : ''}
    `;
    d.onclick = () => {
      map.flyTo(c.coords, 14);
      setTimeout(() => markersMap.get(c.nome.toLowerCase())?.openPopup(), 800);
      criarRotaAte(c.coords);
      suggestions.classList.remove("active");
      searchInput.value = "";
    };
    suggestions.appendChild(d);
  });
  suggestions.classList.toggle("active", resultados.length > 0 && q.length > 0);
});

// ====================== EVENTOS ======================

// Botão mostrar/esconder
document.getElementById("btn-todos")?.addEventListener("click", mostrarTodosClubes);

// Botão localização
document.getElementById("btn-localizacao")?.addEventListener("click", () => {
  if (userLatLng) {
    map.flyTo(userLatLng, 12);
    userMarker?.openPopup();
  } else {
    pedirLocalizacao();
  }
});

// Modos de mapa
document.querySelectorAll("[data-map]").forEach(btn => {
  btn.addEventListener("click", () => {
    Object.values(baseLayers).forEach(l => map.removeLayer(l));
    baseLayers[btn.dataset.map]?.addTo(map);
  });
});

// Filtros de modalidade
document.querySelectorAll(".filtro-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".filtro-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    carregarModalidade(btn.dataset.mod);
  });
});

// Fechar sugestões ao clicar fora
document.addEventListener("click", e => {
  if (!placeInput?.contains(e.target))  { placeSugg.innerHTML = ""; placeSugg.classList.remove("active"); }
  if (!searchInput?.contains(e.target)) suggestions.classList.remove("active");
});

// ====================== INIT ======================
// Carrega todos os clubes de futebol imediatamente
carregarModalidade("futebol");
// Pede localização — ao obter, voa para lá e actualiza sidebar
pedirLocalizacao();

// ── RE-RENDER on language change ──────────────────────────────
document.addEventListener("langChanged", () => {
  // Re-populate sidebar (Ver mais links update automatically via window._dict)
  if (typeof popularProximosSidebar === "function" && modalidadeAtual) {
    popularProximosSidebar(modalidadeAtual);
  }
  // Close any open popup so next open uses new lang
  if (typeof map !== "undefined") map.closePopup();
});
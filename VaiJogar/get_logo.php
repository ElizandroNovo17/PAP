<?php
// ============================================================
//  PHP/get_logo.php
//  Vai buscar a logo de um clube à Wikipedia automaticamente
//  GET: ?clube=FC+Porto
//  Devolve JSON com { url: "https://..." } ou { url: null }
// ============================================================
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Cache simples em ficheiro para não chamar a Wikipedia a toda a hora
$cachedir = __DIR__ . '/../assets/images/clubes_cache/';
if (!is_dir($cachedir)) mkdir($cachedir, 0755, true);

$clube = trim($_GET['clube'] ?? '');
if (!$clube) { echo json_encode(['url' => null]); exit; }

$cacheFile = $cachedir . md5($clube) . '.json';

// Devolve cache se existir (válida por 7 dias)
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 604800) {
    echo file_get_contents($cacheFile);
    exit;
}

// Mapeamento de nomes para o título exato na Wikipedia PT
$wiki_map = [
    'FC Porto'              => 'FC Porto',
    'Sporting CP'           => 'Sporting Clube de Portugal',
    'SL Benfica'            => 'Sport Lisboa e Benfica',
    'SC Braga'              => 'Sporting Clube de Braga',
    'Vitória SC'            => 'Vitória Sport Clube',
    'Gil Vicente'           => 'Gil Vicente Futebol Clube',
    'FC Famalicão'          => 'Futebol Clube de Famalicão',
    'Moreirense'            => 'Moreirense Futebol Clube',
    'Estoril Praia'         => 'Grupo Desportivo Estoril Praia',
    'FC Alverca'            => 'Futebol Clube de Alverca',
    'Rio Ave'               => 'Rio Ave Futebol Clube',
    'Nacional'              => 'Club Desportivo Nacional',
    'Santa Clara'           => 'Club Desportivo Santa Clara',
    'Estrela da Amadora'    => 'Estrela da Amadora FC',
    'Casa Pia AC'           => 'Casa Pia Atlético Clube',
    'FC Arouca'             => 'Futebol Clube de Arouca',
    'CD Tondela'            => 'Clube Desportivo de Tondela',
    'AFS Futebol SAD'       => 'AVS Futebol SAD',
    'Marítimo'              => 'Club Sport Marítimo',
    'Sporting CP B'         => 'Sporting Clube de Portugal',
    'Académico de Viseu'    => 'Académico de Viseu FC',
    'GD Chaves'             => 'Grupo Desportivo de Chaves',
    'Vizela'                => 'Futebol Clube de Vizela',
    'UD Leiria'             => 'União Desportiva de Leiria',
    'SC União Torreense'    => 'Sporting Clube União Torreense',
    'FC Penafiel'           => 'Futebol Clube de Penafiel',
    'FC Felgueiras 1932'    => 'FC Felgueiras 1932',
    'SC Farense'            => 'Sport Clube Farense',
    'SL Benfica B'          => 'Sport Lisboa e Benfica',
    'FC Porto B'            => 'FC Porto',
    'CD Feirense'           => 'Clube Desportivo Feirense',
    'Portimonense SAD'      => 'Portimonense Sporting Clube',
    'UD Oliveirense'        => 'União Desportiva Oliveirense',
    'FC Paços de Ferreira'  => 'FC Paços de Ferreira',
    'Leixões SC'            => 'Leixões Sport Club',
    'Lusitânia FC Lourosa'  => 'Lusitânia Futebol Clube',
    'Varzim SC'             => 'Varzim Sport Club',
    'CD Trofense'           => 'Clube Desportivo Trofense',
    'SC Braga B'            => 'Sporting Clube de Braga',
    'AD Fafe'               => 'Associação Desportiva de Fafe',
    'FC Paredes'            => 'Futebol Clube de Paredes',
    'Amarante FC'           => 'Amarante Futebol Clube',
    'CD Mafra'              => 'Clube Desportivo Mafra',
    'Amora FC'              => 'Amora Futebol Clube',
    'CF Os Belenenses'      => 'Clube de Futebol Os Belenenses',
    'Académica OAF'         => 'Associação Académica de Coimbra',
    'SC Covilhã'            => 'Sport Clube Covilhã',
    'Lusitano GC Évora'     => 'Lusitano Ginásio Clube',
    'Ovarense GAVEX'        => 'Ovarense Basquetebol',
    'Imortal LUZiGÁS'       => 'Imortal Desporto Clube',
    'CA Queluz'             => 'Club de Basquetebol de Queluz',
    'CP Esgueira'           => 'Clube Pinheirense de Esgueira',
    'SC Vasco da Gama'      => 'Sport Club Vasco da Gama',
    'Galitos Barreiro'      => 'Sport Clube Os Galitos',
    'Illiabum Clube'        => 'Illiabum Clube',
    'Sangalhos DC'          => 'Desportivo de Sangalhos',
    'Maia Basket'           => 'Maia Basket',
    'Guifões SC'            => 'Sport Clube de Guifões',
    'CAB Madeira'           => 'Clube Atlético e Benfica da Madeira',
    'SC Lusitânia'          => 'Sport Clube Lusitânia',
    'Gaeirense Basket'      => 'Gaeirense BC',
    'Belenenses'            => 'Clube de Futebol Os Belenenses',
    'Académica de Coimbra'  => 'Associação Académica de Coimbra',
    'Ginásio Figueirense'   => 'Ginásio Clube Figueirense',
    'Basket Santo André'    => 'Basket Santo André',
    'Viana Basket'          => 'Viana Basket',
    'Sporting de Espinho'   => 'Sporting Clube de Espinho',
    'Académica de Espinho'  => 'Académica de Espinho',
    'Castêlo da Maia GC'    => 'Castêlo da Maia Ginásio Clube',
    'VC Viana'              => 'Voleibol Clube de Viana',
    'Ala de Gondomar'       => 'Ala de Gondomar',
    'Académica São Mamede'  => 'Académica de São Mamede',
    'Clube Atlântico Madalena' => 'Clube Atlântico da Madalena',
    'Santo Tirso'           => 'Sport Clube de Santo Tirso',
    'Esmoriz GC'            => 'Esmoriz Ginásio Clube',
    'GC Vilacondense'       => 'Ginásio Clube Vilacondense',
    'GDC Gueifães'          => 'Grupo Desportivo e Cultural de Gueifães',
    'SO Marinhense'         => 'Sport Operário Marinhense',
    'SC Caldas'             => 'Sport Clube das Caldas',
    'CV Oeiras'             => 'Clube de Voleibol de Oeiras',
    'CN Ginástica'          => 'Clube Nacional de Ginástica',
    'AC Albufeira'          => 'Atlético Clube de Albufeira',
    'VC Braga'              => 'Voleibol Clube de Braga',
    'AA Coimbra'            => 'Associação Académica de Coimbra',
    'Figueira VC'           => 'Figueira Voleibol Clube',
    'Lousã VC'              => 'Voleibol Clube da Lousã',
    'Amares Volei'          => 'Amares Volei',
    'Académica Volei Setúbal' => 'Académica Voleibol Setúbal',
];

$titulo = $wiki_map[$clube] ?? $clube;

// Chama a API da Wikipedia para ir buscar a imagem principal do artigo
$apiUrl = 'https://pt.wikipedia.org/w/api.php?' . http_build_query([
    'action'      => 'query',
    'titles'      => $titulo,
    'prop'        => 'pageimages',
    'pithumbsize' => 200,
    'format'      => 'json',
    'redirects'   => 1,
]);

$ctx = stream_context_create(['http' => [
    'timeout' => 5,
    'header'  => 'User-Agent: VaiJogar/1.0 (vaijjogar@gmail.com)',
]]);

$resp = @file_get_contents($apiUrl, false, $ctx);
$url  = null;

if ($resp) {
    $data = json_decode($resp, true);
    $pages = $data['query']['pages'] ?? [];
    foreach ($pages as $page) {
        if (isset($page['thumbnail']['source'])) {
            $url = $page['thumbnail']['source'];
            break;
        }
    }
}

// Se a Wikipedia PT falhou, tenta EN
if (!$url) {
    $apiUrlEN = 'https://en.wikipedia.org/w/api.php?' . http_build_query([
        'action'      => 'query',
        'titles'      => $clube,
        'prop'        => 'pageimages',
        'pithumbsize' => 200,
        'format'      => 'json',
        'redirects'   => 1,
    ]);
    $resp2 = @file_get_contents($apiUrlEN, false, $ctx);
    if ($resp2) {
        $data2 = json_decode($resp2, true);
        $pages2 = $data2['query']['pages'] ?? [];
        foreach ($pages2 as $page) {
            if (isset($page['thumbnail']['source'])) {
                $url = $page['thumbnail']['source'];
                break;
            }
        }
    }
}

$result = json_encode(['url' => $url]);
file_put_contents($cacheFile, $result); // guarda em cache
echo $result;
?>
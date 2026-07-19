<?php

ob_start();
ini_set('post_max_size','10M');
ini_set('upload_max_filesize','10M');
session_start();
require __DIR__ . "/conexao.php";
header('Content-Type: application/json; charset=utf-8');

function ok($d=[])  { ob_clean(); echo json_encode(array_merge(['sucesso'=>true],$d));  exit; }
function err($m)    { ob_clean(); echo json_encode(['sucesso'=>false,'mensagem'=>$m]);   exit; }

if (!isset($_SESSION['user_id'])) err('Não autenticado.');

$id     = $_SESSION['user_id'];
$action = $_POST['action'] ?? 'profile';

switch ($action) {

// ── PERFIL (nome + foto) ──────────────────────────────────────
case 'profile':
    $nome   = trim($_POST['nome'] ?? '');
    $avatar = $_POST['avatar']    ?? null;
    if (!$nome) err('O nome não pode estar vazio.');
    if ($avatar && strpos($avatar,'data:image/')!==0) $avatar=null;
    if ($avatar) {
        $s=$conn->prepare("UPDATE utilizadores SET nome=?,foto_perfil=? WHERE id_utilizador=?");
        $s->bind_param('ssi',$nome,$avatar,$id);
    } else {
        $s=$conn->prepare("UPDATE utilizadores SET nome=? WHERE id_utilizador=?");
        $s->bind_param('si',$nome,$id);
    }
    if ($s->execute()) { $_SESSION['user_nome']=$nome; ok(); }
    err('Erro ao guardar: '.$conn->error);

// ── AVATAR ────────────────────────────────────────────────────
case 'avatar':
    $avatar=$_POST['avatar']??null;
    if (!$avatar||strpos($avatar,'data:image/')!==0) err('Imagem inválida.');
    $s=$conn->prepare("UPDATE utilizadores SET foto_perfil=? WHERE id_utilizador=?");
    $s->bind_param('si',$avatar,$id);
    $s->execute() ? ok() : err('Erro ao guardar a foto.');

// ── PASSWORD ──────────────────────────────────────────────────
case 'password':
    $atual=$_POST['atual']??''; $nova=$_POST['nova']??''; $conf=$_POST['confirmar']??'';
    if (strlen($nova)<6) err('A nova palavra-passe deve ter pelo menos 6 caracteres.');
    if ($nova!==$conf)   err('As palavras-passe não coincidem.');
    $s=$conn->prepare("SELECT password FROM utilizadores WHERE id_utilizador=?");
    $s->bind_param('i',$id); $s->execute();
    $u=$s->get_result()->fetch_assoc();
    if (!password_verify($atual,$u['password'])) err('Palavra-passe atual incorreta.');
    $hash=password_hash($nova,PASSWORD_DEFAULT);
    $s2=$conn->prepare("UPDATE utilizadores SET password=? WHERE id_utilizador=?");
    $s2->bind_param('si',$hash,$id);
    $s2->execute() ? ok() : err('Erro ao alterar a palavra-passe.');

// ── EMAIL ─────────────────────────────────────────────────────
case 'email':
    $novo=trim($_POST['novo_email']??''); $pass=$_POST['password_conf']??'';
    if (!$novo||!filter_var($novo,FILTER_VALIDATE_EMAIL)) err('Email inválido.');
    $s=$conn->prepare("SELECT id_utilizador FROM utilizadores WHERE email=? AND id_utilizador!=?");
    $s->bind_param('si',$novo,$id); $s->execute(); $s->store_result();
    if ($s->num_rows>0) err('Este email já está em uso.');
    $s2=$conn->prepare("SELECT password FROM utilizadores WHERE id_utilizador=?");
    $s2->bind_param('i',$id); $s2->execute();
    $u=$s2->get_result()->fetch_assoc();
    if (!password_verify($pass,$u['password'])) err('Palavra-passe incorreta.');
    $s3=$conn->prepare("UPDATE utilizadores SET email=? WHERE id_utilizador=?");
    $s3->bind_param('si',$novo,$id);
    $s3->execute() ? ok(['novo_email'=>$novo]) : err('Erro ao atualizar email.');

// ── DELETE ────────────────────────────────────────────────────
case 'delete':
    $s=$conn->prepare("DELETE FROM utilizadores WHERE id_utilizador=?");
    $s->bind_param('i',$id);
    if ($s->execute()) { session_destroy(); ok(); }
    err('Erro ao eliminar a conta.');

default:
    err('Ação desconhecida.');
}
?>

<?php
/**
 * Handler do formulário de contato — Santos Assessoria
 * Recebe o POST de contato.html e envia por e-mail para o atendimento.
 * Retorna JSON { ok:true, msg } ou { erro }.
 */
header('Content-Type: application/json; charset=utf-8');

// só POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['erro' => 'Método não permitido.']);
  exit;
}

// honeypot anti-spam: bots preenchem o campo escondido "website"
if (!empty($_POST['website'])) {
  echo json_encode(['ok' => true, 'msg' => 'Recebido!']); // finge sucesso pro bot
  exit;
}

function limpa($s) { return trim(str_replace(["\r", "\n"], ' ', strip_tags((string)$s))); }

$nome     = limpa($_POST['nome'] ?? '');
$doc      = limpa($_POST['documento'] ?? '');
$email    = limpa($_POST['email'] ?? '');
$whatsapp = limpa($_POST['whatsapp'] ?? '');
$mensagem = trim(strip_tags((string)($_POST['mensagem'] ?? '')));

// validação
if (mb_strlen($nome) < 3)  { echo json_encode(['erro' => 'Informe seu nome completo.']); exit; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(['erro' => 'Informe um e-mail válido.']); exit; }
if ($doc === '')           { echo json_encode(['erro' => 'Informe o CPF/CNPJ.']); exit; }
if (mb_strlen($mensagem) < 5) { echo json_encode(['erro' => 'Descreva um pouco mais a situação.']); exit; }

// ==== destino ====
$para    = 'atendimento@santosassessoria.com';
$assunto = 'Nova solicitação de análise — site';

$corpo  = "Nova solicitação pelo site santosassessoria.com\n\n";
$corpo .= "Nome:      $nome\n";
$corpo .= "CPF/CNPJ:  $doc\n";
$corpo .= "E-mail:    $email\n";
$corpo .= "WhatsApp:  $whatsapp\n\n";
$corpo .= "Situação:\n$mensagem\n\n";
$corpo .= "-----\nEnviado em " . date('d/m/Y H:i') . "\n";

// remetente no domínio (evita cair em spam); Reply-To = e-mail do lead
$headers  = "From: Site Santos <no-reply@santosassessoria.com>\r\n";
$headers .= "Reply-To: $nome <$email>\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

$ok = @mail($para, '=?UTF-8?B?' . base64_encode($assunto) . '?=', $corpo, $headers);

if ($ok) {
  echo json_encode(['ok' => true, 'msg' => 'Recebido! Nossa equipe analisa e entra em contato em breve. Para urgência, chame no WhatsApp.']);
} else {
  echo json_encode(['erro' => 'Não foi possível enviar agora. Por favor, fale com a gente pelo WhatsApp.']);
}

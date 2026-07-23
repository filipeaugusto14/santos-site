<?php
/**
 * Captura de intenção de assinatura da Blindagem Santos.
 * Recebe (via sendBeacon do modal) os dados que a Santos precisa para saber
 * QUEM assinou e QUAL CPF/CNPJ monitorar. O cliente segue para o checkout do
 * InfinitePay em paralelo. Best-effort: não bloqueia o pagamento.
 */
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['erro' => 'Método não permitido.']);
  exit;
}

function limpa($s) { return trim(str_replace(["\r", "\n"], ' ', strip_tags((string)$s))); }

$plano   = limpa($_POST['plano'] ?? '');
$periodo = limpa($_POST['periodo'] ?? '');
$nome    = limpa($_POST['nome'] ?? '');
$doc     = limpa($_POST['documento'] ?? '');
$tel     = limpa($_POST['telefone'] ?? '');

// validação leve (o gate real é no front; aqui só evita registro vazio/spam)
if (mb_strlen($nome) < 3 || preg_replace('/\D/', '', $doc) === '' || $plano === '') {
  echo json_encode(['erro' => 'Dados incompletos.']);
  exit;
}

$para    = 'atendimento@santosassessoria.com';
$assunto = 'Nova assinatura Blindagem — ' . $plano . ' (' . $periodo . ')';

$corpo  = "Novo início de assinatura da Blindagem Santos pelo site.\n\n";
$corpo .= "Plano:                 $plano ($periodo)\n";
$corpo .= "Nome:                  $nome\n";
$corpo .= "CPF/CNPJ a monitorar:  $doc\n";
$corpo .= "Telefone:              $tel\n\n";
$corpo .= "O cliente foi redirecionado ao checkout do InfinitePay.\n";
$corpo .= "Confirme o pagamento no InfinitePay antes de iniciar o monitoramento.\n\n";
$corpo .= "Enviado em " . date('d/m/Y H:i') . "\n";

$headers  = "From: Site Santos <no-reply@santosassessoria.com>\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

@mail($para, '=?UTF-8?B?' . base64_encode($assunto) . '?=', $corpo, $headers);

echo json_encode(['ok' => true]);

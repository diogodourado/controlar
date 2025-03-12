<?
$translate['nome_obrigatorio'] = [
    'pt-br' => 'Você deve informar o nome',
    'en' => 'Você deve informar o nome',
    'es' => 'Você deve informar o nome',
];

$translate['email_obrigatorio'] = [
    'pt-br' => 'Você deve informar o e-mail',
    'en' => 'Você deve informar o e-mail',
    'es' => 'Você deve informar o e-mail',
];

$translate['telefone_obrigatorio'] = [
    'pt-br' => 'Você deve informar o telefone',
    'en' => 'Você deve informar o telefone',
    'es' => 'Você deve informar o telefone',
];


$translate['produto_obrigatorio'] = [
    'pt-br' => 'Você deve informar o produto do seu interesse',
    'en' => 'Você deve informar o produto do seu interesse',
    'es' => 'Você deve informar o produto do seu interesse',
];


$translate['conheceu_obrigatorio'] = [
    'pt-br' => 'Você deve informar o como conheceu a CONTROLAR',
    'en' => 'Você deve informar o como conheceu a CONTROLAR',
    'es' => 'Você deve informar o como conheceu a CONTROLAR',
];

$translate['mensagem_obrigatorio'] = [
    'pt-br' => 'Você deve digitar os detalhes no campo de mensagem',
    'en' => 'Você deve digitar os detalhes no campo de mensagem',
    'es' => 'Você deve digitar os detalhes no campo de mensagem',
];


$translate['reCAPTCHA_error'] = [
    'pt-br' => 'Erro ao verificar o reCAPTCHA.',
    'en' => 'Erro ao verificar o reCAPTCHA.',
    'es' => 'Erro ao verificar o reCAPTCHA.',
];


$translate['mensagem_sucesso'] = [
    'pt-br' => 'Mensagem enviada com sucesso!',
    'en' => 'Mensagem enviada com sucesso!',
    'es' => 'Mensagem enviada com sucesso!',
];


$translate['mensagem_erro'] = [
    'pt-br' => 'Erro(s) encontrado(s)',
    'en' => 'Erro(s) encontrado(s)',
    'es' => 'Erro(s) encontrado(s)',
];



// Nao alterar dessa linha para baixo

if (preg_match('/\/(es|en)\//', $_SERVER['REQUEST_URI'], $matches)) {
    $lang =  $matches[1];
} else {
    $lang = 'pt-br';
}

define('LANGUAGE', $lang);

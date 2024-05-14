<?php

require_once("globals.php");
require_once("models/Message.php");

$message = new Message($BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Verificação do tipo de formulário
if ($type === "register") {

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // Verificação de dados mínimos
    if ($name && $lastname && $email && $password) {

        // Verificar se as senhas batem
        if ($password === $confirmpassword) {

            // Construir o payload para o cadastro
            $data = [
                'name' => $name,
                'lastname' => $lastname,
                'email' => $email,
                'password' => $password
            ];

            // Fazer requisição POST para o endpoint de cadastro
            $cadastroResponse = enviarRequisicaoPOST("http://localhost:8080/usuario", $data);

            // Verificar se o cadastro foi bem-sucedido
            if ($cadastroResponse === "Success") {
                $message->setMessage("Usuário cadastrado com sucesso!", "success", "login.php");
            } else {
                $message->setMessage("Erro ao cadastrar usuário. Tente novamente mais tarde.", "error", "back");
            }

        } else {
            // Enviar uma msg de erro, de senhas não batem
            $message->setMessage("As senhas não são iguais.", "error", "back");
        }

    } else {
        // Enviar uma msg de erro, de dados faltantes
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
} elseif ($type === "login") {

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    // Construir o payload para o login
    $data = [
        'email' => $email,
        'password' => $password
    ];

    // Fazer requisição POST para o endpoint de login
    $loginResponse = enviarRequisicaoPOST("http://localhost:8080/usuario/login", $data);

    // Verificar se o login foi bem-sucedido
    if ($loginResponse === "Success") {
        $message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
    } else {
        $message->setMessage("Usuário e/ou senha incorretos.", "error", "back");
    }

} else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
}

// Função para enviar uma requisição POST com cURL
function enviarRequisicaoPOST($url, $data) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data)
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

<?php

$localization = [
    'firstName' => 'Имя',
    'secondName' => 'Фамилия',
    'email' => 'E-mail',
    'password' => 'Пароль',
    'passwordConfirmation' => 'Подтверждение пароля',
];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo file_get_contents('index.html');
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo handlePost();
}

function handlePost() {
    $_POST = json_decode(file_get_contents("php://input"), true);
    try {
        validateRegistration();
        return response(true);
    } catch (Exception $e) {
        logError($e->getMessage());
        return response(false, $e->getMessage());
    }
}

function validateRegistration() {
    $requiredFields = ['firstName', 'secondName', 'email', 'password', 'passwordConfirmation'];
    foreach ($requiredFields as $fieldName) {
        if (!isset($_POST[$fieldName]) || empty($_POST[$fieldName])) {
            throw new Exception("Поле " . getLocalization($fieldName) . " не заполнено");
        }
    }
    if (!strpos($_POST['email'], '@')) {
        throw new Exception("Поле " . getLocalization('email') . " заполнено неправильно");
    }
    if ($_POST['password'] != $_POST['passwordConfirmation']) {
        throw new Exception("Пароли не совпадают");
    }
    $existingUsers = json_decode(file_get_contents('users.json'));
    foreach ($existingUsers as $user) {
        if ($user->email == $_POST['email']) {
            throw new Exception("Пользователь с таким email уже существует");
        }
    }
}

function logError($errorMessage) {
    try {
        date_default_timezone_set('Europe/Moscow');
        $date = date('d/m/Y H:i:s', time());
        $logFile = fopen(__DIR__ . "/logs/error.log", "a+");
        fwrite($logFile, "[{$date}] " . $errorMessage . PHP_EOL);
        fclose($logFile);
    } catch (Exception $e) {

    }
}

function getLocalization($fieldName) {
    return $GLOBALS['localization'][$fieldName];
}

function response($status=true, $message="") {
    return json_encode([
        "status" => $status,
        "message" => $message,
    ]);
}
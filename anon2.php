<?php

define("BOT_TOKEN", "6254609328:AAGTbEZL9zM1w3VDg6JIPyHlGklqGk0Ue2k");
define("API_URL", "https://api.telegram.org/bot" . BOT_TOKEN . "/");
// Define the list of words to filter
$badWords = [
    "kontol",
    "memek",
    "bangsat",
    "bokep",
    "sange",
    "sex",
    "kondom",
    "mmk",
    "tanktop",
    "tantop",
    "nafsuan",
    "kntl",
    "bo kep",
    "bok ep",
    "boke p",
    "b okep",
    "b o kep",
    "b o k ep",
    "b o k e p",
    "b o k ep",
    "b o kep",
    "ngaceng",
    "nga ceng",
    "b okep",
    "boke p",
    "bok e p",
    "bo k e p",
    "pake bh",
    "adult",
    "sexy",
    "snge",
    "kemem",
    "kmem",
    "bkep",
    "tocil",
    "jilmek",
    "pap tt",
    "pap tete",
    "tete ",
    "foto tt",
    "coli",
    "cli",
    "colmek",
    "clmk",
    "clmek",
    "colmk",
    "Sa***nge",
    "Sa*nge",
    "bugil",
    "Sa**nge",
    "horny",
    "engas",
    "entot",
    "titit",
    "titid",
    "entod",
    "ntod",
    "fuck",
    "j i l m e k",
    "vcs",
    "Vcs",
    "v.c.s",
    "v c s",
    "v
c
s",
    "v-c-s",
    "vicies",
    "vicis",
    "vecies",
];

// Mendapatkan update dan memproses permintaan:

$update = json_decode(file_get_contents("php://input"), true);
$message = isset($update["message"]) ? $update["message"] : null;
$chat_id = isset($message["chat"]["id"]) ? $message["chat"]["id"] : null;
$chat_id2 = $message["chat"]["id"];
$username1 = $message["chat"]["username"];
$text = isset($message["text"]) ? $message["text"] : null;
$data = json_decode(file_get_contents("anondata100566.json"), true);
$simpan = json_decode(file_get_contents("simpan100565.json"), true);
$premiumData = json_decode(file_get_contents("premium100565.json"), true);

 $memberc = false;

    // Check if user is a member of the channel
    $result = file_get_contents(
        "https://api.telegram.org/bot" . "6254609328:AAGTbEZL9zM1w3VDg6JIPyHlGklqGk0Ue2k" . "/" .
            "getChatMember?chat_id=@RasiTechChannel1&user_id=" .
            $chat_id2
    );
    $memberData = json_decode($result, true);
        if (
        $memberData["ok"] &&
        in_array($memberData["result"]["status"], [
            "creator",
            "administrator",
            "member",
        ])
    ) {
        $memberc = true;
    }
    if (!$memberc) {
        sendMessage(
            $chat_id2,
            "Silakan join ke channel @RasiTechChannel1 terlebih dahulu untuk menggunakan bot ini."
        );
        exit();
    }


// Fungsi untuk menyimpan data ke file
function saveData($filename, $data)
{
    $jsonData = json_encode($data);
    file_put_contents($filename, $jsonData);
}

// Fungsi untuk membaca data dari file
function readData($filename)
{
    $jsonData = file_get_contents($filename);
    return json_decode($jsonData, true);
}

$banned = json_decode(file_get_contents("banned100565.json"), true);

if (in_array($chat_id, $banned)) {
    sendMessage(
        $chat_id,
        "Anda telah dibanned dari bot 🤖, kontak @rasirt2 untuk membukanya. 📞"
    );
    exit();
}

if ( $text == "/id") {
    sendMessage(
        $chat_id,
        "ID Anda : $chat_id"
    );
    exit();
}
if (strpos($text, "/ban") === 0) {
    if ($chat_id2 == 1613688326) {
        $userId = trim(substr($text, 5));
        $banned = json_decode(file_get_contents("banned100565.json"), true);
        if (!in_array($userId, $banned)) {
            $banned[] = $userId;
            file_put_contents("banned100565.json", json_encode($banned));
            sendMessage($chat_id, "User ID $userId telah dibanned.");
        } else {
            sendMessage($chat_id, "User ID $userId sudah dibanned sebelumnya.");
        }
        exit();
    } else {
        sendMessage($chat_id, "Anda bukan owner");
        exit();
    }
}

if (strpos($text, "/unban") === 0) {
    if ($chat_id2 == 1613688326) {
        $userId = trim(substr($text, 7));
        $banned = json_decode(file_get_contents("banned100565.json"), true);
        if (in_array($userId, $banned)) {
            $banned = array_diff($banned, [$userId]);
            file_put_contents(
                "banned100565.json",
                json_encode(array_values($banned))
            );
            sendMessage($chat_id, "User ID $userId telah diunban.");
        } else {
            sendMessage(
                $chat_id,
                "User ID $userId tidak ditemukan dalam daftar banned."
            );
        }
        exit();
    } else {
        sendMessage($chat_id, "Anda bukan owner");
        exit();
    }
}

if (strpos($text, "/lapor") === 0) {
    if (isset($data["matched"][$chat_id])) {
        $other_chat_id = $data["matched"][$chat_id];
        $textArr = explode(" ", $text);
        $reason =
            count($textArr) > 1 ? implode(" ", array_slice($textArr, 1)) : "";

        if ($reason === "") {
            sendMessage(
                $chat_id,
                "Mohon masukkan alasan Anda untuk melaporkan pengguna ini. contoh : /lapor mengirim 18+ 🤫"
            );
            exit();
        }

        $reportData = [
            "username" => $other_chat_id,
            "reason" => $reason,
        ];
        $reportUrl =
            "https://api.telegram.org/bot" .
            BOT_TOKEN .
            "/sendMessage?chat_id=1613688326&text=";
        $reportMessage = urlencode(
            "👤 User ID " .
                $chat_id .
                " melaporkan 👥 user id " .
                $other_chat_id . 
                " @" .
                $username1 .
                " dengan alasan: " .
                $reason
        );
        file_get_contents($reportUrl . $reportMessage);
        $reportUrl =
            "https://api.telegram.org/bot" .
            BOT_TOKEN .
            "/sendMessage?chat_id=@rasitechchannel1&text=";
        $reportMessage = urlencode(
            "👤 User ID " .
                substr_replace($chat_id, "***", 0, 3) .
                " melaporkan 👥 user id " .
                $other_chat_id .
                " dengan alasan: " .
                $reason
        );
        file_get_contents($reportUrl . $reportMessage);
        sendMessage(
            $other_chat_id,
            "Lawan bicara, 🚨 telah melaporkan anda. Cek @RasiTechChannel1 untuk alasan. 🕵️‍♀️"
        );
        sendMessage(
            $chat_id,
            "Terima kasih, laporan 📝 telah dikirim ke admin! 🧑‍💼"
        );
        exit();
    } else {
        sendMessage($chat_id, "Kamu harus dalam percakapan 🤬");
        exit();
    }
}

if (strpos($text, "/prem") === 0) {
    if ($chat_id2 == 1613688326) {
        $apikeys = json_decode(file_get_contents("premium100565.json"), true);
        $userId = trim(substr($text, 5));
        $new_apikey = [
            "id" => $userId,
            "expdate" => date("Y-m-d", strtotime("+1 month")),
        ];

        $found = false;
        foreach ($apikeys as &$apikey) {
            if ($apikey["id"] == $userId) {
                $apikey["expdate"] = date("Y-m-d", strtotime("+1 month"));
                $found = true;
                break;
            }
        }

        if (!$found) {
            $apikeys[] = $new_apikey;
        }

        file_put_contents("premium100565.json", json_encode($apikeys));

        $text = "🎉 User ID $userId berhasil diupgrade menjadi akun premium selama 1 bulan! 🎉";
        sendMessage($chat_id2, $text);
        $text =
            "🎉 Selamat! Akun Anda telah berhasil diupgrade menjadi premium selama 1 bulan 🎉";
        sendMessage($userId, $text);
        exit();
    } else {
        $text = "Anda bukan owner";
        sendMessage($chat_id2, $text);
        exit();
    }
}

if ($text == "/start") {
    if (!isset($data["matched"][$chat_id])) {
        if (!in_array($chat_id, $simpan)) {
            $simpan[] = $chat_id;
            file_put_contents("simpan100565.json", json_encode($simpan));
        }
        sendMessage($chat_id, "Bot Created Using Bot @BotMakerApp

Official Bot @AnoymousIndoChatBot");
        sendMessage(
            $chat_id,
            '👋 Hi! Aku adalah Anonymous Chat Bot. Aku siap membantumu mencari percakapan! 🕵️‍♀️💬

🔍 Ketik /search untuk memulai pencarian percakapan.
❌ Ketik /stopsearch jika kamu ingin berhenti mencari percakapan.

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
        );
        return;
    } else {
        sendMessage($chat_id, "Kamu sedang dalam percakapan 🤬");
    }
}

if ($text == "/stopsearch") {
    if (!isset($data["matched"][$chat_id])) {
        if (isset($data["searching"])) {
            unset($data["searching"]);
            // Menyimpan data yang telah dihapus
            file_put_contents("anondata100566.json", json_encode($data));
            // Mengirim pesan balasan ke pengguna
            sendMessage($chat_id, "Pencarian dibatalkan 🚫");
        } else {
            sendMessage($chat_id, "Tidak sedang mencari user lain 😕");
        }

        return;
    } else {
        sendMessage($chat_id, "Kamu sedang dalam percakapan 🤬");
    }
}

if ($text == "/search") {
    if (!isset($data["matched"][$chat_id])) {
        // Pencarian pengguna kosong, memulai pencarian
        if (isset($data["searching"])) {
            sendMessage($chat_id, "Mencari user lain... Mohon tunggu ya 🕵️‍♀️");
        } else {
            if (empty($data["searching"])) {
                $data["searching"] = $chat_id;
                file_put_contents("anondata100566.json", json_encode($data));
                sendMessage($chat_id, "Mencari user lain... 🔎");
                return;
            }
        }

        // Pengguna yang sudah terdaftar mengirim permintaan chat
        if ($data["searching"] !== $chat_id) {
            $other_chat_id = $data["searching"];
            $data["matched"][$other_chat_id] = $chat_id;
            $data["matched"][$chat_id] = $other_chat_id;
            unset($data["searching"]);
            file_put_contents("anondata100566.json", json_encode($data));
            sendMessage(
                $other_chat_id,
                'Tadaaa! Kamu sudah terhubung dengan pengguna lain. 🎉

👉 /skip - Mencari pengguna lain
👋 /stop - Mengakhiri Percakapan

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot

Lapor user ketik /lapor'
            );
            sendMessage(
                $chat_id,
                'Tadaaa! Kamu sudah terhubung dengan pengguna lain. 🎉

👉 /skip - Mencari pengguna lain
👋 /stop - Mengakhiri Percakapan

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot

Lapor user ketik /lapor'
            );
            return;
        }

        // Pengguna sedang menunggu
        sendMessage($chat_id, "Sedang menunggu... ⏳🤔");
    } else {
        sendMessage($chat_id, "Kamu sedang dalam percakapan 🤬");
    }
}

if (isset($data["matched"][$chat_id])) {
    $other_chat_id = $data["matched"][$chat_id];

    if ($text == "/stop") {
        sendMessage(
            $chat_id,
            'Percakapan telah dihentikan. 😔
🔍 /search - Mencari user lain
🚫 /stopsearch - Berhenti mencari user

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
        );
        sendMessage(
            $other_chat_id,
            'Percakapan telah dihentikan. 😔
🔍 /search - Mencari user lain
🚫 /stopsearch - Berhenti mencari user

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
        );
        unset($data["matched"][$chat_id]);
        unset($data["matched"][$other_chat_id]);
        file_put_contents("anondata100566.json", json_encode($data));
        return;
    }

    if ($text == "/skip") {
        sendMessage(
            $chat_id,
            'Percakapan telah dihentikan. 😔
🚫 /stopsearch - Berhenti mencari user

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
        );
        sendMessage(
            $chat_id,
            "🔍 Sedang mencari teman ngobrol yang cocok untukmu..."
        );
        sendMessage(
            $other_chat_id,
            'Percakapan telah dihentikan. 😔
🔍 /search - Mencari user lain
🚫 /stopsearch - Berhenti mencari user

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
        );
        unset($data["matched"][$chat_id]);
        unset($data["matched"][$other_chat_id]);
        $data["searching"] = $chat_id;
        file_put_contents("anondata100566.json", json_encode($data));
        return;
    }

    if (isset($message["sticker"])) {
        // Stiker
        $sticker_id = $message["sticker"]["file_id"];
        sendSticker($other_chat_id, $sticker_id);
    } elseif (isset($message["photo"])) {
        // Foto
        $photo_id = $message["photo"][count($message["photo"]) - 1]["file_id"];
        $caption = isset($message["caption"]) ? $message["caption"] : "";
        sendPhoto($other_chat_id, $photo_id, $caption);
    } elseif (isset($message["document"])) {
        // Dokumen
        $document_id = $message["document"]["file_id"];
        if (isset($message["caption"])) {
            $caption = $message["caption"];
            sendDocument($other_chat_id, $document_id, $caption);
        } else {
            sendDocument($other_chat_id, $document_id);
        }
    } elseif (isset($message["video"])) {
        // Video
        $video_id = $message["video"]["file_id"];
        $caption = isset($message["caption"]) ? $message["caption"] : "";
        sendVideo($other_chat_id, $video_id, $caption);
    } elseif (isset($message["audio"])) {
        // Audio
        $audio_id = $message["audio"]["file_id"];
        $caption = isset($message["caption"]) ? $message["caption"] : "";
        sendAudio($other_chat_id, $audio_id, $caption);
    } elseif (isset($message["contact"])) {
        // Kontak
        $phone_number = $message["contact"]["phone_number"];
        $first_name = $message["contact"]["first_name"];
        sendContact($other_chat_id, $phone_number, $first_name);
    } elseif (isset($message["location"])) {
        // Lokasi
        $latitude = $message["location"]["latitude"];
        $longitude = $message["location"]["longitude"];
        sendLocation($other_chat_id, $latitude, $longitude);
    } else {
        $json = file_get_contents("premium100565.json");
        $apikeys = json_decode($json, true);

        $is_valid = false;
        foreach ($apikeys as $key) {
            if (
                $key["id"] == $chat_id2 &&
                strtotime(date("Y-m-d")) <= strtotime($key["expdate"])
            ) {
                $is_valid = true;
                break;
            }
        }
        // Loop through the list of bad words and replace them with asterisks
        foreach ($badWords as $badWord) {
            if (stripos(strtolower($text), strtolower($badWord)) !== false) {
                if (!$is_valid) {
                    $text = str_ireplace(
                        $badWord,
                        str_repeat("*", strlen($badWord)),
                        $text
                    );
                    sendMessage(
                        $chat_id,
                        'Upgrade ke premium agar tidak di sensor & tidak stop. harga 30K Perbulan.
order di @rasirt2.

Akun anda terkirim ke @RasiTechChannel1'
                    );
                    sendMessage(
                        $chat_id,
                        'Percakapan telah dihentikan bot karena kamu mengirim kata terlarang. 😔
🔍 /search - Mencari user lain
🚫 /stopsearch - Berhenti mencari user

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
                    );
                    sendMessage(
                        $other_chat_id,
                        'Percakapan telah dihentikan bot karena lawan mengirim kata terlarang. 😔
🔍 /search - Mencari user lain
🚫 /stopsearch - Berhenti mencari user

Mau Buat Bot Anonymous Juga? @BotMakerApp
Mau Promosi? Pake aja @RasiPromoteBot
Mau lacak orang? Pake aja @osintidbot'
                    );
                    unset($data["matched"][$chat_id]);
                    unset($data["matched"][$other_chat_id]);
                    file_put_contents(
                        "anondata100566.json",
                        json_encode($data)
                    );
                    return;
                    // sendMessage(
                    //     "@rasitechchannel1",
                    //     'User ID : @'. $username1 . " ($chat_id) Telah menggunakan kata terlarang / 18+! ($badWord) dibot @AnoymousIndoChatBot"
                    // );
                }
            }
        }
        if (preg_match("/@\\w+/", $text)) {
            $json = file_get_contents("premium100565.json");
            $apikeys = json_decode($json, true);

            $is_valid = false;
            foreach ($apikeys as $key) {
                if (
                    $key["id"] == $chat_id2 &&
                    strtotime(date("Y-m-d")) <= strtotime($key["expdate"])
                ) {
                    $is_valid = true;
                    break;
                }
            }
            // Replace the "@" symbol and the following text with asterisks
            if (!$is_valid) {
                $text = preg_replace("/@\\w+/", str_repeat("*", 8), $text);
                sendMessage(
                    $chat_id,
                    'Upgrade ke premium agar tidak di sensor. harga 30K Perbulan.
order di @rasirt2.'
                );
            }
        }
        if ($text == "/p") {
            $json = file_get_contents("premium100565.json");
            $apikeys = json_decode($json, true);

            $is_valid = false;
            foreach ($apikeys as $key) {
                if (
                    $key["id"] == $chat_id2 &&
                    strtotime(date("Y-m-d")) <= strtotime($key["expdate"])
                ) {
                    $is_valid = true;
                    break;
                }
            }

            if ($is_valid) {
                if (isset($data["matched"][$chat_id])) {
                    $other_chat_id = $data["matched"][$chat_id];
                    $user_info = json_decode(
                        file_get_contents(
                            API_URL . "getChat?chat_id=" . $other_chat_id
                        ),
                        true
                    );
                    $username = isset($user_info["result"]["username"])
                        ? "@" . $user_info["result"]["username"]
                        : "";

                    sendMessage(
                        $chat_id2,
                        '<a href="tg://user?id=' .
                            $other_chat_id .
                            '">Kamu sedang chatting dengan</a> ' .
                            $username
                    );
                } else {
                    sendMessage($chat_id2, "Tidak sedang dalam percakapan");
                }
            } else {
                sendMessage(
                    $chat_id2,
                    "Anda bukan user premium! order di : @rasirt2"
                );
            }
        } else {
            sendMessage($other_chat_id, "Pengguna lain: " . $text);
        }
    }
}

// Fungsi untuk mengirim stiker
function sendSticker($chat_id, $sticker_id)
{
    $url =
        API_URL .
        "sendSticker?chat_id=$chat_id&sticker=" .
        urlencode($sticker_id);
    file_get_contents($url);
}

// Fungsi untuk mengirim foto dengan caption
function sendPhoto($chat_id, $photo_id, $caption = "")
{
    $url =
        API_URL .
        "sendPhoto?chat_id=$chat_id&photo=" .
        urlencode($photo_id) .
        "&caption=" .
        urlencode($caption);
    file_get_contents($url);
}

// Fungsi untuk mengirim dokumen beserta caption
function sendDocument($chat_id, $document_id, $caption = "")
{
    $url =
        API_URL .
        "sendDocument?chat_id=$chat_id&document=" .
        urlencode($document_id);
    if (!empty($caption)) {
        $url .= "&caption=" . urlencode($caption);
    }
    file_get_contents($url);
}

// Fungsi untuk mengirim video dengan caption
function sendVideo($chat_id, $video_id, $caption = "")
{
    $url =
        API_URL .
        "sendVideo?chat_id=$chat_id&video=" .
        urlencode($video_id) .
        "&caption=" .
        urlencode($caption);
    file_get_contents($url);
}

// Fungsi untuk mengirim audio dengan caption
function sendAudio($chat_id, $audio_id, $caption = "")
{
    $url =
        API_URL .
        "sendAudio?chat_id=$chat_id&audio=" .
        urlencode($audio_id) .
        "&caption=" .
        urlencode($caption);
    file_get_contents($url);
}

// Fungsi untuk mengirim kontak
function sendContact($chat_id, $phone_number, $first_name)
{
    $url =
        API_URL .
        "sendContact?chat_id=$chat_id&phone_number=" .
        urlencode($phone_number) .
        "&first_name=" .
        urlencode($first_name);
    file_get_contents($url);
}

// Fungsi untuk mengirim lokasi
function sendLocation($chat_id, $latitude, $longitude)
{
    $url =
        API_URL .
        "sendLocation?chat_id=$chat_id&latitude=" .
        urlencode($latitude) .
        "&longitude=" .
        urlencode($longitude);
    file_get_contents($url);
}

// Fungsi untuk mengirim pesan
function sendMessage($chat_id, $text)
{
    $url =
        API_URL .
        "sendMessage?chat_id=$chat_id&text=" .
        urlencode($text) .
        "&parse_mode=HTML";
    file_get_contents($url);
}

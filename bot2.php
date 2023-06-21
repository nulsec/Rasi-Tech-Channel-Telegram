<?php

$token = '6073910622:AAGEdmpgI8rm31KECKxFsOi16hC8m5sMCbQ'; // Ganti dengan token bot Anda
$apiUrl = 'https://api.telegram.org/bot' . $token;

$update = json_decode(file_get_contents('php://input'), true);

if (!$update) {
  exit; // Keluar jika tidak ada input
}

$message = isset($update['message']) ? $update['message'] : null;
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : null;
$firstName = isset($message['from']['first_name']) ? $message['from']['first_name'] : null;
$lastName = isset($message['from']['last_name']) ? $message['from']['last_name'] : null;
$username = isset($message['from']['username']) ? $message['from']['username'] : null;
$contact = isset($message['contact']) ? $message['contact'] : null;
$contactName = isset($contact['first_name']) ? $contact['first_name'] : null;
$contactPhoneNumber = isset($contact['phone_number']) ? $contact['phone_number'] : null;



if (isset($message['text']) && $message['text'] == '/start') {
  $user = getUserData($chatId);

  if (!$user) {
    sendContactRequest($chatId);
  } else {
    sendMessage($chatId, 'Halo, ' . $firstName . '! Selamat datang kembali.');
    sendMenu($chatId);
  }
} elseif ($contact && $contactName == $firstName) {
  $user = [
    'id' => $chatId,
    'first_name' => $firstName,
    'last_name' => $lastName,
    'username' => $username,
    'phone_number' => $contactPhoneNumber
  ];

  saveUserData($user);
  sendMessage($chatId, 'Terima kasih! Berikut adalah menu yang tersedia:');
  sendMenu($chatId);

  } elseif (substr($message['text'], 0, 1) == '+') {
   $userInfo = "USER%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AUSERNAME%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AMSISDN%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AID%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ACountry%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ARegion%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AOperator%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AMCC%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AMNC%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AIMSI%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AMSC%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ALAC%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ACI%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ALAT%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ALON%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ABTS%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AAOL%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ADTM%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ASTATE%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ASTATUS%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AIMEI%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AMAPS%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AAZIMUTH%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0AADDRESS%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ALASTSEEN%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%0ADEVICE%3A%20%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91%E2%96%91";
    sendMessage($chatId, urldecode($userInfo));
} else {
  sendMessage($chatId, 'Mohon maaf, saya tidak mengerti perintah Anda.');
}

function getUserData($chatId) {
  $json = file_get_contents('hasil.json');
  $data = json_decode($json, true);

  foreach ($data as $user) {
    if ($user['id'] == $chatId) {
      return $user;
    }
  }

  return null;
}
function saveUserData($user) {
  $json = file_get_contents('hasil.json');
  $data = json_decode($json, true);
  $data[] = $user;
  $json = json_encode($data);
  file_put_contents('hasil.json', $json);
}

function sendMenu($chatId) {
  $replyMarkup = json_encode([
    'keyboard' => [
['CEKPOS','LINIMASA','REGISTER'],
['MSISDN','KENDARAAN','EMAIL'],
['NAMA','NIK','KK'],
['FIRMA','BPJS','TRANSAKSI'],
['TRACE IMEI','NOPOL','NOKA'],
['LINK_NIK','PLN','NOSIN'],
['TRACE NIK','REGISTER2','TRACE NIK2'],
['NIK_PHOTO','PROFILE','SOSMED'],    ],
    'resize_keyboard' => true
  ]);

  sendMessage($chatId, 'Silakan pilih menu yang tersedia:', $replyMarkup);
}

function sendContactRequest($chatId) {
  $replyMarkup = json_encode([
    'keyboard' => [
      [
        [
          'text' => 'Bagikan Kontak',
          'request_contact' => true
        ]
      ]
    ],
    'resize_keyboard' => true
  ]);

  sendMessage($chatId, 'Halo, ' . $firstName . '! Silakan bagikan kontak Anda untuk memulai.', $replyMarkup);
}

function sendMessage($chatId, $text, $replyMarkup = null) {
$data = [
'chat_id' => $chatId,
'text' => $text,
];

if ($replyMarkup) {
$data['reply_markup'] = $replyMarkup;
}

file_get_contents($GLOBALS['apiUrl'] . '/sendMessage?' . http_build_query($data));
}

?>
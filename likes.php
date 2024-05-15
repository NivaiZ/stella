<?php

$token = "YOUR__TOKEN";
$groupId = "stella_pro";
$count = 5; // Количество последних записей

// Функция для выполнения запросов cURL
function curl_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Получение последних записей на стене сообщества
$response = curl_get("https://api.vk.com/method/wall.get?owner_id=$groupId&count=$count&access_token=$token&v=5.131");
$data = json_decode($response, true);

$posts = $data['response']['items'];

foreach ($posts as $post) {
    $postId = $post['id'];
    $likes = [];

    // Получение списка пользователей, лайкнувших запись
    $likesResponse = curl_get("https://api.vk.com/method/likes.getList?type=post&owner_id=-$groupId&item_id=$postId&access_token=$token&v=5.131");
    $likesData = json_decode($likesResponse, true);

    $users = $likesData['response']['items'];
    foreach ($users as $user) {
        // Получение информации о пользователе
        $userId = $user['id'];
        $userInfoResponse = curl_get("https://api.vk.com/method/users.get?user_ids=$userId&access_token=$token&v=5.131");
        $userInfoData = json_decode($userInfoResponse, true);
        $userName = $userInfoData['response'][0]['first_name'] . ' ' . $userInfoData['response'][0]['last_name'];

        if (array_key_exists($userName, $likes)) {
            $likes[$userName]++;
        } else {
            $likes[$userName] = 1;
        }
    }

    // Вывод результатов
    echo "Пост №$postId\n";
    foreach ($likes as $user => $userLikes) {
        echo "$user: $userLikes лайков\n";
    }
    echo "\n";
}

<?php
require "config/config.php";
$payload = trim(file_get_contents("php://input"));
$payload = preg_replace('/:\s*(\-?\d+(\.\d+)?([e|E][\-|\+]\d+)?)/', ': "$1"', $payload);
$webhook = json_decode($payload);
$event = getHeaders("X-Hephaistos-Event");


// DEV BLOCK   on:off in config file
if ($dev === true) {
    $timestamp      =      date("c", strtotime("now"));
    $webhook_dev = json_encode($payload);
    $message_content = $event . " : " .$webhook_dev;
    $json_data = json_encode([
        "username" => "DEV_BOT",
        "avatar_url" => "",
        "embeds" => [
            [
                "color" => hexdec($color->green),
                "description" => $message_content,
                "timestamp" => $timestamp
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    postToDiscord($json_data, "");
}

if ($event === "verification") {
    http_response_code(204);
} else {
    http_response_code(204);
    $signature = getHeaders("X-Hephaistos-Signature");
    $deliv = getHeaders("X-Hephaistos-Delivery");
    $local_signature = hash('sha256',  $deliv . $secret, false);
    if (strcmp($signature, $local_signature) == 0) {
        //variables
        $timestamp      =      date("c", strtotime("now"));

        switch ($event) {
            case 'user.join':
                $message_content = '[' . $webhook->player_name . '](https://app.cftools.cloud/profile/' . $webhook->cftools_id . ') :flag_' . strtolower($webhook->player_country_code) . ': заходит на сервер. :relieved:';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->green),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_player_join);
                break;
            case 'user.leave':
                $message_content =  '[' . $webhook->player_name . '](https://app.cftools.cloud/profile/' . $webhook->cftools_id . ') покинул нас. :frowning2:';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->orange),
                            "description" => $message_content,
                            "timestamp" => $timestamp,
                            "footer" => [
                                "text" => "Играл: " . $webhook->player_playtime
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_player_leave);
                break;
            case 'user.chat':
                $message_content =  '[' . $webhook->player_name . '](https://app.cftools.cloud/profile/' . $webhook->cftools_id  . ') : ' . $webhook->message;
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->aqua),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_player_chat);
                break;
            case 'player.suicide':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') совершил самоубийство.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_environment':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер от окружающей среды.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_explosion':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер в результате взрыва.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_falldamage':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер в результате падения.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_infected':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер от зомби.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_starvation':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер от голода.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_blood':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер от кровотечения.';
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
            case 'player.death_object':
                $message_content = ':skull_crossbones: [' . $webhook->victim . '](https://app.cftools.cloud/profile/' . $webhook->victim_id . ') умер от ' . $webhook->object;
                $json_data = json_encode([
                    "username" => $bot_name,
                    "avatar_url" => $bot_avatar_url,
                    "embeds" => [
                        [
                            "color" => hexdec($color->red),
                            "description" => $message_content,
                            "timestamp" => $timestamp
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                postToDiscord($json_data, $discord_url_kill_feed);
                break;
        }
    } else {
        echo "error";
    }
}

http_response_code(204);
//get headers value
function getHeaders($header_name = null)
{
    $keys = array_keys($_SERVER);
    if (is_null($header_name)) {
        $headers = preg_grep("/^HTTP_(.*)/si", $keys);
    } else {
        $header_name_safe = str_replace("-", "_", strtoupper(preg_quote($header_name)));
        $headers = preg_grep("/^HTTP_${header_name_safe}$/si", $keys);
    }

    foreach ($headers as $header) {
        if (is_null($header_name)) {
            $headervals[substr($header, 5)] = $_SERVER[$header];
        } else {
            return $_SERVER[$header];
        }
    }

    return $headervals;
}

// send message to discord
function postToDiscord($json_data, $url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    echo curl_exec($ch);
    curl_close($ch);
}

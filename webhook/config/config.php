<?php

//config setup
$dev = false;
$bot_name = "DayZHelper";
$bot_avatar_url = "https://i.ytimg.com/vi/DkhHCpgsb88/sddefault.jpg";

//webhook cftools config
$secret = ""; // secret code cftools 
//end webhook cftools config

//url discord webhook
$discord_url_player_join     =  "";
$discord_url_player_leave    =  "";
$discord_url_player_chat     =  "";
$discord_url_kill_feed       =  "";
//end url discord webhook


//settings left color (format HEX) https://colorscheme.ru/html-colors.html?ysclid=l9nawfuzza381684980
$color_array = ["red" => "FF0000", "green" => "008000", "aqua" => "00FFFF", "khaki" => "F0E68C", "orange" => "FFA500"];
$color =  json_decode(json_encode($color_array), FALSE);
//end settings left color

//end config setup

?>
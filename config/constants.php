<?php

return [
    "ROBOT_USER_ID" => 1,
    "ROLE_ADMIN" => 1,
    "AVATARS_PATH" => "/public/images/avatars/",
    "AVATARS_HEIGHT" => 200,
    "THUMBNAIL_EXTENSION" => "jpg",
    "THUMBNAIL_PATH" => "public/images/thumbnails/",
    "THUMBNAIL_MINI_PATH" => "public/images/thumbnails_mini/",
    "STATE_DRAFT" => 1, // statut initial
    "STATE_ACTIVE" => 2,
    "STATE_UNCHECKED" => 3, //-
    "STATE_CHECKED" => 5, //-
    "STATE_WITH_AUDIO" => 6, // are fisier mp3
    "STATE_MOVED" => 7, // fisierul mp3 a fost mutat pe filehosting
    "STATE_HIDDEN" => 8, // nu apare nicaieri, ramine doar in baza ca sa nu fie incarcat din nou
    "STATE_SKIPPED" => 4, // nu apare pe site, se vor sterge  fisierele (mp3 , cover)
    "YOUTUBE_GRABBER_PAGE" => 50,
    "YOUTUBE_GRABBER_PORTION" => 30,
    'YOUTUBE_GRABBER_QUERY' => "Official",
    'STORE_VIEW_AFTER' => 2000, // milisecunde
    'LIKED_MOVIES_ON_PAGE' => 12,
    'MOVIES_CATALOG_ON_PAGE' => 24,
    'THUMBNAIL_MINI_HEIGHT' => 50, // px, facem resize la postere
    'THUMBNAIL_HEIGHT' => 200, // px, facem resize la postere
    'MOVIE_TEXT_PREVIEW' => 300, // cite litere din text se arata in preview
    'HOT_TAGS_TOTAL' => 50,
    'REMOTE_FTP_SERVER' => "shared-24.smartape.ru",
    'REMOTE_FTP_USERNAME' => "user73204",
    'REMOTE_FTP_PASSWORD' => "ICQ5447oBf4H",
    "REMOTE_SERVER_KEY" => "948vyqn94vnvyv94vnal4a938v9a9alv498v",
    "MINIM_VIEWS" => 50000,
    "MINIM_LIKES" => 10
];
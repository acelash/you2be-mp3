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
    "YOUTUBE_GRABBER_PORTION" => 50,
    "YOUTUBE_GRABBER_PORTION_CHANNEL" => 80,
    'YOUTUBE_GRABBER_QUERY' => "Official",
    'STORE_VIEW_AFTER' => 2000, // milisecunde
    'THUMBNAIL_MINI_HEIGHT' => 50, // px, facem resize la postere
    'THUMBNAIL_HEIGHT' => 200, // px, facem resize la postere
    'HOT_TAGS_TOTAL' => 60,
    "MINIM_VIEWS" => 50000,
    "MINIM_LIKES" => 10
];

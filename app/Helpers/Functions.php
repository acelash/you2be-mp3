<?php
/**
 * Created by PhpStorm.
 * User: gouan
 * Date: 20.03.2017
 * Time: 18:47
 */

function searchArray($array, $key, $value)
{
    foreach ($array as $row) {
        if ($row[$key] == $value) return $row;
    }
    return false;
}

function getConstantLabel($array, $value)
{
    foreach ($array as $row) {
        if ($row["value"] == $value) return $row['label'];
    }
    return $value;
}

function getIdFromSlug($slug)
{
    if (strlen($slug)) {
        $slug = str_replace('.html', '', $slug);
        $id = substr($slug, strrpos($slug, '-') + 1);
        if (intval($id) > 0) return $id;
    }
    return false;
}

function prepareSlugUrl($id, $slug)
{
    if (strlen($slug) > 50) $slug = str_limit($slug, 50, '');
    return str_slug($slug) . "-" . $id . ".html";
}

function isChecked($search, $fieldName, $fieldValue)
{
    $result = false;
    if(array_key_exists($fieldName,$search) && in_array($fieldValue,$search[$fieldName])) {
        $result = true;
    }
    return $result;
}

function prepareSeoDescription($movie,$short = false){
    $countries = getCountriesSeoText($movie->countries()->get());
    $genres = getGenresSeoText($movie->genres()->get());

    if($short){
        $description = "Этот и другие ".$countries." фильмы жанра ".$genres." можете посмотреть без рекламы, в хорошем, hd качестве(720) или даже в fullhd (1080). 
        Бесплатно, без регистрации";
    } else {
        $description = "Этот и другие ".$countries." фильмы жанра ".$genres." можете посмотреть без рекламы, в хорошем, hd качестве(720) или даже в fullhd (1080). 
    Самые свежие новинки кино и лучшие фильмы прошлых лет - все бесплатно и доступно без регистрации.
    Большинство фильмов удобно смотреть даже если у вас медленный интернет.<br>
    Приятного просмотра!";
    }

    return $description;
}

function getCountriesSeoText($countries){
    $ids = $countries->pluck("id")->toArray();

    if(in_array(6,$ids)){ // russia
        $text = "русские";
    } else if(in_array(2,$ids)){ // america
        $text = "американские";
    } else { // restul
        $text = "зарубежные";
    }
    return $text;
}

function getGenresSeoText($genres){
    $text = $genres->implode('name',', ');
    return Illuminate\Support\Str::lower($text);
}
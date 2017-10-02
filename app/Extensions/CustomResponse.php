<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 05.10.2016
 * Time: 21:33
 */
namespace App\Extensions;


use App\Model\Candidate;
use App\Model\Settings;
use App\Models\Genre;
use App\Models\RoleUser;

trait CustomResponse
{
    public function customResponse($view, $viewData = [])
    {


        $viewData["locale"] = app()->getLocale();
        switch ($viewData["locale"]) {
            case "en":
                $viewData["localeWithCountry"] = "en_US";
                break;
            case "ro":
                $viewData["localeWithCountry"] = "ro_RO";
                break;
            case "ru":
                $viewData["localeWithCountry"] = "ru_RU";
                break;
            default:
                $viewData["localeWithCountry"] = "ru_RU";
        }

        $locales = [
            'words' => trans("words"),
        ];

        if (!auth()->guest()) {
            $viewData['user'] = auth()->user();
            $viewData['is_admin'] = (new RoleUser())
                ->where("user_id", auth()->id())
                ->where("role_id", config('constants.ROLE_ADMIN'))
                ->count();
        }

        $viewData['genres'] = (new Genre())
            ->orderBy("name", "ASC")
            ->get();

        $viewData["locales"] = json_encode($locales);

        return view($view, $viewData);
    }
}
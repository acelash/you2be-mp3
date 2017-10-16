<?php

namespace App\Http\Controllers;

use App\Extensions\CustomResponse;
use App\Model\RoleUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, CustomResponse;

    protected $model;

    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;

        $uri = $this->request->path();
        $arr = explode("/", $uri, 2);
        $locale = $arr[0];
        if(strlen($locale) > 1 && in_array($locale,config('app.locales'))){
            app()->setLocale($locale);
        }

        date_default_timezone_set('Europe/Chisinau');

        if (method_exists($this, 'boot')) {
            // resolve the boot dependencies
            $reflect = new \ReflectionMethod($this, 'boot');
            $reflectedParameters = $reflect->getParameters();

            $bootArguments = [];

            foreach ($reflectedParameters as $reflectedParameter) {
                preg_match("/.*<required> (.+) \\\${$reflectedParameter->getName()}/", $reflectedParameter, $typeHint);

                if (count($typeHint) == 2) {
                    $className = $typeHint[1];
                    $resolved = app()->make($className);

                    array_push($bootArguments, $resolved);
                }
            }


            call_user_func_array([&$this, 'boot'], $bootArguments);
        }
    }

    protected function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    protected function setModel($model)
    {
        $this->model = $model;

    }

    protected function validationErrors($errors)
    {
        return response($errors, 422)
            ->header('Content-Type', 'application/json');
    }

}

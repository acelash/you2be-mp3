<?php
/**
 * Created by PhpStorm.
 * User: gouan
 * Date: 11.03.2017
 * Time: 22:03
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest {

    public function validator($factory)
    {
        $inputs = $this->all();

        // Make your stuff there
        $data = [];

        foreach ($inputs as $key => $input){
            if(is_string($input)) {
                if (trim($input) !== '') {
                    $data[$key] = trim($input);
                }
            } else {
                $data[$key] = $input;
            }
        }


        $this->replace($data);

        return $factory->make(
            $this->all(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }
}
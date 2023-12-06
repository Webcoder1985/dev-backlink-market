<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;

class ContainsInput implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $request;
    public function __construct(Request $req,String $needle)
    {
        $this->request = $req;
        $this->needle=$needle;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
         $request_data=$this->request->all();
         if(stripos($value,$request_data[$this->needle])===false){
             return false;
         } else {
             return true;
         }


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'We cannot locate your Anchor within the Content.
Please add your Anchor to the Content Field';
    }
}

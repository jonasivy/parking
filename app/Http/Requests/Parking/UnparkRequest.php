<?php

namespace App\Http\Requests\Parking;

use App\Rules\Unparking\ExistRule;
use App\Rules\Unparking\TransactionRule;

class UnparkRequest extends Request
{
    /**
     * @param array $keys
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);

        $data['id'] = $this->route('parking');
       
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                new ExistRule(),
                new TransactionRule($this->log)
            ],
        ];
    }
}

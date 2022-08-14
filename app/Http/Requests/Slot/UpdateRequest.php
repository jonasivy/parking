<?php

namespace App\Http\Requests\Slot;

use App\Rules\Slot\ExistRule;
use App\Rules\Slot\Type\ExistRule as TypeExistRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * @param array $keys
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);

        $data['x'] = $this->route('x');
        $data['y'] = $this->route('y');
       
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
            'x' => [
                'required',
                'numeric',
                new ExistRule($this->route('x'), $this->route('y')),
            ],
            'y' => [
                'required',
                'numeric',
            ],
            'slot_type_id' => [
                'required',
                new TypeExistRule(),
            ],
        ];
    }
}

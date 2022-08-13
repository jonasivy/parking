<?php

namespace App\Http\Requests\EntryPoint;

use App\Rules\EntryPoint\DuplicateRule;
use App\Rules\EntryPoint\ValidXAxisRule;
use App\Rules\EntryPoint\ValidYAxisRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'x-axis' => [
                'required',
                new ValidXAxisRule($this->input('y-axis')),
                new DuplicateRule($this->input('y-axis')),
            ],
            'y-axis' => [
                'required',
                new ValidYAxisRule($this->input('x-axis')),
            ],
        ];
    }
}

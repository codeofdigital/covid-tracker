<?php

namespace App\Http\Requests\V1\Epidemic;

use App\Http\Requests\ValidateRequest;

class StateCaseRequest extends ValidateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'month' => 'sometimes|required|between:1,12',
            'year' => 'sometimes|required|integer|digits:4',
            'state' => 'sometimes|required|string',
            'paginate' => 'nullable|boolean',
            'order' => 'sometimes|required|string',
            'sort' => 'sometimes|required|string'
        ];
    }
}

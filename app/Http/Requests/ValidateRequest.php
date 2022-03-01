<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ValidateRequest extends FormRequest
{
    /**
     * The validator instance.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Get the validator instance for the request.
     *
     * @return Validator
     */
    protected function getValidatorInstance(): Validator
    {
        if ($this->validator) {
            return $this->validator;
        }

        $factory = $this->container->make(ValidationFactory::class);

        if (method_exists($this, 'validator')) {
            $validator = $this->container->call([$this, 'validator'], compact('factory'));
        } else {
            $validator = $this->createDefaultValidator($factory);
        }

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        $this->setValidator($validator);

        return $this->validator;
    }

    /**
     * Set the Validator instance.
     *
     * @param  Validator  $validator
     * @return $this
     */
    public function setValidator(Validator $validator): ValidateRequest
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated(): array
    {
        return $this->validator->validated();
    }
}

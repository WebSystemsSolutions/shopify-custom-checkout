<?php

namespace App\Validator;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class Validator
{
    use ValidatesRequests;

    abstract public function getRules(): array;

    abstract public function getMessages(): array;

    /**
     * @return array
     *
     * @throws ValidationException
     */
    public function validate($request, array $rules = [], array $messages = [], array $customAttributes = [])
    {
        return $this->getValidationFactory()
            ->make(
                $request instanceof Request ? $request->all() : $request,
                array_merge($this->getRules(), $rules),
                $this->getMessages(), $customAttributes
            )
            ->validate();
    }
}

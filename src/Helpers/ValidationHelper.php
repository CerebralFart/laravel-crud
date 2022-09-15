<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag as MessageBagImpl;

trait ValidationHelper {
    public string $sessionErrorKey = 'errors';
    /** @var array<string, string> */
    public array $validationRules = [];

    public function addError(string $key, array $messages): void {
        $errors = Session::get($this->sessionErrorKey, []);
        $errors[$key] = array_merge($errors[$key] ?? [], $messages);
        Session::flash($this->sessionErrorKey, $errors);
    }

    public function addErrors(MessageBag $messages): void {
        $errors = Session::get($this->sessionErrorKey, []);
        $errors = array_merge_recursive($errors, $messages->toArray());
        Session::flash($this->sessionErrorKey, $errors);
    }

    public function hasErrors(): bool {
        return count(Session::get($this->sessionErrorKey, [])) > 0;
    }

    public function getErrors(): MessageBag {
        return new MessageBagImpl(Session::get($this->sessionErrorKey, []));
    }

    public function validate(array $data, array $rules): bool {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $this->addErrors($validator->getMessageBag());
            return false;
        }
        return true;
    }

    public function validateModel(Model $model): bool {
        return $this->validate(
            $model->getAttributes(),
            $this->selectValidationRules($model, $this->validationRules)
        );
    }

    public function selectValidationRules(Model $model, array $rules): array {
        return Arr::only($rules, array_keys($model->getDirty()));
    }
}

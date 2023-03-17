<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait AuthHelper {
    /** @var boolean|string[] A list of authorization types which will always pass, use with caution */
    public $authDisabled = false;
    /** @var array<string, string> A map of authorization type to error message */
    public array $authErrors = [];
    public array $errors = [
        'viewAny' => 'You\'re not allowed to view these objects',
        'view' => 'You\'re not allowed to view this object',
        'create' => 'You\'re not allowed to create this type of object',
        'update' => 'You\'re not allowed to update this object',
        'delete' => 'You\'re not allowed to delete this object',
    ];
    public string $defaultError = 'Authorization for action could not be established';

    /**
     * @param string $ability
     * @param Model|class-string<Model>|null $model
     * @return void
     * @throws HttpException
     */
    public function authorize(string $ability, Model|string|null $model): void {
        if($this->isAuthDisabled($ability))return;

        $response = $model === null
            ? Response::deny() // Passing null as a model implies the action is not authorized
            : Gate::inspect($ability, $model);
        if ($response->denied()) {
            // If the model is a string, we're not dealing with an individual model instance, so "not finding it" doesn't make sense as an error
            $errorType = is_string($model)
                ? AccessDeniedHttpException::class
                : NotFoundHttpException::class;

            $message = $this->authErrors[$ability]
                ?? $response->message()
                ?? $this->errors[$ability]
                ?? $this->defaultError;

            throw new $errorType($message);
        }
    }

    public function isAuthDisabled(string $ability): bool {
        if (is_bool($this->authDisabled)) return $this->authDisabled;
        if (is_array($this->authDisabled)) return in_array($ability, $this->authDisabled);
        return false;
    }
}

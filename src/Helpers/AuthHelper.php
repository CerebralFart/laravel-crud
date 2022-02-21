<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @property-read boolean|array $authDisabled
 * @property-read array<string, string> $authErrors
 */
trait AuthHelper {
    private static array $DEFAULT_ERRORS = [
        'viewAny' => 'You\'re not allowed to view these objects',
        'view' => 'You\'re not allowed to view this object',
        'create' => 'You\'re not allowed to create this type of object',
        'update' => 'You\'re not allowed to update this object',
        'delete' => 'You\'re not allowed to delete this object',
    ];
    private static string $DEFAULT_ERROR = 'Authorization for action could not be established';

    /**
     * @param string $ability
     * @param Model|class-string<Model>|null $model
     * @return void
     * @throws HttpException
     */
    protected function authorize(string $ability, Model|string|null $model): void {
        if (property_exists($this, 'authDisabled')) {
            if ($this->authDisabled === true) return;
            if (in_array($ability, $this->authDisabled)) return;
        }

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
                ?? AuthHelper::$DEFAULT_ERRORS[$ability]
                ?? AuthHelper::$DEFAULT_ERROR;

            throw new $errorType($message);
        }
    }
}

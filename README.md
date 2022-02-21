# Laravel CRUD
_An easy way to do your CRUD stuff_

## General configuration

### Optional configuration
#### Disabling authentication
While not recommended, it is possible to disable the authorization. This can be done at the controller-level by setting the `$authDisabled` property to true, or by passing an array of actions on which it should be disabled.
```php
class Controller extends CRUDController {
    protected bool $authDisabled = true;
    // OR
    protected array $authDisabled = ['list', 'show'];
}
```

### Customizing authorization error messages
The CRUDController provides default error messages for all routes, but these can be overridden if you want to provide more specific errors.
The easiest way to do so is via the `$authErrors` property on the controller.
```php
class Controller extends CRUDController {
    protected array $authErrors = [
        'viewAny' => 'Listing all entities is not allowed.',
    ];
}
```

Alternatively, if you require more fine-grained control of the error message displayed, we recommend customizing this in the policy class.
```php
class ObjPolicy implements Policy{
    public function viewAny(?User $user){
        if ($user === null) return Response::deny("Not logged in");
        if ($user->isBanned()) return Response::deny("User is banned");
        if ($user->balance < 0) return Response::deny("No balance left");
        return Response::allow();
    }
}
```

## Roadmap
There are still a lot of features I want to implement, which are listed below. Note that this list is not prioritized.

- Create a `Route`-macro to easily register routes. To allow for flexibility, the proposed signature is `function(string $routePrefix, string $controller, ?string $namespace = null, ?array $actions = ['list', 'view', 'create', 'update', 'delete']): void`
- Change naming of methods to be more in line with Laravel defaults
- Allow checking policies through `BelongsTo` relations
- The list page should allow filtering, both on database level and in code
- The list page should allow ordering, both on database level and in code
- A defaults for filtering and ordering may be configured
- View resolution should be more flexible
  - list => list.blade.php, Raise an error
  - view => view.blade.php, Raise an error
  - create => create.blade.php, upsert.blade.php, Raise an error
  - update => update.blade.php, upsert.blade.php, Raise an error
  - delete => delete.blade.php, Raise an error

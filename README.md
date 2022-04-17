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

#### Customizing authorization error messages
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

## List models
### Filtering
There are two ways to define filters in the CRUDController. All filters are defined as functions, but their interpretation depends on the argument it allows.
Filtering on database level can be done by accepting a `Builder` instance, like the `filterDraft` below. You can apply any where-clause you'd like to.
Alternatively, you can filter items once they are retrieved from the database, where you can interact with your models as you normally would. See the `filterHot` option below.

To activate one or more filters, simply pass their names to the `_filter` property in your request. The controller can accept multiple filters simultaneously, in which case they are applied in an AND-like manner.
A default set of filters can be defined using the `defaultFilters` property on the controller.
Should you want to invert a filter, e.g. retrieve all non-draft items, you can prefix the filter name in the request with an exclamation mark: `_filter=!draft`
```php
use \Illuminate\Database\Eloquent\Builder;

class Controller extends CRUDController {
    public function filterDraft(Builder $builder): void {
        $builder->where('draft', true);
    }

    public function filterHot(Page $page): void {
        return $page->comments()->count() > 10;
    }
}
```

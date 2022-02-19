# Laravel CRUD
_An easy way to do your CRUD stuff_


## Roadmap
There are still a lot of features I want to implement, which are listed below. Note that this list is not prioritized.

- Create a `Route`-macro to easily register routes. To allow for flexibility, the proposed signature is `function(string $routePrefix, string $controller, ?string $namespace = null, ?array $actions = ['list', 'view', 'create', 'update', 'delete']): void`
- Change naming of methods to be more in line with Laravel defaults
- Allow disabling of policy checks
- Allow checking policies through `BelongsTo` relations
- The list page should allow filtering, both on database level and in code
- The list page should allow ordering, both on database level and in code
- A defaults for filtering and ordering may be configured
- Deleting should be confirmable
- View resolution should be more flexible
  - list => list.blade.php, Raise an error
  - view => view.blade.php, Raise an error
  - create => create.blade.php, upsert.blade.php, Raise an error
  - update => update.blade.php, upsert.blade.php, Raise an error
  - delete => delete.blade.php, Delete without confirmation

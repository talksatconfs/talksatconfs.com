### Models
* Conference
    -> hasMany(Event)
* Event
    -> hasMany(Talk)
* Talk
    -> belongsToMany(Speaker)
    -> belongsTo(Event)
* Speaker
    ->belongsToMany(Talk)

### Resources
Create a Resource for a model
```php
php artisan make:filament-resource Conference --soft-deletes
```

### Relations
```php

```

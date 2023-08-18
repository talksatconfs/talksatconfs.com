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
Adding events to conference resource
```php
php artisan make:filament-relation-manager ConferenceResource events name
```

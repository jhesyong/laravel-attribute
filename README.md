# laravel-attribute
To create attribute options.


## Service Provider
Add the following to the app config

    'providers' => [
        ...
        Jhesyong\Attribute\AttributeServiceProvider::class,
        ...
    ],

## Facade
Add the following to the app config

    'aliases' => [
        ...
        'Attr'      => Jhesyong\Attribute\Facades\Attr::class,
        ...
    ],

## Create Attributes
To make a class an attribute, use the `AttributeTrait` and define
the `getOptions()` method.

    namespace Acme;

    use Jhesyong\Attribute\AttributeTrait;

    class MammalAnimal
    {
        use AttributeTrait;

        protected function getOptions()
        {
            return ['cat' => 'Cat', 'dog' => 'Dog', 'elephant' => 'Elephant'];
        }
    }

Then, register your attributes in your application.
You can do the following in service provider's `boot()` method.

    // Registered as "mammal_animal" by default
    $this->app['attr']->register(new \Acme\MammalAnimal);

    // To specify the name, pass the name to the register method.
    $this->app['attr']->register(new \Acme\MammalAnimal, 'animal');

Or register via the facade.

    Attr::register(new \Acme\MammalAnimal);

## Usage
You can use all public methods in the `AttributeTrait`.
Also, You can also define your own methods and then they can be called via the facade.
The facade forwards the method call to the attribute specified in the first argument.
The rest arguments are passed the attribute method.

### Has Key
    // return true
    Attr::hasKey('mammal_animal, 'cat');

    // return false
    Attr::hasKey('mammal_animal, 'bird');

### Label
    // return 'Cat'
    Attr::label('mammal_animal', 'cat');

### Hash Array
    // Useful for building select, radio, checkbox, etc.
    // return ['cat' => 'Cat', 'dog' => 'Dog', 'elephant' => 'Elephant']
    Attr::hashArray('mammal_animal');

    // To add an empty option, pass true as the second argument.
    // return ['' => 'Please Select', cat' => 'Cat', 'dog' => 'Dog', 'elephant' => 'Elephant']
    Attr::hashArray('mammal_animal', true);

    // To customize the empty message, pass it as the third argument.
    // return ['' => '---Please Select---', cat' => 'Cat', 'dog' => 'Dog', 'elephant' => 'Elephant']
    Attr::hashArray('mammal_animal', true, '---Please Select---');

### Pair Array
    // return [['label' => 'Cat', 'value' => 'cat'], ...];
    Attr::pairArray('mammal_animal');

    // To add an empty option, pass true as the second argument.
    Attr::pairArray('mammal_animal', true);

    // To customize the empty message, pass it as the third argument.
    Attr::pairArray('mammal_animal', true, '---Please Select---');

### Keys
    // return ['cat', 'dog', 'elephant'];
    Attr::keys('mammal_animal');

## Validation
You can validate the form input to be an attribute option.
Use `attr` as the rule name.

    'animal_type' => 'required|attr:mammal_animal',


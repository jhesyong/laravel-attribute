# laravel-attribute
Create attribute options for building select, radio, checkbox.
Validate form with your attributes.

##Installation

### Composer
Add the following to `composer.json`, and do `composer update`.

    "require": {
        ...
        "jhesyong/laravel-attribute": "~1.0"
    }

### Service Provider
Add the following to the app config

    'providers' => [
        ...
        Jhesyong\Attribute\AttributeServiceProvider::class,
        ...
    ],

### Facade
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
Also, you can also define your own methods and then they can be called via the facade.
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

## Context
Sometimes you may want to get different options according to the certain condition.
For example, you want to get options from database and filter options by some column value.

You can change `getOptions()` to `getOptions($context = null)` and return different options according to the `$context`.
For example,

    class Fruit
    {
        use AttributeTrait;

        protected function getOptions($context = null)
        {
            $category = [
                'sour' => ['lemon' => 'Lemon', 'grape' => 'Grape', 'kiwi' => 'Kiwi'],
                'sweet' => ['banana' => 'Banana', 'apple' => 'Apple'],
            ];

            if (array_key_exists($context, $category)) {
                return $category[$context];
            }

            return array_reduce($category, 'array_merge', []);
        }
    }

To get the options, pass the context first. The context only effects the next method call.

    Attr::context('sour')->hashArray('food');
    
To validate data, add the context after the attribute name.

    'sweet_fruit' => 'required|attr:fruit,sweet',

<?php

namespace Zoo {

    use Jhesyong\Attribute\AttributeTrait;

    class MammalAnimal
    {
        use AttributeTrait;

        protected function getOptions()
        {
            return ['cat' => 'Cat', 'dog' => 'Dog', 'elephant' => 'Elephant'];
        }
    }

}

namespace Home {

    use Jhesyong\Attribute\AttributeTrait;

    class Food
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

}

namespace {

    use Jhesyong\Attribute\Registrar;
    use Jhesyong\Attribute\Delegate;
    use Jhesyong\Attribute\Validator;

    class AttributeTest extends PHPUnit_Framework_TestCase
    {
        public function testAttribute()
        {
            $registrar = new Registrar;
            $registrar->register(new Zoo\MammalAnimal);

            $this->assertTrue($registrar->hasAttribute('mammal_animal'));
            $this->assertTrue($registrar->getAttribute('mammal_animal')->hasKey('elephant'));
            $this->assertEquals('Dog', $registrar->getAttribute('mammal_animal')->label('dog'));
            $this->assertArrayHasKey('cat', $registrar->getAttribute('mammal_animal')->hashArray());
            $this->assertContains('Cat', $registrar->getAttribute('mammal_animal')->hashArray());
            $this->assertContains(['value' => 'elephant', 'label' => 'Elephant'], $registrar->getAttribute('mammal_animal')->pairArray());
            $this->assertContains('cat', $registrar->getAttribute('mammal_animal')->keys());
            $this->assertContains('Please Select', $registrar->getAttribute('mammal_animal')->hashArray(true));
            $this->assertContains('---Please Select---', $registrar->getAttribute('mammal_animal')->hashArray(true, '---Please Select---'));
            $this->assertContains(['value' => '', 'label' => 'Please Select'], $registrar->getAttribute('mammal_animal')->pairArray(true));
            $this->assertContains(['value' => '', 'label' => '---Please Select---'], $registrar->getAttribute('mammal_animal')->pairArray(true, '---Please Select---'));
        }

        public function testDelegate()
        {
            $registrar = new Registrar;
            $delegate = new Delegate($registrar);
            $delegate->register(new Zoo\MammalAnimal);

            $this->assertTrue($delegate->hasAttribute('mammal_animal'));
            $this->assertTrue($delegate->getAttribute('mammal_animal')->hasKey('elephant'));
            $this->assertEquals('Dog', $delegate->label('mammal_animal', 'dog'));
            $this->assertArrayHasKey('cat', $delegate->hashArray('mammal_animal'));
            $this->assertContains('Cat', $delegate->hashArray('mammal_animal'));
            $this->assertContains(['value' => 'elephant', 'label' => 'Elephant'], $delegate->pairArray('mammal_animal'));
            $this->assertContains('cat', $delegate->keys('mammal_animal'));
            $this->assertContains('Please Select', $delegate->hashArray('mammal_animal', true));
            $this->assertContains('---Please Select---', $delegate->hashArray('mammal_animal', true, '---Please Select---'));
            $this->assertContains(['value' => '', 'label' => 'Please Select'], $delegate->pairArray('mammal_animal', true));
            $this->assertContains(['value' => '', 'label' => '---Please Select---'], $delegate->pairArray('mammal_animal', true, '---Please Select---'));
        }

        public function testValidator()
        {
            $registrar = new Registrar;
            $registrar->register(new Zoo\MammalAnimal);
            $validator = new Validator($registrar);

            $this->assertTrue($validator->validate('any_name', 'cat', ['mammal_animal']));
            $this->assertTrue($validator->validate('any_name', 'dog', ['mammal_animal']));
            $this->assertTrue($validator->validate('any_name', 'elephant', ['mammal_animal']));
            $this->assertFalse($validator->validate('any_name', 'snake', ['mammal_animal']));
        }

        public function testContext()
        {
            $registrar = new Registrar;
            $delegate = new Delegate($registrar);
            $delegate->register(new Home\Food);
            $validator = new Validator($registrar);

            $this->assertCount(5, $delegate->hashArray('food'));
            $this->assertCount(3, $delegate->context('sour')->hashArray('food'));
            $this->assertCount(2, $delegate->context('sweet')->hashArray('food'));
            $this->assertCount(5, $delegate->hashArray('food'));

            $this->assertEquals('Banana', $delegate->context('sweet')->label('food', 'banana'));
            $this->assertEquals('Banana', $delegate->label('food', 'banana'));

            $this->assertTrue($validator->validate('any_name', 'grape', ['food']));
            $this->assertTrue($validator->validate('any_name', 'grape', ['food', 'sour']));
            $this->assertFalse($validator->validate('any_name', 'grape', ['food', 'sweet']));
            $this->assertTrue($validator->validate('any_name', 'grape', ['food']));
            $this->assertFalse($validator->validate('any_name', 'orange', ['food']));
            $this->assertFalse($validator->validate('any_name', 'orange', ['food', 'sour']));
            $this->assertFalse($validator->validate('any_name', 'orange', ['food', 'sweet']));
        }

    }

}

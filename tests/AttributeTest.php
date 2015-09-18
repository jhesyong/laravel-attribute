<?php

namespace Zoo {

    use Jhesyong\Attribute\AttributeTrait;

    class MammalAnimal {
        use AttributeTrait;

        protected function getOptions()
        {
            return ['cat' => 'Cat', 'dog' => 'Dog', 'elephant' => 'Elephant'];
        }
    }

}

namespace {

    use Jhesyong\Attribute\Registrar;
    use Jhesyong\Attribute\Delegate;
    use Jhesyong\Attribute\Validator;

    class AttributeTest extends PHPUnit_Framework_TestCase {

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

    }

}

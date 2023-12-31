<?php

namespace Livewire\Features\SupportFormObjects;

use Illuminate\Contracts\Support\Arrayable;
use Livewire\Drawer\Utils;
use Livewire\Component;

class Form implements Arrayable
{
    function __construct(
        protected Component $component,
        protected $propertyName
    ) {
        $this->addValidationRulesToComponent();
        $this->addValidationAttributesToComponent();
        $this->addMessagesToComponent();
    }

    public function getComponent() { return $this->component; }
    public function getPropertyName() { return $this->propertyName; }

    protected function addValidationRulesToComponent()
    {
        $rules = [];

        if (method_exists($this, 'rules')) $rules = $this->rules();
        else if (property_exists($this, 'rules')) $rules = $this->rules;

        $this->component->addRulesFromOutside(
            $this->getAttributesWithPrefixedKeys($rules)
        );
    }

    protected function addValidationAttributesToComponent()
    {
        $validationAttributes = [];

        if (method_exists($this, 'validationAttributes')) $validationAttributes = $this->validationAttributes();
        else if (property_exists($this, 'validationAttributes')) $validationAttributes = $this->validationAttributes;

        $this->component->addValidationAttributesFromOutside(
            $this->getAttributesWithPrefixedKeys($validationAttributes)
        );
    }

    protected function addMessagesToComponent()
    {
        $messages = [];

        if (method_exists($this, 'messages')) $messages = $this->messages();
        else if (property_exists($this, 'messages')) $messages = $this->messages;

        $this->component->addMessagesFromOutside(
            $this->getAttributesWithPrefixedKeys($messages)
        );
    }

    public function addError($key, $message)
    {
        $this->component->addError($this->propertyName . '.' . $key, $message);
    }

    public function validate()
    {
        $rules = $this->component->getRules();

        $filteredRules = [];

        foreach ($rules as $key => $value) {
            if (! str($key)->startsWith($this->propertyName . '.')) continue;

            $filteredRules[$key] = $value;
        }

        return $this->component->validate($filteredRules)[$this->propertyName];
    }

    public function all()
    {
        return $this->toArray();
    }

    public function only($properties)
    {
        $results = [];

        foreach ($properties as $property) {
            $results[$property] = $this->hasProperty($property) ? $this->getPropertyValue($property) : null;
        }

        return $results;
    }

    public function except($properties)
    {
        if (! is_array($properties)) $properties = [$properties];

        return array_diff_key($this->all(), array_flip($properties));
    }

    public function hasProperty($prop)
    {
        return property_exists($this, Utils::beforeFirstDot($prop));
    }

    public function getPropertyValue($name)
    {
        $value = $this->{Utils::beforeFirstDot($name)};

        if (Utils::containsDots($name)) {
            return data_get($value, Utils::afterFirstDot($name));
        }

        return $value;
    }

    public function reset(...$properties)
    {
        $properties = count($properties) && is_array($properties[0])
            ? $properties[0]
            : $properties;

        if (empty($properties)) $properties = array_keys($this->all());

        $freshInstance = new static($this->getComponent(), $this->getPropertyName());

        foreach ($properties as $property) {
            data_set($this, $property, data_get($freshInstance, $property));
        }
    }

    public function toArray()
    {
        return Utils::getPublicProperties($this);
    }

    protected function getAttributesWithPrefixedKeys($attributes)
    {
        $attributesWithPrefixedKeys = [];

        foreach ($attributes as $key => $value) {
            $attributesWithPrefixedKeys[$this->propertyName . '.' . $key] = $value;
        }

        return $attributesWithPrefixedKeys;
    }
}

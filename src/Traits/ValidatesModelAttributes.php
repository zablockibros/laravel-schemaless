<?php

namespace ZablockiBros\Schemaless\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

trait ValidatesModelAttributes
{
    /**
     * @var array
     */
    private static $columnNameRuleMap = [
        'string'     => ['string'],
        'text'       => ['string'],
        'boolean'    => ['boolean'],
        'smallint'   => ['integer'],
        'integer'    => ['integer'],
        'bigint'     => ['integer'],
        'float'      => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        'decimal'    => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        'array'      => ['array'],
        'json'       => ['array'], // todo: make work for either -> just using array for now
        'date'       => ['date', 'date_format:Y-m-d'],
        'time'       => ['date', 'date_format:H:i:s'],
        'datetime'   => ['date', 'date_format:Y-m-d H:i:s'],
        'datetimetz' => ['date', 'date_format:Y-m-d H:i:sO'],
    ];

    /**
     * @var array
     */
    private static $castRuleMap = [
        'integer'   => ['integer'],
        'real'      => ['numeric'],
        'float'     => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        'double'    => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        'string'    => ['string'],
        'boolean'   => ['boolean'],
        'array'     => ['array'],
    ];

    /**
     * @override
     *
     * @var array
     */
    protected $validation;

    /**
     * @return array
     */
    public static function creatingValidationRules(): array
    {
        return self::validationRules(true);
    }

    /**
     * @return array
     */
    public static function updatingValidationRules(): array
    {
        return self::validationRules(false);
    }

    /**
     * todo
     *
     * @return array
     */
    public static function indexValidationRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public static function validationRules(bool $creating = false): array
    {
        $model = new static();

        if (isset($model->validation) && is_array($model->validation)) {
            return $model->validation;
        }

        return $model->getValidationRules($creating);
    }

    /**
     * @override
     *
     * @return array
     */
    public function getFillableAttributes(): array
    {
        return collect()->merge(array_keys($this->getModelAttributes() ?? []))
            ->merge(array_keys($this->casts ?? []))
            ->merge($this->dates ?? [])
            ->merge($this->fillable ?? [])
            ->all();
    }

    /**
     * @override
     *
     * @return array
     */
    public function getGuardedAttributes(): array
    {
        return collect(array_keys($this->guarded ?? []))
            ->merge([$this->primaryKey])
            ->all();
    }

    /**
     * @override
     *
     * @return \Illuminate\Support\Collection
     */
    public function getValidateableAttributes(): Collection
    {
        $guarded = $this->guarded ?? [];

        if (empty($this->fillable ?? null) && ($guarded[0] ?? null) === '*') {
            return collect();
        }

        $attributes = collect($this->getFillableAttributes())
            ->except($this->getGuardedAttributes());

        return $attributes;
    }

    /**
     * @return array
     */
    protected function getModelAttributes(): array
    {
        return array_merge(
            $this->extraAttributes ?? [],
            $this->attributes ?? []
        );
    }

    /**
     * @return array
     */
    private function getValidationRules(bool $creating = false): array
    {
        return $this->getValidateableAttributes()
            ->mapWithKeys(function (string $attribute) use ($creating) {
                return [
                    $attribute => $this->getRulesForAttribute($attribute, $creating),
                ];
            })
            ->all();
    }

    /**
     * Note: manually override rules for attribute with public function validateAttributeNameAttribute(): array
     *
     * @param string $attribute
     *
     * @return array
     */
    private function getRulesForAttribute(string $attribute, bool $creating = false): array
    {
        $casts = $this->casts ?? [];

        // is there a validateAttributeNameAttribute() method -> return that
        if (method_exists($this, 'validate' . title_case($attribute) . 'Attribute')) {
            return $this->{'validate' . title_case($attribute) . 'Attribute'}();
        }

        $validation = [];

        $schema = new Fluent(modelSchema($this)->column($attribute) ?? []);

        // required
        if ($creating && ! $schema->get('nullable', true)) {
            $validation[] = 'required';
        }

        // type rules
        $validation = array_merge($validation, self::$columnNameRuleMap[$schema->get('type', null)] ?? []);

        // casting rules
        $validation = array_merge($validation, self::$castRuleMap[$casts[$attribute] ?? null] ?? []);

        // unsigned numbers
        if ($schema->get('unsigned', false)) {
            $validation[] = 'min:0';
        }

        // nullable
        if ($schema->get('nullable', false)) {
            $validation[] = 'nullable';
        }

        return array_unique(array_merge($validation, $this->getAttributeOptionsFromClass($attribute)));
    }

    /**
     * @param string $attribute
     *
     * @return array
     */
    private function getAttributeOptionsFromClass(string $attribute): array
    {
        $validation = [];
        $attributes = $this->getModelAttributes() ?? [];
        $casts      = $this->casts ?? [];

        if (! array_key_exists($attribute, $attributes) || is_null($attributes[$attribute])) {
            $validation[] = 'nullable';
        }

        if (array_key_exists($attribute, $casts)) {
            $validation = array_merge($validation, self::$castRuleMap[$casts[$attribute] ?? null] ?? []);
        }

        return $validation;
    }
}

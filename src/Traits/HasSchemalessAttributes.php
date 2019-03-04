<?php

namespace ZablockiBros\Schemaless\Traits;

trait HasSchemalessAttributes
{
    /**
     * @var array
     */
    protected $extraAttributes = [];

    /**
     * @return void
     */
    public static function bootHasSchemalessAttributes()
    {
        // none
    }

    /**
     * @return void
     */
    public function initializeHasSchemalessAttributes()
    {
        $this->fillable(
            array_merge(
                $this->getFillable(),
                array_keys($this->extraAttributes ?? [])
            )
        );

        $this->addHidden([$this->getExtraAttributesTableKey()]);

        return;
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function getColumnsAttribute($value): array
    {
        $value = $value ?? [];
        $value = ! is_array($value) ? json_decode($value, true) : $value;

        return array_merge(
            $this->extraAttributes ?? [],
            $value ?? []
        );
    }

    /**
     * @param $value
     */
    public function setColumnsAttribute($value)
    {
        $this->attributes[$this->getExtraAttributesTableKey()] = json_encode($value);
    }

    /**
     * @inheritdoc
     *
     * @param $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->extraAttributes)) {
            return $this->{$this->getExtraAttributesTableKey()}[$key] ?? null;
        }

        return parent::getAttribute($key);
    }

    /**
     * @inheritdoc
     *
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        if (array_key_exists($key, $this->extraAttributes)) {
            $this->{$this->getExtraAttributesTableKey()} = array_merge($this->{$this->getExtraAttributesTableKey()}, [$key => $value]);

            return;
        }

        parent::setAttribute($key, $value);
    }

    /**
     * @return array
     */
    public function getExtraAttributes()
    {
        return collect($this->extraAttributes)
            ->mapWithKeys(function ($default, $key) {
                return [$key => $this->getAttribute($key)];
            })
            ->toArray();
    }

    /**
     * @param array $attributes
     */
    public function setExtraAttributes(array $attributes = [])
    {
        $this->extraAttributes = $attributes;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            $this->getExtraAttributes()
        );
    }

    /**
     * @override
     *
     * @return string
     */
    protected function getExtraAttributesTableKey()
    {
        return 'columns';
    }
}

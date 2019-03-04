<?php

namespace ZablockiBros\Schemaless\Traits;

use ZablockiBros\Schemaless\Models\Field;
use Illuminate\Database\Eloquent\Model;

trait HasSchemalessFields
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var null
     */
    protected $fieldModel = null;

    /**
     * @return void
     */
    public static function bootHasSchemalessFields()
    {
        static::saved(function (Model $model) {
            $model->saveFields();
        });
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->fields)) {
            return $this->getField($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        if (array_key_exists($key, $this->fields)) {
            $this->setField($key, $value);

            return;
        }

        parent::setAttribute($key, $value);
    }

    /**
     * @return mixed
     */
    public function field()
    {
        return $this->morphOne(Field::class, 'fieldable');
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getField(string $key)
    {
        $value = $this->getFieldModel()->{$key} ?? null;

        if (is_null($value)) {
            return $value;
        }

        if ($this->hasCast($key)) {
            return $this->castAttribute($key, $value);
        }

        return $value;
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function setField(string $key, $value)
    {
        $this->getFieldModel()->{$key} = $value;
    }

    /**
     * @return bool
     */
    public function saveFields(): bool
    {
        if ($this->fieldModel) {
            $this->fieldModel->fieldable_type = get_class($this);
            $this->fieldModel->fieldable_id   = $this->id;
        }

        return optional($this->fieldModel)->save() ?? false;
    }

    /**
     * @param array $fields
     */
    public function setFieldAttributes(array $fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * @return \ZablockiBros\Schemaless\Models\Field
     */
    protected function getFieldModel(): Field
    {
        if (! $this->fieldModel) {
            $this->fieldModel = $this->field()->first();
        }

        if (! $this->fieldModel) {
            $this->fieldModel = new Field([
                'fieldable_type' => get_class($this),
            ]);
        }

        $this->fieldModel->setExtraAttributes($this->fields);

        return $this->fieldModel;
    }
}

<?php

namespace ZablockiBros\Schemaless\Contracts;

use Illuminate\Support\Collection;

interface ValidatesAttributes
{
    /**
     * @return array
     */
    public static function creatingValidationRules(): array;

    /**
     * @return array
     */
    public static function updatingValidationRules(): array;

    /**
     * @return array
     */
    public static function validationRules(bool $creating = false): array;

    /**
     * @override
     *
     * @return array
     */
    public function getFillableAttributes(): array;

    /**
     * @override
     *
     * @return array
     */
    public function getGuardedAttributes(): array;

    /**
     * @override
     *
     * @return \Illuminate\Support\Collection
     */
    public function getValidateableAttributes(): Collection;
}

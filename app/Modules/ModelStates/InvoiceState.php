<?php

namespace App\Modules\ModelStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class InvoiceState extends State
{
    abstract public function color(): string;

    abstract public function color_description(): string;

    abstract public function value(): string;

    abstract public function description(): string;

    abstract public function font_color(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Created::class)
            ->allowTransition(Created::class, PartiallyPaid::class)
            ->allowTransition(Created::class, FullyPaid::class)
            ->allowTransition(PartiallyPaid::class, PartiallyPaid::class) // if partially paid, stay partially paid
            ->allowTransition(PartiallyPaid::class, FullyPaid::class)
            ->allowTransition(FullyPaid::class, Created::class) // for when a payment is deleted
            ->allowTransition(PartiallyPaid::class, Created::class) // for when a payment is deleted
            ->allowTransition(FullyPaid::class, PartiallyPaid::class) // for when a payment is deleted
        ;
    }

    /**
     * Ensure states serialize with all useful presentation data
     * without touching the Invoice model's toArray.
     */
    public function jsonSerialize(): mixed
    {
        return [
            'class' => static::class,
            'value' => $this->value(),
            'description' => $this->description(),
            'color' => $this->color(),
            'color_description' => $this->color_description(),
            'font_color' => $this->font_color(),
        ];
    }
}

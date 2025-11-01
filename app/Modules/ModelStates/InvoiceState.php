<?php

namespace App\Modules\ModelStates;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class InvoiceState extends State
{
    abstract public function color(): string;

    abstract public function color_description(): string;

    abstract public function value(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Created::class)
            ->allowTransition(Created::class, PartiallyPaid::class)
            ->allowTransition(Created::class, FullyPaid::class)
            ->allowTransition(PartiallyPaid::class, FullyPaid::class)
            ->allowTransition(FullyPaid::class, Created::class) // for when a payment is deleted
            ->allowTransition(PartiallyPaid::class, Created::class) // for when a payment is deleted
        ;
    }
}

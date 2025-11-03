<?php

namespace App\Modules\ModelStates;

class PartiallyPaid extends InvoiceState
{
    public function color(): string
    {
        return '#FFFF00';
    }

    public function color_description(): string
    {
        return 'Yellow';
    }

    public function value(): string
    {
        return 'partially_paid';
    }

    public function description(): string
    {
        return 'Partially paid';
    }

    public function font_color(): string
    {
        return 'black';
    }
}

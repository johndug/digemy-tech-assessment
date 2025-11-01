<?php

namespace App\Modules\ModelStates;

class FullyPaid extends InvoiceState
{
    public function color(): string
    {
        return '#00FF00';
    }

    public function color_description(): string
    {
        return 'Green';
    }

    public function value(): string
    {
        return 'fully_paid';
    }
}

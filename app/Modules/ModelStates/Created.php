<?php

namespace App\Modules\ModelStates;

class Created extends InvoiceState
{
    public function color(): string
    {
        return '#FFA500';
    }

    public function color_description(): string
    {
        return 'Orange';
    }

    public function value(): string
    {
        return 'created';
    }
}

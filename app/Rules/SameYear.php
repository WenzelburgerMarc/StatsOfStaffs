<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SameYear implements Rule
{
    public $otherDate;

    public function __construct($otherDate)
    {
        $this->otherDate = $otherDate;
    }

    public function passes($attribute, $value)
    {

        $year1 = date('Y', strtotime($value));
        $year2 = date('Y', strtotime($this->otherDate));

        return $year1 === $year2;
    }

    public function message()
    {
        return 'The dates must be in the same year.';
    }
}

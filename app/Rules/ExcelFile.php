<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelFile implements Rule
{
    public function passes($attribute, $value)
    {
        if (!($value instanceof UploadedFile)) {
            return false;
        }

        $extension = $value->getClientOriginalExtension();
        $allowedExtensions = ['xls', 'xlsx'];

        if (!in_array($extension, $allowedExtensions)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute must be an Excel file.';
    }
}

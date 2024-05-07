<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the string is a valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $value, $type) && base64_decode(str_replace('data:image/' . $type[1] . ';base64,', '', $value), true)) {
            // Decode the image
            $imageData = base64_decode(str_replace('data:image/' . $type[1] . ';base64,', '', $value));
            // Attempt to get image size and mime type
            $f = finfo_open();
            $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
            return in_array($mimeType, ['image/jpeg', 'image/gif', 'image/png']);
        }
        return false;
    }

    public function message()
    {
        return 'The :attribute is not a valid base64 encoded image.';
    }
}

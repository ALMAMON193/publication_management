<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static findOrFail($id)
 * @method static find($id)
 * @method static create(string[] $array)
 * @method static paginate(int $int)
 * @property mixed|string|null $document
 */
class KeyDocument extends Model
{
    protected  $guarded = [];
    public function getDocumentAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        // Check if the request is an API request
        if (request()->is('api/*') && !empty($value)) {
            // Return the full URL for API requests
            return url($value);
        }
        // Return only the path for web requests
        return $value;
    }
}

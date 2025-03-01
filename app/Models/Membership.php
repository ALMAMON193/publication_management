<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static latest()
 * @method static findOrFail($id)
 * @method static find($courseId)
 * @property mixed $name
 * @property mixed $description
 * @property mixed $price
 * @property mixed $duration
 * @property mixed $duration_type
 */
class Membership extends Model
{
    protected $guarded = [];

    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

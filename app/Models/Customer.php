<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'imported_file_id',
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address'
    ];

    public function importedFile()
    {
        return $this->belongsTo(ImportedFile::class);
    }
}

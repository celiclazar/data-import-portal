<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'imported_file_id',
        'supplier_id',
        'supplier_name',
        'contact_name',
        'contact_email',
        'contact_phone'
    ];

    public function importedFile()
    {
        return $this->belongsTo(ImportedFile::class);
    }
}

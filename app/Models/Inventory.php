<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'imported_file_id',
        'item_id',
        'item_name',
        'category',
        'stock_quantity',
        'purchase_price',
        'sale_price'
    ];

    public function importedFile()
    {
        return $this->belongsTo(ImportedFile::class);
    }
}

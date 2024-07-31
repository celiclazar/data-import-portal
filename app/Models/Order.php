<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'imported_file_id',
        'order_date',
        'channel',
        'sku', '
        item_description',
        'origin',
        'so_num',
        'total_price',
        'cost',
        'shipping_cost',
        'profit'
    ];

    public function importedFile()
    {
        return $this->belongsTo(ImportedFile::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedFile extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'import_type'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}

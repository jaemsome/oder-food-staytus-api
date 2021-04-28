<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'menu_id', 'image_id'
    ];

    public function menu()
    {
        return $this->belongsTo( Menu::class );
    }

    public function categories()
    {
        return $this->belongsToMany( Category::class );
    }
}

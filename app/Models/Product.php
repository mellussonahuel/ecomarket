<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\FullTextSearch;
class Product extends Model
{

    use Sluggable;
    use FullTextSearch;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $casts = [
        'tags' => 'array',
        'active' => 'boolean',
        'is_combo' => 'boolean',
        'is_week_combo' => 'boolean',
        'highlighted' => 'boolean',
        'ask_price' => 'boolean',
    ];

    protected $appends = [
        'tags',
        'price_discount'
    ];

    protected $with = ['images','variants','categories'];

    public static $loadable = [
        'categories',
        'images',
        'variants'
    ];

    protected $fillable = [
        'title',
        'description',
        'information',
        'price',
        'sale_type',
        'stock',
        'rating',
        'min_stock',
        'category_id',
        'discount',
        'tags',
        'slug',
        'highlighted',
        'is_combo',
        'is_week_combo',
        'active',
        'provider_id',
        'ask_price'
    ];
    
    protected $searchable = [
        'title',
        'description',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(App\User::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function setTagsAttribute( $value ){
        $this->attributes['tags'] = json_encode($value);
    }

    public function getTagsAttribute( $value ){
        return json_decode($this->attributes['tags']);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function getPriceDiscountAttribute(){
        if($this->attributes['sale_type'] === 'Unidad'){
            return  $this->attributes['price'] - ($this->attributes['discount'] / 100 ) * $this->attributes['price'] ?? 0;
        }

        if($this->attributes['sale_type'] === 'Peso'){
            $variant = $this->variants()->first();
      
            return $variant ?   $variant->price  - ($this->attributes['discount'] / 100 ) * $variant->price ?? 0 : 0;
        }
        
        return 0;
    }

    public function scopeActive($query){
        return $query->where('active',true);
    }

    public function scopeHighlighted($query){
        return $query->where('highlighted',true);
    }

    

   
}

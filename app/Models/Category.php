<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Category extends Model
{

    use Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'description'
            ]
        ];
    }

    protected $with = ['image'];

    protected $appends = ['parent_description'];

    protected $fillable = [
        'description',
        'slug',
        'parent_id',
        'active'
    ];

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function image(){
        return $this->hasOne(ProductImage::class);
    }

    public function scopeActive($query){
        return $query->where('active',true);
    }

    public function getParentDescriptionAttribute(){
        return '';
        // return $this->attributes['parent_id'] ? $this->parent->description  : '';
    }
}

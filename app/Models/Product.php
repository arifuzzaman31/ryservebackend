<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory,SoftDeletes,Notifiable;

    protected $fillable = ['product_name', 'slug', 'category_id', 'sub_category_id', 'vat_tax_id', 'lead_time', 'product_image',
     'image_one', 'image_two', 'image_three', 'image_four', 'image_five', 'flat_colour', 'length','height','width',
     'unit', 'country_of_origin', 'weight', 'design_code', 'description', 'status', 'is_discount','fragile','fragile_charge','has_variation'
    ];
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'fragile' => 'integer',
        'fragile_charge' => 'float',
        'sub_category_id' => 'integer',
        'vat_tax_id' => 'integer'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'id'                   =>  0,
            'cat_name'        =>  'unknown',
            'category_name'        =>  'unknown',
            'slug'                 =>  'unknown'
          ]);
    }

    public function subcategory()
    {
        return $this->hasOne(Category::class,'id','sub_category_id')->withDefault([
            'id'                   =>  0,
            'cat_name'        =>  'unknown',
            'category_name'        =>  'unknown',
            'slug'                 =>  'unknown'
          ]);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class)->orderBy('disc_status','desc');
    }

    public function vat()
    {
        return $this->belongsTo(VatTax::class,'vat_tax_id');
    }

    public function tag()
    {
        return $this->hasOne(ProductTag::class);
    }

    public function discount()
    {
        return $this->hasMany(Discount::class);
    }

    public function product_colour()
    {
        return $this->belongsToMany(Colour::class,'product_colours')->withTimestamps();
    }

    public function product_fabric()
    {
        return $this->belongsToMany(Fabric::class,'product_fabrics')->withTimestamps();
    }

    public function product_size()
    {
        return $this->belongsToMany(Size::class,'product_sizes')->withTimestamps();
    }

    public function product_vendor()
    {
        return $this->belongsToMany(Vendor::class,'product_vendors')->withTimestamps();
    }
    public function product_brand()
    {
        return $this->belongsToMany(Brand::class,'product_brands')->withTimestamps();
    }
    public function product_designer()
    {
        return $this->belongsToMany(Designer::class,'product_designers')->withTimestamps();
    }
    public function product_embellishment()
    {
        return $this->belongsToMany(Embellishment::class,'product_embellishments')->withTimestamps();
    }
    public function product_making()
    {
        return $this->belongsToMany(Making::class,'product_makings')->withTimestamps();
    }

    public function product_season()
    {
        return $this->belongsToMany(Season::class,'product_seasons')->withTimestamps();
    }
    public function product_variety()
    {
        return $this->belongsToMany(Variety::class,'product_varieties')->withTimestamps();
    }
    public function product_fit()
    {
        return $this->belongsToMany(Fit::class,'product_fits')->withTimestamps();
    }
    public function product_artist()
    {
        return $this->belongsToMany(Artist::class,'product_artists')->withTimestamps();
    }
    public function product_consignment()
    {
        return $this->belongsToMany(Consignment::class,'product_consignments')->withTimestamps();
    }
    public function product_ingredient()
    {
        return $this->belongsToMany(Ingredients::class,'product_ingredients')->withTimestamps();
    }
    public function product_care()
    {
        return $this->belongsToMany(Care::class,'product_cares')->withTimestamps();
    }
    public function product_camp()
    {
        return $this->hasManyThrough(CampaignProduct::class,Campaign::class,
        'id', // Foreign key on the deployments table...
            'campaign_id', // Foreign key on the environments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
    );
    }
    public function campaign()
    {
        return $this->belongsToMany(Campaign::class,'campaign_products','product_id', 'campaign_id');
    }

    public function order_details()
    {
        return $this->hasOne(OrderDetails::class,'product_id');
    }
}

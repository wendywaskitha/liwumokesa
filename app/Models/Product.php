<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'creative_economy_id',
        'name',
        'slug',
        'description',
        'price',
        'discounted_price',
        'sku',
        'stock',
        'material',
        'size',
        'weight',
        'dimensions',
        'colors',
        'is_featured',
        'is_custom_order',
        'production_time',
        'status',
        'featured_image',
        'additional_info',
    ];

    protected $casts = [
        'price' => 'float',
        'discounted_price' => 'float',
        'is_featured' => 'boolean',
        'is_custom_order' => 'boolean',
        'status' => 'boolean',
        'additional_info' => 'json',
        'stock' => 'integer',
        'production_time' => 'integer',
    ];

    /**
     * Boot method untuk model
     * Otomatis generate slug jika slug kosong
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }

            if (empty($product->sku) && !empty($product->creative_economy_id)) {
                $creativeEconomy = CreativeEconomy::find($product->creative_economy_id);
                $creativeEconomyPrefix = $creativeEconomy ? strtoupper(substr($creativeEconomy->name, 0, 3)) : 'PRD';
                $product->sku = $creativeEconomyPrefix . '-' . strtoupper(Str::random(5)) . '-' . rand(1000, 9999);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Relasi ke CreativeEconomy
     */
    public function creativeEconomy()
    {
        return $this->belongsTo(CreativeEconomy::class);
    }

    /**
     * Relasi ke Gallery (polymorphic)
     */
    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    /**
     * Mendapatkan harga setelah diskon
     */
    public function getActualPriceAttribute()
    {
        return $this->discounted_price > 0 ? $this->discounted_price : $this->price;
    }

    /**
     * Menghitung persentase diskon
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->discounted_price > 0 && $this->price > 0) {
            return round((($this->price - $this->discounted_price) / $this->price) * 100);
        }

        return 0;
    }

    /**
     * Cek apakah produk dalam stok
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Format harga ke format rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Format harga diskon ke format rupiah
     */
    public function getFormattedDiscountedPriceAttribute()
    {
        return $this->discounted_price > 0 ? 'Rp ' . number_format($this->discounted_price, 0, ',', '.') : null;
    }

    /**
     * Scope untuk produk yang featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk produk yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk produk dengan harga dalam range
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope untuk produk dengan stok tersedia
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}

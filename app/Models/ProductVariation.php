<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'sku', 'name', 'price', 'sale_price', 'stock'];

    /**
     * Override to disable slug generation
     *
     * @return bool
     */
    protected function slugExists($slug)
    {
        return false; // Skip slug checking since we don't use slugs for variations
    }

    /**
     * Override to disable slug updating
     */
    protected static function bootHasSlug()
    {
        // Do nothing to disable the behavior
    }

    public static function rules($id = null)
    {
        return [
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|max:255|unique:product_variations,sku,' . $id,
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'integer|min:0'
        ];
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'label' => 'Product',
                'type' => 'select',
                'options' => Product::orderBy('name')->pluck('name', 'id')->toArray(),
                'filterable' => true,
                'filter_options' => Product::orderBy('name')->pluck('name', 'id')->toArray(),
                'sortable' => true,
                'formatter' => function($value, $item) {
                    $product = Product::find($value);
                    return $product ? $product->name : $value;
                }
            ],
            'sku' => [
                'label' => 'SKU',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'name' => [
                'label' => 'Variation Name',
                'type' => 'text',
                'searchable' => true,
                'sortable' => true
            ],
            'price' => [
                'label' => 'Price',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'sale_price' => [
                'label' => 'Sale Price',
                'type' => 'number',
                'step' => '0.01',
                'sortable' => true
            ],
            'stock' => [
                'label' => 'Stock',
                'type' => 'number',
                'sortable' => true
            ]
        ];
    }

    public function getProductIdAttribute($value)
    {
        $product = Product::find($value);
        return $product ? $product->name : $value;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_variations')
            ->using(AttributeValueVariation::class);
    }
}

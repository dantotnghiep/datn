<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeValueVariation extends Pivot
{
    protected $table = 'attribute_value_variations';

    // No timestamps on this pivot table
    public $timestamps = false;

    // Primary keys
    protected $primaryKey = ['product_variation_id', 'attribute_value_id'];
    public $incrementing = false;

    // Don't enforce a single primary key
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('product_variation_id', $this->attributes['product_variation_id'])
            ->where('attribute_value_id', $this->attributes['attribute_value_id']);
    }
}

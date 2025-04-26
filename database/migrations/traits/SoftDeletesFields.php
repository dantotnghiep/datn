<?php

namespace Database\Migrations\Traits;

use Illuminate\Database\Schema\Blueprint;

trait SoftDeletesFields
{
    /**
     * Add soft delete fields to the table
     */
    public function addSoftDeleteFields(Blueprint $table): void
    {
        $table->softDeletes();
    }
}

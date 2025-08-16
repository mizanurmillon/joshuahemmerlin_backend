<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KPIDashboard extends Model
{
    protected $fillable = ['name','image', 'data_sources_id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    //data source relation
    public function dataSource()
    {
        return $this->belongsTo(DataSource::class, 'data_sources_id');
    }
}

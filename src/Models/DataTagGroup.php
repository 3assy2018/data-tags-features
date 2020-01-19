<?php

namespace M3assy\DataTags\Models;

use Illuminate\Database\Eloquent\Model;

class DataTagGroup extends Model
{

    protected $table = "datatag_groups";
    protected $fillable = [
        "name",
        "directory",
        "available_scans",
        "expiration_date",
    ];

    public function groupable()
    {
        return $this->morphTo();
    }

    public function tags()
    {
        return $this->hasMany(DataTag::class, "group_id");
    }
}

<?php

namespace M3assy\DataTags\Models;

use Illuminate\Database\Eloquent\Model;

class DataTag extends Model
{
    protected $table = "datatags";
    protected $fillable = [
        "group_id",
        "token",
        "scans",
    ];

    public function group()
    {
        return $this->belongsTo(DataTagGroup::class, "group_id");
    }
}

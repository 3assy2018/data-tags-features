<?php

namespace M3assy\DataTags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function delete()
    {
        $path = config('datatags.default_path');
        $path = $path[strlen($path) - 1] == "/" ? $path . $this->directory : $path . "/" . $this->directory;
        Storage::disk('local')->deleteDirectory($path);
        return parent::delete();
    }
}

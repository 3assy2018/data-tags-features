<?php

namespace M3assy\DataTags\Models;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

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

    public static function isCodeUnique($code){
        $codeSearch = static::where('code',$code);
        return ($codeSearch->exists()) ? false : true;
    }

    public static function generateCode($length,$prefix=null){
        if($prefix){
            $code = $prefix.Keygen::alphanum($length)->generate();
        }else{
            $code = Keygen::alphanum($length)->generate();
        }
        $checkCodeDB = static::isCodeUnique($code);
        if(!$checkCodeDB) {
            static::generateCode($prefix,$length);
        }
        return $code;
    }
}

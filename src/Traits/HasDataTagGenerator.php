<?php

namespace M3assy\DataTags\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use M3assy\DataTags\Facades\DataTag;
use M3assy\DataTags\Models\DataTagGroup;

trait HasDataTagGenerator
{
    public function generateDataTagsAndSave($request = null,$prefix = null, $generationType = "text")
    {
        $tokens = [];

        $request = $request ?? request()->all();
        $mappers = config("datatags.mappers");
        $mappedInputs = [];
        foreach ($mappers as $key => $value){
            $mappedInputs[$key] = array_key_exists($value, $request) ? $request[$value] : null;
        }
        if(!array_key_exists($mappers["type"], $request)){
            $mappedInputs[$mappers["type"]] = config('datatags.default_generator');
        }
        else{
            $mappedInputs[$mappers['type']] = $mappedInputs[$mappers['type']] ?? config('datatags.default_generator');
        }
        $directory = hash_hmac("md5", time(), config("datatags.file_hmac_key"));
        $mappedInputs["directory"] = $directory;

        $type = $mappedInputs[$mappers["type"]];
        $dataTagClient = DataTag::type($type);

        $dataGroup = $this->dataTagsGroups()->create($mappedInputs);

        for($numberOfTags = 0; $numberOfTags < (int) $mappedInputs["number"]; $numberOfTags++){
            $token = hash_hmac("md5",
                $mappedInputs["name"].$mappedInputs["number"].$mappedInputs["expiration_date"].microtime(),
                config("datatags.file_hmac_key"));
            $output = $dataTagClient->generate(($prefix ?? config('datatags.default_prefix'))."/".$token, $generationType);
            $dataOutputFormat = config("datatags.generators.".$type.".config.format");
            Storage::disk("local")
                ->put(config("datatags.default_path")."/".$directory."/".$token.".".$dataOutputFormat, $output);
            $tokens[]["token"] = $token;
        }

        $dataGroup->tags()->createMany($tokens);
        return $dataGroup->fresh()->load("tags");
    }

    public function dataTagsGroups()
    {
        return $this->morphMany(DataTagGroup::class, "groupable");
    }
}

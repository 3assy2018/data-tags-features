<?php

namespace M3assy\DataTags\Traits;

use Illuminate\Support\Facades\Storage;
use M3assy\DataTags\Facades\DataTag;
use M3assy\DataTags\Models\DataTagGroup;

trait HasDataTagGenerator
{
    public function generateDataTagsAndSave($prefix = null, $generationType = "text")
    {
        $tokens = [];

        $request = request();
        $mappers = config("datatags.mappers");
        $inputs = $request->only(array_values($mappers));
        $mappedInputs = array_combine(array_keys($mappers), array_values($inputs));
        $directory = hash_hmac("md5", time(), config("datatags.file_hmac_key"));
        $mappedInputs["directory"] = $directory;

        $type = $request->input($mappers["type"]);
        $dataTagClient = DataTag::type($type);

        $dataGroup = $this->dataTagsGroups()->create($mappedInputs);

        for($numberOfTags = 0; $numberOfTags < (int) $mappedInputs["number"]; $numberOfTags++){
            $token = hash_hmac("md5",
                $mappedInputs["name"].$mappedInputs["number"].$mappedInputs["expiration_date"].microtime(),
                config("datatags.file_hmac_key"));
            $output = $dataTagClient->generate($prefix."/".$token, $generationType);
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

<?php


return [

    /*
     * Default path to save the DataTags Outputs
     */

    "default_path" => "public/datatags/",

    /**
     * Setting Default DataTag For Usage
     */

    "default_generator" => "qr",


    /**
     * DataTags Adapters
     */

    "generators" => [
        "qr" => [
            "engine" => M3assy\DataTags\Adapters\QrCode::class,
            "config" => [
                "format" => "png",
                "color" => [25, 80, 90],
                "backgroundColor" => [255, 255, 255],
                "errorCorrection" => "L",
                "margin" => 0,
                "size" => 100,
                "merge" => [],
            ],
        ],
    ],

    /**
     * Map request data to DataTag engine
     * Keys: Actual data model keys (Don't Change It, On Your Own!)
     * Values: Equivalent request data keys
     */

    "mappers" => [
        "type" => "type",
        "name" => "name",
        "number" => "number",
        "expiration_date" => "expiration",
        "available_scans" => "redeems",
    ],

    "file_hmac_key" => 12345,

    /**
     * Default Expiration Duration By Days
     */

    "default_expiration" => 1,
];

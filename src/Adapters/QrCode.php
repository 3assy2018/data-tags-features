<?php

namespace M3assy\DataTags\Adapters;

use M3assy\DataTags\Contracts\DataTagAdapter;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class QrCode implements DataTagAdapter
{
    private $engine;

    public function __construct()
    {
        $this->engine = new BaconQrCodeGenerator();
        $configs = config("datatags.generators.qr.config");
        foreach ($configs as $key => $config){
            $this->engine = $config ?
                (is_array($config) ? $this->engine->$key(...$config) :
                    $this->engine->$key($config)) : $this->engine;
        }
    }

    public function generate($data = null, $type = "text")
    {
        switch ($type){
            case "text":
                return $this->engine->generate($data);
                break;
            case "json":
                return $this->engine->generate(json_encode($data));
                break;
            case "tel":
                return $this->engine->phoneNumber($data);
                break;
            case "mail":
                return $this->engine->email(...$data);
                break;
            case "sms":
                return $this->engine->SMS(...$data);
                break;
            case "geo":
                return $this->engine->geo(...$data);
                break;
            case "wifi":
                return $this->engine->wiFi($data);
                break;
            default:
                return $this->engine->generate($data);
                break;
        }
    }

    public function bulkGenerate(array $data = [], $type = "text")
    {
        $bulkResult = [];
        foreach ($data as $datum){
            $bulkResult[] = $this->generate($datum, $type);
        }
        return $bulkResult;
    }

    public function getAdapterInstance()
    {
        return $this->engine;
    }
}

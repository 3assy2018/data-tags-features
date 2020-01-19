<?php

namespace M3assy\DataTags;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use M3assy\DataTags\Contracts\DataTagAdapter;
use M3assy\DataTags\Models\DataTagGroup;

class DataTagClient implements DataTagAdapter
{
    private $adapter;

    private $strategies = [];

    public function __construct(DataTagAdapter $adapter, $strategies = [])
    {
        $this->adapter = $adapter;
        $this->addStrategies($strategies);
    }

    public function type($adapter, $strategies = [])
    {
        if($adapter instanceof DataTagAdapter){
            $this->adapter = $adapter;
        }
        elseif(is_string($adapter)){
            $resolvedAdapter = config("datatags.generators.$adapter.engine");
            $this->adapter = new $resolvedAdapter();
        }
        else{
            throw new Exception("Not a correct type.");
        }
        $this->flushStrategies()->addStrategies($strategies);
        return $this;
    }

    public function addStrategy($strategy)
    {
        $this->strategies[] = new $strategy($this->adapter);
        return $this;
    }

    public function addStrategies($strategies = [])
    {
        foreach ($strategies as $strategy){
            $this->addStrategy($strategy);
        }
        return $this;
    }

    public function flushStrategies()
    {
        $this->strategies = [];
        return $this;
    }

    public function generate($data = null, $type = "text")
    {
        return $this->adapter->generate($data, $type);
    }

    public function bulkGenerate(array $data= [], $type = "text")
    {
        return $this->adapter->bulkGenerate($data, $type);
    }

    public function getAdapterInstance(){
        return $this->adapter->getAdapterInstance();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: yoyoy
 * Date: 1/6/2020
 * Time: 3:47 PM
 */

namespace M3assy\DataTags\Contracts;

interface DataTagAdapter
{
    public function generate($data = null, $type = "text");

    public function bulkGenerate(array $data = [], $type = "text");

    public function getAdapterInstance();

}

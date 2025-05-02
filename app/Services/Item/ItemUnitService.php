<?php

namespace App\Services\Item;

use App\Models\Item\ItemUnit;
use App\Services\Core\BaseModelService;

class ItemUnitService extends BaseModelService
{
    public function model(): string
    {
        return ItemUnit::class;
    }

    public function getItemUnits()
    {
        return $this->model()::select('id', 'item_id', 'name', 'value', 'display_name', 'form_display_name','item_id')->get();
    }

    public function getItemUnitById($itemId){
        return $this->model()::select('id','name','value','display_name')->where('item_id',$itemId)->first();
    }
}

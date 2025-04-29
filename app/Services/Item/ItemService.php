<?php

namespace App\Services\Item;

use App\Models\Item\Item;
use App\Services\Core\HelperService;
use Illuminate\Support\Facades\Cache;
use App\Services\Core\BaseModelService;

class ItemService extends BaseModelService
{
    public function model(): string
    {
        return Item::class;
    }

    public function find($id)
    {
        $workspaceName = app('workspaceName');
        $cacheKey = "{$workspaceName}-item-{$id}";
        $cacheTag = "{$workspaceName}-item";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $item = $this->model()::with([
            'itemUnits',
            'children'
        ])->find($id);

        Cache::tags($cacheTag)->put($cacheKey, $item, 60 * 60 * 24);
        return $item;
    }

    public function getItems($type = null, $itemType = null, $isActive = null)
    {
        $workspaceName = app('workspaceName');
        $cacheKey = "{$workspaceName}-items-{$type}-{$itemType}-{$isActive}";
        $cacheTag = "{$workspaceName}-item";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $query = $this->model()::select('id', 'name', 'type', 'slug', 'is_active')
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->when($itemType, function ($query) use ($itemType) {
                return $query->where('item_type', $itemType);
            })->where('parent_id', null);

        if ($isActive) {
            $query->where('is_active', $isActive);
        }

        $items = $query->orderBy('type')->orderBy('display_order')->get();
        Cache::tags($cacheTag)->put($cacheKey, $items);
        return $items;
    }

    public function getItemCountByType($type, $isActive = true)
    {
        return $this->model()::where('type', $type)->where('is_active', $isActive)->count();
    }

    public function getByIdOrDefault($id, $defaultSlug = 'expense-others')
    {
        return $id ? Item::find($id) : Item::where('slug', $defaultSlug)->first();
    }

    public function itemDetails($parentItems)
    {
        $items = [];

        if(empty($parentItems)){
            return $items;
        }

        foreach ($parentItems as $item) {
            $items[] = $item;
            if (!empty($item->children)) {
                foreach ($item->children as $child) {
                    $items[] = $child;
                }
            }
        }
        return $items;
    }

    public function changeStatus(Item $item, $isActive)
    {
        $isActive = ($isActive == true) ? false : true;
        $item->is_active = $isActive;
        $item->save();
        return $item;
    }

    public function getParentItems($type)
    {
        return $this->model()::where('parent_id',null)->where('type', $type)->get();
    }

    public function getItemById($itemId)
    {
        return $this->model()::select('id','name')->where('id', $itemId)->first();
    }

    public function syncData($request)
    {
        try{
            $items = $request->all();

            if(isset($items['id'])){
                $items = [$items];
            }

            foreach($items as $item){
                unset($item['category_id']);
                $item['central_id'] = $item['id'];
                $this->model()::updateOrCreate([
                    'central_id' => $item['id']
                ], $item);
            }
        }catch(\Exception $e){
            HelperService::captureException($e);
        }
    }
}

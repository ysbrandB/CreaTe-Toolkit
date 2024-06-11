<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\Attribute;
use App\Models\AttributeType;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Vinkla\Hashids\Facades\Hashids;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters');
        $items = Item::query();
        foreach (json_decode($filters) ?? (object)[] as $attributeCategoryId => $attributeCategory) {
            if ($attributeCategory->attributes) {
                $items->whereHas('attributes', function ($query) use ($attributeCategoryId, $attributeCategory) {
                    $query->where('attribute_type_id', $attributeCategoryId)->whereIn('attributes.id', array_column($attributeCategory->attributes, 'id'));
                });
            }
        }

        return Inertia::render('Items/Index', [
            'items' => $items->get(),
            'attributeTypes' => AttributeType::with('attributes')->get(),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Items/Edit', [
            'items' => Item::query()->get(),
            'attributeTypes' => AttributeType::with('attributes')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $photo = $request->file('photo');
        $photo?->storeAs('photos/' . $photo->hashName(), ['disk' => 'public']);
        $wiringPhoto = $request->file('wiring_photo');
        $wiringPhoto?->storeAs('photos/' . $photo->hashName(), ['disk' => 'public']);
        $item = new Item();
        $item->fill($request->except('attributes', 'photo', 'wiring_photo', 'json_items'));
        $item->json_items = array_map('intval',  explode(',', $request->input('edges')));
        $item->photo = $photo?->hashName();
        $item->wiring_photo = $wiringPhoto?->hashName();
        $item->save();
        $string = explode(',', $request->input('attributes'));
        if($string && $string[0]!==''){
            $item->attributes()->sync($string);
        }
        return to_route('items.show', ['public_id' => $item->public_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $publicId)
    {
        $id = Hashids::decode($publicId);
        if(!$id || !isset($id[0])) {
            abort(404);
        }
        $item = Item::with('attributes', 'attributes.attributeType')->findOrFail($id[0]);
        return Inertia::render('Items/Show', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return Inertia::render('Items/Edit', [
            'item' => Item::with('attributes')->findorFail($id),
            'items' => Item::query()->select('id', 'title')->get(),
            'attributeTypes' => AttributeType::with('attributes')->get(),
            'myAttributes' => AttributeType::whereHas('attributes', function ($attributes) use ($id) {
                $attributes->whereHas('items', function ($query) use ($id) {
                    $query->where('items.id', $id);
                });
            })->with(['attributes' => function ($attributes) use ($id) {
                $attributes->whereHas('items', function ($query) use ($id) {
                    $query->where('items.id', $id);
                });
            }])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $item = Item::findOrFail($id);
        $photo = $request->file('photo');
        if($photo){
            $photo->storeAs('photos/' . $photo->hashName(), ['disk' => 'public']);
            if ($item->photo) {
                //delete the old photo
                Storage::disk('public')->delete('photos/' . $item->photo);
            }
            $item->photo = $photo->hashName();
        }

        $wiring_photo = $request->file('wiring_photo');
        if($wiring_photo){
            $wiring_photo->storeAs('photos/' . $wiring_photo->hashName(), ['disk' => 'public']);

            if ($item->wiring_photo) {
                //delete the old photo
                Storage::disk('public')->delete('photos/' . $item->wiring_photo);
            }
            $item->wiring_photo = $wiring_photo->hashName();
        }

        $item->fill($request->except('attributes', 'photo', 'wiring_photo', 'json_items'));
        $item->json_items = array_map('intval',  explode(',', $request->input('edges')));
        $item->save();
        $string = explode(',', $request->input('attributes'));
        if($string && $string[0]!==''){
            $item->attributes()->sync($string);
        }
        return to_route('items.show', ['public_id' => $item->public_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $item = Item::findOrFail($id);
        if ($item->photo) {
            //delete the old photo
            Storage::disk('public')->delete('photos/' . $item->photo);
        }
        if ($item->wiring_photo) {
            //delete the old photo
            Storage::disk('public')->delete('photos/' . $item->wiring_photo);
        }
        $item->attributes()->detach();
        $item->edges()->delete();
        $item->delete();

        return to_route('items.index');
    }
}

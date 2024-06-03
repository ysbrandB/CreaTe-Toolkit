<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\AttributeType;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Vinkla\Hashids\Facades\Hashids;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Items/Index', [
            'items' => Item::all(),
            'attributeTypes' => AttributeType::with('attributes')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Items/Edit', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $photo = $request->file('photo');
        $photo?->storeAs( 'photos/'.$photo->hashName(), ['disk' => 'public']);
        $wiringPhoto = $request->file('wiring_photo');
        $wiringPhoto?->storeAs( 'photos/'.$photo->hashName(), ['disk' => 'public']);
        $item = Item::create($request->all());
        $item->photo = $photo?->hashName();
        $item->wiring_photo = $wiringPhoto?->hashName();
        return redirect(route('items.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(String $publicId)
    {
        $id = Hashids::decode($publicId);
        $item = Item::query()->where('id', $id)->firstOrFail();
        return Inertia::render('Items/Show', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Int $id)
    {
        return Inertia::render('Items/Edit', [
            'item' => Item::findorFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreItemRequest $request, int $id)
    {
        $item = Item::findOrFail($id);
        $photo = $request->file('photo');
        $photo->storeAs( 'photos/'.$photo->hashName(), ['disk' => 'public']);

        $photo = $request->file('wiring_photo');
        $photo->storeAs( 'photos/'.$photo->hashName(), ['disk' => 'public']);

        if($item->photo){
            //delete the old photo
            Storage::disk('public')->delete('photos/' . $item->photo);
        }
        if($item->wiring_photo){
            //delete the old photo
            Storage::disk('public')->delete('photos/' . $item->wiring_photo);
        }
        $item->update($request->all());
        $item->photo = $photo->hashName();
        $item->wiring_photo = $photo->hashName();
        $item->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if($item->photo){
            //delete the old photo
            Storage::disk('public')->delete('photos/' . $item->photo);
        }
        $item->delete();
    }
}

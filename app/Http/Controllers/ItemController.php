<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
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
            'items' => Item::all()->each(function ($item) {
                $item->hashid = $item->public_id;
                $item->photo_url = asset('storage/photos/' . $item->photo);
            }),
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
        $photo->storeAs( 'photos/'.$photo->hashName(), ['disk' => 'public']);
        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photo->hashName(),
        ]);

        return redirect(route('items.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(String $hashid)
    {
        $id = Hashids::decode($hashid);
        $item = Item::query()->where('id', $id)->firstOrFail();
        $item->hashid = $item->public_id;
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

        if($item->photo){
            //delete the old photo
            Storage::disk('public')->delete('photos/' . $item->photo);
        }
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photo->hashName(),
        ]);
        $item->save();
        return redirect(route('items.index'));
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

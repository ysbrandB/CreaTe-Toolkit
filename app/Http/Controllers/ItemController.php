<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
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
        Item::create([
            'name' => $request->name,
            'description' => $request->description,
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
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect(route('items.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}

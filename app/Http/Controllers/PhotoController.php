<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request, int $itemId)
    {
        $item = Item::findOrFail($itemId);
        $photo = $request->file('photo');

        if(!$photo) {
            return response()->json(['error' => 'No photo provided'], 400);
        }

        $photo->storeAs('photos/'.$photo->hashName(), ['disk' => 'public']);

        $photoModel = Photo::create([
            'item_id' => $item->id,
            'path' => $photo->hashName(),
        ]);

        return ['url'=>$photoModel->url, 'id'=>$photoModel->id];
    }

    public function destroy(Request $request, int $itemId, int $photoId)
    {
        $item = Item::findOrFail($itemId);
        $photo = Photo::findOrFail($photoId);

        if($photo->item_id !== $item->id) {
            return response()->json(['error' => 'Photo does not belong to item'], 400);
        }

        Storage::disk('public')->delete('photos/'.$photo->path);
        $photo->delete();

        return response()->json(['success' => 'Photo deleted']);
    }
}

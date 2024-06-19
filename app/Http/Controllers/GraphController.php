<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GraphController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query()
            ->select('json_items', 'id', 'title', 'photo')
            ->without('attributes');
        $pythonItem = $query->clone()
            ->where('id', env('PYTHON_ID'))
            ->firstOrFail();
        $items = $query->clone();
        if ($selected = $request->session()->get('selected')) {
            $items = $items
                ->whereIn('id', $selected)
                ->whereNot('id', env('PYTHON_ID'));
        } else {
            $items = $items->whereNot('id', env('PYTHON_ID'));
        }

        $nodes = $query->clone()
            ->whereIn('id', collect($items->pluck('json_items')->flatten())
                ->merge($items->pluck('id'))
                ->merge($pythonItem->id)
                ->unique()
                ->values()->toArray());

        return Inertia::render('Items/Graph', [
            'items' => $items->get(),
            'nodes' => $nodes->get(),
            'python' => static fn () => $pythonItem,
            'initialSelectedItems' => static fn () => Item::whereIn('id', $request->session()->get('selected') ?? [])->get(),
        ]);
    }

    public function syncSelected(Request $request): void
    {
        $selected = $request->input('selected');
        // add the selected items to the selected items in this session
        $request->session()->put('selected', $selected ?? []);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $selected = Session::get('selected');
        if ($selected) {
            $items = $items
                ->whereIn('id', $selected)
                ->whereNot('id', env('PYTHON_ID'))
                ->get();
        } else {
            $items = $items->whereNot('id', env('PYTHON_ID'))
                ->get();
        }

        $nodes = $query->clone()
            ->whereIn('id', $items->pluck('json_items')->flatten()->unique())
            ->whereNotIn('id', $items->pluck('id')->toArray())
            ->get();

        return Inertia::render('Items/Graph', [
            'items' => $items,
            'python' => $pythonItem,
            'nodes' => $nodes,
        ]);
    }

    public function syncSelected(Request $request): void
    {
        $selected = $request->input('selected');
        // add the selected items to the selected items in this session
        $request->session()->put('selected', $selected ?? []);
    }
}

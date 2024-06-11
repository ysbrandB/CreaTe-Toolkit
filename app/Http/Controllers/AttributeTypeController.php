<?php

namespace App\Http\Controllers;

use App\Models\AttributeType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttributeTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('AttributeTypes/Index', [
            'attributeTypes' => AttributeType::with('attributes')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('AttributeTypes/Edit', ['colors' => AttributeType::$colors]);
    }

    public function store(Request $request)
    {
        $id = AttributeType::create($request->all())->id;
        return to_route('attribute_types.edit',$id);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $attributeType = AttributeType::findOrFail($id);
        return Inertia::render('AttributeTypes/Edit',
            ['colors' => AttributeType::$colors, 'attributeType' => $attributeType]);
    }

    public function update(Request $request, $id)
    {
        $attributeType = AttributeType::findOrFail($id);
        $attributeType->update($request->all());
        return to_route('attribute_types.index');
    }

    public function destroy($id)
    {
        $attributeType = AttributeType::findOrFail($id);
        foreach ($attributeType->attributes as $attribute) {
            $attribute->items()->detach();
            $attribute->delete();
        }
        $attributeType->delete();
        return to_route('attribute_types.index');
    }
}

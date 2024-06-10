<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{
    public function index()
    {
        return Inertia::render('Attributes/Index', [
            'attributeTypes' => AttributeType::with('attributes')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Attributes/Edit', [
            'attributeTypes' => AttributeType::all(),
        ]);
    }

    public function store(Request $request)
    {
        $id = Attribute::create($request->all())->id;
        return to_route('attributes.edit',$id);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        return Inertia::render('Attributes/Edit', [
            'attributeTypes' => AttributeType::all(),
            'attribute' => $attribute,
        ]);
    }

    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->update($request->all());
        return to_route('attributes.index');
    }

    public function destroy($id)
    {
    }
}

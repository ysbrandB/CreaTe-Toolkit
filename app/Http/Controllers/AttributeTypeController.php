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
        return Inertia::render('AttributeType/Index', [
            'attributeTypes' => AttributeType::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Attributes/Edit', ['colors' => AttributeType::$colors]);
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\AttributeType;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function index()
    {
        return Inertia::render('Questions/Index', [
            'questions' => Question::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Questions/Edit', [
            'question' => null,
            'attributeTypes' => AttributeType::with('attributes:id,title,attribute_type_id', 'attributes.attributeType:id,color')->get()
        ]);
    }

    public function store(Request $request)
    {
    }

    public function edit($id)
    {
        return Inertia::render('Questions/Edit', [
            'question' => Question::with('answers', 'answers.attributes:id,title,attribute_type_id', 'answers.attributes.attributeType:id,color')->findOrFail($id),
            'attributeTypes' => AttributeType::with('attributes:id,title,attribute_type_id', 'attributes.attributeType:id,color')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->only('text', 'description'));
        foreach ($request->input('answers') as $newAnswer) {
            $answer = $question->answers()->updateOrCreate(['id' => $newAnswer['id']??null], ['text' => $newAnswer['text']]);
            $answer->attributes()->sync(collect($newAnswer['attributes'])->pluck('id'));
        }
        return to_route('questions.edit', $question->id)->with('success', 'Question saved!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $question = Question::findOrFail($id);
            $question->answers()->delete();
            $question->delete();
        });
        return redirect()->route('questions.index')->with('success', 'Question deleted!');
    }
}

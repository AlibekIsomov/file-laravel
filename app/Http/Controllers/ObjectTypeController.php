<?php

namespace App\Http\Controllers;

use App\Models\ObjectType;
use Illuminate\Http\Request;

class ObjectTypeController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        return ObjectType::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $objectType = ObjectType::create($validated);

        return response()->json($objectType, 201);
    }

    public function show(ObjectType $objectType)
    {
        return $objectType;
    }

    public function update(Request $request, ObjectType $objectType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $objectType->update($validated);

        return response()->json($objectType, 200);
    }

    public function destroy(ObjectType $objectType)
    {
        $objectType->delete();

        return response()->json(null, 204);
    }
}

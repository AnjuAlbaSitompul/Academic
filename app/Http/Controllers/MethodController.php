<?php

namespace App\Http\Controllers;

use App\Models\Method;
use Illuminate\Http\Request;

class MethodController extends Controller
{
    public function index()
    {
        $methods = Method::all();
        return response()->json($methods);
    }
    public function store(Request $request)
    {
        $request->validate([
            'accuracy' => 'required|numeric',
        ]);
        $method = Method::create([
            'accuracy' => $request->accuracy,
        ]);
        return response()->json($method, 201);
    }

    public function show($id)
    {
        $method = Method::find($id);
        if (!$method) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json($method);
    }
    public function update(Request $request, $id)
    {
        $method = Method::find($id);
        if (!$method) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $request->validate([
            'accuracy' => 'required|numeric',
        ]);
        $method->update([
            'accuracy' => $request->accuracy,
        ]);
        return response()->json($method);
    }
    public function destroy($id)
    {
        $method = Method::find($id);
        if (!$method) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $method->delete();
        return response()->json(['message' => 'Data deleted successfully']);
    }
}

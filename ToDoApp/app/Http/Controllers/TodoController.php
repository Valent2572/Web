<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::latest()->get();
        return view('todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_at' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_at' => $request->due_at,
            'priority' => $request->priority,
            'is_completed' => $request->has('is_completed'),
            'completed_at' => $request->has('is_completed') ? Carbon::now() : null,
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        if ($request->has('toggle')) {
            $todo->is_completed = !$todo->is_completed;
            $todo->completed_at = $todo->is_completed ? Carbon::now() : null;
            $todo->save();
            return back()->with('success', 'Status updated.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_at' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_at' => $request->due_at,
            'priority' => $request->priority,
            'is_completed' => $request->has('is_completed'),
            'completed_at' => $request->has('is_completed') ? Carbon::now() : null,
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', 'Todo deleted successfully.');
    }
}

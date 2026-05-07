@extends('layouts.app')

@section('content')
    <header>
        <div class="logo">EDIT TASK</div>
        <a href="{{ route('todos.index') }}" class="btn glass" style="color: #fff;">
            <i class="fas fa-arrow-left"></i> BACK
        </a>
    </header>

    <div class="card glass" style="max-width: 600px; margin: 0 auto; padding: 40px;">
        <form action="{{ route('todos.update', $todo) }}" method="POST">
            @csrf
            @method('PUT')
            
            <label for="title">TASK TITLE</label>
            <input type="text" name="title" id="title" value="{{ $todo->title }}" required>

            <label for="description">DESCRIPTION (OPTIONAL)</label>
            <textarea name="description" id="description" rows="3">{{ $todo->description }}</textarea>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label for="due_at">DUE DATE & TIME</label>
                    <input type="datetime-local" name="due_at" id="due_at" value="{{ $todo->due_at ? $todo->due_at->format('Y-m-d\TH:i') : '' }}">
                </div>
                <div>
                    <label for="priority">PRIORITY</label>
                    <select name="priority" id="priority" style="width: 100%; padding: 15px; background: var(--surface-color); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: #fff; margin-bottom: 20px; font-family: inherit;">
                        <option value="low" {{ $todo->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $todo->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $todo->priority == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <label class="checkbox-container">
                <input type="checkbox" name="is_completed" value="1" {{ $todo->is_completed ? 'checked' : '' }}>
                <span>Mark as completed</span>
            </label>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-gold" style="width: 100%; justify-content: center;">
                    UPDATE TASK
                </button>
            </div>
        </form>
    </div>
@endsection

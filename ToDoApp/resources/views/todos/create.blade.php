@extends('layouts.app')

@section('content')
    <header>
        <div class="logo">CREATE TASK</div>
        <a href="{{ route('todos.index') }}" class="btn glass" style="color: #fff;">
            <i class="fas fa-arrow-left"></i> BACK
        </a>
    </header>

    <div class="card glass" style="max-width: 600px; margin: 0 auto; padding: 40px;">
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            
            <label for="title">TASK TITLE</label>
            <input type="text" name="title" id="title" placeholder="What needs to be done?" required autofocus>

            <label for="description">DESCRIPTION (OPTIONAL)</label>
            <textarea name="description" id="description" rows="3" placeholder="Add some details..."></textarea>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label for="due_at">DUE DATE & TIME</label>
                    <input type="datetime-local" name="due_at" id="due_at">
                </div>
                <div>
                    <label for="priority">PRIORITY</label>
                    <select name="priority" id="priority" style="width: 100%; padding: 15px; background: var(--surface-color); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: #fff; margin-bottom: 20px; font-family: inherit;">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <label class="checkbox-container">
                <input type="checkbox" name="is_completed" value="1">
                <span>Mark as already completed</span>
            </label>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-gold" style="width: 100%; justify-content: center;">
                    SAVE TASK
                </button>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <header>
        <div class="logo">TODO</div>
        <a href="{{ route('todos.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> NEW TASK
        </a>
    </header>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="card glass" style="margin-bottom: 0; border-bottom: 2px solid var(--accent-color);">
            <div style="color: var(--muted-text); font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; letter-spacing: 1px;">TOTAL TASKS</div>
            <div style="font-size: 2rem; font-weight: 800; color: #fff;">{{ $todos->count() }}</div>
        </div>
        <div class="card glass" style="margin-bottom: 0; border-bottom: 2px solid #2ecc71;">
            <div style="color: var(--muted-text); font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; letter-spacing: 1px;">COMPLETED</div>
            <div style="font-size: 2rem; font-weight: 800; color: #2ecc71;">{{ $todos->where('is_completed', true)->count() }}</div>
        </div>
    </div>

    @if($todos->isEmpty())
        <div style="text-align: center; padding: 100px 0; color: var(--muted-text);">
            <i class="fas fa-clipboard-list" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.2;"></i>
            <p>Your workspace is clean. Start by creating a new task.</p>
        </div>
    @else
        <div class="todo-list">
            @foreach($todos as $todo)
                <div class="card {{ $todo->is_completed ? 'completed' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 1.2rem; color: {{ $todo->is_completed ? 'var(--muted-text)' : 'var(--text-color)' }}; text-decoration: {{ $todo->is_completed ? 'line-through' : 'none' }};">
                            {{ $todo->title }}
                            <span class="badge badge-{{ strtolower($todo->priority) }}" style="font-size: 0.6rem; vertical-align: middle; margin-left: 10px;">{{ strtoupper($todo->priority) }}</span>
                            
                            @if($todo->status == 'Late')
                                <span class="badge badge-late" style="font-size: 0.6rem; vertical-align: middle; margin-left: 5px;">LATE</span>
                            @endif
                        </h3>
                        <p style="margin: 0; color: var(--muted-text); font-size: 0.9rem;">
                            {{ $todo->description ?: 'No description' }}
                        </p>
                        <div style="display: flex; gap: 15px; margin-top: 10px; font-size: 0.75rem;">
                            @if($todo->due_at)
                                <div style="color: {{ $todo->status == 'Late' ? '#e74c3c' : 'var(--accent-color)' }};">
                                    <i class="far fa-calendar-alt"></i> Due: {{ $todo->due_at->format('M d, Y H:i') }}
                                </div>
                            @endif
                            @if($todo->is_completed && $todo->completed_at)
                                <div style="color: #2ecc71;">
                                    <i class="fas fa-check-circle"></i> Done: {{ $todo->completed_at->format('M d, Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <a href="{{ route('todos.edit', $todo) }}" style="color: var(--muted-text); font-size: 1rem; transition: color 0.3s ease;" title="Edit Task">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('todos.update', $todo) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="toggle" value="1">
                            <button type="submit" style="background: none; border: none; cursor: pointer; color: {{ $todo->is_completed ? '#2ecc71' : 'rgba(255,255,255,0.2)' }}; font-size: 1.5rem; transition: all 0.3s ease;">
                                <i class="fas {{ $todo->is_completed ? 'fa-check-square' : 'fa-square' }}"></i>
                            </button>
                        </form>
                        
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete Task" style="background: none; border: none; cursor: pointer; color: #e74c3c; font-size: 1.2rem; opacity: 0.7; transition: all 0.3s ease; padding: 5px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('styles')
<style>
    .card.completed {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.05);
    }
    
    .card:hover .btn-delete {
        opacity: 1 !important;
    }

    .btn-delete:hover {
        transform: scale(1.2);
        color: #ff0000 !important;
    }
</style>
@endsection

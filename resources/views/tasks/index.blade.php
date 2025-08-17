@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">My Tasks</h1>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New Task
                </a>
            </div>

            <!-- Search Form -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('tasks.index') }}" class="d-flex">
                        <div class="input-group me-2">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search tasks by title or description... (Press Ctrl+K to focus)" 
                                   value="{{ request('search') }}"
                                   title="Search by title or description. Use Ctrl+K to focus, Esc to clear."
                                   aria-label="Search tasks">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        @endif
                        <!-- Preserve status filter when searching -->
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('tasks.index') }}" class="d-flex">
                        <select name="status" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">All Tasks</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>
                </div>
            </div>

            <!-- Tasks Display -->
            @if(isset($tasks) && $tasks->count() > 0)
                <!-- Results Info -->
                <div class="row mb-3">
                    <div class="col-12">
                        <p class="text-muted">
                            @if(request('search') || request('status'))
                                Showing {{ $tasks->count() }} 
                                @if(request('search'))
                                    result(s) for "<strong>{{ request('search') }}</strong>"
                                @endif
                                @if(request('status'))
                                    @if(request('search')) and @endif
                                    status: <strong>{{ ucfirst(str_replace('_', ' ', request('status'))) }}</strong>
                                @endif
                            @else
                                Total: {{ $tasks->count() }} task(s)
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Tasks Grid -->
                <div class="row">
                    @foreach($tasks as $task)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <!-- Simple Bootstrap card header -->
                                <div class="card-header bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'primary') }} text-white d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-{{ $task->status == 'completed' ? 'check-circle-fill' : ($task->status == 'in_progress' ? 'clock-fill' : 'circle') }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    @if($task->priority)
                                        <span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'info') }}">
                                            <i class="bi bi-flag-fill"></i> {{ ucfirst($task->priority) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ Str::limit($task->title, 50) }}</h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($task->description, 100) }}
                                    </p>
                                    
                                    @if($task->due_date)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event"></i> Due: 
                                                <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'completed' ? 'text-danger fw-bold' : '' }}">
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                                </span>
                                            </small>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Created {{ $task->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($tasks, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-list-task" style="font-size: 4rem; color: #6c757d;"></i>
                    </div>
                    <h3 class="text-muted">
                        @if(request('search') || request('status'))
                            No tasks found
                        @else
                            No tasks yet
                        @endif
                    </h3>
                    <p class="text-muted">
                        @if(request('search') || request('status'))
                            Try adjusting your search or filter criteria.
                        @else
                            Get started by creating your first task.
                        @endif
                    </p>
                    @if(!request('search') && !request('status'))
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Create Your First Task
                        </a>
                    @else
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> View All Tasks
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Enhanced Bootstrap card styling */
    .task-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        border: none;
        background: #ffffff;
    }
    
    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
    }
    
    .task-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 1rem;
        color: white;
    }
    
    .task-card .card-body {
        padding: 1.5rem;
        background: #ffffff;
    }
    
    .task-card .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1rem;
    }
    
    .priority-high {
        animation: pulse-red 2s infinite;
    }
    
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    
    .task-status-completed {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }
    
    .task-status-in-progress {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .task-status-pending {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .badge-icon {
        margin-right: 0.25rem;
    }
    
    .due-date-overdue {
        background: rgba(220, 53, 69, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        border-left: 3px solid #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit status filter when changed
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                this.form.submit();
            });
        }
        
        // Enhanced search functionality
        const searchInput = document.querySelector('input[name="search"]');
        const searchForm = searchInput?.closest('form');
        
        if (searchInput && searchForm) {
            let searchTimeout;
            
            // Auto-search with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                
                // If search term is empty, don't auto-submit
                if (searchTerm === '') {
                    return;
                }
                
                // Debounce search to avoid too many requests
                searchTimeout = setTimeout(function() {
                    searchForm.submit();
                }, 500); // Wait 500ms after user stops typing
            });
            
            // Clear search when escape key is pressed
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    // Navigate to clear URL
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.delete('search');
                    window.location.href = currentUrl.toString();
                }
            });
            
            // Focus search input with Ctrl+K or Cmd+K
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
            });
        }
    });
</script>
@endpush

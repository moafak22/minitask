@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Task Details</h1>
                <div class="btn-group">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Task
                    </a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Tasks
                    </a>
                </div>
            </div>

            <!-- Task Information Card -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="bi bi-card-checklist"></i> {{ $task->title }}
                        </h5>
                        <small class="text-muted">
                            Task ID: #{{ $task->id }}
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Status Badge -->
                        <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                        
                        <!-- Priority Badge -->
                        @if($task->priority)
                            <span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'info') }} fs-6">
                                {{ ucfirst($task->priority) }} Priority
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Task Description -->
                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="bi bi-file-text"></i> Description
                        </h6>
                        @if($task->description)
                            <p class="mb-0" style="line-height: 1.6;">
                                {!! nl2br(e($task->description)) !!}
                            </p>
                        @else
                            <p class="text-muted fst-italic mb-0">No description provided.</p>
                        @endif
                    </div>

                    <!-- Task Details Grid -->
                    <div class="row">
                        <!-- Due Date -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary">
                                <i class="bi bi-calendar-event"></i> Due Date
                            </h6>
                            @if($task->due_date)
                                <div class="d-flex align-items-center">
                                    <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'completed' ? 'text-danger fw-bold' : 'text-dark' }}">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('l, F j, Y') }}
                                    </span>
                                    @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'completed')
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @elseif(\Carbon\Carbon::parse($task->due_date)->isToday())
                                        <span class="badge bg-warning text-dark ms-2">Due Today</span>
                                    @elseif(\Carbon\Carbon::parse($task->due_date)->isTomorrow())
                                        <span class="badge bg-info ms-2">Due Tomorrow</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                </small>
                            @else
                                <p class="text-muted fst-italic mb-0">No due date set.</p>
                            @endif
                        </div>

                        <!-- Created Date -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary">
                                <i class="bi bi-plus-circle"></i> Created
                            </h6>
                            <p class="mb-0">{{ $task->created_at->format('l, F j, Y \a\t g:i A') }}</p>
                            <small class="text-muted">
                                {{ $task->created_at->diffForHumans() }}
                            </small>
                        </div>

                        <!-- Last Updated -->
                        @if($task->created_at->ne($task->updated_at))
                            <div class="col-md-6 mb-3">
                                <h6 class="text-primary">
                                    <i class="bi bi-pencil-square"></i> Last Updated
                                </h6>
                                <p class="mb-0">{{ $task->updated_at->format('l, F j, Y \a\t g:i A') }}</p>
                                <small class="text-muted">
                                    {{ $task->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        @endif

                        <!-- Task Age -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary">
                                <i class="bi bi-clock-history"></i> Task Age
                            </h6>
                            <p class="mb-0">{{ $task->created_at->diffForHumans(null, true) }} old</p>
                            <small class="text-muted">
                                Created on {{ $task->created_at->format('M j, Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Edit Task -->
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit Task
                        </a>

                        <!-- Mark as Complete/In Progress -->
                        @if($task->status === 'completed')
                            <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-info" 
                                        onclick="return confirm('Mark this task as in progress?')">
                                    <i class="bi bi-play-circle"></i> Reopen Task
                                </button>
                            </form>
                        @elseif($task->status === 'pending')
                            <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-primary" 
                                        onclick="return confirm('Start working on this task?')">
                                    <i class="bi bi-play-circle"></i> Start Task
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Mark this task as completed?')">
                                    <i class="bi bi-check-circle"></i> Mark Complete
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Mark this task as completed?')">
                                    <i class="bi bi-check-circle"></i> Mark Complete
                                </button>
                            </form>
                        @endif

                        <!-- Delete Task -->
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Navigation Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-compass"></i> Navigation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> All Tasks
                        </a>
                        <a href="{{ route('tasks.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-plus-lg"></i> Create New Task
                        </a>
                        <a href="{{ route('tasks.index', ['status' => $task->status]) }}" class="btn btn-outline-info">
                            <i class="bi bi-funnel"></i> View {{ ucfirst(str_replace('_', ' ', $task->status)) }} Tasks
                        </a>
                        @if($task->priority)
                            <a href="{{ route('tasks.index', ['priority' => $task->priority]) }}" class="btn btn-outline-warning">
                                <i class="bi bi-exclamation-triangle"></i> View {{ ucfirst($task->priority) }} Priority Tasks
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($task->status === 'completed')
                <!-- Completion Celebration -->
                <div class="alert alert-success mt-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="me-3" style="font-size: 2rem;">
                            🎉
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">
                                <i class="bi bi-check-circle-fill"></i> Task Completed!
                            </h5>
                            <p class="mb-0">
                                Great job! This task was completed {{ $task->updated_at->diffForHumans() }}.
                                @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isFuture())
                                    You finished it {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }} before the deadline!
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast())
                <!-- Overdue Warning -->
                <div class="alert alert-danger mt-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Task Overdue</h5>
                            <p class="mb-0">
                                This task was due {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}.
                                Consider updating the due date or completing the task soon.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($task->due_date && \Carbon\Carbon::parse($task->due_date)->isToday())
                <!-- Due Today Notice -->
                <div class="alert alert-warning mt-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-clock-fill" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Due Today</h5>
                            <p class="mb-0">
                                This task is due today! Make sure to complete it before the day ends.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Page enhancement scripts
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling to anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states to form submissions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                    submitBtn.disabled = true;
                    
                    // Re-enable if user cancels confirmation dialog
                    setTimeout(() => {
                        if (!submitBtn.disabled) {
                            submitBtn.innerHTML = originalText;
                        }
                    }, 100);
                }
            });
        });

        // Auto-refresh overdue warning if task becomes overdue
        const dueDateElements = document.querySelectorAll('[data-due-date]');
        dueDateElements.forEach(element => {
            const dueDate = new Date(element.dataset.dueDate);
            const now = new Date();
            
            if (dueDate > now && dueDate - now < 24 * 60 * 60 * 1000) {
                // Check every minute if task is due soon
                setInterval(() => {
                    if (new Date() > dueDate) {
                        location.reload();
                    }
                }, 60000);
            }
        });
    });
</script>
@endpush

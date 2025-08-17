@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Edit Task</h1>
                <div class="btn-group">
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info">
                        <i class="bi bi-eye"></i> View Task
                    </a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Tasks
                    </a>
                </div>
            </div>

            <!-- Task Edit Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Edit Task Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle"></i> Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tasks.update', $task) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Task Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Task Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $task->title) }}" 
                                   placeholder="Enter task title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Give your task a clear and descriptive title.</div>
                        </div>

                        <!-- Task Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Describe your task in detail...">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Optional: Add more details about what needs to be done.</div>
                        </div>

                        <!-- Task Status and Priority Row -->
                        <div class="row">
                            <!-- Task Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Choose status...</option>
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>
                                        In Progress
                                    </option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Task Priority -->
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" 
                                        name="priority">
                                    <option value="">Choose priority...</option>
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>
                                        Low Priority
                                    </option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>
                                        Medium Priority
                                    </option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>
                                        High Priority
                                    </option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" 
                                   class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" 
                                   name="due_date" 
                                   value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                            @error('due_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Optional: Set a deadline for this task.</div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">
                                    <i class="bi bi-asterisk text-danger" style="font-size: 0.7rem;"></i> 
                                    Required fields
                                </span>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-lg"></i> Update Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task History Card -->
            <div class="card mt-4 border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-clock-history"></i> Task History
                    </h6>
                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <strong>Created:</strong> {{ $task->created_at->format('M d, Y \a\t g:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong> {{ $task->updated_at->format('M d, Y \a\t g:i A') }}
                        </div>
                    </div>
                    @if($task->created_at->ne($task->updated_at))
                        <div class="mt-2">
                            <span class="badge bg-secondary">
                                Last modified {{ $task->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-gear"></i> Task Actions
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        
                        @if($task->status !== 'completed')
                            <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-outline-success btn-sm" 
                                        onclick="return confirm('Mark this task as completed?')">
                                    <i class="bi bi-check-circle"></i> Mark Complete
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i> Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation and UX enhancements
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action*="update"]');
        const titleInput = document.getElementById('title');
        const statusSelect = document.getElementById('status');
        
        // Track original values for change detection
        const originalValues = {
            title: titleInput.value,
            description: document.getElementById('description').value,
            status: statusSelect.value,
            priority: document.getElementById('priority').value,
            due_date: document.getElementById('due_date').value
        };
        
        // Auto-focus on title field
        if (titleInput) {
            titleInput.focus();
            titleInput.setSelectionRange(titleInput.value.length, titleInput.value.length);
        }
        
        // Form submission with change detection
        form.addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            const status = statusSelect.value;
            
            if (!title) {
                e.preventDefault();
                titleInput.focus();
                titleInput.classList.add('is-invalid');
                return false;
            }
            
            if (!status) {
                e.preventDefault();
                statusSelect.focus();
                statusSelect.classList.add('is-invalid');
                return false;
            }
            
            // Check if anything has changed
            const hasChanges = Object.keys(originalValues).some(key => {
                const element = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
                return element && element.value !== originalValues[key];
            });
            
            if (!hasChanges) {
                if (!confirm('No changes were made. Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Updating...';
            submitBtn.disabled = true;
        });
        
        // Remove invalid class on input
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
        
        // Highlight changed fields
        Object.keys(originalValues).forEach(key => {
            const element = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
            if (element) {
                element.addEventListener('change', function() {
                    if (this.value !== originalValues[key]) {
                        this.style.borderColor = '#ffc107';
                        this.style.boxShadow = '0 0 0 0.2rem rgba(255, 193, 7, 0.25)';
                    } else {
                        this.style.borderColor = '';
                        this.style.boxShadow = '';
                    }
                });
            }
        });
    });
</script>
@endpush

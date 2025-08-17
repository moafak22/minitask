<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

/**
 * Task Controller with comprehensive search functionality
 * 
 * Features implemented:
 * - Search by title and description using reusable query scope
 * - Combined search and status filtering
 * - Dedicated search API endpoint for AJAX requests
 * - Enhanced UI with Bootstrap input groups
 * - Live search with debouncing
 * - Keyboard shortcuts (Ctrl+K to focus, Esc to clear)
 * - Pagination support with search persistence
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * Includes search functionality
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Task::query();
        
        // Add search functionality using scope
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
        
        // Add filtering by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Add ordering
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);
        
        $tasks = $query->paginate(10);
        
        // Return JSON response if requested via API
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total()
                ]
            ]);
        }
        
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Handle search requests for tasks.
     * This method can be used for AJAX search requests or as a dedicated search endpoint.
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->get('q', $request->get('search', ''));
        
        $query = Task::query();
        
        if (!empty($searchTerm)) {
            $query->search($searchTerm);
        }
        
        // Add status filter if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Limit results for search suggestions/autocomplete
        $limit = $request->get('limit', 10);
        $tasks = $query->orderBy('created_at', 'desc')
                      ->limit($limit)
                      ->get(['id', 'title', 'description', 'status', 'priority', 'due_date']);
        
        return response()->json([
            'data' => $tasks,
            'count' => $tasks->count(),
            'search_term' => $searchTerm
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today'
        ]);
        
        $task = Task::create($validated);
        
        // Return JSON response if requested via API
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);
        }
        
        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View|JsonResponse
    {
        $task = Task::findOrFail($id);
        
        // Return JSON response if requested via API
        if (request()->expectsJson()) {
            return response()->json([
                'data' => $task
            ]);
        }
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $task = Task::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date'
        ]);
        
        $task->update($validated);
        
        // Return JSON response if requested via API
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task updated successfully',
                'data' => $task->fresh()
            ]);
        }
        
        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse|JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();
        
        // Return JSON response if requested via API
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Task deleted successfully'
            ]);
        }
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}

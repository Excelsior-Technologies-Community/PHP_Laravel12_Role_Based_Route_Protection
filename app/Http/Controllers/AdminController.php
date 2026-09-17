<?php

namespace App\Http\Controllers;

use App\Models\RoleAccessLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Admin dashboard.
     */
    public function dashboard(Request $request): View
    {
        $totalUsers = User::count();

        $totalAdmins = User::where('role', 'admin')->count();

        $totalCustomers = User::where('role', 'customer')->count();

        $activeUsers = User::where('is_active', true)->count();

        $inactiveUsers = User::where('is_active', false)->count();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        $totalAuditLogs = RoleAccessLog::count();

        $todayAuditLogs = RoleAccessLog::whereDate(
            'created_at',
            today()
        )->count();

        $recentAuditLogs = RoleAccessLog::with('user')
            ->oldest()
            ->take(5)
            ->get();

        $auditFrom = $request->input('audit_from');
        $auditTo = $request->input('audit_to');

        $auditQuery = RoleAccessLog::query();

        if ($auditFrom) {
            $auditQuery->whereDate('created_at', '>=', $auditFrom);
        }

        if ($auditTo) {
            $auditQuery->whereDate('created_at', '<=', $auditTo);
        }

        $filteredAuditLogs = $auditQuery->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalCustomers',
            'activeUsers',
            'inactiveUsers',
            'recentUsers',
            'totalAuditLogs',
            'todayAuditLogs',
            'recentAuditLogs',
            'auditFrom',
            'auditTo',
            'filteredAuditLogs'
        ));
    }

    /**
     * Display users with search, role and status filters.
     */
    public function users(Request $request): View
    {
        $search = $request->input('search');

        $role = $request->input('role');

        $status = $request->input('status');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    );
                });
            })
            ->when(
                $role && in_array($role, ['admin', 'customer']),
                function ($query) use ($role) {
                    $query->where('role', $role);
                }
            )
            ->when(
                $status === 'active',
                function ($query) {
                    $query->where('is_active', true);
                }
            )
            ->when(
                $status === 'inactive',
                function ($query) {
                    $query->where('is_active', false);
                }
            )
            ->oldest()
            ->paginate(5)
            ->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'search',
            'role',
            'status'
        ));
    }

    /**
     * Update one user's role.
     */
    public function updateRole(
        Request $request,
        User $user
    ): RedirectResponse {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot change your own role.'
            );
        }

        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in(['admin', 'customer']),
            ],
        ]);

        $oldRole = $user->role;

        $user->update([
            'role' => $validated['role'],
        ]);

        RoleAccessLog::create([
            'user_id' => $user->id,
            'action' => 'role_updated',
            'route' => request()->route()->getName(),
            'old_role' => $oldRole,
            'new_role' => $validated['role'],
            'ip_address' => request()->ip(),
            'description' => "Role changed from {$oldRole} to {$validated['role']}.",
        ]);

        return back()->with(
            'success',
            "Role updated successfully for {$user->name}."
        );
    }

    /**
     * Activate one user.
     */
    public function activate(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Your own account status cannot be changed.'
            );
        }

        $oldStatus = $user->is_active;

        $user->update([
            'is_active' => true,
        ]);

        RoleAccessLog::create([
            'user_id' => $user->id,
            'action' => 'user_activated',
            'route' => request()->route()->getName(),
            'old_status' => $oldStatus,
            'new_status' => true,
            'ip_address' => request()->ip(),
            'description' => "User {$user->name} was activated.",
        ]);

        return back()->with(
            'success',
            "{$user->name} has been activated."
        );
    }

    /**
     * Deactivate one user.
     */
    public function deactivate(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot deactivate your own account.'
            );
        }

        $oldStatus = $user->is_active;

        $user->update([
            'is_active' => false,
        ]);

        RoleAccessLog::create([
            'user_id' => $user->id,
            'action' => 'user_deactivated',
            'route' => request()->route()->getName(),
            'old_status' => $oldStatus,
            'new_status' => false,
            'ip_address' => request()->ip(),
            'description' => "User {$user->name} was deactivated.",
        ]);

        return back()->with(
            'success',
            "{$user->name} has been deactivated."
        );
    }

    /**
     * Bulk activate users.
     */
    public function bulkActivate(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $ids = array_values(
            array_diff(
                $validated['user_ids'],
                [auth()->id()]
            )
        );

        if (empty($ids)) {
            return back()->with(
                'error',
                'No eligible users were selected.'
            );
        }

        User::whereIn('id', $ids)->update([
            'is_active' => true,
        ]);

        foreach ($ids as $id) {
            RoleAccessLog::create([
                'user_id' => $id,
                'action' => 'bulk_activated',
                'route' => request()->route()->getName(),
                'new_status' => true,
                'ip_address' => request()->ip(),
                'description' => 'User activated through bulk action.',
            ]);
        }

        return back()->with(
            'success',
            count($ids) . ' user(s) activated successfully.'
        );
    }

    /**
     * Bulk deactivate users.
     */
    public function bulkDeactivate(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $ids = array_values(
            array_diff(
                $validated['user_ids'],
                [auth()->id()]
            )
        );

        if (empty($ids)) {
            return back()->with(
                'error',
                'No eligible users were selected.'
            );
        }

        User::whereIn('id', $ids)->update([
            'is_active' => false,
        ]);

        foreach ($ids as $id) {
            RoleAccessLog::create([
                'user_id' => $id,
                'action' => 'bulk_deactivated',
                'route' => request()->route()->getName(),
                'new_status' => false,
                'ip_address' => request()->ip(),
                'description' => 'User deactivated through bulk action.',
            ]);
        }

        return back()->with(
            'success',
            count($ids) . ' user(s) deactivated successfully.'
        );
    }

    /**
     * Bulk role assignment.
     */
    public function bulkRole(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'role' => [
                'required',
                Rule::in(['admin', 'customer']),
            ],
        ]);

        $ids = array_values(
            array_diff(
                $validated['user_ids'],
                [auth()->id()]
            )
        );

        if (empty($ids)) {
            return back()->with(
                'error',
                'No eligible users were selected.'
            );
        }

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            $oldRole = $user->role;

            $user->update([
                'role' => $validated['role'],
            ]);

            RoleAccessLog::create([
                'user_id' => $user->id,
                'action' => 'bulk_role_updated',
                'route' => request()->route()->getName(),
                'old_role' => $oldRole,
                'new_role' => $validated['role'],
                'ip_address' => request()->ip(),
                'description' => 'Role changed through bulk action.',
            ]);
        }

        return back()->with(
            'success',
            count($ids) . ' user(s) role updated successfully.'
        );
    }

    /**
     * Export users as CSV.
     */
    public function exportUsers(
        Request $request
    ): Response {
        $search = $request->input('search');

        $role = $request->input('role');

        $status = $request->input('status');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    );
                });
            })
            ->when(
                $role && in_array($role, ['admin', 'customer']),
                fn ($query) => $query->where('role', $role)
            )
            ->when(
                $status === 'active',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                $status === 'inactive',
                fn ($query) => $query->where('is_active', false)
            )
            ->latest()
            ->get();

        $csv = [];

        $csv[] = [
            'ID',
            'Name',
            'Email',
            'Role',
            'Status',
            'Registered At',
        ];

        foreach ($users as $user) {
            $csv[] = [
                $user->id,
                $user->name,
                $user->email,
                ucfirst($user->role),
                $user->is_active ? 'Active' : 'Inactive',
                optional($user->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        $output = '';

        foreach ($csv as $row) {
            $escaped = array_map(
                function ($value) {
                    return '"' . str_replace(
                        '"',
                        '""',
                        (string) $value
                    ) . '"';
                },
                $row
            );

            $output .= implode(',', $escaped) . "\r\n";
        }

        return response(
            $output,
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' =>
                    'attachment; filename="users-' .
                    now()->format('Y-m-d-H-i-s') .
                    '.csv"',
            ]
        );
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions
        foreach ($this->permissions() as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $this->createRole('super_admin',     $this->permissions());
        $this->createRole('admin',           $this->permissions());
        $this->createRole('client_manager',  $this->clientManagerPermissions()); // ← new
        $this->createRole('director',        $this->directorPermissions());
        $this->createRole('analyst',         $this->analystPermissions());
        $this->createRole('reception',       $this->receptionPermissions());
        $this->createRole('client',          $this->clientPermissions());
        $this->createRole('auditor',         $this->auditorPermissions());
    }

    protected function createRole(string $name, array $permissions): Role
    {
        $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
        return $role;
    }

    // ─── All permissions in the system ────────────────────────────────────────

    protected function permissions(): array
    {
        return array_merge(
            $this->submissionPermissions(),
            $this->sampleAssessmentPermissions(),
            $this->sampleTestPermissions(),
            $this->resultPermissions(),
            $this->invoicePermissions(),
            $this->notificationPermissions(),
            $this->complaintPermissions(),
            $this->userPermissions(),
            $this->clientPermissionsList(), // ← add client CRUD permissions
            $this->auditLogPermissions(),
            $this->rolePermissions(),
        );
    }

    // ─── Permission groups ─────────────────────────────────────────────────────

    protected function submissionPermissions(): array
    {
        return [
            'submissions.viewAny',
            'submissions.view',
            'submissions.viewOwn',
            'submissions.create',
            'submissions.update',
            'submissions.updateStatus',
            'submissions.authorise',
            'submissions.delete',
        ];
    }

    protected function sampleAssessmentPermissions(): array
    {
        return [
            'sample_assessments.viewAny',
            'sample_assessments.view',
            'sample_assessments.create',
            'sample_assessments.update',
            'sample_assessments.delete',
        ];
    }

    protected function sampleTestPermissions(): array
    {
        return [
            'sample_tests.viewAny',
            'sample_tests.view',
            'sample_tests.create',
            'sample_tests.update',
            'sample_tests.delete',
        ];
    }

    protected function resultPermissions(): array
    {
        return [
            'results.viewAny',
            'results.view',
            'results.viewOwn',
            'results.create',
            'results.update',
            'results.authorise',
            'results.delete',
        ];
    }

    protected function invoicePermissions(): array
    {
        return [
            'invoices.viewAny',
            'invoices.view',
            'invoices.viewOwn',
            'invoices.create',
            'invoices.update',
            'invoices.pay',
            'invoices.delete',
        ];
    }

    protected function notificationPermissions(): array
    {
        return [
            'notifications.viewAny',
            'notifications.view',
            'notifications.viewOwn',
            'notifications.send',
            'notifications.delete',
        ];
    }

    protected function complaintPermissions(): array
    {
        return [
            'complaints.viewAny',
            'complaints.view',
            'complaints.viewOwn',
            'complaints.create',
            'complaints.update',
            'complaints.resolve',
            'complaints.delete',
        ];
    }

    protected function userPermissions(): array
    {
        return [
            'users.viewAny',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];
    }

    // ─── Client CRUD permissions (managed by client_manager) ──────────────────
    protected function clientPermissionsList(): array
    {
        return [
            'clients.viewAny',
            'clients.view',
            'clients.create',
            'clients.update',
            'clients.delete',
        ];
    }

    protected function auditLogPermissions(): array
    {
        return [
            'audit_logs.viewAny',
            'audit_logs.view',
            'audit_logs.delete',
        ];
    }

    protected function rolePermissions(): array
    {
        return [
            'roles.viewAny',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
        ];
    }

    // ─── Role permission sets ──────────────────────────────────────────────────

    // ── Client Manager — only sees and manages clients ─────────────────────────
    protected function clientManagerPermissions(): array
    {
        return [
            'clients.viewAny',  // see the clients list
            'clients.view',     // view a single client
            'clients.create',   // create a new client account
            'clients.update',   // edit client details
        ];
        // no clients.delete — intentionally excluded
        // no access to submissions, results, invoices, users, roles etc.
    }

    // ── Client ────────────────────────────────────────────────────────────────
    protected function clientPermissions(): array
    {
        return [
            'submissions.viewOwn',
            'submissions.create',
            'results.viewOwn',
            'invoices.viewOwn',
            'invoices.pay',
            'notifications.viewOwn',
            'complaints.viewOwn',
            'complaints.create',
        ];
    }

    // ── Reception ─────────────────────────────────────────────────────────────
    protected function receptionPermissions(): array
    {
        return [
            'submissions.viewAny',
            'submissions.view',
            'submissions.updateStatus',
            'sample_assessments.viewAny',
            'sample_assessments.view',
            'sample_assessments.create',
            'sample_assessments.update',
            'notifications.viewAny',
            'notifications.view',
            'notifications.send',
        ];
    }

    // ── Analyst ───────────────────────────────────────────────────────────────
    protected function analystPermissions(): array
    {
        return [
            'submissions.viewAny',
            'submissions.view',
            'sample_assessments.viewAny',
            'sample_assessments.view',
            'sample_tests.viewAny',
            'sample_tests.view',
            'sample_tests.create',
            'sample_tests.update',
            'results.viewAny',
            'results.view',
            'notifications.viewAny',
            'notifications.view',
            'notifications.send',
        ];
    }

    // ── Director ──────────────────────────────────────────────────────────────
    protected function directorPermissions(): array
    {
        return [
            'submissions.viewAny',
            'submissions.view',
            'submissions.authorise',
            'sample_assessments.viewAny',
            'sample_assessments.view',
            'sample_tests.viewAny',
            'sample_tests.view',
            'results.viewAny',
            'results.view',
            'results.authorise',
            'invoices.viewAny',
            'invoices.view',
            'notifications.viewAny',
            'notifications.view',
            'notifications.send',
            'complaints.viewAny',
            'complaints.view',
            'complaints.resolve',
            'audit_logs.viewAny',
            'audit_logs.view',
        ];
    }

    // ── Auditor ───────────────────────────────────────────────────────────────
    protected function auditorPermissions(): array
    {
        return [
            // Read-only audit log access
            'audit_logs.viewAny',
            'audit_logs.view',

            // Read-only context access
            'submissions.viewAny',
            'submissions.view',
            'results.viewAny',
            'results.view',
            'invoices.viewAny',
            'invoices.view',
            'complaints.viewAny',
            'complaints.view',
            'users.viewAny',
            'users.view',
        ];
    }
}
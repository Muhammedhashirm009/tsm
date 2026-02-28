<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Role Constants ──
    const ROLE_ADMIN = 'admin';
    const ROLE_SECRETARY = 'secretary';
    const ROLE_JOINT_SECRETARY = 'joint_secretary';
    const ROLE_PRESIDENT = 'president';
    const ROLE_COLLECTOR = 'collector';

    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SECRETARY => 'Secretary',
        self::ROLE_JOINT_SECRETARY => 'Joint Secretary',
        self::ROLE_PRESIDENT => 'President',
        self::ROLE_COLLECTOR => 'Collector',
    ];

    // ── Role Checks ──
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSecretary(): bool
    {
        return in_array($this->role, [self::ROLE_SECRETARY, self::ROLE_JOINT_SECRETARY]);
    }

    public function isPresident(): bool
    {
        return $this->role === self::ROLE_PRESIDENT;
    }

    public function isCollector(): bool
    {
        return $this->role === self::ROLE_COLLECTOR;
    }

    // ── Permission Helpers ──

    /** Can create/edit/delete receipts, vouchers, debts, etc. */
    public function canEdit(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SECRETARY, self::ROLE_JOINT_SECRETARY]);
    }

    /** Can view dashboard, transactions, accounts, debts, creditors, books */
    public function canViewFinance(): bool
    {
        return $this->role !== self::ROLE_COLLECTOR;
    }

    /** Can add receipts (income) */
    public function canAddReceipts(): bool
    {
        return true; // All roles can add receipts
    }

    /** Can manage users */
    public function canManageUsers(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Can add expenses */
    public function canAddExpenses(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SECRETARY, self::ROLE_JOINT_SECRETARY]);
    }

    /** Can manage debts (add, repay) */
    public function canManageDebts(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SECRETARY, self::ROLE_JOINT_SECRETARY]);
    }

    /** Get role display label */
    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst($this->role);
    }
}

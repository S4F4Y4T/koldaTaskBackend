<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // User Management
    case USER_READ = 'user_read';
    case USER_CREATE = 'user_create';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    // Role Management
    case ROLE_READ = 'role_read';
    case ROLE_CREATE = 'role_create';
    case ROLE_UPDATE = 'role_update';
    case ROLE_DELETE = 'role_delete';
    case ROLE_ASSIGN_PERMISSION = 'role_assign_permission';

    // Animal Management
    case ANIMAL_READ = 'animal_read';
    case ANIMAL_CREATE = 'animal_create';
    case ANIMAL_UPDATE = 'animal_update';
    case ANIMAL_DELETE = 'animal_delete';

    // Breed Management
    case BREED_READ = 'breed_read';
    case BREED_CREATE = 'breed_create';
    case BREED_UPDATE = 'breed_update';
    case BREED_DELETE = 'breed_delete';

    // Supplier Management
    case SUPPLIER_READ = 'supplier_read';
    case SUPPLIER_CREATE = 'supplier_create';
    case SUPPLIER_UPDATE = 'supplier_update';
    case SUPPLIER_DELETE = 'supplier_delete';

    // Customer Management
    case CUSTOMER_READ = 'customer_read';
    case CUSTOMER_CREATE = 'customer_create';
    case CUSTOMER_UPDATE = 'customer_update';
    case CUSTOMER_DELETE = 'customer_delete';

    // Staff Management
    case STAFF_READ = 'staff_read';
    case STAFF_CREATE = 'staff_create';
    case STAFF_UPDATE = 'staff_update';
    case STAFF_DELETE = 'staff_delete';

    // Finance (Transactions)
    case TRANSACTION_READ = 'transaction_read';
    case TRANSACTION_CREATE = 'transaction_create';
    case TRANSACTION_UPDATE = 'transaction_update';
    case TRANSACTION_DELETE = 'transaction_delete';

    // Inventory
    case INVENTORY_READ = 'inventory_read';
    case INVENTORY_CREATE = 'inventory_create';
    case INVENTORY_UPDATE = 'inventory_update';
    case INVENTORY_DELETE = 'inventory_delete';

    // Sales
    case SELL_READ = 'sell_read';
    case SELL_CREATE = 'sell_create';
    case SELL_UPDATE = 'sell_update';
    case SELL_DELETE = 'sell_delete';

    // Purchases
    case PURCHASE_READ = 'purchase_read';
    case PURCHASE_CREATE = 'purchase_create';
    case PURCHASE_UPDATE = 'purchase_update';
    case PURCHASE_DELETE = 'purchase_delete';

    // Production
    case PRODUCTION_READ = 'production_read';
    case PRODUCTION_CREATE = 'production_create';
    case PRODUCTION_UPDATE = 'production_update';
    case PRODUCTION_DELETE = 'production_delete';

    public static function modules(): array
    {
        return [
            'User Management' => [
                self::USER_READ, self::USER_CREATE, self::USER_UPDATE, self::USER_DELETE
            ],
            'Role Management' => [
                self::ROLE_READ, self::ROLE_CREATE, self::ROLE_UPDATE, self::ROLE_DELETE, self::ROLE_ASSIGN_PERMISSION
            ],
            'Animal Management' => [
                self::ANIMAL_READ, self::ANIMAL_CREATE, self::ANIMAL_UPDATE, self::ANIMAL_DELETE
            ],
            'Breed Management' => [
                self::BREED_READ, self::BREED_CREATE, self::BREED_UPDATE, self::BREED_DELETE
            ],
            'Supplier Management' => [
                self::SUPPLIER_READ, self::SUPPLIER_CREATE, self::SUPPLIER_UPDATE, self::SUPPLIER_DELETE
            ],
            'Customer Management' => [
                self::CUSTOMER_READ, self::CUSTOMER_CREATE, self::CUSTOMER_UPDATE, self::CUSTOMER_DELETE
            ],
            'Staff Management' => [
                self::STAFF_READ, self::STAFF_CREATE, self::STAFF_UPDATE, self::STAFF_DELETE
            ],
            'Finance' => [
                self::TRANSACTION_READ, self::TRANSACTION_CREATE, self::TRANSACTION_UPDATE, self::TRANSACTION_DELETE
            ],
            'Inventory' => [
                self::INVENTORY_READ, self::INVENTORY_CREATE, self::INVENTORY_UPDATE, self::INVENTORY_DELETE
            ],
            'Sales' => [
                self::SELL_READ, self::SELL_CREATE, self::SELL_UPDATE, self::SELL_DELETE
            ],
            'Purchases' => [
                self::PURCHASE_READ, self::PURCHASE_CREATE, self::PURCHASE_UPDATE, self::PURCHASE_DELETE
            ],
            'Production' => [
                self::PRODUCTION_READ, self::PRODUCTION_CREATE, self::PRODUCTION_UPDATE, self::PRODUCTION_DELETE
            ],
        ];
    }
}

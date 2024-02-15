<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                'group_name' => 'Attribute',
                'permission_name' => 'Attribute View',
                'route_name' => null,
                'slug' => 'attribute-view',
                'status' => 1
            ],
            [
                'group_name' => 'Attribute',
                'permission_name' => 'Attribute Edit',
                'route_name' => null,
                'slug' => 'attribute-edit',
                'status' => 1
            ],
            [
                'group_name' => 'Attribute',
                'permission_name' => 'Attribute Delete',
                'route_name' => null,
                'slug' => 'attribute-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Attribute',
                'permission_name' => 'Attribute Create',
                'route_name' => null,
                'slug' => 'attribute-create',
                'status' => 1
            ],
            [
                'group_name' => 'Page',
                'permission_name' => 'Page View',
                'route_name' => null,
                'slug' => 'page-view',
                'status' => 1
            ],
            [
                'group_name' => 'Page',
                'permission_name' => 'Page Update',
                'route_name' => null,
                'slug' => 'page-update',
                'status' => 1
            ],
            [
                'group_name' => 'Menu',
                'permission_name' => 'Menu View',
                'route_name' => null,
                'slug' => 'menu-view',
                'status' => 1
            ],
            [
                'group_name' => 'Menu',
                'permission_name' => 'Menu Edit',
                'route_name' => null,
                'slug' => 'menu-edit',
                'status' => 1
            ],
            [
                'group_name' => 'Menu',
                'permission_name' => 'Menu Delete',
                'route_name' => null,
                'slug' => 'menu-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Menu',
                'permission_name' => 'Menu Create',
                'route_name' => null,
                'slug' => 'menu-create',
                'status' => 1
            ],
            [
                'group_name' => 'Role & Permission',
                'permission_name' => 'Role&Permission View',
                'route_name' => null,
                'slug' => 'role-view',
                'status' => 1
            ],
            [
                'group_name' => 'Role & Permission',
                'permission_name' => 'Role Edit',
                'route_name' => null,
                'slug' => 'role-edit',
                'status' => 1
            ],
            [
                'group_name' => 'Role & Permission',
                'permission_name' => 'Role Delete',
                'route_name' => null,
                'slug' => 'role-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Role & Permission',
                'permission_name' => 'Role Create',
                'route_name' => null,
                'slug' => 'role-create',
                'status' => 1
            ],
            [
                'group_name' => 'Employee',
                'permission_name' => 'Employee View',
                'route_name' => null,
                'slug' => 'employee-view',
                'status' => 1
            ],
            [
                'group_name' => 'Employee',
                'permission_name' => 'Employee Edit',
                'route_name' => null,
                'slug' => 'employee-edit',
                'status' => 1
            ],
            [
                'group_name' => 'Employee',
                'permission_name' => 'Employee Delete',
                'route_name' => null,
                'slug' => 'employee-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Employee',
                'permission_name' => 'Employee Create',
                'route_name' => null,
                'slug' => 'employee-create',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Product View',
                'route_name' => null,
                'slug' => 'product-view',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Product Edit',
                'route_name' => null,
                'slug' => 'product-edit',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Product Delete',
                'route_name' => null,
                'slug' => 'product-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Product Create',
                'route_name' => null,
                'slug' => 'product-create',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Bulk Upload',
                'route_name' => null,
                'slug' => 'bulk-upload',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Add To Campaign',
                'route_name' => null,
                'slug' => 'add-to-campaign',
                'status' => 1
            ],
            [
                'group_name' => 'Product',
                'permission_name' => 'Stock Sheet',
                'route_name' => null,
                'slug' => 'stock-sheet',
                'status' => 1
            ],
            [
                'group_name' => 'Order',
                'permission_name' => 'Order View',
                'route_name' => null,
                'slug' => 'order-view',
                'status' => 1
            ],
            [
                'group_name' => 'Order',
                'permission_name' => 'Order Update',
                'route_name' => null,
                'slug' => 'order-update',
                'status' => 1
            ],
            [
                'group_name' => 'Order',
                'permission_name' => 'Order Delete',
                'route_name' => null,
                'slug' => 'order-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Refund',
                'permission_name' => 'Refund Configure',
                'route_name' => null,
                'slug' => 'refund-configure',
                'status' => 1
            ],
            [
                'group_name' => 'Refund',
                'permission_name' => 'Refund View',
                'route_name' => null,
                'slug' => 'refund-view',
                'status' => 1
            ],
            [
                'group_name' => 'Refund',
                'permission_name' => 'Refund Request View',
                'route_name' => null,
                'slug' => 'refund-request-view',
                'status' => 1
            ],
            [
                'group_name' => 'Refund',
                'permission_name' => 'Approve Refund View',
                'route_name' => null,
                'slug' => 'approve-refund-view',
                'status' => 1
            ],
            [
                'group_name' => 'Refund',
                'permission_name' => 'Reject Refund View',
                'route_name' => null,
                'slug' => 'reject-refund-view',
                'status' => 1
            ],
            [
                'group_name' => 'Refund',
                'permission_name' => 'Refund Action',
                'route_name' => null,
                'slug' => 'refund-action',
                'status' => 1
            ],
            [
                'group_name' => 'Customer',
                'permission_name' => 'Customer View',
                'route_name' => null,
                'slug' => 'customer-view',
                'status' => 1
            ],
            [
                'group_name' => 'Customer',
                'permission_name' => 'Customer Order View',
                'route_name' => null,
                'slug' => 'customer-order-view',
                'status' => 1
            ],
            [
                'group_name' => 'Campaign',
                'permission_name' => 'Campaign View',
                'route_name' => null,
                'slug' => 'campaign-view',
                'status' => 1
            ],
            [
                'group_name' => 'Campaign',
                'permission_name' => 'Campaign Edit',
                'route_name' => null,
                'slug' => 'campaign-edit',
                'status' => 1
            ],
            [
                'group_name' => 'Campaign',
                'permission_name' => 'Campaign Delete',
                'route_name' => null,
                'slug' => 'campaign-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Campaign',
                'permission_name' => 'Campaign Create',
                'route_name' => null,
                'slug' => 'campaign-create',
                'status' => 1
            ],
            [
                'group_name' => 'Campaign',
                'permission_name' => 'Item Drop From Campaign',
                'route_name' => null,
                'slug' => 'product-remove-campaign',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Report View',
                'route_name' => null,
                'slug' => 'report-view',
                'status' => 1
            ],
            [
                'group_name' => 'Shipping',
                'permission_name' => 'Shipping View',
                'route_name' => null,
                'slug' => 'shipping-view',
                'status' => 1
            ],
            [
                'group_name' => 'Shipping',
                'permission_name' => 'Add Shipping',
                'route_name' => null,
                'slug' => 'add-shipping',
                'status' => 1
            ],
            [
                'group_name' => 'Shipping',
                'permission_name' => 'Edit Shipping',
                'route_name' => null,
                'slug' => 'edit-shipping',
                'status' => 1
            ],
            [
                'group_name' => 'Shipping',
                'permission_name' => 'Delete Shipping',
                'route_name' => null,
                'slug' => 'delete-shipping',
                'status' => 1
            ],
            [
                'group_name' => 'Media-Manager',
                'permission_name' => 'Media View',
                'route_name' => null,
                'slug' => 'media-view',
                'status' => 1
            ],
            [
                'group_name' => 'Media-Manager',
                'permission_name' => 'Add Media',
                'route_name' => null,
                'slug' => 'add-media',
                'status' => 1
            ],
            [
                'group_name' => 'Media-Manager',
                'permission_name' => 'Media Delete',
                'route_name' => null,
                'slug' => 'media-delete',
                'status' => 1
            ],
            [
                'group_name' => 'Information',
                'permission_name' => 'Add Info',
                'route_name' => null,
                'slug' => 'add-info',
                'status' => 1
            ],
            [
                'group_name' => 'Information',
                'permission_name' => 'Edit Info',
                'route_name' => null,
                'slug' => 'edit-info',
                'status' => 1
            ],
            [
                'group_name' => 'Information',
                'permission_name' => 'Info Delete',
                'route_name' => null,
                'slug' => 'delete-info',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Invoice Report',
                'route_name' => null,
                'slug' => 'invoice-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Individual Customer Report',
                'route_name' => null,
                'slug' => 'individual-customer-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Customer Refund Report',
                'route_name' => null,
                'slug' => 'customer-refund-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Customer Lifetime Value',
                'route_name' => null,
                'slug' => 'customer-lifetime-value-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Payment Report',
                'route_name' => null,
                'slug' => 'payment-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Stock Report',
                'route_name' => null,
                'slug' => 'stock-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Campaign Report',
                'route_name' => null,
                'slug' => 'campaign-report',
                'status' => 1
            ],
            [
                'group_name' => 'Report',
                'permission_name' => 'Sales Report',
                'route_name' => null,
                'slug' => 'sales-report',
                'status' => 1
            ]
        ];

        collect($permission)->each(function ($perm) { Permission::create($perm); });
        
    }
}

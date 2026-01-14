<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Permission;

class CustomerPriceSettingsBreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create DataType for customer-price-settings
        $dataType = DataType::firstOrNew(['slug' => 'customer-price-settings']);
        $dataType->fill([
            'name' => 'customer-price-settings',
            'display_name_singular' => 'Customer Price Setting',
            'display_name_plural' => 'Customer Price Settings',
            'icon' => 'voyager-dollar',
            'model_name' => 'App\\Models\\CustomerPriceSetting',
            'controller' => 'App\\Http\\Controllers\\Voyager\\customer_priceController',
            'generate_permissions' => 1,
            'description' => 'Manage customer price settings with dependent dropdowns.',
        ])->save();

        // Create DataRows for each field
        $dataRows = [
            [
                'data_type_id' => $dataType->id,
                'field' => 'id',
                'type' => 'number',
                'display_name' => 'ID',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'customer_id',
                'type' => 'relationship',
                'display_name' => 'Customer',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => json_encode([
                    'model' => 'App\\Models\\User',
                    'table' => 'users',
                    'type' => 'belongsTo',
                    'column' => 'customer_id',
                    'key' => 'id',
                    'label' => 'name',
                    'pivot_table' => null,
                    'pivot' => 0,
                    'taggable' => null,
                ]),
                'order' => 2,
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'customer_type',
                'type' => 'select_dropdown',
                'display_name' => 'Customer Type',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => json_encode([
                    'options' => [
                        'retail' => 'Retail',
                        'wholesale' => 'Wholesale',
                    ],
                ]),
                'order' => 3,
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'wheat_price_per_kg',
                'type' => 'select_dependent_dropdown',
                'display_name' => 'Wheat Price Per KG',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => json_encode([
                    'model' => 'App\\Models\\WheatPrice',
                    'route' => 'api.v1.dropdown',
                    'placeholder' => 'Select Wheat Price',
                    'key' => 'id',
                    'label' => 'price_per_kg',
                    'where' => 'customer_type',
                ]),
                'order' => 4,
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'grinding_charge_per_kg',
                'type' => 'select_dependent_dropdown',
                'display_name' => 'Grinding Charge Per KG',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => json_encode([
                    'model' => 'App\\Models\\GrindingCharge',
                    'route' => 'api.v1.dropdown',
                    'placeholder' => 'Select Grinding Charge',
                    'key' => 'id',
                    'label' => 'charge_per_kg',
                    'where' => 'customer_type',
                ]),
                'order' => 5,
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 6,
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 7,
            ],
        ];

        foreach ($dataRows as $dataRow) {
            DataRow::firstOrNew(['data_type_id' => $dataRow['data_type_id'], 'field' => $dataRow['field']])->fill($dataRow)->save();
        }

        // Generate permissions
        Permission::generateFor('customer-price-settings');
    }
}

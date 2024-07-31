<?php

return [
    'orders' => [
        "label" => "Import Orders",
        "permission_required" => "import-orders",
        "files" => [
            "ds_sheet" => [
                "label" => "DS Sheet",
                "headers_to_db" => [
                    'Order Date' => 'order_date',
                    'Channel' => 'channel',
                    'SKU' => 'sku',
                    'Item Description' => 'item_description',
                    'Origin' => 'origin',
                    'SO#' => 'so_num',
                    'Total Price' => 'total_price',
                    'Cost' => 'cost',
                    'Shipping Cost' => 'shipping_cost',
                    'Profit' => 'profit'
                ],
            ],
        ],
    ],
    'customer_data' => [
        "label" => "Import Customer Data",
        "permission_required" => "import-customer-data",
        "files" => [
            "customer_file" => [
                "label" => "Customer File",
                "headers_to_db" => [
                    'Customer ID' => 'customer_id',
                    'First Name' => 'first_name',
                    'Last Name' => 'last_name',
                    'Email' => 'email',
                    'Phone Number' => 'phone_number',
                    'Address' => 'address',
                ],
            ],
        ],
    ],
    'inventory_data' => [
        "label" => "Import Inventory Data",
        "permission_required" => "import-inventory-data",
        "files" => [
            "inventory_file" => [
                "label" => "Inventory File",
                "headers_to_db" => [
                    'Item ID' => 'item_id',
                    'Item Name' => 'item_name',
                    'Category' => 'category',
                    'Stock Quantity' => 'stock_quantity',
                    'Purchase Price' => 'purchase_price',
                    'Sale Price' => 'sale_price',
                ],
            ],
            "supplier_file" => [
                "label" => "Supplier File",
                "headers_to_db" => [
                    'Supplier ID' => 'supplier_id',
                    'Supplier Name' => 'supplier_name',
                    'Contact Name' => 'contact_name',
                    'Contact Email' => 'contact_email',
                    'Contact Phone' => 'contact_phone',
                ],
            ],
        ],
    ],
];

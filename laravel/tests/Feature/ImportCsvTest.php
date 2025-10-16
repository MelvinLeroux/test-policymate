<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class ImportCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testImport()
    {
        $originalCsvPath = storage_path('app/sales.csv');
        $backupCsvPath = storage_path('app/sales_backup.csv');

        if (file_exists($originalCsvPath)) {
            rename($originalCsvPath, $backupCsvPath);
        }

        $csvContent = <<<CSV
            order_id,order_date,customer_email,product_sku,product_name,unit_price,quantity
            1,invalid-date,alice@example.com,SKU-1,Widget A,10.00,3
            2,2025-08-01,bad-email,SKU-2,Widget B,20.00,2
            3,2025-08-15,bob@example.com,SKU-3,Widget C,-5,1
            4,2025-08-16,carol@example.com,SKU-4,Widget D,15.00,0
            CSV;

        file_put_contents($originalCsvPath, $csvContent);

        Log::spy();

        $response = $this->get('/import');

        $response->assertStatus(200);

        $data = $response->json();

        $this->assertEquals(0, $data['imported']);
        $this->assertEquals(4, $data['skipped']);

        Log::shouldHaveReceived('warning')->times(4);

        unlink($originalCsvPath);
        if (file_exists($backupCsvPath)) {
            rename($backupCsvPath, $originalCsvPath);
        }
    }
}

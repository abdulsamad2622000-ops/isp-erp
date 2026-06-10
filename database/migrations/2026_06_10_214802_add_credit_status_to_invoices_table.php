<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditStatusToInvoicesTable extends Migration
{
    public function up()
    {
        \DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid','paid','partial','overdue','credit') NOT NULL DEFAULT 'unpaid'");
    }

    public function down()
    {
        \DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid','paid','partial','overdue') NOT NULL DEFAULT 'unpaid'");
    }
}
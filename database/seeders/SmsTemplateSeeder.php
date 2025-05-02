<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sms_templates')->truncate();

        DB::table('sms_templates')->insert([
            [
                'name' => 'Sale Confirmation',
                'slug' => 'sale-confirmation',
                'sms_template_en' => "Date: {date}\nInvoice No: {invoice_no}\nTotal: {total_amount}\nPayment: {paid_amount} BDT\nDue: {due_amount} BDT\n",
                'sms_template_bn' => "তারিখ: {date}\nচালান নম্বর: {invoice_no}\nমোট: {total_amount}\nজমা: {paid_amount} BDT\nবকেয়া: {due_amount} BDT\n",
                'is_active' => true,
                'is_editable' => false,
                'is_deletable' => false,
                'central_id' => 1,
            ],
            [
                'name' => 'Buyer Payment Confirmation',
                'slug' => 'buyer-payment-confirmation',
                'sms_template_en' => "Date: {date}\nInvoice No: {invoice_no}\nPayment: {paid_amount} BDT\nDue: {total_due} BDT\n",
                'sms_template_bn' => "তারিখ: {date}\nচালান নম্বর: {invoice_no}\nজমা: {paid_amount} BDT\nবকেয়া: {total_due} BDT\n",
                'is_active' => true,
                'is_editable' => false,
                'is_deletable' => false,
                'central_id' => 2,
            ],
            [
                'name' => 'Due Reminder',
                'slug' => 'due-reminder',
                'sms_template_en' => "Total transaction: {total_transaction}\nTotal payment: {total_payment}\nDue: {total_due} BDT\n. Please clear your due soon.\n",
                'sms_template_bn' => "মোট লেনদেন: {total_transaction}\nমোট জমা: {total_payment}\nবকেয়া: {total_due} BDT\nবকেয়া দ্রুত পরিশোধ করার জন্য অনুরোধ জানাচ্ছি।\n",
                'is_active' => true,
                'is_editable' => false,
                'is_deletable' => false,
                'central_id' => 3,
            ]
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

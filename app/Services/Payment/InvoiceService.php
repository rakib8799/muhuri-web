<?php

namespace App\Services\Payment;

use App\Constants\Constants;
use App\Models\Payment\Invoice;
use App\Services\Core\BaseModelService;

class InvoiceService extends BaseModelService
{
    public function model(): string
    {
        return Invoice::class;
    }

    public function generateInvoiceNumber()
    {
        $lastInvoiceNo = $this->model()::latest('id')->value('invoice_number') ?? 1000;
        return $lastInvoiceNo + 1;
    }

    public function createInvoice(array $invoiceData)
    {
        // Ensure necessary fields like 'amount', 'details', and 'invoice_type' are present in the data
        if (!isset($invoiceData['amount']) || !isset($invoiceData['details']) || !isset($invoiceData['invoice_type'])) {
            throw new \Exception('Amount, details, and invoice_type are required to create an invoice.');
        }

        // Default fields for the invoice
        $invoiceData = array_merge([
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_date' => now(),
            'expire_date' => now()->addMonth(),
            'currency_code' => 'BDT',
            'is_active' => true,
        ], $invoiceData);

        // Create the invoice
        return $this->create($invoiceData);
    }

    public function expirePendingInvoices()
    {
        $expiredInvoices = $this->model()::where('status', Constants::PENDING)
        ->where('expire_date', '<', now())
        ->update([
            'status' => Constants::EXPIRED,
            'is_active' => false
        ]);

        return $expiredInvoices;
    }
}

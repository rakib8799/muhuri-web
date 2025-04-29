<?php

namespace App\Services\SMS;

use App\Models\Sale\Sale;
use App\Models\Buyer\BuyerPayment;
use App\Services\Sale\SaleService;
use Illuminate\Support\Facades\DB;
use App\Services\ConfigurationService;

class SMSService
{
    private SMSGatewayService $smsGatewayService;
    private SaleService $saleService;
    private SMSTemplateService $smsTemplateService;
    private ConfigurationService $configurationService;
    private $configuration;
    private SMSPurchaseService $smsPurchaseService;

    public function __construct(
        SMSGatewayService  $smsGatewayService,
        SaleService        $saleService,
        SMSTemplateService $smsTemplateService,
        ConfigurationService $configurationService,
        SMSPurchaseService $smsPurchaseService
    )
    {
        $this->smsGatewayService = $smsGatewayService;
        $this->saleService = $saleService;
        $this->smsTemplateService = $smsTemplateService;
        $this->configurationService = $configurationService;
        $this->configuration = $this->configurationService->getCompanyPublicConfiguration();
        $this->smsPurchaseService = $smsPurchaseService;
    }

    public function sendSaleConfirmation(Sale $sale)
    {
        $lang = 'bn'; // TODO: Get the language from the configuration or user preference
        $companyName = $this->configuration->get("name_{$lang}");
        $template = $this->smsTemplateService->getSmsTemplate('sale-confirmation', $lang);


        $message = str_replace(
            ['{date}', '{invoice_no}', '{total_amount}', '{paid_amount}', '{due_amount}'],
            [
                $sale->sale_date,
                $sale->invoice_number,
                $sale->sub_total ?? 0,
                $sale->paid_amount ?? 0,
                ($sale->grand_total ?? 0) - ($sale->paid_amount ?? 0)
            ],
            $template
        );
        $message .= $companyName;

        return $this->smsGatewayService->sendSMS($sale->buyer->mobile_number, $message, 'sale_confirmation');
    }

    public function sendBuyerPaymentConfirmation(BuyerPayment $buyerPayment)
    {
        $lang = 'bn'; // TODO: Get the language from the configuration or user preference
        $companyName = $this->configuration->get("name_{$lang}");
        $template = $this->smsTemplateService->getSmsTemplate('buyer-payment-confirmation', $lang);
        $totalAmount = $this->saleService->getGrandTotalById($buyerPayment->buyer_id);
        $totalDue = $totalAmount - $buyerPayment->sum('amount');


        $message = str_replace(
            ['{date}', '{invoice_no}', '{paid_amount}', '{total_due}'],
            [
                $buyerPayment->payment_date,
                $buyerPayment->invoice_number,
                $buyerPayment->amount,
                $totalDue
            ],
            $template
        );
        $message .= $companyName;

        return $this->smsGatewayService->sendSMS(
            $buyerPayment->buyer->mobile_number,
            $message,
            'buyer_payment_confirmation'
        );
    }

    public function sendDueReminder($buyer)
    {
        $lang = 'bn'; // TODO: Get the language from the configuration or user preference
        $companyName = $this->configuration->get("name_{$lang}");
        $template = $this->smsTemplateService->getSmsTemplate('due-reminder', $lang);
        $message = str_replace(
            ['{total_transaction}', '{total_payment}', '{total_due}'],
            [
                $buyer['total_transaction'],
                $buyer['total_payment'],
                $buyer['total_due']
            ],
            $template
        );
        $message .= $companyName;

        return $this->smsGatewayService->sendSMS(
            $buyer['mobile_number'],
            $message,
            'due_reminder'
        );
    }

    public function addFreeSMS($request)
    {
        $sms = $request->all();

        $smsRate = $sms['sms_rate'];
        $smsQuantity = $sms['free_sms_quantity'];
        $totalPrice = $smsRate * $smsQuantity;

        $smsPurchaseData = [
            'sms_quantity' => $smsQuantity,
            'sms_rate' => $smsRate,
            'total_price' => $totalPrice,
            'payment_status' => 'paid',
            'transaction_id' => null,
            'note' => 'free sms added',
            'created_by' => $sms['created_by'],
            'updated_by' => $sms['updated_by']
        ];

        return DB::transaction(function () use ($smsPurchaseData) {
            $smsPurchase = $this->smsPurchaseService->create($smsPurchaseData);

            $smsCreditService = new SMSCreditService();
            $smsCredit = $smsCreditService->getOrCreateSMSCredit();
            $smsCredit->update([
                'total_credits' => $smsCredit->total_credits + $smsPurchase->sms_quantity,
                'remaining_credits' => $smsCredit->remaining_credits + $smsPurchase->sms_quantity,
                'updated_by' => $smsPurchaseData['updated_by'],
            ]);
        });
    }
}

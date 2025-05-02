<?php

namespace App\Services\Core;

use App\Services\LocationService;
use Exception;
use App\Models\User;
use App\Constants\Constants;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;

class HelperService
{
    public static function getBloodGroups()
    {
        return [
            [ 'value' => 'A+', 'text' => 'A+' ],
            [ 'value' => 'A-', 'text' => 'A-' ],
            [ 'value' => 'B+', 'text' => 'B+' ],
            [ 'value' => 'B-', 'text' => 'B-' ],
            [ 'value' => 'AB+', 'text' => 'AB+' ],
            [ 'value' => 'AB-', 'text' => 'AB-' ],
            [ 'value' => 'O+', 'text' => 'O+' ],
            [ 'value' => 'O-', 'text' => 'O-' ]
        ];
    }

    public static function getGenders()
    {
        return [
            [ 'value' => Constants::MALE, 'text' => ucfirst(Constants::MALE) ],
            [ 'value' => Constants::FEMALE, 'text' => ucfirst(Constants::FEMALE) ],
            [ 'value' => Constants::OTHERS, 'text' => ucfirst(Constants::OTHERS) ]
        ];
    }

    public static function getMaritalStatus()
    {
        return [
            [ 'value' => Constants::SINGLE, 'text' => ucfirst(Constants::SINGLE) ],
            [ 'value' => Constants::MARRIED, 'text' => ucfirst(Constants::MARRIED) ],
            [ 'value' =>  Constants::LEGAL_COHABITANT, 'text' => ucfirst(Constants::LEGAL_COHABITANT) ],
            [ 'value' =>  Constants::WIDOWER, 'text' => ucfirst(Constants::WIDOWER) ],
            [ 'value' =>  Constants::DIVORCED, 'text' => ucfirst(Constants::DIVORCED) ]
        ];
    }

    public static function getDateFormats()
    {
        return [
            [ 'value' => Constants::Y_M_D, 'text' => Constants::Y_M_D ],
            [ 'value' => Constants::M_D_Y, 'text' => Constants::M_D_Y ],
            [ 'value' => Constants::D_M_Y, 'text' => Constants::D_M_Y ]
        ];
    }

    public static function getWeekDays()
    {
        return [
            [ 'value' => Constants::SUNDAY, 'text' => ucfirst(Constants::SUNDAY) ],
            [ 'value' => Constants::MONDAY, 'text' => ucfirst(Constants::MONDAY) ],
            [ 'value' => Constants::TUESDAY, 'text' => ucfirst(Constants::TUESDAY) ],
            [ 'value' => Constants::WEDNESDAY, 'text' => ucfirst(Constants::WEDNESDAY) ],
            [ 'value' => Constants::THURSDAY, 'text' => ucfirst(Constants::THURSDAY) ],
            [ 'value' => Constants::FRIDAY, 'text' => ucfirst(Constants::FRIDAY) ],
            [ 'value' => Constants::SATURDAY, 'text' => ucfirst(Constants::SATURDAY) ]
        ];
    }

    public static function getWorkLocations() {
        return [
            [ 'value' => Constants::WORK_FROM_HOME, 'text' => ucfirst(Constants::WORK_FROM_HOME) ],
            [ 'value' => Constants::WORK_FROM_OFFICE, 'text' => ucfirst(Constants::WORK_FROM_OFFICE) ],
            [ 'value' => Constants::WORK_REMOTELY, 'text' => ucfirst(Constants::WORK_REMOTELY) ]
        ];
    }

    public static function getRequestStatuses() {
        return [
            [ 'value' => Constants::PENDING, 'text' => ucfirst(Constants::PENDING) ],
            [ 'value' => Constants::APPROVED, 'text' => ucfirst(Constants::APPROVED) ],
            [ 'value' => Constants::DECLINED, 'text' => ucfirst(Constants::DECLINED) ]
        ];
    }

    public static function getLeaveValidationTypes() {
        return [
            [ 'value' => Constants::HR, 'text' => ucfirst(Constants::HR) ],
            [ 'value' => Constants::MANAGER, 'text' => ucfirst(Constants::MANAGER) ],
            [ 'value' => Constants::BOTH, 'text' => ucfirst(Constants::BOTH) ],
            [ 'value' => Constants::NO_VALIDATIONS, 'text' => ucfirst(Constants::NO_VALIDATIONS)]
        ];
    }

    public static function getRequestUnits() {
        return [
            [ 'value' => Constants::DAY, 'text' => ucfirst(Constants::DAY) ],
            [ 'value' => Constants::HOUR, 'text' => ucfirst(Constants::HOUR) ]
        ];
    }

    public static function isManagerOrMuhuri(User $user)
    {
        if($user->hasRole(Constants::ROLE_MUHURI)) {
            return false;
        } else if($user->hasRole(Constants::ROLE_MANAGER)) {
            return true;
        }
        return false;
    }

    public static function isSuperAdminOrHR(User $user)
    {
        if($user->hasAnyRole([Constants::ROLE_SUPER_ADMIN, Constants::ROLE_MUHURI])) {
            return true;
        }

        return false;
    }

    public static function isEmployee(User $user)
    {
        if($user->hasRole(Constants::ROLE_EMPLOYEE)) {
            return true;
        }

        return false;
    }

    public static function captureException(Exception $e)
    {
        \Sentry\captureException($e);
    }

    static function getAddress($object)
    {
        $locationService = new LocationService();
        $addressParts = [];
        if (!empty($object->village)) {
            $addressParts[] = $object->village;
        }
        $union = $locationService->getUnion($object->union_id);
        if ($union) {
            $addressParts[] = $union->bn_name;
        }
        $upazila = $locationService->getUpazila($object->upazila_id);
        if ($upazila) {
            $addressParts[] = $upazila->bn_name;
        }
        $district = $locationService->getDistrict($object->district_id);
        if ($district) {
            $addressParts[] = $district->bn_name;
        }
        $address = implode(', ', $addressParts);
        return $address;
    }

    public static function getPaymentMethodEnum(): array
    {
        return [
            'cash',
            'bank',
            'bkash',
            'rocket',
            'nagad',
            'others'
        ];
    }

    public static function getPaymentMethodDisplayName($method): string
    {
        $paymentMethods = [
            'cash' => 'ক্যাশ',
            'bank' => 'ব্যাংক',
            'bkash' => 'বিকাশ',
            'rocket' => 'রকেট',
            'nagad' => 'নগদ',
            'others' => 'অন্যান্য'
        ];
        return $paymentMethods[$method];
    }

    public static function getTransactionStatusEnum(): array
    {
        return [
            'initiated',
            'success',
            'failed'
        ];
    }

    public static function getInvoiceStatusEnum(): array
    {
        return [
            'pending',
            'expired'
        ];
    }

    public static function getInvoicePaymentStatusEnum(): array
    {
        return [
            'pending',
            'paid',
            'failed'
        ];
    }

    public static function getSmsCount($responseData)
    {
        $smsCount = 0;
        if(isset($responseData['response_code']) && $responseData['response_code'] == 202){
            if(!empty($responseData['success_message'])){
                preg_match('/(\d+)$/', $responseData['success_message'], $matches);
                if(!empty($matches[1])){
                    $smsCount = (int)$matches[1];
                }
            }
        }
        return $smsCount;
    }

    public static function checkSaleItemAccess($sale, $saleItem)
    {
        if ($sale->id != $saleItem->sale_id) {
            $responseData = response()->json(
                ['message' => 'Sale Item Access Denied'], 403
            );
            abort($responseData);
        }
    }

    public static function checkPurchaseItemAccess($purchase, $purchaseItem)
    {
        if ($purchase->id != $purchaseItem->purchase_id) {
            $responseData = response()->json(
                ['message' => 'Purchase Item Access Denied'], 403
            );
            abort($responseData);
        }
    }

    public static function generateInvoiceNumber(string $prefix, string $modelClass): string
    {
        $lastRecord = $modelClass::withTrashed()
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRecord && preg_match('/' . preg_quote($prefix, '/') . '(\d+)/', $lastRecord->invoice_number, $matches)) {
            $lastNumber = (int) $matches[1];
        } else {
            $lastNumber = 0;
        }

        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public static function generateUniqueSlugFromTranslatedName(string $name, string $modelClass, string $lang = 'en'): string
    {
        $slug = $name;

        if (preg_match('/[\x{0980}-\x{09FF}]/u', $name)) {
            $tr = new GoogleTranslate($lang);
            $translated = $tr->translate($name);
            $slug = $translated;
        }

        $slug = Str::slug($slug);
        $originalSlug = $slug;
        $i = 1;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }

    public static function getPaymentNote($payment): string
    {
        $payee = $payment->getPayee();
        $payeeName = $payee->name ?? '';
        $paymentMethod = HelperService::getPaymentMethodDisplayName($payment->payment_method);
        return "{$payeeName}, $paymentMethod - $payment->note";
    }
}

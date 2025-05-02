<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\Brand;
use App\Models\Buyer\Buyer;
use App\Models\Buyer\BuyerPayment;
use App\Models\Expense\Expense;
use App\Models\FiscalYear;
use App\Models\Purchase\Purchase;
use App\Models\Sale\Sale;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPayment;
use App\Models\User;
use App\Models\HR\Employee;
use App\Models\HR\Department;
use App\Models\SMS\SMSTemplate;
use Spatie\Permission\Models\Role;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Models\Subscription\Subscription;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.

// Dashboard as Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('pageTitle.custom.home'), route('dashboard'));
});

// Settings
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.configuration.parentInfo'), route('configurations.details'));
});

// Settings - Basic Info
Breadcrumbs::for('settingsBasicInfo', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.basicInfo'), route('configurations.basicInfo'));
});

// Settings - Additional Info
Breadcrumbs::for('settingsAdditionalInfo', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.additionalInfo'), route('configurations.additionalInfo'));
});

// Settings - Contact Info
Breadcrumbs::for('settingsContactInfo', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.contactInfo'), route('configurations.contactInfo'));
});

// Settings - System Setting
Breadcrumbs::for('systemSetting', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.smsSendPermission'), route('configurations.systemSetting'));
});

// User Management Menu
Breadcrumbs::for('userManagement', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.user.parentInfo'));
});

// Roles
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('userManagement');
    $trail->push(__('breadcrumb.custom.role.childInfo.index'), route('roles.index'));
});

// Add Roles
Breadcrumbs::for('addRole', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(__('breadcrumb.custom.role.childInfo.create'), route('roles.create'));
});

// Edit Roles
Breadcrumbs::for('editRole', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('roles');
    $trail->push(__('breadcrumb.custom.role.childInfo.edit'), route('roles.edit', $role));
});

// Roles Details
Breadcrumbs::for('roleDetails', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('roles');
    $trail->push(__('breadcrumb.custom.role.childInfo.details'), route('roles.show', $role));
});

// User List
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('userManagement');
    $trail->push(__('breadcrumb.custom.user.childInfo.index'), route('users.index'));
});

// Add User
Breadcrumbs::for('addUser', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(__('breadcrumb.custom.user.childInfo.create'), route('users.create'));
});

// Edit User
Breadcrumbs::for('editUser', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users');
    $trail->push(__('breadcrumb.custom.user.childInfo.edit'), route('users.edit', $user));
});

// User Details
Breadcrumbs::for('userDetails', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users');
    $trail->push(__('breadcrumb.custom.user.childInfo.details'), route('users.show', $user));
});


// SMS Management Menu
Breadcrumbs::for('smsManagement', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.sms.parentInfo'));
});

//SMS dashboard
Breadcrumbs::for('smsDashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.index'), route('sms.index'));
});

//Purchase SMS
Breadcrumbs::for('smsPurchase', function (BreadcrumbTrail $trail) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.smsPurchase'), route('sms.purchase'));
});

//SMS purchase history
Breadcrumbs::for('smsPurchaseReport', function (BreadcrumbTrail $trail) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.smsPurchaseReport'), route('sms.purchaseReport'));
});

// SMS log
Breadcrumbs::for('smsLog', function (BreadcrumbTrail $trail) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.smsLog'), route('sms.smsLog'));
});

// SMS template
Breadcrumbs::for('smsTemplate', function (BreadcrumbTrail $trail) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.smsTemplate'), route('sms.templates.index'));
});

// Add SMS template
Breadcrumbs::for('addSMSTemplate', function (BreadcrumbTrail $trail) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.addSMSTemplate'), route('sms.templates.create'));
});

// Edit SMS template
Breadcrumbs::for('editSMSTemplate', function (BreadcrumbTrail $trail, SMSTemplate $smsTemplate) {
    $trail->parent('smsManagement');
    $trail->push(__('breadcrumb.custom.sms.childInfo.editSMSTemplate'), route('sms.templates.edit', $smsTemplate));
});

// for expense
Breadcrumbs::for('expenses', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.expense.parentInfo'), route('expenses.index'));
});

Breadcrumbs::for('expensesList', function (BreadcrumbTrail $trail) {
    $trail->parent('expenses');
    $trail->push(__('breadcrumb.custom.expense.childInfo.index'), route('expenses.index'));
});

// Add Expense
Breadcrumbs::for('addExpense', function (BreadcrumbTrail $trail) {
    $trail->parent('expenses');
    $trail->push(__('breadcrumb.custom.expense.childInfo.create'), route('expenses.create'));
});

// Edit Expense
Breadcrumbs::for('editExpense', function (BreadcrumbTrail $trail, Expense $expense) {
    $trail->parent('expenses');
    $trail->push(__('breadcrumb.custom.expense.childInfo.edit'), route('expenses.edit', $expense));
});


//Parent Items
Breadcrumbs::for('parentItems', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.item.parentInfo'), route('items.index'));
});

//Type wise Items
Breadcrumbs::for('items', function (BreadcrumbTrail $trail, $itemType) {
    $titles = [
        'sale' => __('breadcrumb.custom.item.childInfo.sale'),
        'purchase' => __('breadcrumb.custom.item.childInfo.purchase'),
        'expense' => __('breadcrumb.custom.item.childInfo.expense'),
    ];
    $trail->parent('parentItems');
    $trail->push($titles[$itemType], route('items.index', ['itemType' => $itemType]));
});

//Buyer list
Breadcrumbs::for('buyers', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.buyer.childInfo.index'), route('buyers.index'));
});

//Add Buyer
Breadcrumbs::for('addBuyers', function (BreadcrumbTrail $trail) {
    $trail->parent('buyers');
    $trail->push(__('breadcrumb.custom.buyer.childInfo.create'), route('buyers.create'));
});

//Buyer Details
Breadcrumbs::for('buyerDetails', function (BreadcrumbTrail $trail, Buyer $buyer) {
    $trail->parent('buyers');
    $trail->push(__('breadcrumb.custom.buyer.childInfo.show'), route('buyers.show', $buyer));
});

//Edit Buyer
Breadcrumbs::for('editBuyer', function (BreadcrumbTrail $trail, Buyer $buyer) {
    $trail->parent('buyers');
    $trail->push(__('breadcrumb.custom.buyer.childInfo.edit'), route('buyers.edit', $buyer));
});

// Buyer payment Invoice
Breadcrumbs::for('buyerPaymentInvoice', function (BreadcrumbTrail $trail, Buyer $buyer, BuyerPayment $payment) {
    $trail->parent('buyerDetails', $buyer);
    $trail->push(__('breadcrumb.custom.buyer.childInfo.paymentDetails'), route('buyers.payments.invoice', [$buyer, $payment]));
});


// supplier list
Breadcrumbs::for('suppliers', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.supplier.childInfo.index'), route('suppliers.index'));
});

//Add Supplier
Breadcrumbs::for('addSuppliers', function (BreadcrumbTrail $trail) {
    $trail->parent('suppliers');
    $trail->push(__('breadcrumb.custom.supplier.childInfo.create'), route('suppliers.create'));
});

//Supplier Details
Breadcrumbs::for('supplierDetails', function (BreadcrumbTrail $trail, Supplier $supplier) {
    $trail->parent('suppliers');
    $trail->push(__('breadcrumb.custom.supplier.childInfo.show'), route('suppliers.show', $supplier));
});

//Edit Supplier
Breadcrumbs::for('editSupplier', function (BreadcrumbTrail $trail, Supplier $supplier) {
    $trail->parent('suppliers');
    $trail->push(__('breadcrumb.custom.supplier.childInfo.edit'), route('suppliers.edit', $supplier));
});

// Supplier payment Invoice
Breadcrumbs::for('supplierPaymentInvoice', function (BreadcrumbTrail $trail, Supplier $supplier, SupplierPayment $payment) {
    $trail->parent('supplierDetails', $supplier);
    $trail->push(__('breadcrumb.custom.supplier.childInfo.paymentDetails'), route('suppliers.payments.invoice', [$supplier, $payment]));
});

// Fiscal Year
Breadcrumbs::for('fiscalYear', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.fiscalYear.parentInfo'), route('fiscal-years.index'));
});

// Fiscal Year List
Breadcrumbs::for('fiscalYears', function (BreadcrumbTrail $trail) {
    $trail->parent('fiscalYear');
    $trail->push(__('breadcrumb.custom.fiscalYear.childInfo.index'), route('fiscal-years.index'));
});

// Add Fiscal Year
Breadcrumbs::for('addFiscalYear', function (BreadcrumbTrail $trail) {
    $trail->parent('fiscalYear');
    $trail->push(__('breadcrumb.custom.fiscalYear.childInfo.create'), route('fiscal-years.create'));
});

// Edit Fiscal Year
Breadcrumbs::for('editFiscalYear', function (BreadcrumbTrail $trail, FiscalYear $fiscalYear) {
    $trail->parent('fiscalYear');
    $trail->push(__('breadcrumb.custom.fiscalYear.childInfo.edit'), route('fiscal-years.edit', $fiscalYear));
});

// Details Fiscal Year
Breadcrumbs::for('fiscalYearDetails', function (BreadcrumbTrail $trail, FiscalYear $fiscalYear) {
    $trail->parent('fiscalYear');
    $trail->push(__('breadcrumb.custom.fiscalYear.childInfo.show'), route('fiscal-years.show', $fiscalYear));
});

//Brand Menu
Breadcrumbs::for('allBrands', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.brand.parentInfo'), route('brands.index'));
});

//Brand List
Breadcrumbs::for('brands', function (BreadcrumbTrail $trail) {
    $trail->parent('allBrands');
    $trail->push(__('breadcrumb.custom.brand.childInfo.index'), route('brands.index'));
});

//Add Brand
Breadcrumbs::for('addBrand', function (BreadcrumbTrail $trail) {
    $trail->parent('brands');
    $trail->push(__('breadcrumb.custom.brand.childInfo.create'), route('brands.create'));
});

//Edit Brand
Breadcrumbs::for('editBrand', function (BreadcrumbTrail $trail, Brand $brand) {
    $trail->parent('brands');
    $trail->push(__('breadcrumb.custom.brand.childInfo.edit'), route('brands.edit', $brand));
});

// Order Management Menu
Breadcrumbs::for('orderManagement', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.sale.parentInfo'));
});

//Sale List
Breadcrumbs::for('sales', function (BreadcrumbTrail $trail) {
    $trail->parent('orderManagement');
    $trail->push(__('breadcrumb.custom.sale.childInfo.index'), route('sales.index'));
});

//Add Sale
Breadcrumbs::for('addSale', function (BreadcrumbTrail $trail) {
    $trail->parent('sales');
    $trail->push(__('breadcrumb.custom.sale.childInfo.create'), route('sales.create'));
});

//Update Sale
Breadcrumbs::for('editSale', function (BreadcrumbTrail $trail, Sale $sale) {
    $trail->parent('sales');
    $trail->push(__('breadcrumb.custom.sale.childInfo.edit'), route('sales.edit', $sale));
});

//Sale Details
Breadcrumbs::for('saleDetails', function (BreadcrumbTrail $trail, Sale $sale) {
    $trail->parent('sales');
    $trail->push(__('breadcrumb.custom.sale.childInfo.show'), route('sales.show', $sale));
});

Breadcrumbs::for('otherSales', function (BreadcrumbTrail $trail) {
    $trail->parent('orderManagement');
    $trail->push(__('breadcrumb.custom.sale.other.childInfo.index'), route('sales.index'));
});

//Add other Sale
Breadcrumbs::for('addOtherSale', function (BreadcrumbTrail $trail) {
    $trail->parent('otherSales');
    $trail->push(__('breadcrumb.custom.sale.other.childInfo.create'), route('sales.others.create'));
});

//Edit other Sale
Breadcrumbs::for('editOtherSale', function (BreadcrumbTrail $trail, Sale $sale) {
    $trail->parent('otherSales');
    $trail->push(__('breadcrumb.custom.sale.other.childInfo.edit'), route('sales.others.edit', $sale));
});

// Purchase Management Menu
Breadcrumbs::for('purchaseManagement', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.purchase.parentInfo'));
});

//Purchase List
Breadcrumbs::for('purchases', function (BreadcrumbTrail $trail) {
    $trail->parent('purchaseManagement');
    $trail->push(__('breadcrumb.custom.purchase.childInfo.index'), route('purchases.index'));
});

//Add Purchase
Breadcrumbs::for('addPurchase', function (BreadcrumbTrail $trail) {
    $trail->parent('purchases');
    $trail->push(__('breadcrumb.custom.purchase.childInfo.create'), route('purchases.create'));
});

//Update Purchase
Breadcrumbs::for('editPurchase', function (BreadcrumbTrail $trail, Purchase $purchase) {
    $trail->parent('purchases');
    $trail->push(__('breadcrumb.custom.purchase.childInfo.edit'), route('purchases.edit', $purchase));
});

//Purchase Details
Breadcrumbs::for('purchaseDetails', function (BreadcrumbTrail $trail, Purchase $purchase) {
    $trail->parent('purchases');
    $trail->push(__('breadcrumb.custom.purchase.childInfo.show'), route('purchases.show', $purchase));
});

//Add other Purchase
Breadcrumbs::for('addOtherPurchase', function (BreadcrumbTrail $trail) {
    $trail->parent('purchases');
    $trail->push(__('breadcrumb.custom.purchase.other.childInfo.create'), route('purchases.others.create'));
});

//Edit Other Purchase
Breadcrumbs::for('editOtherPurchase', function (BreadcrumbTrail $trail, PUrchase $purchase) {
    $trail->parent('purchases');
    $trail->push(__('breadcrumb.custom.purchase.other.childInfo.edit'), route('purchases.others.edit', $purchase));
});

// Report Menu
Breadcrumbs::for('report', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.report.parentInfo'));
});

// Report at a glance Menu
Breadcrumbs::for('atAGlance', function (BreadcrumbTrail $trail) {
    $trail->parent('report');
    $trail->push(__('breadcrumb.custom.report.childInfo.atAGlance'));
});

// Buyer Due
Breadcrumbs::for('buyerDue', function (BreadcrumbTrail $trail) {
    $trail->parent('report');
    $trail->push(__('breadcrumb.custom.report.childInfo.buyerDue'));
});

// Sale
Breadcrumbs::for('sale', function (BreadcrumbTrail $trail) {
    $trail->parent('report');
    $trail->push(__('breadcrumb.custom.report.childInfo.sale'));
});

// Employee Menu
Breadcrumbs::for('employee', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.employee.parentInfo'));
});

// Job Position List
Breadcrumbs::for('jobPositions', function (BreadcrumbTrail $trail) {
    $trail->parent('employee');
    $trail->push(__('breadcrumb.custom.jobPosition.childInfo'), route('job-positions.index'));
});

// Employee List
Breadcrumbs::for('employees', function (BreadcrumbTrail $trail) {
    $trail->parent('employee');
    $trail->push(__('breadcrumb.custom.employee.childInfo.index'), route('employees.index'));
});

// Add Employee
Breadcrumbs::for('addEmployee', function (BreadcrumbTrail $trail) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.create'), route('employees.create'));
});

// Employee Details
Breadcrumbs::for('employeeDetails', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.show', $employee));
});

// Employee Basic Info
Breadcrumbs::for('basicInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.basicInfo', $employee));
});

// Employee Additional Info
Breadcrumbs::for('additionalInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.additionalInfo', $employee));
});

// Employee Work Info
Breadcrumbs::for('workInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.workInfo', $employee));
});

// Employee Departure Info
Breadcrumbs::for('departureInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.departureInfo', $employee));
});

// My Profile
Breadcrumbs::for('myProfile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.user.childInfo.profile'), route('profile.edit'));
});

// Department Menu
Breadcrumbs::for('department', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.department.parentInfo'));
});

// Department List
Breadcrumbs::for('departments', function (BreadcrumbTrail $trail) {
    $trail->parent('department');
    $trail->push(__('breadcrumb.custom.department.childInfo.index'), route('departments.index'));
});

// Add Department
Breadcrumbs::for('addDepartment', function (BreadcrumbTrail $trail) {
    $trail->parent('departments');
    $trail->push(__('breadcrumb.custom.department.childInfo.create'), route('departments.create'));
});

// Subscription Menu
Breadcrumbs::for('subscription', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.subscription.parentInfo'));
});

// Subscription List
Breadcrumbs::for('subscriptions', function (BreadcrumbTrail $trail) {
    $trail->parent('subscription');
    $trail->push(__('breadcrumb.custom.subscription.childInfo.index'), route('subscriptions.show'));
});

// Add Subscription
Breadcrumbs::for('editSubscription', function (BreadcrumbTrail $trail, Subscription $subscription) {
    $trail->parent('subscription');
    $trail->push(__('breadcrumb.custom.subscription.childInfo.edit'), route('subscriptions.edit', $subscription));
});

// Payment status
Breadcrumbs::for('paymentStatus', function (BreadcrumbTrail $trail) {
    $trail->parent('subscription');
    $trail->push('Payment Status');
});


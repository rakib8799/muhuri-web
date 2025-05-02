<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!-- begin::Invoice 3-->
                <div class="card">
                    <!-- begin::Body-->
                    <div class="card-body py-20">
                        <!-- begin::Wrapper-->
                        <div class="mw-lg-950px mx-auto w-100">
                            <!--begin::Body-->
                            <div class="pb-12" id="invoice-content">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column gap-7 gap-md-10">
                                    <!--begin::Message-->
                                    <div class="fw-bold fs-2 text-center">
                                        <h1 class="mb-3">পপুলার হ্যাচারি এন্ড না‍র্চারী</h1>
                                        <h4 class="mb-3">POPULAR HATCHERY & NURSERY</h4>
                                        <h5>পাইকগাছা, খুলনা | {{ $t('label.mobile_number') }}: ০১৭১৬-০৭৯৩২২, ০১৯২৬-৯৬৭৯৯৯</h5>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="mb-1">
                                            <span class="fw-bold fs-7">{{ $t('label.name') }}:</span> {{ props.buyer?.name }}
                                        </div>
                                        <div class="mb-1">
                                            <span class="fw-bold fs-7">{{ $t('label.address') }}:</span> {{ props.address }}
                                        </div>
                                        <div>
                                            <span class="fw-bold fs-7">{{ $t('label.mobileNumber') }}:</span> {{ props.buyer?.mobile_number }}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="fw-bold fs-7">{{ $t('buyer.label.date') }}:</span> {{ props.payment?.payment_date }}
                                        </div>
                                         <div>
                                            <span class="fw-bold fs-7">{{ $t('sale.label.invoiceNumber') }}:</span> {{ props.payment?.invoice_number }}
                                        </div>
                                    </div>
                                    <!--begin::Message-->
                                    <!--begin::Separator-->
                                    <div class="separator"></div>
                                    <!--begin::Separator-->
                                    <!--begin:Order summary-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Table-->
                                        <div class="table-responsive border-bottom mb-9">
                                            <table class="table table-row-dashed fs-6 gy-5 mb-0">
                                                <thead>
                                                    <tr class="border-bottom fs-6 fw-bold text-muted d-flex justify-content-between">
                                                        <th class="pb-2">{{ $t('buyer.label.description') }}</th>
                                                        <th class="pb-2">{{ $t('label.note') }}</th>
                                                        <th class="pb-2">{{ $t('buyer.label.amount') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    <tr class="d-flex justify-content-between">
                                                        <td class="fw-bold">{{ props.payment?.payment_method }}</td>
                                                        <td>{{ props.payment?.note }}</td>
                                                        <td class="text-end">{{ props.payment?.amount }}</td>
                                                    </tr>
                                                    <tr class="d-flex justify-content-between">
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-end">{{ $t('payment.label.total') }} : {{ props.payment?.amount }}</td>
                                                    </tr>
                                                    <tr class="d-flex justify-content-between">
                                                      <td class="fw-bold fs-7">{{ $t('buyer.label.totalTransaction') }}: {{ props.buyerSummary?.total_transaction }}</td>
                                                      <td class="fw-bold fs-7">{{ $t('buyer.label.totalPayment') }}: {{ props.buyerSummary?.total_payment }}</td>
                                                      <td class="fw-bold fs-7 text-end">{{ $t('buyer.label.totalDue') }}: {{ props.buyerSummary?.total_due }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                    <div class="d-flex justify-content-between mt-20">
                                        <div>
                                            {{ $t('buyer.label.receiverSignature') }}
                                        </div>
                                        <div style="margin-left: 44px;">
                                            {{ $t('buyer.label.producerSignature') }}
                                        </div>
                                        <div>
                                            {{ $t('buyer.label.employeeSignature') }}
                                        </div>
                                    </div>
                                    <!--end:Order summary-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Body-->
                            <!-- begin::Footer-->
                            <div class="d-flex flex-stack flex-wrap mt-lg-2 pt-8">
                                <!-- begin::Actions-->
                                <div class="my-1 me-5">
                                    <!-- begin::Pint-->
                                    <button type="button" class="btn btn-primary my-1 me-12" onclick="window.print()">{{ $t('buyer.header.show.buttonValue.printInvoice') }}</button>
                                    <!-- end::Pint-->
                                </div>
                                <!-- end::Actions-->
                            </div>
                            <!-- end::Footer-->
                        </div>
                        <!-- end::Wrapper-->
                    </div>
                    <!-- end::Body-->
                </div>
                <!-- end::Invoice 1-->
            </div>
            <!--end::Content container-->
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineProps } from "vue";
import { router } from '@inertiajs/vue3';
import { MenuComponent } from "@/Assets/ts/components";

const props = defineProps({
    buyer: Object,
    payment: Object,
    address: String,
    buyerSummary: Object,
    pageTitle: String,
    breadcrumbs: Array as () => Breadcrumb[],
});

interface Breadcrumb {
    url: string;
    title: string;
}

</script>
<style>
@media print {
    body * {
        visibility: hidden;
    }

    #invoice-content, #invoice-content * {
        visibility: visible;
    }
}

</style>

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
                                        <h1 class="mb-3">{{ props.configuration?.name_bn }}</h1>
                                        <h4 class="mb-3">{{ props.configuration?.name }}</h4>
                                        <h5>
                                            <span v-if="props.configuration?.address">{{ props.configuration.address }}</span>
                                            <span v-if="props.configuration?.mobile_number">
                                            | {{ $t('label.mobileNumber') }}: {{ props.configuration.mobile_number }}
                                            {{ props.configuration.additional_mobile_number ? `, ${props.configuration.additional_mobile_number}` : '' }}
                                            </span>
                                        </h5>

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
                                            <span class="fw-bold fs-7">{{ $t('label.date') }}:</span> {{ formatDate(props.sale?.sale_date) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold fs-7"> {{ $t('sale.label.invoiceNumber') }}:</span> {{ props.sale?.invoice_number }}
                                        </div>
                                    </div>
                                    <!--begin::Message-->
                                    <!--begin::Separator-->
                                    <div class="separator"></div>
                                    <!--begin::Separator-->
                                    <!--begin:Order summary-->
                                    <div class="">
                                        <!--begin::Table-->
                                        <div class="w-100">
                                            <table class="table w-100 align-middle table-row-dashed fs-6 gy-5 mb-0">
                                                <!-- Table Header -->
                                                <thead class="fs-6 fw-semibold text-gray-600">
                                                    <tr>
                                                        <th class="text-start w-15">{{ $t('sale.label.description') }}</th>
                                                        <th class="text-end w-10" v-if="props.sale?.sale_type !=='other'">{{ $t('sale.label.box') }}</th>
                                                        <th class="text-end w-10" v-if="props.sale?.sale_type !=='other'">{{ $t('sale.label.poly') }}</th>
                                                        <th class="text-end w-10" v-if="props.sale?.sale_type !=='other'">{{ $t('sale.label.mir') }}</th>
                                                        <th class="text-end w-10">{{ $t('sale.label.quantity') }}</th>
                                                        <th class="text-end w-10">{{ $t('sale.label.unitPrice') }}</th>
                                                        <th class="text-end w-15">{{ $t('sale.label.totalPrice') }}</th>
                                                    </tr>
                                                </thead>

                                                <!-- Table Body -->
                                                <tbody class="fw-semibold text-gray-600">
                                                    <tr v-for="saleItem in props.saleItems" :key="saleItem.id">
                                                        <td class="w-10">{{ items(saleItem) }}{{ brands(saleItem) ? ` (${brands(saleItem)})` : '' }}</td>
                                                        <td class="text-end w-10" v-if="props.sale?.sale_type !=='other'">{{ saleItem.total_box }}</td>
                                                        <td class="text-end w-10" v-if="props.sale?.sale_type !=='other'">{{ saleItem.total_poly }}</td>
                                                        <td class="text-end w-10" v-if="props.sale?.sale_type !=='other'">{{ saleItem.mir }}</td>
                                                        <td class="text-end w-10">{{ saleItem.quantity }}</td>
                                                        <td class="text-end w-10">{{ saleItem.unit_price }}</td>
                                                        <td class="text-end">{{ saleItem.total_price }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td :colspan="props.sale?.sale_type === 'other'? 2 : 5"></td>
                                                        <td class="text-end">{{ $t('sale.label.total') }}</td>
                                                        <td class="text-end">{{ props.sale?.sub_total }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td :colspan="props.sale?.sale_type === 'other'? 2 : 5"></td>
                                                        <td class="text-end">{{ $t('sale.label.discountAmount') }}</td>
                                                        <td class="text-end">{{ props.sale?.discount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td :colspan="props.sale?.sale_type === 'other'? 2 : 5"></td>
                                                        <td class="text-end">{{ $t('sale.label.grandTotal') }}</td>
                                                        <td class="text-end">{{ props.sale?.grand_total }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td :colspan="props.sale?.sale_type === 'other'? 2 : 5"></td>
                                                        <td class="text-end">{{ $t('sale.label.paid') }}</td>
                                                        <td class="text-end">{{ props.sale?.paid_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td :colspan="props.sale?.sale_type === 'other'? 2 : 5"></td>
                                                        <td class="text-end">{{ $t('sale.label.due') }}</td>
                                                        <td class="text-end">{{ props.sale?.grand_total - props.sale?.paid_amount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                    <div class="d-flex justify-content-between mt-20">
                                        <div>
                                            {{ $t('sale.label.receiverSignature') }}
                                        </div>
                                        <div>
                                            {{ $t('sale.label.producerSignature') }}
                                        </div>
                                        <div>
                                            {{ $t('sale.label.employeeSignature') }}
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
                                    <button type="button" class="btn btn-primary my-1 me-12" @click="printInvoice">{{ $t('sale.buttonValue.printInvoice') }}</button>
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
import { MenuComponent } from "@/Assets/ts/components";
import dayjs from "dayjs";

const props = defineProps({
    buyer: Object,
    sale: Object,
    address: String,
    saleItems: Array as () => ISaleItem[],
    items: Array as () => IItem[],
    brands: Array as () => IBrand[],
    buyerSummary: Object,
    configuration: Object,
    pageTitle: String,
    breadcrumbs: Array as () => Breadcrumb[],
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISaleItem {
    id: number;
    item_id: number;
    brand_id: number;
    total_box: number;
    total_poly: number;
    mir: number;
    quantity: number;
    unit_price: number;
    total_price: number;
}

interface IItem {
    id: number;
    name: string;
}

interface IBrand {
    id: number;
    name: string;
}

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
};

const items = (saleItem: any) => {
    const foundItem = props.items?.find((item: any) => Number(item.id) === Number(saleItem.item_id));
    return foundItem ? foundItem.name : "Item not found";
};

const brands = (saleItem: any) => {
    const foundBrand = props.brands?.find((brand: any) => Number(brand.id) === Number(saleItem.brand_id));
    return foundBrand ? foundBrand.name : '';
};

const printInvoice = () => {
    const invoiceNumber = props.sale?.invoice_number || 'invoice';
    document.title = `Invoice_${invoiceNumber}`;
    window.print();
};

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

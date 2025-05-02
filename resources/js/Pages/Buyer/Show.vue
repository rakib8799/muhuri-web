<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
            <div class="content flex-row-fluid" id="kt_content">
                <div class="d-flex flex-column flex-lg-row">
                    <!-- User Information Section -->
                    <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column py-5">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-50px symbol-circle mb-7">
                                        <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                        <div class="symbol-label fs-3 bg-light-danger text-danger" >
                                            {{ getInitials(props?.buyer) }}
                                        </div>
                                    </div>
                                    <!--end::Avatar-->

                                    <!--begin::Name-->
                                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ props.buyer?.name }}</a
                                    >
                                    <!--end::Name-->

                                    <!--begin::Mobile Number-->
                                    <div class="mb-2">
                                        <div class="me-3">
                                            {{ props.buyer?.mobile_number }}
                                        </div>
                                    </div>

                                    <div class="mb-9">
                                        <div class="me-3">
                                            {{ props.address }}
                                        </div>
                                    </div>
                                    <!--end::Mobile Number-->
                                </div>
                                <!--end::Summary-->

                                <div class="separator"></div>
                                <!--begin::Details content-->
                                <div id="kt_user_view_details" class="collapse show">
                                    <div class="pb-5 fs-6">
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">
                                            {{ $t('label.totalTransaction') }}
                                        </div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary">
                                                {{ props.buyerSummary?.total_transaction }}
                                            </a>
                                        </div>

                                        <div class="fw-bold mt-5">
                                            {{ $t('label.totalPayment') }}
                                        </div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary">
                                                {{ props.buyerSummary?.total_payment }}
                                            </a>
                                        </div>

                                        <div class="fw-bold mt-5">
                                            {{ $t('label.totalDue') }}
                                        </div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary">
                                                {{ Math.abs(props.buyerSummary?.total_due || 0) }}
                                            </a>
                                        </div>
                                        <!--begin::Details item-->
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                        </div>
                    </div>

                    <!-- Tab Sections -->
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <!--begin::Nav Tab-->
                        <div class="card-toolbar m-0">
                            <ul class="nav nav-stretch fs-5 fw-semibold nav-line-tabs nav-line-tabs-2x border-transparent" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a
                                        id="kt_referrals_branch_details_tab"
                                        class="nav-link text-active-primary"
                                        data-bs-toggle="tab"
                                        role="tab"
                                        href="javascript:void(0)"
                                        @click="activeTab = 1"
                                        :class="{ active: activeTab === 1 }"
                                    >
                                        {{ $t('buyer.header.transactions') }}
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a
                                        id="kt_referrals_employee_list_tab"
                                        class="nav-link text-active-primary"
                                        data-bs-toggle="tab"
                                        role="tab"
                                        href="javascript:void(0)"
                                        @click="activeTab = 2"
                                        :class="{ active: activeTab === 2 }"
                                    >
                                         {{ $t('buyer.header.payment') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!--end::Nav Tab-->

                        <!-- Start: My Sale Section -->
                        <div id="kt_referred_employees_tab_content" class="tab-content">
                            <div id="branch_details" class="py-4 tab-pane fade active show" role="tabpanel" v-show="activeTab === 1">
                                <div class="card card-flush mb-6 mb-xl-9">
                                    <div class="card-header mt-6">
                                        <div class="card-title flex-column">
                                            <h2 class="mb-1">
                                                {{ $t('buyer.label.transaction') }}
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <Datatable
                                                @on-sort="sortData"
                                                :data="saleTableData"
                                                :header="saleTableHeader"
                                                :enable-items-per-page-dropdown="false"
                                                :items-per-page="Infinity"
                                                :pagination-enabled="true"
                                                :checkbox-enabled="false"
                                            >
                                            <template v-slot:sale_date="{ row: sale }">
                                                {{ formatDate(sale.sale_date) }}
                                            </template>

                                            <template v-slot:invoice_number="{ row: sale }">
                                                {{ sale.invoice_number }}
                                            </template>

                                            <template v-slot:paid_amount="{ row: sale }">
                                                {{ sale.paid_amount }}
                                            </template>

                                            <template v-slot:grand_total="{ row: sale }">
                                                {{ sale.grand_total }}
                                            </template>

                                            <template v-slot:action="{ row: sale }">
                                                <Link :href="route('sales.show', sale)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.view')">
                                                    <KTIcon icon-name="book" icon-class="fs-3 text-primary"/>
                                                </Link>
                                            </template>

                                            </Datatable>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: My Sale Section -->

                        <!-- Start Payment -->
                        <div id="kt_referred_employees_tab_content" class="tab-content">
                            <div id="branch_details" class="py-4 tab-pane fade active show" role="tabpanel" v-show="activeTab === 2">
                                <div class="card card-flush mb-6 mb-xl-9">
                                    <div class="card-header mt-6">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <h2>{{ $t('buyer.header.payment') }}</h2>
                                            </div>
                                        </div>

                                        <div class="card-toolbar">
                                            <!--begin::Toolbar-->
                                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                                <!--begin::Add Buyer Payment-->
                                                <button
                                                    v-if="checkPermission('can-create-buyer-payment')"
                                                    type="button"
                                                    class="btn btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_add_buyer_payment"
                                                >
                                                    <KTIcon icon-name="plus" icon-class="fs-2"/>
                                                    {{ $t('buyer.header.show.buttonValue.addPayment') }}
                                                </button>
                                                <!--end::Add Buyer Payment -->
                                            </div>
                                            <!--end::Toolbar-->
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <Datatable
                                            @on-sort="sortData"
                                            :data="tableData"
                                            :header="tableHeader"
                                            :enable-items-per-page-dropdown="false"
                                            :items-per-page="Infinity"
                                            :pagination-enabled="true"
                                            :checkbox-enabled="false"
                                        >
                                            <!-- Date -->
                                            <template
                                                v-slot:payment_date="{ row: buyerPayment }">
                                                {{ formatDate(buyerPayment.payment_date) }}
                                            </template>

                                            <template v-slot:payment="{ row: buyerPayment }">
                                                {{ buyerPayment.amount }}
                                                <div class="fs-6 fw-semibold text-muted">
                                                    <small>{{ buyerPayment.payment_method }}</small>
                                                </div>
                                            </template>

                                            <!-- Action -->
                                            <template
                                                v-slot:actions="{ row: buyerPayment }">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <button
                                                        type="button"
                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_edit_buyer_payment"
                                                        @click="openEditModal(buyerPayment)"
                                                    >
                                                        <in class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </in>
                                                    </button>
                                                    <!-- Delete -->
                                                    <DeleteConfirmationButton v-if="checkPermission('can-delete-buyer-payment')"iconClass="fs-1" :buyerId="props?.buyer?.id" :payment="buyerPayment" confirmRoute="buyers.payments.destroy" title="Buyer Payment" :messageTitle="`${buyerPayment?.payment_date} তারিখের ${buyerPayment?.amount} টাকার পেমেন্ট?`"/>

                                                    <Link :href="route('buyers.payments.show', [props.buyer?.id, buyerPayment?.id])" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.view')">
                                                        <KTIcon icon-name="book" icon-class="fs-3 text-primary"/>
                                                    </Link>
                                                </div>
                                            </template>
                                        </Datatable>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Payment -->
                    </div>
                </div>
            </div>
        </div>

        <AddPayment
            :allPaymentMethod="props?.paymentMethods"
            :buyer="props?.buyer"
            :totalDue="props.buyerSummary?.total_due"
        ></AddPayment>
        <EditPayment
            :allPaymentMethod="props?.paymentMethods"
            :buyer="props?.buyer"
            :payment="selectedBuyerPayment"
        ></EditPayment>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, onMounted, onBeforeMount, watch } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { checkPermission } from "@/Core/helpers/Permission";
import arraySort from "array-sort";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { getInitials } from "@/Core/helpers/Helper";
import i18n from "@/Core/plugins/i18n";
import dayjs from "dayjs";
import AddPayment from "@/Pages/Buyer/Modal/AddPayment.vue";
import DeleteConfirmationButton from "@/Components/Button/DeleteConfirmationButton.vue";
import EditPayment from "@/Pages/Buyer/Modal/EditPayment.vue";

const { t } = i18n.global;

const props = defineProps({
    buyer: Object,
    address: String,
    paymentMethods: Array,
    buyerPayments: Array as () => IBuyerPayment[],
    buyerSummary: Object,
    sales: Object as () => ISale[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IBuyerPayment {
    id: number;
    waiver_amount: string;
    payment_date: string;
    payment_method: string;
    amount: number;
    note: string;
}

interface ISale {
    id: number;
    sale_date: string;
    invoice_number: number;
    paid_amount: number;
    grand_total: string;
}

const activeTab = ref(1);
const tabTitle = ref(props?.pageTitle);

watch(activeTab, (newValue) => {
    if (activeTab.value == 1) {
        tabTitle.value = "Transactions";
    } else {
        tabTitle.value = "Payments";
    }
});

// If a user status is changed, the following code be will be able to keep in the same tab.
onBeforeMount(() => {
    const flash = usePage().props.flash;
    let { success, error } = flash as any;

    if (flash && (success || error)) {
        activeTab.value = 2;
    }
});

const tableHeader = ref([
    {
        columnName: t('label.date'),
        columnLabel: "payment_date",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('buyer.header.show.payment'),
        columnLabel: "payment",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.actions'),
        columnLabel: "actions",
        sortEnabled: true,
        columnWidth: 100,
    }
]);

const tableData = ref<IBuyerPayment[]>([]);
const initBuyerPayments = ref<IBuyerPayment[]>([]);

onMounted(() => {
    if (props.buyerPayments) {
        initBuyerPayments.value = props.buyerPayments.map(
            (buyerPayment: any) => ({
                id: buyerPayment.id,
                waiver_amount: buyerPayment.waiver_amount,
                payment_date: buyerPayment.payment_date,
                payment_method: buyerPayment.payment_method,
                amount: buyerPayment.amount,
                note: buyerPayment.note,
            }),
        );

        tableData.value = [...initBuyerPayments.value];
    }
});

const saleTableHeader = ref([
    {
        columnName: t('sale.label.saleDate'),
        columnLabel: "sale_date",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('sale.label.invoiceNumber'),
        columnLabel: "invoice_number",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('sale.label.paid'),
        columnLabel: "paid_amount",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('sale.label.totalAmount'),
        columnLabel: "grand_total",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.actions'),
        columnLabel: "action",
        sortEnabled: true,
        columnWidth: 100,
    }
]);

const saleTableData = ref<ISale[]>([]);
const initSales = ref<ISale[]>([]);

onMounted(()=>{
    if(props.sales){
        initSales.value = props.sales.map((sale: any)=>({
            id: sale.id,
            sale_date: sale.sale_date,
            invoice_number: sale.invoice_number,
            paid_amount: sale.paid_amount,
            grand_total: sale.grand_total,
        }));
        saleTableData.value = initSales.value;
    }
});

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
};

const selectedBuyerPayment = ref({});
const openEditModal = (buyerPayment: any) => {
    selectedBuyerPayment.value = buyerPayment;
};

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse,
        });
    }
};
</script>

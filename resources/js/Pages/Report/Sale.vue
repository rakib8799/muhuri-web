<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card pt-5">
            <CompanyInformation :configuration="props?.configuration" />

            <div id="invoice-content">
                <h5 class="fs-3 text-center">{{ $t('report.label.fromDate', { date: formData.from_date }) }} - {{ $t('report.label.toDate', { date: formData.to_date }) }}</h5>
            </div>

            <div class="mt-8 mb-3 ms-8" id="invoice-content">
                <h5 class="fs-3">{{ $t('sale.label.totalAmount') }}: <span class="fw-normal">{{ props.totalAmount }}</span></h5>
            </div>

            <div class="card-header border-0">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input
                            type="text"
                            v-model="search"
                            @input="searchData()"
                            class="form-control form-control-solid w-250px ps-15"
                            :placeholder="$t('report.placeholder.searchSales')"
                        />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!-- Start: Filter -->
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>{{ $t('buttonValue.filter') }}
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">{{ $t('filterOptions.title') }}</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->

                            <!--begin::Content-->
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <VForm @submit="submit()" class="form">
                                    <!-- Start: Date filter -->
                                    <div class="mb-10 row">
                                        <div class="col-md-6">
                                            <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.report.subTitle.label.fromDate') }}</label>
                                            <Field type="date" class="form-control form-control-lg form-control-solid" name="from_date" v-model="formData.from_date" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.report.subTitle.label.toDate') }}</label>
                                            <Field type="date" class="form-control form-control-lg form-control-solid" name="to_date" v-model="formData.to_date" />
                                        </div>
                                    </div>
                                    <!-- End: Date filter -->

                                    <!-- Start: Sale type filter -->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.report.subTitle.label.saleType') }}:</label>
                                        <Multiselect
                                            :placeholder = "$t('filterOptions.report.subTitle.placeholder.saleType')"
                                            v-model="formData.sale_type"
                                            :searchable="true"
                                            :options="props?.saleTypes"
                                            label="text"
                                            trackBy="value"
                                        />
                                    </div>
                                    <!-- End: Sale type filter -->

                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset" @click="applyReset()">{{ $t('buttonValue.reset') }}</button>
                                        <button class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" @click="applyFilter()">{{ $t('buttonValue.apply') }}</button>
                                    </div>
                                </VForm>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--End: Filter-->

                        <!--begin::Print-->
                        <button type="button" class="btn btn-primary my-1 me-12" @click="printInvoice">{{ $t('sale.buttonValue.printInvoice') }}</button>
                        <!--end::Print-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>


            <div class="card-body pt-0" id="invoice-content">
                <Datatable
                    class="table"
                    @on-sort="sortData"
                    :data="tableData"
                    :header="tableHeader"
                    :enable-items-per-page-dropdown="false"
                    :items-per-page="Infinity"
                    :pagination-enabled="true"
                    :checkbox-enabled="false"
                >

                    <template v-slot:invoice_number="{ row: sale }">
                        {{ sale.invoice_number }}
                    </template>

                    <template v-slot:sale_date="{ row: sale }">
                        {{ formatDate(sale.sale_date) }}
                    </template>

                    <template v-slot:buyer="{ row: sale }">
                        {{ sale.buyer }}
                    </template>

                    <template v-slot:sale_type="{ row: sale }">
                        {{ sale.sale_type }}
                    </template>

                    <template v-slot:sub_total="{ row: sale }">
                        {{ sale.sub_total }}
                    </template>

                    <template v-slot:discount="{ row: sale }">
                        {{ sale.discount }}
                    </template>

                    <template v-slot:grand_total="{ row: sale }">
                        {{ sale.grand_total }}
                    </template>

                    <template v-slot:paid_amount="{ row: sale }">
                        {{ sale.paid_amount }}
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, defineProps, onMounted } from "vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import arraySort from "array-sort";
import { useForm } from "@inertiajs/vue3";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { MenuComponent } from "@/Assets/ts/components";
import i18n from '@/Core/plugins/i18n';
import dayjs from "dayjs";
import { Field } from "vee-validate";
import Multiselect from '@vueform/multiselect';
import { Inertia } from '@inertiajs/inertia';
import CompanyInformation from "@/Components/CompanyInformation.vue";

const { t } = i18n.global;

const props = defineProps({
    configuration: Object,
    sales: Array as () => ISale[] | undefined,
    totalAmount: Number,
    saleTypes: Array,
    query: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISale {
    id: number;
    buyer: string;
    invoice_number: number;
    sale_date: string;
    sale_type: string;
    sub_total: number;
    discount: number;
    grand_total: number;
}

const tableHeader = ref([
    {
        columnName: t('sale.label.invoiceNumber'),
        columnLabel: "invoice_number",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('label.date'),
        columnLabel: "sale_date",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.buyer'),
        columnLabel: "buyer",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.saleType'),
        columnLabel: "sale_type",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.subTotal'),
        columnLabel: "sub_total",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.discountAmount'),
        columnLabel: "discount",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.grandTotal'),
        columnLabel: "grand_total",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.paidAmount'),
        columnLabel: "paid_amount",
        sortEnabled: true,
        columnWidth: 75,
    }
]);

const tableData = ref<ISale[]>([]);
const initData = ref<ISale[]>([]);

onMounted(()=>{
    if(props.sales){
        initData.value = props.sales.map((sale: any)=>({
            id: sale.id,
            buyer: sale.buyer?.name,
            invoice_number: sale.invoice_number,
            sale_date: sale.sale_date,
            sale_type: sale.sale_type === 'larvae' ? 'পোনা' : 'অন্যান্য',
            sub_total: sale.sub_total,
            discount: sale.discount,
            grand_total: sale.grand_total,
            paid_amount: sale.paid_amount
        }));
        tableData.value = [...initData.value];
    }
});

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
};

const today = dayjs().format('YYYY-MM-DD');
const { from_date, to_date } = props.query || { from_date: '', to_date: '' };

const formData = useForm({
    from_date: from_date || today,
    to_date: to_date || today,
    sale_type: ''
});

// Apply Filter Function
const applyFilter = () => {
    Inertia.replace(route('reports.sale'), {
        preserveScroll: true,
        preserveState: true,
        data: {
            from_date: formData.from_date,
            to_date: formData.to_date,
            sale_type: formData.sale_type
        }
    });
};

const applyReset = () => {
    formData.from_date = today;
    formData.to_date = today;
    formData.sale_type = '';
    applyFilter();
};

const submit = () => {
    applyFilter();
};

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initData.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter((item) =>
            searchingFunc(item, search.value),
        );
    }
    MenuComponent.reinitialization();
};

const searchingFunc = (obj: any, value: string): boolean => {
    for (let key in obj) {
        if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
            if (obj[key].includes(value)) {
                return true;
            }
        }
    }
    return false;
};

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse,
        });
    }
};

const printInvoice = () => {
    document.title = `Invoices_${formData.from_date}-${formData.to_date}`;
    window.print();
};
</script>

<style>
    .table th, .table td {
        text-align: center !important;
    }

    @media print {
        html, body *{
            overflow: hidden !important;
            visibility: hidden;
        }

        .card-header {
            margin-top: -6rem;
        }

        #invoice-content, #invoice-content * {
            visibility: visible;
        }

        .table th, .table td {
            border: 0.2px solid #9291914f !important;
        }

        .table:not(.table-bordered) tfoot tr:last-child, .table:not(.table-bordered) tbody tr:last-child {
            border-bottom: 0.2px solid #9291914f !important;
        }
    }
</style>

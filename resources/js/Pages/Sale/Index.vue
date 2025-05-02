<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input
                            type="text"
                            v-model="search"
                            @input="searchData()"
                            class="form-control form-control-solid w-250px ps-15"
                            :placeholder="$t('sale.placeholder.searchSale')"
                        />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <Link v-if="checkPermission('can-create-sale')" :href="route('sales.others.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t("sale.buttonValue.otherSale") }}
                        </Link>
                    </div>

                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Fiscal Year-->
                        <Link v-if="checkPermission('can-create-sale')" :href="route('sales.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t("sale.buttonValue.larvaeSale") }}
                        </Link>
                        <!--end::Add Fiscal Year-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable
                    @on-sort="sortData"
                    :data="tableData"
                    :header="tableHeader"
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

                <template v-slot:sale_type="{ row: sale }">
                    {{ sale.sale_type }}
                </template>

                <template v-slot:buyer="{ row: sale }">
                    {{ sale.buyer }}
                </template>

                <template v-slot:sub_total="{ row: sale }">
                    {{ sale.sub_total }}
                </template>

                <template v-slot:paid_amount="{ row: sale }">
                    {{ sale.paid_amount }}
                </template>

                <template v-slot:note="{ row: sale }">
                    {{ sale.note }}
                </template>

                <template v-slot:action="{ row: sale }">
                    <Link :href="route('sales.edit', sale)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                        <KTIcon icon-name="pencil" icon-class="fs-3 text-primary"/>
                    </Link>

                    <Link :href="route('sales.show', sale)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.view')">
                        <KTIcon icon-name="book" icon-class="fs-3 text-primary"/>
                    </Link>
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
import { Link } from "@inertiajs/vue3";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { MenuComponent } from "@/Assets/ts/components";
import { checkPermission } from "@/Core/helpers/Permission";
import i18n from '@/Core/plugins/i18n';
import dayjs from "dayjs";

const { t } = i18n.global;

const props = defineProps({
    sales: Object as () => ISale[] | undefined,
    buyers: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISale {
    id: number;
    sale_date: string;
    invoice_number: number;
    buyer: string;
    buyer_id: number;
    paid_amount: number;
    sub_total: string;
    sale_type: string;
    note: string;
}

const tableHeader = ref([
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
        columnName: t('sale.label.saleType'),
        columnLabel: "sale_type",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('sale.label.buyer'),
        columnLabel: "buyer",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('sale.label.totalAmount'),
        columnLabel: "sub_total",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('sale.label.paid'),
        columnLabel: "paid_amount",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.note'),
        columnLabel: "note",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.actions'),
        columnLabel: "action",
        sortEnabled: true,
        columnWidth: 100,
    },
]);

const tableData = ref<ISale[]>([]);
const initSales = ref<ISale[]>([]);

onMounted(()=>{
    if(props.sales){
        initSales.value = props.sales.map((sale: any)=>({
            id: sale.id,
            sale_date: sale.sale_date,
            invoice_number: sale.invoice_number,
            buyer: props.buyers?.find((buyer: any) => buyer.id === sale.buyer_id)?.name,
            buyer_id: sale.buyer_id,
            paid_amount: sale.paid_amount,
            sub_total: sale.sub_total,
            sale_type: (sale.sale_type === 'other') ? 'অন্যান্য' : '',
            note: sale.note
        }));
        tableData.value = initSales.value;
    }
});

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
};

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initSales.value];
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
</script>

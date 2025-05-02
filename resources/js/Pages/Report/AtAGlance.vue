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
                            :placeholder="$t('report.placeholder.searchSummaries')"
                        />
                    </div>
                    <!--end::Search-->
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
                    <template v-slot:summary_date="{ row: summary }">
                        {{ formatDate(summary.summary_date) }}
                    </template>

                    <template v-slot:total_purchase="{ row: summary }">
                        {{ summary.total_purchase }}
                    </template>

                    <template v-slot:total_sale="{ row: summary }">
                        {{ summary.total_sale }}
                    </template>

                    <template v-slot:total_expense="{ row: summary }">
                        {{ summary.total_expense }}
                    </template>

                    <template v-slot:total_buyer_payment="{ row: summary }">
                        {{ summary.total_buyer_payment }}
                    </template>

                    <template v-slot:total_supplier_payment="{ row: summary }">
                        {{ summary.total_supplier_payment }}
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
    summaries: Array as () => ISummary[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISummary {
    id: number;
    summary_date: string;
    total_purchase: number;
    total_sale: number;
    total_expense: number;
    total_buyer_payment: number;
    total_supplier_payment: number;
}

const tableHeader = ref([
    {
        columnName: t('label.date'),
        columnLabel: "summary_date",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('buyer.label.totalPurchase'),
        columnLabel: "total_purchase",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('buyer.label.totalSale'),
        columnLabel: "total_sale",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('buyer.label.totalExpense'),
        columnLabel: "total_expense",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('buyer.label.totalBuyerPayment'),
        columnLabel: "total_buyer_payment",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('buyer.label.totalSupplierPayment'),
        columnLabel: "total_supplier_payment",
        sortEnabled: true,
        columnWidth: 100,
    }
]);

const tableData = ref<ISummary[]>([]);
const initData = ref<ISummary[]>([]);

onMounted(()=>{
    if(props.summaries){
        initData.value = props.summaries.map((summary: any)=>({
            id: summary.id,
            summary_date: summary.summary_date,
            total_purchase: summary.total_purchase,
            total_sale: summary.total_sale,
            total_expense: summary.total_expense,
            total_buyer_payment: summary.total_buyer_payment,
            total_supplier_payment: summary.total_supplier_payment
        }));
        tableData.value = [...initData.value];
    }
});

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
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
</script>

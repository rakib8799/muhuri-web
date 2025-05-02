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
                            :placeholder="$t('purchase.placeholder.searchPurchase')"
                        />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <Link v-if="checkPermission('can-create-purchase')" :href="route('purchases.others.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t("purchase.buttonValue.otherPurchase") }}
                        </Link>
                    </div>

                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Fiscal Year-->
                        <Link v-if="checkPermission('can-create-purchase')" :href="route('purchases.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t("purchase.buttonValue.larvaePurchase") }}
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
                <template v-slot:purchase_date="{ row: purchase }">
                    {{ formatDate(purchase.purchase_date) }}
                </template>

                <template v-slot:invoice_number="{ row: purchase }">
                    {{ purchase.invoice_number }}
                </template>

                <template v-slot:purchase_type="{ row: purchase }">
                    {{ purchase.purchase_type }}
                </template>

                <template v-slot:supplier="{ row: purchase }">
                    {{ purchase.supplier }}
                </template>

                <template v-slot:sub_total="{ row: purchase }">
                    {{ purchase.sub_total }}
                </template>

                <template v-slot:paid_amount="{ row: purchase }">
                    {{ purchase.paid_amount }}
                </template>

                <template v-slot:note="{ row: purchase }">
                    {{ purchase.note }}
                </template>

                <template v-slot:action="{ row: purchase }">
                    <Link v-if="checkPermission('can-edit-purchase')" :href="route('purchases.edit', purchase)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                        <KTIcon icon-name="pencil" icon-class="fs-3 text-primary"/>
                    </Link>

                    <Link v-if="checkPermission('can-view-details-purchase')" :href="route('purchases.show', purchase)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.view')">
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
    purchases: Object as () => IPurchase[] | undefined,
    suppliers: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IPurchase {
    id: number;
    purchase_date: string;
    invoice_number: number;
    supplier: string;
    supplier_id: number;
    paid_amount: number;
    sub_total: string;
    purchase_type: string;
    note: string;
}

const tableHeader = ref([
    {
        columnName: t('purchase.label.purchaseDate'),
        columnLabel: "purchase_date",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('label.invoiceNumber'),
        columnLabel: "invoice_number",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('purchase.label.purchaseType'),
        columnLabel: "purchase_type",
        sortEnabled: true,
        columnWidth: 75,
    },
    {
        columnName: t('purchase.label.supplier'),
        columnLabel: "supplier",
        sortEnabled: true,
        columnWidth: 150,
    },
    {
        columnName: t('label.totalAmount'),
        columnLabel: "sub_total",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.paid'),
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

const tableData = ref<IPurchase[]>([]);
const initPurchases = ref<IPurchase[]>([]);

onMounted(() => {
    if(props.purchases){
        initPurchases.value = props.purchases.map((purchase: any)=>({
            id: purchase.id,
            purchase_date: purchase.purchase_date,
            invoice_number: purchase.invoice_number,
            supplier: props.suppliers?.find((supplier: any) => supplier.id === purchase.supplier_id)?.name,
            supplier_id: purchase.supplier_id,
            paid_amount: purchase.paid_amount,
            sub_total: purchase.sub_total,
            purchase_type: (purchase.purchase_type === 'other') ? 'অন্যান্য' : '',
            note: purchase.note
        }));
        tableData.value = initPurchases.value;
    }
});

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
};

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initPurchases.value];
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

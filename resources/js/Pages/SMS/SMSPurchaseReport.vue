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
                            :placeholder="$t('sms.placeholder.searchSMS')"
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
                    <template v-slot:sms_quantity="{ row: purchase }">
                        {{ purchase.sms_quantity }}
                    </template>

                    <template v-slot:sms_rate="{ row: purchase }">
                        {{ purchase.sms_rate }}
                    </template>

                    <template v-slot:total_price="{ row: purchase }">
                        {{ purchase.total_price }}
                    </template>

                    <template v-slot:payment_status="{ row: purchase }">
                        <span :class="getStatusClass(purchase.payment_status)">{{ purchase.payment_status }}</span>
                    </template>

                    <template v-slot:payment_id="{ row: purchase }">
                        {{ purchase.payment_id }}
                    </template>

                    <template v-slot:created_by="{ row: purchase }">
                        {{ purchase.created_by_name }}
                    </template>
                    <template v-slot:created_at="{ row: purchase }">
                        {{ dayjs(purchase.created_at).format('DD-MM-YYYY') }}
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
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { MenuComponent } from "@/Assets/ts/components";
import i18n from '@/Core/plugins/i18n';
import dayjs from "dayjs";

const { t } = i18n.global;

const props = defineProps({
    smsPurchases: Object as () => ISMSPurchase[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISMSPurchase {
    id: number;
    sms_quantity: number;
    sms_rate: number;
    total_price: number;
    payment_status: string;
    payment_id: string;
    created_by_name: string;
    created_at: string;
}

const tableHeader = ref([
    {
        columnName: t('label.quantity'),
        columnLabel: "sms_quantity",
        sortEnabled: true,
        columnWidth: 125
    },
    {
        columnName: t('label.rate'),
        columnLabel: "sms_rate",
        sortEnabled: true,
        columnWidth: 125
    },
    {
        columnName: t('label.totalPrice'),
        columnLabel: "total_price",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: t('label.paymentStatus'),
        columnLabel: "payment_status",
        sortEnabled: true,
        columnWidth: 125
    },
    {
        columnName: t('sms.label.paymentId'),
        columnLabel: "payment_id",
        sortEnabled: true,
        columnWidth: 200
    },
    {
        columnName: t('label.createdBy'),
        columnLabel: "created_by",
        sortEnabled: true,
        columnWidth: 125
    },
    {
        columnName: t('label.date'),
        columnLabel: "created_at",
        sortEnabled: true,
        columnWidth: 75
    },
]);

const tableData = ref<ISMSPurchase[]>([]);
const initPurchases = ref<ISMSPurchase[]>([]);

onMounted(()=>{
    if(props.smsPurchases){
        console.log(props.smsPurchases);
        initPurchases.value = props.smsPurchases.map((purchase: any) => ({
            id: purchase.id,
            sms_quantity: purchase.sms_quantity,
            sms_rate: purchase.sms_rate,
            total_price: purchase.total_price,
            payment_status: purchase.payment_status,
            payment_id: purchase.transaction?.payment_id,
            created_by_name: purchase.created_by.name,
            created_at: purchase.created_at,
        }));
        tableData.value = initPurchases.value;
    }
});

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

const getStatusClass = (status: string) => {
    switch (status) {
        case 'paid': return 'badge badge-success';
        case 'pending': return 'badge badge-warning';
        case 'failed': return 'badge badge-danger';
        default: return 'badge badge-secondary';
    }
};
</script>

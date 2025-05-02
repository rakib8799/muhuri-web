<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input
                            type="text"
                            v-model="search"
                            @input="searchData()"
                            class="form-control form-control-solid w-250px ps-15"
                            :placeholder="$t('buyer.placeholder.searchBuyers')"
                        />
                    </div>
                </div>
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base" >
                        <!--begin::Add Fiscal Year-->
                        <button v-if="checkPermission ('can-create-buyer')"
                              class="btn btn-primary me-3"
                              @click.prevent="sendReminder"
                        >
                            <i class="fa-solid fa-bell"></i> {{ $t("buyer.header.sendDueReminder") }}
                        </button>
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
                    <template v-slot:name="{ row: buyerDue }">
                        <Link :href="route('buyers.show', buyerDue.id)" class="text-gray-800 text-hover-primary">
                            {{ buyerDue.name }}
                        </Link>
                    </template>

                    <template v-slot:mobile_number="{ row: buyerDue }">
                        {{ buyerDue.mobile_number }}
                    </template>

                    <template v-slot:total_transaction="{ row: buyerDue }">
                        {{ buyerDue.total_transaction }}
                    </template>

                    <template v-slot:total_payment="{ row: buyerDue }">
                        {{ buyerDue.total_payment }}
                    </template>

                    <template v-slot:total_due="{ row: buyerDue }">
                        {{ buyerDue.total_due }}
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, onMounted, defineProps } from "vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import arraySort from "array-sort";
import { Link } from "@inertiajs/vue3";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { MenuComponent } from "@/Assets/ts/components";
import i18n from '@/Core/plugins/i18n';
import {checkPermission} from "@/Core/helpers/Permission";
import axios from "axios";
import Swal from "sweetalert2";

const { t } = i18n.global;

const props = defineProps({
    buyerDues: Object as () => IBuyerDue[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IBuyerDue {
    id: number;
    name: string;
    total_transaction: number;
    total_payment: number;
    total_due: number;
    mobile_number: string;
}

const tableHeader = ref([
    { columnName: t('label.name'), columnLabel: "name", sortEnabled: true },
    { columnName: t('label.mobileNumber'), columnLabel: "mobile_number", sortEnabled: true },
    { columnName: t('label.totalTransaction'), columnLabel: "total_transaction", sortEnabled: true },
    { columnName: t('label.totalPayment'), columnLabel: "total_payment", sortEnabled: true },
    { columnName: t('label.totalDue'), columnLabel: "total_due", sortEnabled: true },
]);

const tableData = ref<IBuyerDue[]>([]);
const initBuyerDues = ref<IBuyerDue[]>([]);

onMounted(() => {
    if (props.buyerDues) {
        initBuyerDues.value = props.buyerDues;
        tableData.value = initBuyerDues.value;
    }
});

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initBuyerDues.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter((item) =>
            Object.values(item).some(value => value.toString().toLowerCase().includes(search.value.toLowerCase()))
        );
    }
    MenuComponent.reinitialization();
};

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, { reverse });
    }
};

const sendReminder = async () => {
    const dueBuyers = tableData.value.filter(buyer => buyer.total_due > 0); // Filter buyers with due
    if (dueBuyers.length === 0) {
        Swal.fire({
            text: `${t('confirmation.buyer.noBuyers')}`,
            icon: "info",
            confirmButtonText: `${t('confirmation.gotIt')}`,
            heightAuto: false,
            buttonsStyling: false,
            customClass: {
                confirmButton: "btn fw-semibold btn-light-primary",
            },
        });
        return;
    }

    // Show confirmation dialog
    const result = await Swal.fire({
        text: `${t('confirmation.buyer.confirmReminder', { count: dueBuyers.length })}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: `${t('confirmation.buyer.yesSendSMS')}`,
        cancelButtonText: `${t('confirmation.cancel')}`,
        heightAuto: false,
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn fw-semibold btn-danger",
            cancelButton: "btn fw-semibold btn-light",
        },
    });

    if (!result.isConfirmed) {
        return;
    }

    try {
        // Axios request to send reminders
        const response = await axios.post(route('buyers.sendDueReminder'), {});

        // Handle successful response
        Swal.fire({
            text: `${t('confirmation.buyer.reminderSent')}`,
            icon: "success",
            confirmButtonText: `${t('confirmation.gotIt')}`,
            heightAuto: false,
            buttonsStyling: false,
            customClass: {
                confirmButton: "btn fw-semibold btn-light-primary",
            },
        });
    } catch (error) {
        // Handle error response
        console.error(error);
        alert("An error occurred while sending reminders.");
    }
};
</script>

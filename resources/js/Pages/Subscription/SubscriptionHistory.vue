<template>
    <!--begin::Section-->
    <div class="mb-0">
        <!--begin::Title-->
        <h5 class="mb-4">{{ $t('subscription.header.subscriptionHistory') }}:</h5>
        <!--begin::Search-->
        <div class="d-flex align-items-center position-relative mt-1">
            <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
            <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('subscription.placeholder.searchSubscriptionHistory')" />
        </div>
        <!--end::Search-->
        <!--end::Title-->

        <!--begin::Subscription table-->
        <div class="table-responsive" style="overflow: hidden;">
            <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                <!-- Plan Name -->
                <template v-slot:subscription_plan="{ row: subscriptionHistory }">
                    {{ subscriptionHistory.subscription_plan }}
                </template>

                <!-- Start Date -->
                <template v-slot:start_date="{ row: subscriptionHistory }">
                    {{ subscriptionHistory.start_date }}
                </template>

                <!-- End Date -->
                <template v-slot:end_date="{ row: subscriptionHistory }">
                    {{ subscriptionHistory.end_date }}
                </template>

                <!-- Final Price -->
                <template v-slot:final_price="{ row: subscriptionHistory }">
                    {{ subscriptionHistory.final_price }}
                </template>
            </Datatable>
        </div>
        <!--end::Subscription table-->
    </div>
    <!--end::Section-->
</template>

<script lang="ts" setup>
import { onMounted, ref } from "vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import i18n from '@/Core/plugins/i18n';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { ISubscriptionHistory } from '@/Core/helpers/Interfaces';
import { extractDate } from "@/Core/helpers/Helper";

const { t } = i18n.global;

const props = defineProps({
    subscriptionHistories: Object as() => ISubscriptionHistory[] | undefined
});

const tableHeader = ref([
    {
        columnName: t('subscription.label.planName'),
        columnLabel: "subscription_plan",
        sortEnabled: true,
        columnWidth: 75
    },
    {
        columnName: t('subscription.label.startDate'),
        columnLabel: "start_date",
        sortEnabled: true,
        columnWidth: 75
    },
    {
        columnName: t('subscription.label.endDate'),
        columnLabel: "end_date",
        sortEnabled: true,
        columnWidth: 75
    },
    {
        columnName: t('subscription.label.finalPrice'),
        columnLabel: "final_price",
        sortEnabled: true,
        columnWidth: 75
    }
]);

const tableData = ref < ISubscriptionHistory[] > ([]);
const initSubscriptionHistory = ref < ISubscriptionHistory[] > ([]);

onMounted(() => {
    if (props.subscriptionHistories) {
        initSubscriptionHistory.value = props.subscriptionHistories.map(subscriptionHistory => ({
            id: subscriptionHistory.id,
            subscription_plan: subscriptionHistory?.subscription_plan?.name,
            start_date: (subscriptionHistory.start_date) ? extractDate(new Date(subscriptionHistory.start_date)) : '---',
            end_date: (subscriptionHistory.end_date) ? extractDate(new Date(subscriptionHistory.end_date)) : '---',
            final_price: subscriptionHistory.final_price,
            is_trial_taken: subscriptionHistory.is_trial_taken
        }));
        tableData.value = initSubscriptionHistory.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initSubscriptionHistory.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter(item => searchingFunc(item, search.value));
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
            reverse
        });
    }
};
</script>


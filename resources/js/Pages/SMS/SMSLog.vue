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
                    <template v-slot:mobile_number="{ row: log }">
                        {{ log.mobile_number }}
                    </template>

                    <template v-slot:sms_type="{ row: log }">
                        {{ log.sms_type }}
                    </template>

                    <template v-slot:message="{ row: log }">
                        {{ log.message }}
                    </template>

                    <template v-slot:sms_count="{ row: log }">
                        {{ log.sms_count }}
                    </template>

                    <template v-slot:status="{ row: log }">
                        <span :class="getStatusClass(log.status)">{{ log.status }}</span>
                    </template>

                    <template v-slot:error_message="{ row: log }">
                        <span>{{ log.error_message }}</span>
                    </template>

                    <template v-slot:created_at="{ row: log }">
                        {{ dayjs(log.created_at).format('DD-MM-YYYY') }}
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
    smsLogs: Object as () => ISMSLog[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISMSLog {
    id: number;
    mobile_number: string;
    sms_type: string;
    message: string;
    sms_count: number;
    status: string;
    created_at: string;
    error_message?: string;
}

const tableHeader = ref([
    {
        columnName: t('label.mobileNumber'),
        columnLabel: "mobile_number",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('label.smsType'),
        columnLabel: "sms_type",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('label.message'),
        columnLabel: "message",
        sortEnabled: false,
        columnWidth: 200
    },
    {
        columnName: t('label.smsCount'),
        columnLabel: "sms_count",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('label.status'),
        columnLabel: "status",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('label.errorMessage'),
        columnLabel: "error_message",
        sortEnabled: true,
        columnWidth: 200
    },
    {
        columnName: t('label.date'),
        columnLabel: "created_at",
        sortEnabled: true,
        columnWidth: 100
    },
]);

const tableData = ref<ISMSLog[]>([]);
const initLogs = ref<ISMSLog[]>([]);

onMounted(()=>{
    if(props.smsLogs){
        console.log(props.smsLogs);
        initLogs.value = props.smsLogs.map((log: any) => {
            const response = JSON.parse(log.response_body);
            const errorMessage = response.error_message ? response.error_message : '';
            return {
                id: log.id,
                mobile_number: log.mobile_number,
                sms_type: log.sms_type,
                message: log.message,
                sms_count: log.sms_count,
                status: log.status,
                created_at: log.created_at,
                error_message: errorMessage
            };
        });
        tableData.value = initLogs.value;
    }
});

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initLogs.value];
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
        case 'sent': return 'badge badge-success';
        case 'failed': return 'badge badge-danger';
        case 'pending': return 'badge badge-warning';
        default: return 'badge badge-secondary';
    }
};
</script>

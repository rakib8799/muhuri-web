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
                            :placeholder="$t('buyer.placeholder.searchBuyers')"
                        />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base" >
                        <!--begin::Add Fiscal Year-->
                        <Link v-if="checkPermission ('can-create-buyer')" :href="route('buyers.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" /> {{ $t("buyer.header.add") }}
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
                    <template v-slot:name="{ row: buyer }">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <Link v-if="checkPermission('can-view-details-buyer')" :href="route('buyers.show',buyer.id)">
                                    <div class="symbol-label">
                                        <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                        <div class="symbol-label fs-3 bg-light-danger text-danger">
                                            {{ getInitials(buyer) }}
                                        </div>
                                    </div>
                                </Link>
                                <div v-else class="symbol-label">
                                    <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                    <div class="symbol-label fs-3 bg-light-danger text-danger">
                                        {{ getInitials(buyer) }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <Link v-if="checkPermission('can-view-details-user')" :href="route('buyers.show', buyer.id)" class="text-gray-800 text-hover-primary mb-1">
                                    {{ buyer.name }}
                                </Link>
                                <span v-else>{{ buyer.name }}</span>
                            </div>
                        </div>
                    </template>

                    <!-- Mobile Number -->
                    <template v-slot:mobile_number="{ row: buyer }">
                        {{ buyer.mobile_number }}
                    </template>

                    <!-- Joining Date -->
                    <template v-slot:created_at="{ row: buyer }">
                        {{ new Date(buyer.created_at).toISOString().slice(0, 10) +" " + new Date(buyer.created_at).toISOString().slice(11, 19) }}
                    </template>

                    <!-- Status -->
                    <template v-slot:is_active="{ row: buyer }">
                        <div class="form-check form-check-solid form-switch">
                            <ChangeStatusButton v-if="checkPermission('can-edit-buyer')" :obj="buyer" confirmRoute="buyers.changeStatus" cancelRoute="buyers.index" />
                        </div>
                    </template>

                    <!-- Action -->
                    <template v-slot:actions="{ row: buyer }">
                        <div class="d-flex align-items-center justify-content-end">
                            <Link v-if="checkPermission('can-edit-buyer')" :href="route('buyers.edit', buyer.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                                <KTIcon icon-name="pencil" icon-class="fs-3 text-primary"/>
                            </Link>
                            <!-- Delete -->
                            <DeleteConfirmationButton v-if="checkPermission('can-delete-buyer')" iconClass="fs-1" :obj="buyer" confirmRoute="buyers.destroy" title="Buyer" :messageTitle="`${buyer.name} ?`"/>
                        </div>
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
import { checkPermission } from "@/Core/helpers/Permission";
import { getInitials } from "@/Core/helpers/Helper";
import DeleteConfirmationButton from "@/Components/Button/DeleteConfirmationButton.vue";
import ChangeStatusButton from "@/Components/Button/ChangeStatusButton.vue";
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    buyers: Object as () => IBuyer[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IBuyer {
    id: number;
    name: string;
    mobile_number: number;
    created_at: string;
    status: string;
    is_active: boolean;
}

const tableHeader = ref([
    {
        columnName: t('label.name'),
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.mobileNumber'),
        columnLabel: "mobile_number",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('buyer.label.joining_date'),
        columnLabel: "created_at",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.status'),
        columnLabel: "is_active",
        sortEnabled: true,
        columnWidth: 100,
    },
    {
        columnName: t('label.actions'),
        columnLabel: "actions",
        sortEnabled: true,
        columnWidth: 100,
    },
]);

const tableData = ref<IBuyer[]>([]);
const initBuyers = ref<IBuyer[]>([]);

onMounted(() => {
    if (props.buyers) {
        initBuyers.value = props.buyers.map((buyer) => ({
            id: buyer.id,
            name: buyer.name,
            mobile_number: buyer.mobile_number,
            created_at: buyer.created_at,
            status: buyer.status,
            is_active: buyer.is_active,
        }));
        tableData.value = initBuyers.value;
    }
});

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initBuyers.value];
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

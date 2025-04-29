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
                            :placeholder="$t('supplier.placeholder.searchSuppliers')"
                        />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base" >
                        <!--begin::Add Supplier-->
                        <Link v-if="checkPermission ('can-create-supplier')" :href="route('suppliers.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" /> {{ $t("supplier.title.add") }}
                        </Link>
                        <!--end::Add Supplier-->
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
                    <template v-slot:name="{ row: supplier }">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <Link v-if="checkPermission('can-view-details-supplier')" :href="route('suppliers.show',supplier.id)">
                                    <div class="symbol-label">
                                        <div class="symbol-label fs-3 bg-light-danger text-danger">
                                            {{ getInitials(supplier) }}
                                        </div>
                                    </div>
                                </Link>
                                <div v-else class="symbol-label">
                                    <div class="symbol-label fs-3 bg-light-danger text-danger">
                                        {{ getInitials(supplier) }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <Link v-if="checkPermission('can-view-details-user')" :href="route('suppliers.show', supplier.id)" class="text-gray-800 text-hover-primary mb-1">
                                    {{ supplier.name }}
                                </Link>
                                <span v-else>{{ supplier.name }}</span>
                            </div>
                        </div>
                    </template>

                    <!-- Mobile Number -->
                    <template v-slot:mobile_number="{ row: supplier }">
                        {{ supplier.mobile_number }}
                    </template>

                    <!-- Joining Date -->
                    <template v-slot:created_at="{ row: supplier }">
                        {{ new Date(supplier.created_at).toISOString().slice(0, 10) +" " + new Date(supplier.created_at).toISOString().slice(11, 19) }}
                    </template>

                    <!-- Status -->
                    <template v-slot:is_active="{ row: supplier }">
                        <div class="form-check form-check-solid form-switch">
                            <ChangeStatusButton v-if="checkPermission('can-edit-supplier')" :obj="supplier" confirmRoute="suppliers.changeStatus" cancelRoute="suppliers.index" />
                        </div>
                    </template>

                    <!-- Action -->
                    <template v-slot:actions="{ row: supplier }">
                        <div class="d-flex align-items-center justify-content-end">
                            <Link v-if="checkPermission('can-edit-supplier')" :href="route('suppliers.edit', supplier.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                                <KTIcon icon-name="pencil" icon-class="fs-3 text-primary"/>
                            </Link>
                            <!-- Delete -->
                            <DeleteConfirmationButton v-if="checkPermission('can-delete-supplier')" iconClass="fs-1" :obj="supplier" confirmRoute="suppliers.destroy" title="Supplier" :messageTitle="`${supplier.name} ?`"/>
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
    suppliers: Object as () => ISupplier[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISupplier {
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
        columnName: t('supplier.label.joiningDate'),
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

const tableData = ref<ISupplier[]>([]);
const initSuppliers = ref<ISupplier[]>([]);

onMounted(() => {
    if (props.suppliers) {
        initSuppliers.value = props.suppliers.map((supplier) => ({
            id: supplier.id,
            name: supplier.name,
            mobile_number: supplier.mobile_number,
            created_at: supplier.created_at,
            status: supplier.status,
            is_active: supplier.is_active,
        }));
        tableData.value = initSuppliers.value;
    }
});

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initSuppliers.value];
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

<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <ItemsHeader :activeTab="($page.props.type as string)"></ItemsHeader>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('item.placeholder.searchItems')" />
                    </div>
                    <!--end::Search-->
                </div>
                <div class="card-toolbar">
                    <!--begin::Add Item-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button v-if="checkPermission('can-create-item') && expenseItemType === 'expense'" type="button" class="btn btn btn-primary me-3 ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_add_expense_item">
                        <KTIcon icon-name="plus" icon-class="fs-2" />
                             {{ $t("item.buttonValue.addExpense") }}
                        </button>
                    </div>
                    <!--end::Add Item-->
                </div>
            </div>

            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="false" :items-per-page="Infinity"
                :pagination-enabled="false" :checkbox-enabled="false">
                    <!-- Name -->
                    <template v-slot:name="{ row: item }">
                        {{ item.name }}
                    </template>

                    <!-- slug -->
                    <template v-slot:slug="{ row: item }">
                        {{ item.slug }}
                    </template>

                    <!-- type -->
                    <template v-slot:type="{ row: item }">
                        {{ item.type }}
                    </template>

                    <!-- Status -->
                    <template v-slot:is_active="{ row: item }">
                        <div class="form-check form-check-solid form-switch d-flex justify-content-end">
                            <ChangeStatusButton v-if="checkPermission('can-edit-item')" :obj="item" confirmRoute="items.changeStatus" cancelRoute="items.index" />
                        </div>
                    </template>
                </Datatable>
            </div>
        </div>
        <AddExpenseModal></AddExpenseModal>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted,defineProps, watch, computed } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import arraySort from "array-sort";
import { Link, usePage, router } from '@inertiajs/vue3';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { MenuComponent } from "@/Assets/ts/components";
import { checkPermission } from "@/Core/helpers/Permission";
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import ItemsHeader from '@/Pages/Items/ItemsHeader.vue';
import AddExpenseModal from '@/Pages/Items/Modal/AddExpenseModal.vue';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    items: Object as() => IItems[] | undefined,
    type: String,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface IItems {
    id: number;
    name: string;
    slug: string;
    type: string;
    status: string;
    is_active: boolean;
}

interface Breadcrumb {
    url: string;
    title: string;
}

const tableHeader = ref([
    {
        columnName: t('label.name'),
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: t('label.slug'),
        columnLabel: "slug",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('item.label.type'),
        columnLabel: "type",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('label.status'),
        columnLabel: "is_active",
        sortEnabled: true,
        columnWidth: 100,
    },
]);

const tableData = ref < IItems[] > ([]);
const initItems = ref < IItems[] > ([]);

onMounted(() => {
    if (props.items) {
        initItems.value = props.items.map((item: any) => ({
            id: item.id,
            name: item.name,
            slug: item.slug,
            type: item.type,
            status: item.status,
            is_active: item.is_active
        }));
        tableData.value = [...initItems.value];
    }
});

const items = ref(usePage().props.items);
const type = ref(usePage().props.type);

watch(() => usePage().props.type, (newType: any) => {
    router.get(route('items.index', { type: newType }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            items.value = usePage().props.items;
        }
    } as any);
});

const expenseItemType = computed(() => usePage().props.type || 'sale');

const search = ref<string>("");

const searchData = () => {
    if (!search.value) {
        tableData.value = [...initItems.value];
    } else {
        tableData.value = initItems.value.filter(item => searchingFunc(item, search.value));
    }
    MenuComponent.reinitialization();
};

const searchingFunc = (obj: any, value: string): boolean => {
    for (let key in obj) {
        const fieldValue = obj[key];
        if (
            fieldValue &&
            typeof fieldValue === "string" &&
            fieldValue.toLowerCase().includes(value.toLowerCase())
        ) {
            return true;
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

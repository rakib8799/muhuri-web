<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6"/>
                        <input
                            type="text"
                            v-model="search"
                            @input="searchData()"
                            class="form-control form-control-solid w-250px ps-15"
                            :placeholder="$t('expense.placeholder.searchExpense')"
                        />
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-expense-table-toolbar="base">
                        <Link v-if="checkPermission('can-create-expense')" :href="route('expenses.create')"
                            class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2"/>
                            {{ $t("expense.buttonValue.addNewExpense") }}
                        </Link>
                    </div>
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
                    <template v-slot:expense_date="{ row: expense }">
                        {{ formatDate(expense.expense_date) }}
                    </template>

                    <template v-slot:item_name="{ row: expense }">
                        {{ expense.item_name }}
                    </template>

                    <template v-slot:amount="{ row: expense }">
                        {{ expense.amount }}
                    </template>

                    <template v-slot:created_at="{ row: expense }">
                        {{ formatDate(expense.created_at) }}
                    </template>

                    <template v-slot:note="{ row: expense }">
                        {{ expense.note }}
                    </template>

                    <template v-slot:action="{ row: expense }">
                        <Link :href="route('expenses.edit', expense)"
                            class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px"
                            data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                            <KTIcon icon-name="pencil" icon-class="fs-3 text-primary"/>
                        </Link>

                        <DeleteConfirmationButton v-if="checkPermission('can-delete-expense')" iconClass="fs-1" :obj="expense" confirmRoute="expenses.destroy" title="Delete Expense" :messageTitle="`${expense.item_name} ?`"/>
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref, defineProps, onMounted} from "vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type {Sort} from "@/Components/kt-datatable/table-partials/Models";
import {Link, router} from "@inertiajs/vue3";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import {checkPermission} from "@/Core/helpers/Permission";
import DeleteConfirmationButton from "@/Components/Button/DeleteConfirmationButton.vue";
import i18n from '@/Core/plugins/i18n';
import dayjs from "dayjs";
import arraySort from "array-sort";

const {t} = i18n.global;

const props = defineProps({
    expenses: Object as () => IExpense[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IExpense {
    id: number;
    expense_date: string;
    item_name: string;
    amount: number;
    created_at: string;
    note: string;
}

const tableHeader = ref([
    {
        columnName: t('label.date'),
        columnLabel: "expense_date",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: t('expense.label.itemName'),
        columnLabel: "item_name",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: t('expense.label.amount'),
        columnLabel: "amount",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('expense.label.createdAt'),
        columnLabel: "created_at",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: t('label.note'),
        columnLabel: "note",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: t('label.actions'),
        columnLabel: "action",
        sortEnabled: false,
        columnWidth: 100
    },
]);

const tableData = ref<IExpense[]>([]);
const initExpenses = ref<IExpense[]>([]);

onMounted(() => {
    if (props.expenses) {
        initExpenses.value = props.expenses.map((expense: any) => ({
            id: expense.id,
            expense_date: expense.expense_date,
            item_name: expense.item_name,
            amount: expense.amount,
            created_at: expense.created_at,
            note: expense.note
        }));
        tableData.value = initExpenses.value;
    }
});

const formatDate = (dateString: string) => {
    return dayjs(dateString).format("DD MMMM YYYY");
};

const search = ref<string>("");
const searchData = () => {
    tableData.value = [...initExpenses.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter((item) =>
            item.item_name.toLowerCase().includes(search.value.toLowerCase())
        );
    }
};

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse,
        });
    }
};

const deleteExpense = (id: number) => {
    if (confirm(t('expense.confirm.delete'))) {
        router.delete(route('expenses.destroy', id), {
            onSuccess: () => {
                tableData.value = tableData.value.filter(expense => expense.id !== id);
            }
        });
    }
};
</script>

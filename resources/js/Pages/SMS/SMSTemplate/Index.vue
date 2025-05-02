<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('sms.placeholder.searchSMSTemplate')" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add SMSTemplate-->
                        <Link v-if="checkPermission('can-create-sms-template')" :href="route('sms.templates.create')" class="btn btn-primary me-3">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t('sms.header.addSMSTemplate') }}
                        </Link>
                        <!--end::Add SMSTemplate -->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="false" :items-per-page="Infinity" :pagination-enabled="false" :checkbox-enabled="false">
                    <!-- SMSTemplate Name -->
                    <template v-slot:name="{ row: template }">
                        {{ template.name }}
                    </template>

                    <!-- Slug -->
                    <template v-slot:slug="{ row: template }">
                        {{ template.slug }}
                    </template>

                    <!-- SMS Template EN -->
                    <template v-slot:sms_template_en="{ row: template }">
                        {{ template.sms_template_en }}
                    </template>

                    <!-- SMS Template BN -->
                    <template v-slot:sms_template_bn="{ row: template }">
                        {{ template.sms_template_bn }}
                    </template>

                    <!-- Status -->
                    <template v-slot:is_active="{ row: template }">
                        <div class="form-check form-check-solid form-switch d-flex justify-content-start">
                            <ChangeStatusButton v-if="checkPermission('can-edit-sms-template')" :obj="template" confirmRoute="sms.templates.changeStatus" cancelRoute="sms.templates.index" :disabled="!template.is_editable" />
                        </div>
                    </template>

                    <!-- Actions -->
                    <template v-slot:actions="{ row: template }">
                        <div class="d-flex align-items-center justify-content-end">
                            <Link :class="{ 'disabled': !template.is_editable || !checkPermission('can-edit-sms-template') }" :href="route('sms.templates.edit', template.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                                <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                            </Link>
                            <!-- Delete -->
                            <DeleteConfirmationButton :disabled="!checkPermission('can-delete-sms-template') || !template.is_deletable" iconClass="fs-1" :obj="template" confirmRoute="sms.templates.destroy" :messageTitle="`${template.name} ?`"/>
                        </div>
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, defineProps } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import arraySort from "array-sort";
import { Link } from '@inertiajs/vue3';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { MenuComponent } from "@/Assets/ts/components";
import { checkPermission } from "@/Core/helpers/Permission";
import DeleteConfirmationButton from '@/Components/Button/DeleteConfirmationButton.vue';
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    smsTemplates: Object as () => ISMSTemplate[] | undefined,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ISMSTemplate{
    id: number;
    name: string;
    slug: string;
    sms_template_en: string;
    sms_template_bn: string;
    is_active: number;
    is_editable: number;
    is_deletable: number;
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
        columnWidth: 150
    },
    {
        columnName: t('sms.label.enTemplate'),
        columnLabel: "sms_template_en",
        sortEnabled: false,
        columnWidth: 200
    },
    {
        columnName: t('sms.label.bnTemplate'),
        columnLabel: "sms_template_bn",
        sortEnabled: false,
        columnWidth: 200
    },
    {
        columnName: t('label.status'),
        columnLabel: "is_active",
        sortEnabled: true,
        columnWidth: 100
    },
    {
        columnName: t('label.actions'),
        columnLabel: "actions",
        sortEnabled: false,
        columnWidth: 100
    },
]);

const tableData = ref<ISMSTemplate[]>([]);
const initTemplates = ref<ISMSTemplate[]>([]);
const search = ref<string>("");

onMounted(() => {
    if (props.smsTemplates) {
        initTemplates.value = props.smsTemplates.map(template => ({
            id: template.id,
            name: template.name,
            slug: template.slug,
            sms_template_en: template.sms_template_en,
            sms_template_bn: template.sms_template_bn,
            is_active: template.is_active,
            is_editable: template.is_editable,
            is_deletable: template.is_deletable
        }));
        tableData.value = initTemplates.value;
    }
});

const searchData = () => {
    tableData.value = [...initTemplates.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter(item => Object.values(item).some(val => String(val).toLowerCase().includes(search.value.toLowerCase())));
    }
    MenuComponent.reinitialization();
};

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, { reverse });
    }
};
</script>

<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
            <div class="content flex-row-fluid" id="kt_content">
                <div class="d-flex flex-column flex-lg-row">
                    <!-- User Information Section -->
                    <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column py-5">
                                    <div class="symbol symbol-50px symbol-circle mb-7">
                                        <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                        <div class="fs-3">{{ $t('fiscalYear.label.fiscalYear') }}</div>
                                    </div>
                                    <!--begin::Name-->
                                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ props.fiscalYear?.fiscal_year }}</a>
                                    <!--end::Name-->

                                    <!--begin::Position-->
                                    <div class="mb-9">
                                        <!-- <div v-for="role in props.user?.roles" :key="role.id"  class="badge badge-lg badge-light-primary d-inline me-3">{{ role.name }}</div> -->
                                    </div>
                                    <!--end::Position-->
                                </div>
                                <!--end::Summary-->
                                <div class="separator"></div>

                                <!--begin::Details content-->
                                <div id="kt_user_view_details" class="collapse show">
                                    <div class="pb-5 fs-6">
                                        <!--begin::Details item-->
                                        <div class="fw-bold mt-5">{{ $t('fiscalYear.label.startDate') }}</div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary">{{ props.fiscalYear?.start_date }}</a>
                                        </div>

                                        <div class="fw-bold mt-5">{{ $t('fiscalYear.label.endDate') }}</div>
                                        <div class="text-gray-600">
                                            <a href="#" class="text-gray-600 text-hover-primary">{{ props.fiscalYear?.end_date }}</a>
                                        </div>
                                        <!--begin::Details item-->
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                        </div>
                    </div>

                    <!-- Tab Sections -->
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <!-- Start: Fiscal Year Section -->
                        <div class="tab-content">
                            <div id="branch_details" class="py-4 tab-pane fade active show" role="tabpanel">
                                <div class="card card-flush mb-6 mb-xl-9">
                                    <div class="card-header mt-6">
                                        <div class="card-body">
                                            <h2 class="mb-8 d-block">{{ $t('fiscalYear.title.details') }}</h2>
                                            <div class="">
                                                <div class="d-flex justify-content-between mb-5">
                                                    <div class="fs-5">{{ $t('fiscalYear.label.status') }}</div>
                                                    <div class = "badge" :class="{'badge-success': props?.fiscalYear?.status === 'running'}">{{ props?.fiscalYear?.status }}</div>
                                                </div>

                                                <div class="d-flex justify-content-between mb-5">
                                                    <div class="fs-5">{{ $t('fiscalYear.label.startDate') }}</div>
                                                    <div class="fs-5">
                                                        <FiscalYearConfirmationButton v-if="checkPermission('can-edit-fiscal-year') && props?.fiscalYear?.status==='pending'" iconName = "add-notepad" :obj="props?.fiscalYear" confirmRoute="fiscal-years.startFiscalYear" :title="$t('tooltip.title.startYear')" :messageTitle="`${$t('confirmation.fiscalYear.start', { year: props?.fiscalYear?.fiscal_year })}`" />
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="fs-5">{{ $t('fiscalYear.label.endDate') }}</div>
                                                    <div class="fs-5">
                                                        <FiscalYearConfirmationButton v-if="checkPermission('can-edit-fiscal-year') && props?.fiscalYear?.status==='running'" iconName = "timer" :obj="props?.fiscalYear" confirmRoute="fiscal-years.closeFiscalYear" :title="$t('tooltip.title.endYear')" :messageTitle="$t('confirmation.fiscalYear.end', { year: props?.fiscalYear?.fiscal_year })"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: Overview Section -->
                        <!-- End: Security Section -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch, onMounted, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";
import i18n from '@/Core/plugins/i18n';
import ChangeStatusButton from "@/Components/Button/ChangeStatusButton.vue";
import FiscalYearConfirmationButton from '@/Components/Button/FiscalYearConfirmationButton.vue';

const { t } = i18n.global;

const props = defineProps({
    fiscalYear: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});
console.log(props.fiscalYear);
interface Breadcrumb {
    url: string;
    title: string;
}
</script>


<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeHeader activeLink="DepartureInfo" :id="`${props.employee?.id}`" :employee="props?.employee" :departureReasons="props?.departureReasons"></EmployeeHeader>

        <!--begin::Departure Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.departureInfo.title') }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Departure Reason -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.departureReason') }}</label>
                    <div class="col-lg-8">
                        <span v-for="departureReason in props?.departureReasons" :key="departureReason.id">
                            <span class="fw-bold fs-6 text-gray-900" v-if="departureReason?.id == employee?.departure_reason_id">{{ departureReason?.name }}</span>
                        </span>
                    </div>
                </div>

                <!-- Departure Description -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.departureDescription') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.employee?.departure_description }}</span>
                    </div>
                </div>

                <!-- Departure Date -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.departureDate') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.employee?.departure_date }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Basic Info-->
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmployeeHeader from '@/Pages/Employee/EmployeeHeader.vue';

const props = defineProps({
    employee: Object,
    departureReasons: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}
</script>

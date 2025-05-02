<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <ConfigurationHeader activeLink="Details" :configuration="props?.configuration"></ConfigurationHeader>

        <!--begin::Basic Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('configuration.header.show.basicInformation') }}</h3>
                </div>
                <!--end::Card title-->
                <!-- Edit -->
                <Link v-if="checkPermission('can-edit-configuration')" :href="route('configurations.basicInfo')" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Company Name (English) -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.companyName') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.name }}</span>
                    </div>
                </div>

                <!-- Company Name (Bangla) -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.companyNameBangla') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.name_bn }}</span>
                    </div>
                </div>

                <!-- Category Name -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.categoryName') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.category }}</span>
                    </div>
                </div>

                <!-- Trade License Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.tradeLicenseNumber') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.trade_license_number }}</span>
                    </div>
                </div>

                <!-- Country -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        {{ $t('configuration.label.country') }}
                        <i class="fas fa-exclamation-circle ms-1 fs-7" v-tooltip :title="$t('configuration.header.title.tooltip')"></i>
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.configuration?.country_name}}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Basic Info-->

        <!--begin::Contact Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('configuration.header.show.contactInformation') }}</h3>
                </div>
                <!--end::Card title-->
                <Link v-if="checkPermission('can-edit-configuration')" :href="route('configurations.contactInfo')" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Email -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.email') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.email }}</span>
                    </div>
                </div>

                <!-- Mobile Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.mobileNumber') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.mobile_number }}</span>
                    </div>
                </div>

                <!-- Additional Mobile Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.additionalMobileNumber') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.additional_mobile_number }}</span>
                    </div>
                </div>

                <!-- Address -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.address') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.address }}</span>
                    </div>
                </div>

                <!-- Admin Email -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.adminEmail') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.support_email }}</span>
                    </div>
                </div>

                <!-- Admin Contact Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.adminContactNumber') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.support_telephone }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Additional Info-->
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfigurationHeader from '@/Pages/Configuration/ConfigurationHeader.vue';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Link } from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";

const props = defineProps({
    configuration: Object,
    languages: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const capitalizeString = (str: string) => str?.charAt(0).toUpperCase() + str?.slice(1);
const capitalizeArrayOfStrings = (str: string[]) => str?.map((str: string) => str?.charAt(0).toUpperCase() + str?.slice(1));
</script>

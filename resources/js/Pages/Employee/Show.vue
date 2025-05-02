<template>

    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeHeader activeLink="Details" :id="`${props.employee?.id}`" :employee="props?.employee" :departureReasons="props?.departureReasons"></EmployeeHeader>

        <!--begin::Basic Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.basicInfo.description') }}</h3>
                </div>
                <!--end::Card title-->
                <!-- Edit -->
                <Link v-if="checkPermission('can-edit-employee') && !props.employee?.is_departed" :href="route('employees.basicInfo', props?.employee?.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" title="Edit">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Full Name -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.fullName') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.employee?.first_name }} {{ props.employee?.last_name }}</span>
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.email') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.employee?.email }}</span>
                    </div>
                </div>

                <!-- Staff ID -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.staffId') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.employee?.staff_id }}</span>
                    </div>
                </div>

                <!-- Social Security Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.socialSecurityNumber') }}</label>
                    <div class="col-lg-8">
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-900">{{ props.employee?.social_security_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Mobile Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('label.mobileNumber') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.employee?.mobile_number }}</span>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.dateOfBirth') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.dob }}</span>
                    </div>
                </div>

                <!-- Blood Group -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.bloodGroup') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.blood_group }}</span>
                    </div>
                </div>

                <!-- Gender -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.gender') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ capitalizeString(props.employee?.gender)? capitalizeString(props.employee?.gender) : '' }}</span>
                    </div>
                </div>

                <!-- Marital Status -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.maritalStatus') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ capitalizeString(props.employee?.marital_status)? capitalizeString(props.employee?.marital_status) : '' }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Basic Info-->

        <!--begin::Additional Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.additionalInfo.description') }}</h3>
                </div>
                <!--end::Card title-->
                <Link v-if="checkPermission('can-edit-employee') && !props.employee?.is_departed" :href="route('employees.additionalInfo', props.employee?.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" title="Edit">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- User Name -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.userName') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.user_id == null ? '' : props.employee?.user.name }}</span>
                    </div>
                </div>

                <!-- Permanent Address -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.permanentAddress') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.permanent_address }}  </span>
                    </div>
                </div>

                <!-- Present Address -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.presentAddress') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900" v-if="props.employee?.address_line_one || props.employee?.address_line_two || props.employee?.floor || props.employee?.city || props.employee?.state || props.employee?.zip_code">Address-1: {{ props.employee?.address_line_one }}, Address-2: {{ props.employee?.address_line_two }}, Floor: {{ props.employee?.floor }}, City: {{ props.employee?.city }}, State: {{ props.employee?.state }}, ZIP: {{ props.employee?.zip_code }}</span>
                    </div>
                </div>

                <!-- Country -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        {{ $t('employee.label.country') }}
                        <i class="fas fa-exclamation-circle ms-1 fs-7" v-tooltip :title="$t('configuration.header.title.tooltip')"></i>
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.country_id == null ? '' : props.employee?.country.name }}</span>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.emergencyContact.title') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900" v-if="props.employee?.emergency_contact_name || props.employee?.emergency_contact_relation || props.employee?.emergency_contact_number">Name: {{ props.employee?.emergency_contact_name }} <br> Relation: {{ props.employee?.emergency_contact_relation }} <br> Number: {{ props.employee?.emergency_contact_number }}</span>
                    </div>
                </div>

                <!-- Status -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.status') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="badge" :class="{ 'badge-success': props.employee?.is_active, 'badge-danger': !props.employee?.is_active }"> {{ props.employee?.is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Additional Info-->

        <!--begin::Work Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.workInfo.description') }}</h3>
                </div>
                <!--end::Card title-->
                <Link v-if="checkPermission('can-edit-employee') && !props.employee?.is_departed" :href="route('employees.workInfo', props.employee?.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" title="Edit">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Job Title -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.jobTitle') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.employee?.job_title }}</span>
                    </div>
                </div>

                <!-- Job Position -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.jobPosition') }}</label>
                    <div class="col-lg-8">
                        <span v-for="(employeeJobPosition, index) in props.employee?.job_positions" :key="employeeJobPosition.id">
                            <span v-for="jobPosition in props?.jobPositions" :key="jobPosition.id">
                                <span class="fw-bold fs-6 text-gray-900" v-if="jobPosition.id == employeeJobPosition">{{ (index == employee?.job_positions?.length-1) ? jobPosition.name :  `${jobPosition.name}, `}}</span>
                            </span>
                        </span>
                    </div>
                </div>

                <!-- Joining Date -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.joiningDate') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.joining_date }}</span>
                    </div>
                </div>

                <!-- Salary -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.salary') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.salary }}</span>
                    </div>
                </div>

                <!-- Hourly Rate -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.hourlyRate') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.hourly_rate }}</span>
                    </div>
                </div>

                <!-- Hour Limit Weekly -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.hourLimitWeekly') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.hour_limit_weekly }}</span>
                    </div>
                </div>

                <!-- Over Time Rate -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.overtimeRate') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.over_time_rate }}</span>
                    </div>
                </div>

                <!-- Over Time Limit -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.overtimeLimit') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.over_time_limit }}</span>
                    </div>
                </div>

                <!-- Linkedin Profile -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.linkedinProfile') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.linkedin_profile }}</span>
                    </div>
                </div>

                <!-- e-Tin Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.eTinNumber') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.etin_number }}</span>
                    </div>
                </div>

                <!-- Passport Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.passportNumber') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.employee?.passport_number }}</span>
                    </div>
                </div>

                <!-- Visa Number with Expiration Date -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.visaInformation') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900" v-if="props.employee?.visa_number || props.employee?.visa_expire_date">Visa Number: {{ props.employee?.visa_number }} <br> Expiration Date: {{ props.employee?.visa_expire_date }}</span>
                    </div>
                </div>

                <!-- Work Permit Number with Expiration Date -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.workPermitInformation') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900" v-if="props.employee?.work_permit_number && props.employee?.work_permit_expiration_date">Work Permit Number: {{ props.employee?.work_permit_number }} <br> Expiration Date: {{ props.employee?.work_permit_expiration_date }}</span>
                    </div>
                </div>

                <!-- NFC Card -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.nfcCard') }}</label>
                    <div class="col-lg-8">
                        <span class="badge" :class="{ 'badge-success': props.employee?.is_nfc_card_issued, 'badge-danger': !props.employee?.is_nfc_card_issued }"> {{ props.employee?.is_nfc_card_issued ? 'Yes' : 'No' }}</span>
                    </div>
                </div>

                <!-- Geo Fencing -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.geoFencing') }}</label>
                    <div class="col-lg-8">
                        <span class="badge" :class="{ 'badge-success': props.employee?.is_geo_fencing_enabled, 'badge-danger': !props.employee?.is_geo_fencing_enabled }"> {{ props.employee?.is_geo_fencing_enabled ? 'Yes' : 'No' }}</span>
                    </div>
                </div>

                <!-- Photo Taking -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('employee.label.photoTaking') }}</label>
                    <div class="col-lg-8">
                        <span class="badge" :class="{ 'badge-success': props.employee?.is_photo_taking_enabled, 'badge-danger': !props.employee?.is_photo_taking_enabled }"> {{ props.employee?.is_photo_taking_enabled ? 'Yes' : 'No' }}</span>
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
import EmployeeHeader from '@/Pages/Employee/EmployeeHeader.vue';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Link } from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";

const props = defineProps({
    employee: Object,
    jobPositions: Object,
    departureReasons: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const capitalizeString = (str: string) => str?.charAt(0).toUpperCase() + str?.slice(1);
</script>

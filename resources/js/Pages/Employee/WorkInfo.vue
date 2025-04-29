<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeHeader activeLink="WorkInfo" :id="`${props.employee?.id}`" :employee="props?.employee" :departureReasons="props?.departureReasons"></EmployeeHeader>

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.workInfo.edit') }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <VForm @submit="submit()" class="form">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!-- Job Title and Joining Date-->
                    <div class="row mb-5 g-4">
                        <!-- Job Position -->
                        <div class="col-12 mb-2 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.jobPosition') }}</label>
                            <Multiselect
                                :placeholder="$t('employee.placeholder.jobPosition')"
                                mode="tags"
                                v-model="formData.job_positions"
                                :searchable="true"
                                :options="jobPositions"
                                label="text"
                                trackBy="text"
                            />
                            <ErrorMessage :errorMessage="formData.errors.job_positions" />
                        </div>

                        <!-- Joining Date -->
                        <div class="col-12 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.joiningDate') }}</label>
                            <Field type="date" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.joiningDate')" name="joining_date" v-model="formData.joining_date"/>
                            <ErrorMessage :errorMessage="formData.errors.joining_date" />
                        </div>

                        <!-- Job Title -->
                        <div class="col-12 fv-row">
                            <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.jobTitle') }}</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.jobTitle')" name="job_title" v-model="formData.job_title"/>
                            <ErrorMessage :errorMessage="formData.errors.job_title"/>
                        </div>
                    </div>

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Salary Information -->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('employee.label.salary') }}</h3>

                        <div class="row g-4">
                            <!-- Salary -->
                            <div class="col-md-12 mb-2">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.salary') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.salary')" name="salary" v-model="formData.salary"/>
                                <ErrorMessage :errorMessage="formData.errors.salary" />
                            </div>

                            <!-- Hourly Rate -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.hourlyRate') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.hourlyRate')" name="hourly_rate" v-model="formData.hourly_rate"/>
                                <ErrorMessage :errorMessage="formData.errors.hourly_rate" />
                            </div>

                            <!-- Hour Limit Weekly -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.hourLimitWeekly') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.hourLimitWeekly')" name="hour_limit_weekly" v-model="formData.hour_limit_weekly"/>
                                <ErrorMessage :errorMessage="formData.errors.hour_limit_weekly" />
                            </div>

                            <!-- Over Time Rate -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.overtimeRate') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.overtimeRate')" name="over_time_rate" v-model="formData.over_time_rate"/>
                                <ErrorMessage :errorMessage="formData.errors.over_time_rate" />
                            </div>

                            <!-- Over Time Limit -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.overtimeLimit') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.overtimeLimit')" name="over_time_limit" v-model="formData.over_time_limit"/>
                                <ErrorMessage :errorMessage="formData.errors.over_time_limit" />
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Passport, Visa and Work Permit-->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('employee.label.workPermit') }}</h3>

                        <div class="row g-4">
                            <!-- Passport Number -->
                            <div class="col-md-12 mb-2">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.passportNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.passportNumber')" name="passport_number" v-model="formData.passport_number"/>
                                <ErrorMessage :errorMessage="formData.errors.passport_number" />
                            </div>

                            <!-- Visa Number -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.visaNumber') }}</label>
                                <Field type="number" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.visaNumber')" name="visa_number" v-model="formData.visa_number"/>
                                <ErrorMessage :errorMessage="formData.errors.visa_number" />
                            </div>

                            <!-- Visa Expire Date -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.visaExpiryDate') }}</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.visaExpiryDate')" name="visa_expire_date" v-model="formData.visa_expire_date"/>
                                <ErrorMessage :errorMessage="formData.errors.visa_expire_date" />
                            </div>

                            <!-- Work Permit Number -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.workPermitNumber') }}</label>
                                <Field type="number" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.workPermitNumber')" name="work_permit_number" v-model="formData.work_permit_number"/>
                                <ErrorMessage :errorMessage="formData.errors.work_permit_number" />
                            </div>

                            <!-- Work Permit Expiration Date -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.workPermitExpirationDate') }}</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.workPermitExpirationDate')" name="work_permit_expiration_date" v-model="formData.work_permit_expiration_date"/>
                                <ErrorMessage :errorMessage="formData.errors.work_permit_expiration_date" />
                            </div>
                        </div>
                    </div>

                    <!-- Departure Date and Departure Reason-->

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Linkedin Profile, e-Tin Number, NFC, GEO and PHOTO-->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('employee.label.others') }}</h3>

                        <div class="row g-4">
                            <!-- Linkedin Profile -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.linkedinProfile') }}</label>
                                <Field type="url" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.linkedinProfile')"  name="linkedin_profile" v-model="formData.linkedin_profile"/>
                                <ErrorMessage :errorMessage="formData.errors.linkedin_profile" />
                            </div>

                            <!-- e-Tin Number -->
                            <div class="col-md-6 mb-2 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.eTinNumber') }}</label>
                                <Field type="number" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.eTinNumber')" name="etin_number" v-model="formData.etin_number"/>
                                <ErrorMessage :errorMessage="formData.errors.etin_number" />
                            </div>

                            <!-- NFC Card -->
                            <div class="col-md-4 mb-2 fv-row">
                                <input class="form-check-input me-3" name="is_nfc_card_issued" type="checkbox" v-model="formData.is_nfc_card_issued" :checked="formData.is_nfc_card_issued"/>
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.nfcCard') }}</label>
                            </div>

                            <!-- Geo Fencing -->
                            <div class="col-md-4 mb-2 fv-row">
                                <input class="form-check-input me-3" name="is_geo_fencing_enabled" type="checkbox" v-model="formData.is_geo_fencing_enabled" :checked="formData.is_geo_fencing_enabled"/>
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.geoFencing') }}</label>
                            </div>

                            <!-- Photo -->
                            <div class="col-md-4 mb-2 fv-row">
                                <input class="form-check-input me-3" name="is_photo_taking_enabled" type="checkbox" v-model="formData.is_photo_taking_enabled" :checked="formData.is_photo_taking_enabled"/>
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.photoTaking') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!-- Submit Button-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <SubmitButton :id="props.employee?.id" />
                </div>
            </VForm>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmployeeHeader from '@/Pages/Employee/EmployeeHeader.vue';
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { Field, Form as VForm } from "vee-validate";
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect';
import { ref } from 'vue';

const props = defineProps({
    jobPositions: Object,
    employee: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
    departureReasons: Object
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    job_title: props.employee?.job_title || '',
    job_positions: props.employee?.job_positions || [],
    salary: props.employee ?.salary || '',
    hourly_rate: props.employee ?.hourly_rate || '',
    hour_limit_weekly: props.employee ?.hour_limit_weekly || '',
    over_time_rate: props.employee ?.over_time_rate || '',
    over_time_limit: props.employee ?.over_time_limit || '',
    joining_date: props.employee ?.joining_date || '',
    linkedin_profile: props.employee?.linkedin_profile || '',
    etin_number: props.employee?.etin_number || '',
    passport_number: props.employee?.passport_number || '',
    visa_number: props.employee?.visa_number || '',
    visa_expire_date: props.employee?.visa_expire_date || '',
    work_permit_number: props.employee?.work_permit_number || '',
    work_permit_expiration_date: props.employee?.work_permit_expiration_date || '',
    departure_date: props.employee?.departure_date || '',
    departure_reason: props.employee?.departure_reason || '',
    is_nfc_card_issued: props.employee?.is_nfc_card_issued ? true : false,
    is_geo_fencing_enabled: props.employee?.is_geo_fencing_enabled ? true : false,
    is_photo_taking_enabled: props.employee?.is_photo_taking_enabled ? true : false
});

// assign all the roles from props to allRoles variable.
const jobPositions = ref<Array<any>>([]);
if (Array.isArray(props.jobPositions) && props.jobPositions.length > 0) {
    jobPositions.value = props.jobPositions.map(jobPosition => ({value: jobPosition.id, text: jobPosition.name}));
}

const submit = () => {
    formData.patch(route('employees.updateWorkInfo', props.employee?.id), {
        preserveScroll: true,
    });
};
</script>

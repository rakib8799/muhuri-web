<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeHeader activeLink="BasicInfo" :id="`${props.employee?.id}`" :employee="props?.employee" :departureReasons="props?.departureReasons"></EmployeeHeader>

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.basicInfo.edit') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- First Name and Last Name-->
                        <div class="row mb-5 g-4">
                            <!-- First Name -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.firstName') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.firstName')" name="first_name" v-model="formData.first_name"/>
                                <ErrorMessage :errorMessage="formData.errors.first_name" />
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.lastName') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.lastName')" name="last_name" v-model="formData.last_name"/>
                                <ErrorMessage :errorMessage="formData.errors.last_name" />
                            </div>
                        </div>

                        <!-- Email and Staff ID-->
                        <div class="row mb-5 g-4">
                            <!-- Email -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('label.email') }}</label>
                                <Field type="email" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.email')" name="email" v-model="formData.email"/>
                                <ErrorMessage :errorMessage="formData.errors.email" />
                            </div>

                            <!-- Staff ID -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.staffId') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.staffId')" name="staff_id" v-model="formData.staff_id"/>
                                <ErrorMessage :errorMessage="formData.errors.staff_id" />
                            </div>
                        </div>

                        <!-- Social Security Number and Mobile Number-->
                        <div class="row mb-5 g-4">
                            <!-- Social Security Number -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.socialSecurityNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.socialSecurityNumber')" name="social_security_number" v-model="formData.social_security_number"/>
                                <ErrorMessage :errorMessage="formData.errors.social_security_number" />
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.mobileNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.mobileNumber')" name="mobile_number" v-model="formData.mobile_number"/>
                                <ErrorMessage :errorMessage="formData.errors.mobile_number" />
                            </div>
                        </div>

                        <!-- Date of Birth and Blood Group-->
                        <div class="row mb-5 g-4">
                            <!-- Date of Birth -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.dateOfBirth') }}</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.dateOfBirth')" name="dob" v-model="formData.dob"/>
                                <ErrorMessage :errorMessage="formData.errors.dob" />
                            </div>

                            <!-- Blood Group -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.bloodGroup') }}</label>
                                <Multiselect
                                    :placeholder="$t('employee.placeholder.bloodGroup')"
                                    v-model="formData.blood_group"
                                    :searchable="true"
                                    label="text"
                                    trackBy="text"
                                    :options="bloodGroups"
                                />
                            </div>
                        </div>

                        <!-- Gender and Marital Status-->
                        <div class="row mb-5 g-4">
                            <!-- Gender -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.gender') }}</label>
                                <Multiselect
                                    :placeholder="$t('employee.placeholder.gender')"
                                    v-model="formData.gender"
                                    :searchable="true"
                                    label="text"
                                    trackBy="text"
                                    :options="genders"
                                />
                            </div>

                            <!-- Marital Status -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.maritalStatus') }}</label>
                                <Multiselect
                                    :placeholder="$t('employee.placeholder.maritalStatus')"
                                    v-model="formData.marital_status"
                                    :searchable="true"
                                    label="text"
                                    trackBy="text"
                                    :options="maritalStatus"
                                />
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.employee?.id" />
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import Multiselect from '@vueform/multiselect';
import EmployeeHeader from '@/Pages/Employee/EmployeeHeader.vue';

const props = defineProps({
    employee: Object,
    bloodGroups: Object,
    genders: Object,
    maritalStatus: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
    departureReasons: Object
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    first_name: props.employee?.first_name || '',
    last_name: props.employee?.last_name || '',
    email: props.employee?.email || '',
    staff_id: props.employee?.staff_id || '',
    social_security_number: props.employee?.social_security_number || '',
    mobile_number: props.employee?.mobile_number || '',
    dob: props.employee?.dob || '',
    blood_group: props.employee?.blood_group || '',
    gender: props.employee?.gender || '',
    marital_status: props.employee?.marital_status || ''
});

const submit = () => {
    formData.patch(route('employees.updateBasicInfo', props.employee?.id), {
        preserveScroll: true,
    });
};
</script>


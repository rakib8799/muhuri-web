<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.basicInfo.add') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('employee.label.firstName') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.firstName')" name="name" v-model="formData.name"/>
                                <ErrorMessage :errorMessage="formData.errors.name" />
                            </div>
                        </div>

                        <!-- NID and Mobile Number-->
                        <div class="row mb-5 g-4">
                            <!-- NID -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.socialSecurityNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.socialSecurityNumber')" name="nid" v-model="formData.nid"/>
                                <ErrorMessage :errorMessage="formData.errors.nid" />
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('label.mobileNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.mobileNumber')" name="mobile_number" v-model="formData.mobile_number"/>
                                <ErrorMessage :errorMessage="formData.errors.mobile_number" />
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="row mb-5 g-4">
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
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton />
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

const props = defineProps({
    bloodGroups: Object,
    genders: Object,
    maritalStatus: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    name: '',
    nid: '',
    mobile_number: '',
    gender: ''
});

const submit = () => {
    // for creating employee
    formData.post(route('employees.store'), {
        preserveScroll: true,
    });
};
</script>

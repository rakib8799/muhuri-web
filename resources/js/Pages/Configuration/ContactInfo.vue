<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <ConfigurationHeader activeLink="ContactInfo" :configuration="props?.configuration"></ConfigurationHeader>

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">

            <VForm @submit="submit()" class="form">
                <!--begin::Card header-->
                <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">{{ $t('configuration.header.title.contactInformation') }}</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div>
                        <!-- Email -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.email') }}</label>
                            <Field type="email" class="form-control form-control-lg form-control-solid" :placeholder="$t('configuration.placeholder.email')" name="email" v-model="formData.email"/>
                            <ErrorMessage :errorMessage="formData.errors.email"/>
                        </div>

                        <!-- Mobile Number -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.mobileNumber') }}</label>
                            <Field type="tel" class="form-control form-control-lg form-control-solid" :placeholder="$t('configuration.placeholder.mobileNumber')" name="mobile_number"
                                    v-model="formData.mobile_number"/>
                            <ErrorMessage :errorMessage="formData.errors.mobile_number"/>
                        </div>

                        <!-- Additional Mobile Number -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.additionalMobileNumber') }}</label>
                            <Field type="tel" class="form-control form-control-lg form-control-solid" :placeholder="$t('configuration.placeholder.additionalMobileNumber')" name="additional_mobile_number"
                                    v-model="formData.additional_mobile_number"/>
                            <ErrorMessage :errorMessage="formData.errors.additional_mobile_number"/>
                        </div>

                        <!-- Address -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.address') }}</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('configuration.placeholder.address')" name="address" v-model="formData.address"/>
                            <ErrorMessage :errorMessage="formData.errors.address"/>
                        </div>
                    </div>

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Admin Contact -->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('configuration.label.adminContact') }}</h3>
                        <!-- Admin Email -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.email') }}</label>
                            <Field type="email" class="form-control form-control-lg form-control-solid"
                                :placeholder="$t('configuration.placeholder.adminEmail')" name="support_email"
                                v-model="formData.support_email" readonly />
                            <ErrorMessage :errorMessage="formData.errors.support_email"/>
                        </div>

                        <!-- Admin Contact Number -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.contactNumber') }}</label>
                            <Field type="tel" class="form-control form-control-lg form-control-solid"
                                :placeholder="$t('configuration.placeholder.adminContactNumber')" name="support_telephone"
                                v-model="formData.support_telephone" readonly />
                            <ErrorMessage :errorMessage="formData.errors.support_telephone"/>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!-- Submit Button-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <SubmitButton :buttonValue="$t('buttonValue.update')" />
                </div>
            </VForm>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfigurationHeader from '@/Pages/Configuration/ConfigurationHeader.vue';
import {Field, Form as VForm} from "vee-validate";
import {useForm} from '@inertiajs/vue3';
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";

const props = defineProps({
    configuration: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    email: props.configuration?.email || '',
    mobile_number: props.configuration?.mobile_number || '',
    additional_mobile_number: props.configuration?.additional_mobile_number || '',
    address: props.configuration?.address || '',
    support_email: props.configuration?.support_email,
    support_telephone: props.configuration?.support_telephone,
});

const submit = () => {
    formData.patch(route('configurations.updateContactInfo', props.configuration?.id), {
        preserveScroll: true,
    });
};
</script>

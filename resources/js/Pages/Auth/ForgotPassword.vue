<template>
    <Head :title="`${props?.pageTitle}`" />
    <!--begin::Wrapper-->
    <div class="forgot-password-page p-10 d-flex justify-content-center align-items-center" style="background-image: url('/media/auth/bg10.jpeg');">
        <!--begin::Form-->
        <VForm @submit="onSubmitForgotPassword">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <div v-if="props?.status" class="mb-4 fs-3 text-success">
                    {{ props.status }}
                </div>
                <!--begin:Logo-->
                <div class="mb-11">
                    <Link href="/" rel="noopener" target="_blank">
                        <img alt="Logo" :src="getAssetPath('media/logos/logo.png')" class="h-60px h-lg-75px">
                    </Link>
                </div>
                <!--end:Logo-->
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-2x">{{ $t('auth.forgotPassword.title') }} ?</h1>
                <!--end::Title-->

                <!--begin::Link-->
                <div class="text-gray-500 fw-semibold fs-3">
                    {{ $t('auth.forgotPassword.description') }}
                </div>
                <!--end::Link-->
            </div>
            <!--end::Heading-->

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <label class="form-label fw-bold text-gray-900 fs-6">{{ $t('label.mobileNumber') }}</label>
                    <Field
                    class="form-control form-control-solid"
                    type="text"
                    :placeholder="$t('placeholder.mobileNumber')"
                    name="mobile_number"
                    v-model="formData.mobile_number"
                />
                <div class="fv-plugins-message-container">
                    <div class="fv-help-block">
                        <ErrorMessage name="mobile_number" :errorMessage="formData.errors.mobile_number"/>
                    </div>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="flex items-center justify-end mt-4">
                <SubmitButton :buttonValue="$t('buttonValue.forgotPassword')" class="btn btn-lg w-100 mb-5"/>
            </div>
            <!--end::Actions-->
        </VForm>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</template>

<script lang="ts" setup>
import { Field, Form as VForm } from "vee-validate";
import {Link, useForm} from "@inertiajs/vue3";
import {getAssetPath} from "@/Core/helpers/Assets";
import { Head } from '@inertiajs/vue3';
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";

const props = defineProps({
    status: String,
    pageTitle: String
});

const formData = useForm({
    mobile_number: '',
});

const onSubmitForgotPassword = async () => {
    formData.post(route('password.mobileNumber'), {
        onFinish: () => {
            formData.reset('mobile_number');
        },
    });
};
</script>

<style>
#app, .forgot-password-page {
    height: 100%;
}
</style>

<template>
    <Head :title="`${props?.pageTitle}`" />
    <!--begin::Wrapper-->
    <div class="reset-password-page p-10 d-flex justify-content-center align-items-center" style="background-image: url('/media/auth/bg10.jpeg');">
        <!--begin::Form-->
        <VForm @submit="onSubmitResetPassword">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin:Logo-->
                <div class="mb-11">
                    <Link href="/" rel="noopener" target="_blank">
                        <img alt="Logo" :src="getAssetPath('media/logos/logo.png')" class="h-60px h-lg-75px">
                    </Link>
                </div>
                <!--end:Logo-->
                <!--begin::Title-->
                <h1 class="text-gray-900 mb-3 fs-2x">{{ $t('auth.resetPassword.title') }} ?</h1>
                <!--end::Title-->

                <!--begin::Link-->
                <div class="text-gray-500 fw-semibold fs-3">
                    {{ $t('auth.resetPassword.description') }}
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
                        readonly
                />
                <div class="fv-plugins-message-container">
                    <div class="fv-help-block">
                        <ErrorMessage name="mobile_number" :errorMessage="formData.errors.mobile_number"/>
                    </div>
                </div>
            </div>

            <div class="fv-row mt-4">
                <label class="form-label fw-bold text-gray-900 fs-6">{{ $t('auth.resetPassword.label.newPassword') }}</label>
                <Field
                    type="password"
                    class="form-control form-control-solid required"
                    :placeholder="$t('auth.resetPassword.placeholder.newPassword')"
                    name="password"
                    autocomplete="off"
                    v-model="formData.password"
                />

                <ErrorMessage name="password" class="mt-2" :errorMessage="formData.errors.password" />
            </div>

            <div class="fv-row mt-4">
                <label class="form-label fw-bold text-gray-900 fs-6">{{ $t('auth.resetPassword.label.confirmNewPassword') }}</label>
                <Field
                    type="password"
                    class="form-control form-control-solid required"
                    :placeholder="$t('auth.resetPassword.placeholder.confirmNewPassword')"
                    name="password_confirmation"
                    v-model="formData.password_confirmation"
                />

                <ErrorMessage name="password_confirmation" class="mt-2" :errorMessage="formData.errors.password_confirmation" />
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="flex items-center justify-end mt-4">
                <SubmitButton :buttonValue="$t('buttonValue.resetPassword')" class="btn btn-lg w-100 mb-5"/>
            </div>
            <!--end::Actions-->
        </VForm>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</template>

<script lang="ts" setup>
import { Field, Form as VForm } from "vee-validate";
import {getAssetPath} from "@/Core/helpers/Assets";
import { Head, Link, useForm } from '@inertiajs/vue3';
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";

const props = defineProps({
    mobile_number: String,
    token: String,
    pageTitle: String
});

const formData = useForm({
    token: props.token,
    mobile_number: props.mobile_number,
    password: '',
    password_confirmation: '',
});

const onSubmitResetPassword = async () => {
    formData.post(route('password.store.mobileNumber'), {
        onFinish: () => {
            formData.reset('password', 'password_confirmation');
        },
    });
};
</script>

<style>
#app, .reset-password-page {
    height: 100%;
}
</style>

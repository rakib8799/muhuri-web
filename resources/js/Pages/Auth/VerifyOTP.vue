<template>
    <Head :title="$t('auth.verifySMS.title')" />
    <!--begin::Wrapper-->
    <div class="verify-sms-page p-10 row d-flex justify-content-center align-items-center" style="background-image: url('/media/auth/bg10.jpeg');">
        <div class="col-md-6">
            <!--begin:Logo-->
            <div class="mb-11 text-center">
                <Link href="/" rel="noopener" target="_blank">
                    <img alt="Logo" :src="getAssetPath('media/logos/logo.png')" class="h-60px h-lg-75px">
                </Link>
            </div>
            <!--end:Logo-->

            <div class="row mb-4 text-gray-600">
                {{ $t('auth.verifySMS.message') }}
            </div>

            <div class="row mb-4 font-medium text-success" v-if="props?.status === 'sent'">
                {{ $t('auth.verifySMS.linkSent') }}
            </div>

            <div class="row mb-4 font-medium text-danger" v-else>
                {{ props?.status }}
            </div>

            <!--begin::Form-->
            <form @submit.prevent="verifyOTP" class="row">
                <!--Mobile Number-->
                <div class="fv-row mb-8">
                    <label class="fs-5 fw-semibold mb-2">Mobile Number</label>
                    <Field class="form-control form-control-lg" type="text"
                        :readonly="true"
                        name="mobile_number"
                        autocomplete="off" v-model="formData.mobile_number" placeholder="Enter your mobile number"/>
                    <ErrorMessage :errorMessage="formData.errors.mobile_number"/>
                </div>

                <!--Verify OTP-->
                <div class="fv-row mb-8">
                    <label class="fs-5 fw-semibold mb-2">Verify OTP</label>
                    <Field class="form-control form-control-lg" type="text"
                        name="otp"
                        autocomplete="off" v-model="formData.otp" placeholder="Enter your OTP"/>
                    <ErrorMessage :errorMessage="formData.errors.otp"/>
                </div>

                <SubmitButton :buttonValue="$t('buttonValue.verifyOTP')" class="btn mb-5 me-3" :class="{ 'opacity-25': formData.processing }" :disabled="formData.processing"/>
            </form>
            <!--end::Form-->

                <form @submit.prevent="resendOTP" class="row text-center">
                    <div class="mt-4 flex items-center justify-between">
                        <SubmitButton :buttonValue="$t('buttonValue.resendVerificationSMS')" class="btn mb-5 me-3" :class="{ 'opacity-25': formData.processing }" :disabled="formData.processing"/>
                        <Link :href="route('logout')" method="post" class="btn btn-secondary mb-5">
                            {{ $t('buttonValue.logout') }}
                        </Link>
                    </div>
                </form>
        </div>
    </div>
    <!--end::Wrapper-->
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import {getAssetPath} from "@/Core/helpers/Assets";
import { Field } from 'vee-validate';
import ErrorMessage from '@/Components/Message/ErrorMessage.vue';

const props = defineProps({
    user: Object,
    status: String
});

const formData = useForm({
    mobile_number: props?.user?.mobile_number || '',
    otp: ''
});

const verifyOTP = () => {
    formData.post(route('otp.verification.verify'));
};

const resendOTP = () => {
    formData.post(route('otp.verification.send', props?.user?.id));
};
</script>

<style>
#app, .verify-sms-page {
    height: 100%;
}
</style>

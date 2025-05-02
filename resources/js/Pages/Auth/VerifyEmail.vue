<template>
    <Head :title="$t('auth.verifyEmail.title')" />
    <!--begin::Wrapper-->
    <div class="verify-email-page p-10 row d-flex justify-content-center align-items-center" style="background-image: url('/media/auth/bg10.jpeg');">
        <div class="col-md-6 text-center">
            <!--begin:Logo-->
            <div class="mb-11">
                <Link href="/" rel="noopener" target="_blank">
                    <img alt="Logo" :src="getAssetPath('media/logos/logo.png')" class="h-60px h-lg-75px">
                </Link>
            </div>
            <!--end:Logo-->

            <div class="row mb-4 text-gray-600">
                {{ $t('auth.verifyEmail.message') }}
            </div>

            <div class="row mb-4 font-medium text-success" v-if="verificationLinkSent">
                {{ $t('auth.verifyEmail.linkSent') }}
            </div>

            <!--begin::Form-->
            <form @submit.prevent="submit" class="row">
                <div class="mt-4 flex items-center justify-between">
                    <SubmitButton :buttonValue="$t('buttonValue.resendVerificationEmail')" class="btn mb-5 me-3" :class="{ 'opacity-25': formData.processing }" :disabled="formData.processing"/>

                    <Link :href="route('logout')" method="post" class="btn btn-secondary mb-5">
                        {{ $t('buttonValue.logout') }}
                    </Link>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!--end::Wrapper-->
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import {getAssetPath} from "@/Core/helpers/Assets";

const props = defineProps<{
    status?: string;
}>();

const formData = useForm({});

const submit = () => {
    formData.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<style>
#app, .verify-email-page {
    height: 100%;
}
</style>

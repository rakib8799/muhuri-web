<template>
    <Head :title="`${props?.pageTitle}`" />
    <div class="login-confirmation-page p-10 d-flex justify-content-center align-items-center" style="background-image: url('/media/auth/bg10.jpeg');">
        <!--begin::Wrapper-->
        <div class="text-center my-10">
            <!--begin:Logo-->
            <div class="mb-11">
                <Link href="/" rel="noopener" target="_blank">
                    <img alt="Logo" :src="getAssetPath('media/logos/logo.png')" class="h-60px h-lg-75px">
                </Link>
            </div>
            <!--end:Logo-->

            <!--begin::Title-->
            <h2 class="fs-2x fw-bold mb-5">{{ $t('auth.signUpConfirmation.title') }} !</h2>
            <!--end::Title-->

            <!--begin::Description-->
            <p class="text-gray-500 fs-4 fw-semibold mb-5">
                {{ $t('auth.signUpConfirmation.description') }}
            </p>
            <!--end::Description-->

            <!--begin::Action-->
            <Link
                :href="route('login')"
                class="btn btn-primary"
            >
                {{ $t('buttonValue.login') }}
            </Link>
            <!--end::Action-->
            <!--begin::Illustration-->
            <div class="text-center px-4 mt-5">
                <img
                class="mw-100 mh-300px"
                alt="agency"
                :src="getAssetPath('media/auth/agency.png')"
                />
            </div>
            <!--end::Illustration-->
        </div>
        <!--end::Wrapper-->
</div>
<!--end::Card-->
</template>

<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import {getAssetPath} from "@/Core/helpers/Assets";

const props = defineProps({
    pageTitle: String
});
</script>

<style>
#app, .login-confirmation-page {
    height: 100%;
}
</style>

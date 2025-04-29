<template>
    <!--begin::Customer-->
    <div class="card card-flush pt-3 mb-5 mb-lg-10">
        <!--begin::Card header-->
        <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2 class="fw-bold">{{ $t('subscription.header.customer') }}</h2>
        </div>
        <!--begin::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">

            <!--begin::Selected customer-->
            <div class="d-flex align-items-center p-3 mb-2">
                <!--begin::Avatar-->
                <div class="symbol symbol-60px symbol-circle me-3">
                    <img alt="Pic" :src="getAssetPath('media/avatars/300-1.jpg')" />
                </div>
                <!--end::Avatar-->

                <!--begin::Info-->
                <div class="d-flex flex-column">
                    <!--begin::Name-->
                    <Link :href="route('subscriptions.show')" class="fs-4 fw-bold text-gray-900 text-hover-primary me-2"
                        >{{ name }}</Link>
                    <!--end::Name-->

                    <!--begin::Email-->
                    <Link :href="route('subscriptions.show')" class="fw-semibold text-gray-600 text-hover-primary"
                        >{{ email }}</Link>
                    <!--end::Email-->
                </div>
                <!--end::Info-->
            </div>
            <!--end::Selected customer-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Customer-->
</template>

<script lang="ts" setup>
import { getAssetPath } from "@/Core/helpers/Assets";
import { Link, usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;
const {name, email} = user;
</script>

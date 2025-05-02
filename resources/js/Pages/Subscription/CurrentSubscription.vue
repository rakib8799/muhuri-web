<template>
    <!--begin::Card-->
    <div class="card card-flush mb-0">
        <!--begin::Card header-->
        <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2>{{ $t('subscription.header.currentSubscription') }}</h2>
        </div>
        <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0 fs-6">
        <!--begin::Section-->
        <div class="mb-7">
            <!--begin::Details-->
            <div class="d-flex align-items-center">
            <!--begin::Avatar-->
            <div class="symbol symbol-60px symbol-circle me-3">
                <img alt="Pic" :src="getAssetPath('media/avatars/300-1.jpg')" />
            </div>
            <!--end::Avatar-->

            <!--begin::Info-->
            <div class="d-flex flex-column">
                <!--begin::Name-->
                <Link
                :href="route('subscriptions.show')"
                class="fs-4 fw-bold text-gray-900 text-hover-primary me-2"
                >{{ name }}</Link>
                <!--end::Name-->

                <!--begin::Email-->
                <Link :href="route('subscriptions.show')" class="fw-semibold text-gray-600 text-hover-primary"
                >{{ email }}</Link>
                <!--end::Email-->
            </div>
            <!--end::Info-->
            </div>
            <!--end::Details-->
        </div>
        <!--end::Section-->

        <!--begin::Separator-->
        <div class="separator separator-dashed mb-7"></div>
        <!--end::Separator-->

        <!--begin::Section-->
        <div class="mb-7">
            <!--begin::Title-->
            <h5 class="mb-4">{{ $t('subscription.header.subscriptionPlanDetails') }}</h5>
            <!--end::Title-->

            <!--begin::Details-->
            <div class="mb-0">
                <!--begin::Plan-->
                <div class="badge badge-light-info me-2">{{ props?.subscription?.subscription_plan?.name }}</div>
                <!--end::Plan-->

                <!--begin::Price-->
                <div class="fw-semibold text-gray-600" v-if="props?.subscription?.subscription_plan?.price && props?.subscription?.subscription_plan?.plan_type">&#2547;{{ props?.subscription?.subscription_plan?.price }} / {{ props?.subscription?.subscription_plan?.plan_type }}</div>
                <!--end::Price-->

                <!--begin::Duration-->
                <div class="fw-semibold text-gray-600" v-if="props?.subscription?.subscription_plan?.duration && props?.subscription?.subscription_plan?.duration_unit">{{ props?.subscription?.subscription_plan?.duration }} / {{ props?.subscription?.subscription_plan?.duration_unit }}</div>
                <!--end::Duration-->
            </div>
            <!--end::Details-->
        </div>
        <!--end::Section-->

        <!--begin::Separator-->
        <div class="separator separator-dashed mb-7"></div>
        <!--end::Separator-->

        <!--begin::Section-->
        <div class="mb-10">
            <!--begin::Title-->
            <h5 class="mb-4">{{ $t('subscription.header.subscriptionDetails') }}</h5>
            <!--end::Title-->

            <!--begin::Details-->
            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                <!--begin::Row-->
                <tr class="">
                    <td class="text-gray-500">{{ $t('subscription.label.started') }}:</td>
                    <td class="text-gray-800" v-if="props?.subscription?.start_date">{{ extractDate(new Date(props?.subscription?.start_date || '')) }}</td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr class="">
                    <td class="text-gray-500">{{ $t('subscription.label.ended') }}:</td>
                    <td class="text-gray-800" v-if="props?.subscription?.end_date">{{ extractDate(new Date(props?.subscription?.end_date || '')) }}</td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.status') }}:</td>
                    <td>
                        <span :class="`badge ${statusColors[props.subscription?.status] || 'bg-secondary'} text-white`">
                            {{ props.subscription?.status }}
                        </span>
                    </td>
                </tr>
                <!--end::Row-->
            </table>
            <!--end::Details-->
        </div>
        <!--end::Section-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</template>

<script lang="ts" setup>
import { getAssetPath } from "@/Core/helpers/Assets";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { extractDate } from "@/Core/helpers/Helper";

const user = usePage().props.auth.user;
const {name, email} = user;

const props = defineProps({
    subscription: Object
});

const statusColors = <any>{
    Running: 'bg-success',
    Trial: 'bg-primary',
    Expired: 'bg-danger'
};
</script>

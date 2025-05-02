<template>
    <!--begin::Section-->
    <div class="mb-10">
        <!--begin::Title-->
        <h5 class="mb-4">{{ $t('subscription.header.billingAddress') }}:</h5>
        <!--end::Title-->

        <!--begin::Details-->
        <div class="d-flex flex-wrap py-5">
        <!--begin::Row-->
        <div class="flex-equal me-5">
            <!--begin::Details-->
            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500 min-w-175px w-175px">{{ $t('subscription.label.billTo') }}:</td>
                    <td class="text-gray-800 min-w-200px">
                        {{ email }}
                    </td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.customerName') }}:</td>
                    <td class="text-gray-800">{{ name }}</td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.address') }}:</td>
                    <td class="text-gray-800">
                        {{ props?.configuration?.address }}
                    </td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.phone') }}:</td>
                    <td class="text-gray-800">{{ props?.configuration?.mobile_number }}</td>
                </tr>
                <!--end::Row-->
            </table>
            <!--end::Details-->
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="flex-equal">
            <!--begin::Details-->
            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500 min-w-175px w-175px">
                        {{ $t('subscription.label.subscriptionPlan') }}:
                    </td>
                    <td class="text-gray-800 min-w-200px">
                        {{ props?.subscription?.subscription_plan?.name }}
                    </td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.subscriptionFees') }}:</td>
                    <td class="text-gray-800" v-if="props?.subscription?.subscription_plan?.price && props?.subscription?.subscription_plan?.plan_type">&#2547;{{ props?.subscription?.subscription_plan?.price }} / {{ props?.subscription?.subscription_plan?.plan_type }}</td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.subscriptionDuration') }}:</td>
                    <td class="text-gray-800">{{ props?.subscription?.subscription_plan?.duration }} / {{ props?.subscription?.subscription_plan?.duration_unit }}</td>
                </tr>
                <!--end::Row-->

                <!--begin::Row-->
                <tr>
                    <td class="text-gray-500">{{ $t('subscription.label.currency') }}:</td>
                    <td class="text-gray-800">BDT - Bangladeshi Taka</td>
                </tr>
                <!--end::Row-->
            </table>
            <!--end::Details-->
        </div>
        <!--end::Row-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Section-->
</template>

<script lang="ts" setup>
import { defineProps } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;
const {name, email} = user;

const props = defineProps({
    subscription: Object,
    configuration: Object
});
</script>

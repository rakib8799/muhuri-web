<template>
    <!--begin::Layout-->
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
            <!--begin::Form-->
            <form class="form" action="#" id="kt_subscriptions_create_new">
                <Customer></Customer>

                <SubscriptionPlan :subscription="subscription" :subscriptionPlans="props?.subscriptionPlans" :subscriptionPlanFeatures="props?.subscriptionPlanFeatures" :paymentMethods="props?.paymentMethods"></SubscriptionPlan>
            </form>
            <!--end::Form-->
            </div>
            <!--end::Content-->

            <!--begin::Sidebar-->
            <div
            class="flex-column flex-lg-row-auto w-100 w-lg-250px w-xl-300px mb-10 order-1 order-lg-2"
            >
            <CurrentSubscription :subscription="props?.subscription"></CurrentSubscription>
            </div>
            <!--end::Sidebar-->
        </div>

    </AuthenticatedLayout>
    <!--end::Layout-->
</template>

<script lang="ts" setup>
import { defineProps } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Customer from "@/Pages/Subscription/Customer.vue";
import SubscriptionPlan from "@/Pages/Subscription/SubscriptionPlan.vue";
import CurrentSubscription from "@/Pages/Subscription/CurrentSubscription.vue";
import { IPaymentMethod } from "@/Core/helpers/Interfaces";

const props = defineProps({
    subscription: Object,
    subscriptionPlans: Object,
    subscriptionPlanFeatures: Object,
    paymentMethods: Array as ()=> IPaymentMethod[],
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}
</script>

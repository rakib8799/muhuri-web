<template>
    <!--begin::Card-->
    <div class="card card-flush pt-3 mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
            <h2 class="fw-bold">{{ $t('subscription.header.subscriptionDetails') }}</h2>
        </div>
        <!--begin::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <Link v-if ="checkPermission('can-edit-subscription') && props?.subscription?.id"
                :href="route('subscriptions.edit', props?.subscription?.id)"
                class="btn btn-light-primary"
            >
                {{ $t('buttonValue.changePlan') }}
            </Link>
            <!--begin::Actions-->
            <button
                v-if="checkPermission('can-edit-subscription')"
                type="button"
                class="btn btn-primary ms-3"
                data-bs-toggle="modal"
                data-bs-target="#kt_modal_payment_confirmation_modal"
            >
                {{ $t('buttonValue.renewSubscription') }}
            </button>
            <!--end::Actions-->
        </div>
        <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-3">
            <BillingAddress :subscription="props?.subscription" :configuration="props?.configuration"></BillingAddress>

            <SubscriptionHistory :subscriptionHistories="props?.subscriptionHistories"></SubscriptionHistory>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <PaymentConfirmationModal :subscription="props?.subscription" :paymentMethods="props?.paymentMethods"></PaymentConfirmationModal>
</template>

<script lang="ts" setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";
import BillingAddress from "@/Pages/Subscription/BillingAddress.vue";
import SubscriptionHistory from "@/Pages/Subscription/SubscriptionHistory.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import { IPaymentMethod, ISubscriptionHistory } from '@/Core/helpers/Interfaces';
import PaymentConfirmationModal from "@/Pages/Subscription/Modal/PaymentConfirmationModal.vue";

const props = defineProps({
    subscription: Object,
    subscriptionHistories: Object as() => ISubscriptionHistory[] | undefined,
    configuration: Object,
    paymentMethods: Array as() => IPaymentMethod[]
});
</script>

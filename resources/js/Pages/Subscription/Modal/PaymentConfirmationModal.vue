<template>
    <div class="modal fade" id="kt_modal_payment_confirmation_modal" ref="paymentConfirmationModalRef" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="fw-bold">{{ $t('subscription.header.paymentConfirmation') }}</h2>
                    <div id="kt_modal_payment_confirmation_modal_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                </div>

                <!-- Modal Body -->
                <VForm @submit="submit" :model="formData" ref="formRef">
                    <div class="modal-body py-10 px-lg-17">
                        <div class="scroll-y me-n7 pe-7">
                            <!-- Subscription Plan Details -->
                            <div class="mb-7">
                                <h5 class="mb-4">{{ $t('subscription.header.subscriptionPlanDetails') }}</h5>
                                <div class="mb-0">
                                    <div v-if="selectedPlan || props?.subscription?.subscription_plan">
                                        <div class="badge badge-light-info me-2">
                                            {{ (selectedPlan?.name || props?.subscription?.subscription_plan?.name) }}
                                        </div>
                                        <div class="fw-semibold text-gray-600" v-if="(selectedPlan?.price || props?.subscription?.subscription_plan?.price) && (selectedPlan?.plan_type || props?.subscription?.subscription_plan?.plan_type)">
                                            &#2547;{{ (selectedPlan?.price || props?.subscription?.subscription_plan?.price) }} / {{ (selectedPlan?.plan_type || props?.subscription?.subscription_plan?.plan_type) }}
                                        </div>
                                        <div class="fw-semibold text-gray-600" v-if="(selectedPlan?.duration || props?.subscription?.subscription_plan?.duration) && (selectedPlan?.duration_unit || props?.subscription?.subscription_plan?.duration_unit)">
                                            {{ (selectedPlan?.duration || props?.subscription?.subscription_plan?.duration) }} / {{ (selectedPlan?.duration_unit || props?.subscription?.subscription_plan?.duration_unit) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-5">
                            <h5 class="mb-4">{{ $t('subscription.header.paymentMethod') }}</h5>
                            <div class="row g-3">
                                <div v-for="paymentMethod in props?.paymentMethods" :key="paymentMethod.slug" class="col-4 col-md-3">
                                    <label class="w-100">
                                        <input
                                            type="radio"
                                            v-model="formData.payment_method_slug"
                                            :value="paymentMethod.slug"
                                            class="btn-check"
                                        />
                                        <div class="card text-center border-2 p-3"
                                            :class="formData.payment_method_slug === paymentMethod.slug ? 'border-primary shadow-sm' : 'border-light'"
                                        >
                                            <img
                                                :src="paymentMethod.logo"
                                                :alt="paymentMethod.name"
                                                class="img-fluid mx-auto"
                                                style="max-height: 50px; width: auto;"
                                            />
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer flex-center">
                            <div data-bs-dismiss="modal" class="btn btn-light me-3">
                                {{ $t('buttonValue.discard') }}
                            </div>
                            <SubmitButton :buttonValue="$t('buttonValue.pay')" />
                        </div>
                    </div>
                </VForm>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { hideModal } from "@/Core/helpers/Modal";
import { useForm } from "@inertiajs/vue3";
import { Form as VForm } from "vee-validate";
import KTIcon from '@/Core/helpers/kt-icon/KTIcon.vue';
import SubmitButton from '@/Components/Button/SubmitButton.vue';
import { IPaymentMethod } from '@/Core/helpers/Interfaces';

const props = defineProps({
    subscription: Object,
    paymentMethods: Array as() => IPaymentMethod[],
    selectedPlan: Object
});

const formData = useForm({
    subscription_plan_id: props.subscription?.subscription_plan?.id,
    subscription_id: props.subscription?.id,
    payment_method_slug: props.paymentMethods?.length ? props.paymentMethods[0].slug : 'bkash'
});

const paymentConfirmationModalRef = ref<null | HTMLElement>(null);
const formRef = ref<null | HTMLFormElement>(null);

watch(() => props.selectedPlan, (newSelectedPlan) => {
    if (newSelectedPlan) {
        formData.subscription_plan_id = newSelectedPlan.id;
    }
}, { immediate: true });

const submit = () => {
    const paymentMethodSlug = formData.payment_method_slug;

    let paymentRoute = '';
    if (paymentMethodSlug === 'bkash') {
        paymentRoute = route("payment.bkash.create.subscription");
    }

    formData.post(paymentRoute, {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(paymentConfirmationModalRef.value);
        }
    });
};
</script>

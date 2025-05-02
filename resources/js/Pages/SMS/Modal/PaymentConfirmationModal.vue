<template>
    <div class="modal fade" id="kt_modal_payment_confirmation_modal" ref="paymentConfirmationModalRef" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="fw-bold">{{ $t('header.paymentConfirmation') }}</h2>
                    <div id="kt_modal_payment_confirmation_modal_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                </div>

                <!-- Modal Body -->
                <VForm @submit="submit" :model="formData" ref="formRef">
                    <div class="modal-body py-10 px-lg-17">
                        <div class="scroll-y me-n7 pe-7">
                            <!-- SMS Purchase Details -->
                            <div class="mb-7">
                                <h5 class="mb-4">{{ $t('sms.header.smsPurchaseDetails') }}</h5>
                                <div class="mb-0">
                                    <div v-if="formData.sms_quantity && formData.total_price">
                                        <div class="badge badge-light-info me-2">
                                            {{ $t('label.quantity') }}: {{ formData.sms_quantity }}
                                        </div>
                                        <div class="fw-semibold text-gray-600">
                                            {{ $t('label.rate') }}: &#2547;{{ SMS_PRICE_PER_UNIT.toFixed(2) }} / {{ $t('sms.unit') }}
                                        </div>
                                        <div class="fw-semibold text-gray-600">
                                            {{ $t('label.totalPrice') }}: &#2547;{{ formData.total_price }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-5">
                            <h5 class="mb-4">{{ $t('header.paymentMethod') }}</h5>
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
    paymentMethods: Array as () => IPaymentMethod[],
    smsPurchaseDetails: Object, // New prop for SMS purchase details
});

const SMS_PRICE_PER_UNIT = 2.0; // Define price per SMS unit

const formData = useForm({
    sms_purchase_id: props.smsPurchaseDetails?.id, // Set initial value for sms_purchase_id
    sms_quantity: props.smsPurchaseDetails?.sms_quantity, // Set initial value for sms_quantity
    total_price: props.smsPurchaseDetails?.total_price, // Set initial value for total_price
    payment_method_slug: props.paymentMethods?.length ? props.paymentMethods[0].slug : 'bkash'
});

const paymentConfirmationModalRef = ref<null | HTMLElement>(null);
const formRef = ref<null | HTMLFormElement>(null);

// Watch for changes to smsPurchaseDetails and update form data accordingly
watch(() => props.smsPurchaseDetails, (newSmsPurchaseDetails) => {
    if (newSmsPurchaseDetails) {
        formData.sms_quantity = newSmsPurchaseDetails.sms_quantity;
        formData.total_price = newSmsPurchaseDetails.total_price;
        formData.sms_purchase_id = newSmsPurchaseDetails.id;
    }
}, { immediate: true });

const submit = () => {
    const paymentMethodSlug = formData.payment_method_slug;

    let paymentRoute = '';
    if (paymentMethodSlug === 'bkash') {
        paymentRoute = route("payment.bkash.create.sms");
        console.log(paymentRoute);
    }

    console.log('route: ', paymentRoute);

    formData.post(paymentRoute, {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(paymentConfirmationModalRef.value);
        }
    });
};
</script>

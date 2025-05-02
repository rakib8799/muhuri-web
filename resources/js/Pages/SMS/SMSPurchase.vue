<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">
                        {{ $t("sms.header.purchase") }}
                    </h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <div class="row mb-5 g-4">
                            <!-- SMS Rate -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t("label.rate") }}</label>
                                <Field
                                    type="number"
                                    class="form-control form-control-lg form-control-solid"
                                    :placeholder="$t('placeholder.rate')"
                                    name="sms_rate"
                                    :value="SMS_PRICE_PER_UNIT.toFixed(2)"
                                    readonly
                                />
                            </div>

                            <!-- Number of SMS -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t("label.quantity") }}</label>
                                <Field
                                    type="number"
                                    class="form-control form-control-lg form-control-solid"
                                    :placeholder="$t('placeholder.quantity')"
                                    name="sms_quantity"
                                    v-model="formData.sms_quantity"
                                    @input="calculateTotalPrice"
                                />
                                <ErrorMessage :errorMessage="formData.errors.sms_quantity" />
                            </div>
                        </div>

                        <div class="row mb-5 g-4">
                            <!-- Total Price (Auto-calculated) -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t("label.totalPrice") }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid"
                                    :placeholder="$t('placeholder.totalPrice')" name="total_price"
                                    v-model="formData.total_price" readonly />
                                <ErrorMessage :errorMessage="formData.errors.total_price" />
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton />
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
    <PaymentConfirmationModal :smsPurchaseDetails="smsPurchaseDetails" :paymentMethods="props?.paymentMethods"></PaymentConfirmationModal>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { ref, defineProps, watch } from "vue";
import { IPaymentMethod } from '@/Core/helpers/Interfaces';
import PaymentConfirmationModal from "@/Pages/SMS/Modal/PaymentConfirmationModal.vue";
import {Modal} from "bootstrap";
import axios from 'axios';

const SMS_PRICE_PER_UNIT = 2.0; // Change this price as per requirement

const props = defineProps({
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
    paymentMethods: Array as() => IPaymentMethod[]
});

const smsPurchaseDetails = ref({
    sms_quantity: 0,
    total_price: 0,
    id: null,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    sms_quantity: 0,
    total_price: 0,
});

// Auto-calculate Total Price
const calculateTotalPrice = () => {
    formData.total_price = formData.sms_quantity * SMS_PRICE_PER_UNIT;
};

watch(() => formData.sms_quantity, () => {
    calculateTotalPrice();
}, { immediate: true });

const submit = async () => {
    try {
        // Send the AJAX request using Axios
        const response = await axios.post(route("sms.storeSMSPurchase"), {
            sms_quantity: formData.sms_quantity,
        });

        // Check if the response is successful
        if (response.data.status === 'success') {
            console.log('response:', response.data);
            console.log('smsPurchaseDetails:', smsPurchaseDetails);
            smsPurchaseDetails.value = response.data.data;
            console.log(smsPurchaseDetails);
            // Show modal using the Bootstrap Modal API
            const modalElement = document.getElementById('kt_modal_payment_confirmation_modal');
            if (modalElement) {
                const modal = new Modal(modalElement);
                modal.show();
            }
        } else {
            // Handle errors if the status is not 'success'
            console.error("Error: " + response.data.message);
        }
    } catch (error) {
        // Handle any errors that occur during the request
        console.error("There was an error making the request:", error);
    }
};
</script>

<template>
    <div class="modal fade" id="kt_modal_add_supplier_payment" ref="addPaymentModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_email_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $t('payment.title.add') }}</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_update_email_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->

                <!--begin::Form-->
                <VForm @submit="submit()" :model="formData" ref="formRef">
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_update_item_unit" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_item_unit_header" data-kt-scroll-wrappers="#kt_modal_update_item_unit_scroll" data-kt-scroll-offset="300px">
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6 d-flex flex-stack flex-grow-1 justify-content-center align-items-center text-center">
                                <!--begin::Content-->
                                <div class="fw-semibold">
                                    <div class="fs-3 text-gray-700">{{ $t('label.totalDue') }}: {{ Math.abs(props.totalDue || 0) }}</div>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--Amount-->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('payment.label.amount') }}</label>
                                <Field type="text" :placeholder="$t('payment.placeholder.amount')" class="form-control form-control-lg form-control-solid" name="amount" v-model="formData.amount" />
                                <ErrorMessage :errorMessage="formData.errors.amount" />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('payment.label.paymentDate') }}</label>
                                <Field type="date" :placeholder="$t('payment.placeholder.paymentDate')" class="form-control form-control-lg form-control-solid" name="payment_date" v-model="formData.payment_date"/>
                                <ErrorMessage :errorMessage="formData.errors.payment_date" />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('payment.label.paymentMethod') }}</label>
                                <Multiselect
                                    :placeholder="$t('payment.placeholder.paymentMethod')"
                                    v-model="formData.payment_method"
                                    :searchable="true"
                                    label="text"
                                    trackBy="text"
                                    :options="allPaymentMethod"
                                />
                                <ErrorMessage :errorMessage="formData.errors.payment_method" />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">{{ $t('label.invoiceNumber') }}</label>
                                <Field type="text" :placeholder="$t('placeholder.invoiceNumber')" class="form-control form-control-lg form-control-solid" name="invoice_number" v-model="formData.invoice_number"/>
                                <ErrorMessage :errorMessage="formData.errors.invoice_number" />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">{{ $t('label.note') }}</label>
                                <Field type="text" :placeholder="$t('placeholder.note')" class="form-control form-control-lg form-control-solid" name="note" v-model="formData.note"/>
                                <ErrorMessage :errorMessage="formData.errors.note" />
                            </div>
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--Discard Button-->
                        <div data-bs-dismiss="modal" class="btn btn-light me-3">
                            {{ $t('buttonValue.discard') }}
                        </div>

                        <!-- Submit Button -->
                        <SubmitButton />
                    </div>
                    <!--end::Modal footer-->
                </VForm>
                <!--end::Form-->
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { ref, watch, defineProps, computed } from "vue";
import { useForm, usePage, router } from '@inertiajs/vue3';
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import toastr from 'toastr';
import 'toastr/toastr.scss';
import Multiselect from '@vueform/multiselect';

const formRef = ref < null | HTMLFormElement > (null);
const addPaymentModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
    supplier: Object,
    allPaymentMethod: Object,
    totalDue: Number
});

const formattedToday = ref(new Date().toISOString().substr(0, 10));

const formData = useForm({
    amount: '',
    payment_date: formattedToday.value,
    payment_method: 'cash',
    invoice_number: '',
    note: ''
});

const submit = () => {
    const url = route('suppliers.payments.store', props.supplier?.id);
    formData.post(url, {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(addPaymentModalRef.value);
            const flash = usePage().props.flash;
            let { success } = flash as any;

            if (flash && success) {
                toastr.success(success);
                success = null;
            }
        }
    })
};

</script>


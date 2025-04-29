<template>
    <div class="modal fade" id="kt_modal_edit_departure_info" ref="departureModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_edit_departure_info_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $t('employee.header.departureInfo.employeeTermination') }}</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_edit_departure_info_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_edit_departure_info_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_edit_departure_info_header" data-kt-scroll-wrappers="#kt_modal_edit_departure_info_scroll" data-kt-scroll-offset="300px">

                            <!-- Departure Reason-->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">{{ $t('employee.label.departureReason') }}</label>
                                <Multiselect
                                    :placeholder="$t('employee.placeholder.departureReason')"
                                    v-model="formData.departure_reason_id"
                                    :searchable="true"
                                    :options="departureReasons"
                                    label="text"
                                    trackBy="text"
                                />
                            </div>

                            <!-- Departure Description -->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">{{ $t('employee.label.departureDescription') }}</label>
                                <Field name="departure_description">
                                    <textarea v-model="formData.departure_description" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.departureDescription')"/>
                                </Field>
                                <ErrorMessage :errorMessage="formData.errors.departure_description" />
                            </div>

                            <!-- Departure Date -->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">{{ $t('employee.label.departureDate') }}</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.departureDate')" name="departure_date" v-model="formData.departure_date"/>
                                <ErrorMessage :errorMessage="formData.errors.departure_date" />
                            </div>
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!-- Submit Button -->
                        <SubmitButton :id="props.employee?.id" />
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
import { ref } from "vue";
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import { router, useForm, usePage } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect';
import toastr from 'toastr';
import 'toastr/toastr.scss';

const formRef = ref < null | HTMLFormElement > (null);
const departureModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
    employee: Object,
    departureReasons: Object
})

const formData = useForm({
    departure_reason_id: props.employee?.departure_reason_id || '',
    departure_description: props.employee?.departure_description || '',
    departure_date: props.employee?.departure_date || ''
});

// assign all the departure reasons from props to departureReasons variable.
const departureReasons = ref<Array<any>>([]);
    if (Array.isArray(props.departureReasons) && props.departureReasons.length > 0) {
        departureReasons.value = props.departureReasons.map(departureReason => ({value: departureReason.id, text: departureReason.name}));
}

const submit = () => {
    formData.patch(route('employees.processEmployeeDeparture', props.employee?.id), {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(departureModalRef.value);
            router.visit(route('employees.show', props.employee?.id), { replace: true });
            const flash = usePage().props.flash;
            let {success} = flash as any;

            if (flash && success) {
                toastr.success(success);
            }
        }
    })
}
</script>

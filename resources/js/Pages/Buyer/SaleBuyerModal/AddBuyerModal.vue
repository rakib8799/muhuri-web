<template>
    <div class="modal fade" id="kt_modal_add_buyer_for_sale" ref="addBuyerModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_email_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Add Buyer</h2>
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
                            <div class="row mb-5 g-4">
                                <!-- Name -->
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-5 fw-semibold mb-2">{{ $t("label.name") }}</label>
                                    <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.name')" name="name" v-model="formData.name" />
                                    <ErrorMessage :errorMessage="formData.errors.name" />
                                </div>

                                <!-- Mobile Number -->
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-5 fw-semibold mb-2">{{ $t("label.mobileNumber") }}</label>
                                    <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.mobileNumber')" name="mobile_number" v-model="formData.mobile_number" />
                                    <ErrorMessage :errorMessage="formData.errors.mobile_number"/>
                                </div>
                            </div>

                            <div class="row mb-5 g-4">
                                <!-- Previous Due -->
                                <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t("buyer.label.previous_due") }}</label>
                                    <Field type="number" class="form-control form-control-lg form-control-solid" :placeholder="$t('buyer.placeholder.previous_due')" name="previous_due" v-model="formData.previous_due" />
                                    <ErrorMessage :errorMessage="formData.errors.previous_due" />
                                </div>

                                <!-- Division -->
                                <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t("label.division") }}</label>
                                    <Multiselect :placeholder="$t('placeholder.division')" v-model="formData.division_id" :searchable="true" label="text" trackBy="text" :options="divisions" />
                                    <ErrorMessage :errorMessage="formData.errors.division_id" />
                                </div>
                            </div>

                            <div class="row mb-5 g-4">
                                <!-- District -->
                                <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t("label.district") }}</label>
                                    <Multiselect :placeholder="$t('placeholder.district')" v-model="formData.district_id" :searchable="true" label="text" trackBy="text" :options="districts" />
                                    <ErrorMessage :errorMessage="formData.errors.district_id" />
                                </div>

                                <!-- Upazila -->
                                <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t("label.upazila") }}</label>
                                    <Multiselect :placeholder="$t('placeholder.upazila')" v-model="formData.upazila_id" :searchable="true" label="text" trackBy="text" :options="upazilas" />
                                    <ErrorMessage :errorMessage="formData.errors.upazila_id" />
                                </div>
                            </div>

                            <div class="row mb-5 g-4">
                                <!-- Union -->
                                <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t("label.union") }}</label>
                                    <Multiselect :placeholder="$t('placeholder.union')" v-model="formData.union_id" :searchable="true" label="text" trackBy="text" :options="unions" />
                                    <ErrorMessage :errorMessage="formData.errors.union_id" />
                                </div>

                                <!-- Village -->
                                <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t("label.village") }}</label>
                                    <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.village')" name="village" v-model="formData.village" />
                                    <ErrorMessage :errorMessage="formData.errors.village" />
                                </div>
                            </div>

                            <!-- Note -->
                            <div lass="mb-5 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t("label.note") }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.note')" name="note" v-model="formData.note" />
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
import { ref, onMounted, defineProps } from "vue";
import { useForm } from '@inertiajs/vue3';
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import Multiselect from '@vueform/multiselect';
import { Location } from '@/Core/helpers/Location';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const formRef = ref < null | HTMLFormElement > (null);
const addBuyerModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
  routeType: String
});


const formData = useForm({
    name: "",
    mobile_number: "",
    previous_due: 0,
    division_id: "",
    district_id: "",
    upazila_id: "",
    union_id: "",
    village: "",
    note: "",
});

// Use the LocationData Component
const { divisions, districts, upazilas, unions, loadInitialData } = Location(formData);

onMounted(() => {
  loadInitialData();
});

const submit = () => {
  const url = props.routeType === 'other'
    ? route('sales.others.buyer.store')
    : route('sales.buyer.store');

  formData.post(url, {
    preserveScroll: true,
    onSuccess: () => {
      hideModal(addBuyerModalRef.value);
      window.location.reload();
    },
  });
};
</script>

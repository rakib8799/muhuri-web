<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">
                        {{ props?.buyer?.id ? $t("buyer.header.edit") : $t("buyer.header.add") }}
                    </h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
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
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.mobileNumber')" name="mobile_umber" v-model="formData.mobile_number" />
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
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.buyer?.id" />
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { ref, onMounted, defineProps } from "vue";
import Multiselect from "@vueform/multiselect";
import { Location } from '@/Core/helpers/Location';

const props = defineProps({
    buyer: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    name: props.buyer?.name || "",
    mobile_number: props.buyer?.mobile_number || "",
    previous_due: props.buyer?.previous_due || 0,
    division_id: props.buyer?.division_id || "",
    district_id: props.buyer?.district_id || "",
    upazila_id: props.buyer?.upazila_id || "",
    union_id: props.buyer?.union_id || "",
    village: props.buyer?.village || "",
    note: props.buyer?.note || "",
});

// Use the LocationData Component
const { divisions, districts, upazilas, unions, loadInitialData } = Location(formData);

onMounted(() => {
  loadInitialData();
});

const submit = () => {
    if (props.buyer?.id) {
        //for updating buyer
        formData.patch(route("buyers.update", props.buyer?.id), {
            preserveScroll: true,
        });
    } else {
        // for adding new buyer
        formData.post(route("buyers.store"), {
            preserveScroll: true,
        });
    }
};
</script>

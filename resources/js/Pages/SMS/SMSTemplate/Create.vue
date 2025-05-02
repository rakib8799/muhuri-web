<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ props.smsTemplate?.id ? $t('sms.header.editSMSTemplate') : $t('sms.header.addSMSTemplate') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('label.name') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.name')" name="name" v-model="formData.name"/>
                                <ErrorMessage :errorMessage="formData.errors.name" />
                            </div>
                        </div>

                        <!-- SMS Template (English) -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('sms.label.enTemplate') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('sms.placeholder.enTemplate')" name="sms_template_en" v-model="formData.sms_template_en"/>
                                <ErrorMessage :errorMessage="formData.errors.sms_template_en" />
                            </div>
                        </div>

                        <!-- SMS Template (Bangla) -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('sms.label.bnTemplate') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('sms.placeholder.bnTemplate')" name="sms_template_bn" v-model="formData.sms_template_bn"/>
                                <ErrorMessage :errorMessage="formData.errors.sms_template_bn" />
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.smsTemplate?.id" />
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from '@inertiajs/vue3';
import { Field, Form as VForm } from "vee-validate";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";

const props = defineProps({
    smsTemplate: Object,
    pageTitle: String,
    breadcrumbs: Array as() => Breadcrumb[],
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    name: props.smsTemplate?.name || '',
    sms_template_en: props.smsTemplate?.sms_template_en || '',
    sms_template_bn: props.smsTemplate?.sms_template_bn || ''
});

const submit = () =>{
    if(props.smsTemplate?.id){
        //for updating sms templates
        formData.patch(route('sms.templates.update', props.smsTemplate?.id), {
            preserveScroll: true,
        });
    } else {
        //for storing sms templates
        formData.post(route('sms.templates.store'), {
            preserveScroll: true,
        });
    }
}

</script>



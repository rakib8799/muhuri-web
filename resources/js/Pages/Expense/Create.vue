<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ props.expense?.id ? $t('expense.title.edit') : $t('expense.title.add') }}</h3>
                </div>
            </div>

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <div class="card-body border-top p-9">
                        <div class="row mb-6 g-4">
                            <div class="col-md-5 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('expense.label.selectItem') }}</label>
                                <Multiselect
                                    :placeholder="$t('expense.placeholder.selectItem')"
                                    v-model="formData.item_id"
                                    :searchable="true"
                                    label="text"
                                    trackBy="text"
                                    :options="allItems"
                                />
                                <ErrorMessage :errorMessage="formData.errors.item_id" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('label.date') }}</label>
                                <Field type="date" :placeholder="$t('placeholder.date')" class="form-control form-control-lg form-control-solid" name="expense_date" v-model="formData.expense_date"/>
                                <ErrorMessage :errorMessage="formData.errors.expense_date" />
                            </div>
                        </div>
                        <div class="row mb-6 g-4">
                            <div class="col-md-5 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('expense.label.invoiceNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" name="invoice_number" v-model="formData.invoice_number"/>
                                <ErrorMessage :errorMessage="formData.errors.invoice_number" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('expense.label.amount') }}</label>
                                <Field type="number" class="form-control form-control-lg form-control-solid" name="amount" v-model="formData.amount"/>
                                <ErrorMessage :errorMessage="formData.errors.amount" />
                            </div>
                        </div>

                        <div class="row fv-row">
                            <label class="fs-5 fw-semibold mb-2">{{ $t('label.note') }}</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" name="note" v-model="formData.note" :placeholder="$t('placeholder.note')" />
                            <ErrorMessage :errorMessage="formData.errors.note" />
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.expense?.id"/>
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, defineProps, onMounted, reactive, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Field, Form as VForm } from "vee-validate";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import Multiselect from "@vueform/multiselect";
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    items: Array as () => Array<{ id: number; name: string }>,
    expense: Object,
    pageTitle: String,
    breadcrumbs: Array as () => Array<{ url: string; title: string }>,
});

const formattedToday = ref(new Date().toISOString().substr(0, 10));

const formData = useForm({
    expense_date: props.expense?.expense_date || formattedToday,
    invoice_number: props.expense?.invoice_number || '',
    item_id: props.expense?.item_id || '',
    amount: props.expense?.amount || '',
    note: props.expense?.note || ''
});

const allItems = ref<Array<{ value: number; text: string }>>([]);

if (Array.isArray(props.items) && props.items.length > 0) {
    allItems.value = props.items.map(item => ({
        value: item.id,
        text: item.name,
    }));
}

const errors = reactive({
    amount: ""
});

const validatedData = () => {
    let valid = true;
    errors.amount = "";

    if (formData.amount !== "" && Number(formData.amount) <= 0) {
        errors.amount = "Amount must be greater than 0";
        valid = false;
    }
    return valid;
};

const submit = () => {
    if (validatedData()) {
        if(props.expense?.id){
            formData.patch(route('expense.update', props.expense?.id), {
                onSuccess: () => {
                    formData.reset();
                },
                onError: (errors) => {
                    console.log("Form Updating Error:", errors);
                },
            });
        } else{
            formData.post(route("expenses.store"), {
                onSuccess: () => {
                    formData.reset();
                },
                onError: (errors) => {
                    console.log("Form Submission Error:", errors);
                },
            });
        }
    }
};
</script>

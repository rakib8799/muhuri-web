<template>
    <div class="modal fade" id="kt_modal_edit_other_sale" ref="editOtherSaleModalRef" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">{{ $t('sale.title.updateItem') }}</h2>
                    <div id="kt_modal_add_product_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                </div>

                <VForm @submit="submit" ref="formRef">
                    <div class="modal-body py-10 px-lg-17">
                        <div class="scroll-y me-n7 pe-7">
                            <!-- Item -->
                            <div class="row mb-5 g-4">
                                <div class="fv-row mb-7 col-md-12">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.items') }}</label>
                                    <Multiselect
                                        :placeholder="$t('sale.placeholder.selectItem')"
                                        v-model="formData.item_id"
                                        :searchable="true"
                                        label="text"
                                        trackBy="value"
                                        :options="props.items"
                                    />
                                    <p v-if="errors.item_id" class="text-danger">{{ errors.item_id }}</p>
                                </div>
                            </div>

                            <div class="row mb-5 g-4" v-if="!formData.item_id || !hasUnitRelation">
                                <div class="fv-row mb-7 col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.quantity') }}</label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.quantity')" class="form-control form-control-lg form-control-solid" name="quantity" v-model="formData.quantity"/>
                                    <p v-if="errors.quantity" class="text-danger">{{ errors.quantity }}</p>
                                </div>
                                <div class="fv-row mb-7 col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">
                                    {{ $t('sale.label.unitPrice') }}
                                    <span v-if="selectedFormDisplay" class="text-muted fs-7">({{ selectedFormDisplay }})</span>
                                    </label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.unitPrice')" class="form-control form-control-lg form-control-solid" name="unit_price" v-model="formData.unit_price" />
                                    <p v-if="errors.unit_price" class="text-danger">{{ errors.unit_price }}</p>
                                </div>
                            </div>

                            <div class="row mb-5 g-4">
                                <div class="fv-row mb-7 col-md-12">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.totalPrice') }}</label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.totalPrice')" :class="['form-control form-control-lg form-control-solid', { 'text-muted': !hasUnitRelation }]"  name="total_price" v-model="formData.total_price" :disabled="!hasUnitRelation"/>
                                    <p v-if="errors.total_price" class="text-danger">{{ errors.total_price }}</p>
                                </div>
                            </div>

                            <!-- Note -->
                            <div lass="mb-5 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t("label.note") }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.note')" name="note" v-model="formData.note" />
                                <ErrorMessage :errorMessage="errors.note" />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <div data-bs-dismiss="modal" class="btn btn-light me-3">
                            {{ $t('buttonValue.discard') }}
                        </div>
                        <button type="submit" class="btn btn-light-primary">{{ $t('sale.buttonValue.updateItem') }}</button>
                    </div>
                </VForm>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, reactive, defineProps, defineEmits, watchEffect, watch, computed } from "vue";
import { Form as VForm, Field } from "vee-validate";
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import Multiselect from '@vueform/multiselect';
import i18n from '@/Core/plugins/i18n';
const { t } = i18n.global;

const formRef = ref<null | HTMLFormElement>(null);
const editOtherSaleModalRef = ref<null | HTMLElement>(null);

const props = defineProps({
    item: Object,
    items: Array as () => IItems[],
    itemUnits: Object,
});

interface IItems {
    id: number;
    item_id: number;
    item_name: string;
    quantity: number;
    unit_price: number;
    total_price: number;
    note: string;
    text?: string;
}

const emit = defineEmits(['updateItem']);

const formData = reactive({
    item_id: props.item?.id,
    item_name: props.item?.name,
    quantity: props.item?.quantity,
    unit_price: props.item?.unit_price,
    total_price: props.item?.total_price,
    note: props.item?.note
});

const hasUnitRelation = computed(() => {
    const item = props.items?.find((item: any) => item.value === formData.item_id);
    const itemName = item?.text || '';
    const targetNames = ['পুরাতন মালমাল', 'অন্যান্য'];
    return targetNames.includes(itemName);
});

const selectedFormDisplay = computed(() => {
    const selectedItemUnit = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === formData.item_id);
    return selectedItemUnit ? selectedItemUnit.form_display_name : '';
});

watch(() => props.item, (newItem) => {
    if (newItem) {
        Object.assign(formData, {
            item_id: newItem.item_id,
            item_name: newItem.item_name,
            quantity: newItem.quantity,
            unit_price: newItem.unit_price,
            total_price: newItem.total_price,
            note: newItem.note
        });
    }
}, { immediate: true });

watchEffect(() => {
    if(!hasUnitRelation.value){
        const quantity = Math.max(parseFloat(String(formData.quantity ?? 0)) || 0, 0);
        const unitPrice = Math.max(parseFloat(String(formData.unit_price ?? 0)) || 0, 0);

        const selectedItem = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === formData.item_id);
        const value = selectedItem ? selectedItem.value : 1;

        formData.total_price = Math.max((formData.quantity * unitPrice) / value, 0);
    }else{
        formData.quantity = 1;
        formData.unit_price = formData.total_price;
    }
});

const errors = reactive({
    item_id: "",
    quantity: "",
    unit_price: "",
    total_price: "",
    note: ""
});

const validateForm = () => {
    let valid = true;

    errors.item_id = "";
    errors.quantity = "";
    errors.unit_price = "";
    errors.total_price = "";
    errors.note = "";

    if (!formData.item_id) {
        errors.item_id = t('validation.item.required');
        valid = false;
    }
    if (!hasUnitRelation.value) {
        if (!formData.quantity || isNaN(Number(formData.quantity))) {
            errors.quantity = t('validation.quantity.required');
            valid = false;
        }
        if (formData.quantity <= 0) {
            errors.quantity = t('validation.quantity.greater');
            valid = false;
        }
        if (!formData.unit_price || isNaN(Number(formData.unit_price))) {
            errors.unit_price = t('validation.unitPrice.numeric');
            valid = false;
        }
        if (formData.unit_price <= 0) {
            errors.unit_price =  t('validation.unitPrice.greater');
            valid = false;
        }
    }
    if (!formData.total_price || isNaN(Number(formData.total_price))) {
        errors.total_price =  t('validation.totalPrice.numeric');
        valid = false;
    }
    if (formData.total_price <= 0) {
        errors.total_price =  t('validation.totalPrice.greater');
        valid = false;
    }

    return valid;
};

const submit = () => {
    if (validateForm()) {
        emit('updateItem', { ...formData });

        formData.item_id = props.item?.item_id;
        formData.item_name = props.item?.item_name;
        formData.quantity = !hasUnitRelation.value ? 0 :  props.item?.quantity;
        formData.unit_price = !hasUnitRelation.value ? 0 : props.item?.unit_price;
        formData.total_price = props.item?.total_price;
        formData.note = props.item?.note;

        hideModal(editOtherSaleModalRef.value);
    }
};
</script>

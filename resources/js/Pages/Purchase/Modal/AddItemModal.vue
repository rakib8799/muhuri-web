<template>
    <div class="modal fade" id="kt_modal_add_purchase" ref="addPurchaseModalRef" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">{{ $t('sale.title.addItem') }}</h2>
                    <div id="kt_modal_add_product_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                </div>

                <VForm @submit="submit" ref="formRef">
                    <div class="modal-body py-10 px-lg-17">
                        <div class="scroll-y me-n7 pe-7">
                            <!-- Item -->
                            <div class="row mb-5 g-4">
                                <div :class="['fv-row mb-7', isTargetItem ? 'col-md-12' : 'col-md-6']">
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

                                <div class="fv-row mb-7 col-md-6" v-if="!isTargetItem">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.brand') }}</label>
                                    <Multiselect
                                        :placeholder="$t('sale.placeholder.selectBrand')"
                                        v-model="formData.brand_id"
                                        :searchable="true"
                                        label="text"
                                        trackBy="text"
                                        :options="brands"
                                    />
                                     <p v-if="errors.brand_id" class="text-danger">{{ errors.brand_id }}</p>
                                </div>
                            </div>

                            <div class="row mb-5 g-4" v-if="!isTargetItem">
                                <div class="fv-row mb-7 col-md-6">
                                    <label class="fs-6 fw-semibold mb-2">{{ $t('sale.label.box') }}</label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.box')" class="form-control form-control-lg form-control-solid" name="total_box" v-model="formData.total_box" />
                                </div>

                                <div class="fv-row mb-7 col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.poly') }}</label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.poly')" class="form-control form-control-lg form-control-solid" name="total_poly" v-model="formData.total_poly"/>
                                    <p v-if="errors.total_poly" class="text-danger">{{ errors.total_poly }}</p>
                                </div>
                            </div>

                            <div class="row mb-5 g-4">
                                <div class="fv-row mb-7 col-md-6" v-if="!isTargetItem">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.mir') }}</label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.mir')" class="form-control form-control-lg form-control-solid" name="mir" v-model="formData.mir" />
                                    <p v-if="errors.mir" class="text-danger">{{ errors.mir }}</p>
                                </div>

                                <div :class="!isTargetItem ? 'fv-row mb-7 col-md-6' : 'fv-row mb-7 col-md-12'">
                                    <label class="required fs-6 fw-semibold mb-2">
                                        {{ $t('sale.label.quantity') }}
                                        <span v-if="formDisplayText" class="text-muted fs-7">({{ formDisplayText }})</span>
                                    </label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.quantity')" :class="['form-control form-control-lg form-control-solid ', {'text-muted' : !isTargetItem}]" name="quantity" v-model="formData.quantity" :disabled="!isTargetItem"/>
                                    <p v-if="errors.quantity" class="text-danger">{{ errors.quantity }}</p>
                                </div>
                            </div>

                            <div class="row mb-5 g-4">
                                <div class="fv-row mb-7 col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">
                                    {{ $t('sale.label.unitPrice') }}
                                    <span v-if="selectedFormDisplay" class="text-muted fs-7">({{ selectedFormDisplay }})</span>
                                    </label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.unitPrice')" class="form-control form-control-lg form-control-solid" name="unit_price" v-model="formData.unit_price" />
                                    <p v-if="errors.unit_price" class="text-danger">{{ errors.unit_price }}</p>
                                </div>

                                <div class="fv-row mb-7 col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('sale.label.totalPrice') }}</label>
                                    <Field type="number" :placeholder="$t('sale.placeholder.totalPrice')" class="form-control form-control-lg form-control-solid text-muted" name="total_price" v-model="formData.total_price" disabled/>
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
                        <button type="submit" class="btn btn-light-primary">{{ $t('sale.buttonValue.addItem') }}</button>
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
const addPurchaseModalRef = ref<null | HTMLElement>(null);

const props = defineProps({
    items: Array as () => IItems[],
    itemUnits: Object,
    brands: Object
});

interface IItems {
    id?: number;
    item_id: number;
    item_name: string;
    brand_id?: number | null;
    brand_name?: string;
    total_box?: number | null;
    total_poly: number;
    mir: number;
    quantity: number;
    unit_price: number;
    total_price: number;
    note: string;
    text?: string;
}

const emit = defineEmits(['addItem']);

const formData = reactive<IItems>({
    item_id: 0,
    item_name: '',
    brand_id: 0,
    brand_name: '',
    total_box: 0,
    total_poly: 1,
    mir: 0,
    quantity: 0,
    unit_price: 0,
    total_price: 0,
    note: ''
});

const errors = reactive({
    item_id: "",
    brand_id: "",
    total_poly: "",
    mir: "",
    quantity: "",
    unit_price: "",
    total_price: "",
    note: ""
});

const isTargetItem = computed(() => {
    const item = props.items?.find((item: any) => item.value === formData.item_id);
    const itemName = item?.text || '';
    const targetNames = ['ভেনামি মাদার', 'ভেনামি নফলি', 'বাগদা মাদার', 'বাগদা নফলি'];
    return targetNames.includes(itemName);
});

const selectedFormDisplay = computed(() => {
    const selectedItemUnit = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === formData.item_id);
    return selectedItemUnit ? selectedItemUnit.form_display_name : '';
});

const formDisplayText = computed (() => {
    if(selectedFormDisplay.value === 'প্রতি হাজারের দাম'){
        return 'হাজার';
    } else if (selectedFormDisplay.value === 'প্রতি পিসের দাম') {
        return 'পিস';
    } else if(selectedFormDisplay.value === 'প্রতি মিলিয়নের দাম'){
        return 'মিলিয়ন';
    } else {
        return '';
    }
});

const validateForm = () => {
    let valid = true;

    errors.item_id = "";
    errors.brand_id = "";
    errors.total_poly = "";
    errors.mir = "";
    errors.quantity = "";
    errors.unit_price = "";
    errors.total_price = "";
    errors.note = "";

    if (!formData.item_id) {
        errors.item_id = t('validation.item.required');
        valid = false;
    }

    if(!isTargetItem.value){
        if (!formData.brand_id) {
            errors.brand_id = t('validation.brand.required');
            valid = false;
        }
        if (!formData.total_poly || isNaN(Number(formData.total_poly))) {
            errors.total_poly = t('validation.totalPoly.numeric');
            valid = false;
        }
        if (formData.total_poly <= 0) {
            errors.total_poly = t('validation.totalPoly.greater');
            valid = false;
        }
        if (!formData.mir || isNaN(Number(formData.mir))) {
            errors.mir = t('validation.mir.numeric');
            valid = false;
        }
        if (formData.mir <= 0) {
            errors.mir = t('validation.mir.greater');
            valid = false;
        }
    }
    if (!formData.quantity || isNaN(Number(formData.quantity))) {
        errors.quantity = t('validation.quantity.numeric');
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
        errors.unit_price = t('validation.unitPrice.greater');
        valid = false;
    }
    if (!formData.total_price || isNaN(Number(formData.total_price))) {
        errors.total_price = t('validation.totalPrice.numeric');
        valid = false;
    }
    if (formData.total_price <= 0) {
        errors.total_price = t('validation.totalPrice.greater');
        valid = false;
    }

    return valid;
};

const isTotalBoxEntered = ref(false);

watch(() => formData.total_box, (newValue) => {
        if (newValue) {
            isTotalBoxEntered.value = true;
            formData.total_poly = newValue ? newValue * 2 : 0;
        }
    }
);

watch(() => formData.total_poly, (newValue) => {
        if (!isTotalBoxEntered.value) {
            formData.quantity = Math.max((formData.mir ?? 0) * newValue, 0);
        }
    }
);

watchEffect(() => {
    if(!isTargetItem.value){
        const mir = Math.max(parseFloat(String(formData.mir ?? 0)) || 0, 0);
        const unitPrice = Math.max(parseFloat(String(formData.unit_price ?? 0)) || 0, 0);

        const selectedItem = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === formData.item_id);
        const value = selectedItem ? selectedItem.value : 1;

        formData.quantity = Math.max(mir * formData.total_poly, 0);

        formData.total_price = Math.max((formData.quantity * unitPrice) / value, 0);
    }else{
        const selectedItem = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === formData.item_id);
        const value = selectedItem ? selectedItem.value : 1;

        const unitPrice = Math.max(parseFloat(String(formData.unit_price ?? 0)) || 0, 0);
        const quantity = Math.max(parseFloat(String(formData.quantity ?? 0)) || 0, 0);
        formData.total_price = Math.max((quantity * unitPrice) / value, 0);
    }
});

const submit = () => {
    if (validateForm()) {
        if(isTargetItem.value){
            formData.total_poly = 0;
        }
        emit('addItem', { ...formData });

        // Reset form
        formData.item_id = 0;
        formData.item_name = '';
        formData.brand_id = 0;
        formData.brand_name = '';
        formData.total_box = 0;
        formData.total_poly = 1;
        formData.mir = 0;
        formData.quantity = 0;
        formData.unit_price = 0;
        formData.total_price = 0;
        formData.note = '';

        hideModal(addPurchaseModalRef.value);
    }
};
</script>

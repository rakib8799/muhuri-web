<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Item</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Buyer -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('sale.label.selectBuyer') }}</label>
                                <Field type="text" :placeholder="$t('sale.placeholder.selectBuyer')" class="form-control form-control-lg form-control-solid" name="buyer_name" v-model="formData.buyer_name" readonly/>
                                <ErrorMessage :errorMessage="formData.errors.buyer_name" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('label.date') }}</label>
                                <Field type="date" :placeholder="$t('placeholder.date')" class="form-control form-control-lg form-control-solid" name="sale_date" v-model="formData.sale_date"/>
                                <ErrorMessage :errorMessage="formData.errors.sale_date" />
                            </div>
                        </div>
                        <div class="rounded-3 mt-20" style="border: 1px solid #E9EAEC; padding: 10px">
                            <div class="row mb-5 g-4 border-0 cursor-pointer mt-5">
                                <div class="col-md-9 fv-row card-title m-0">
                                    <h3 class="fw-bold m-0">{{ $t('sale.label.items') }}</h3>
                                </div>
                            </div>
                            <!-- table -->
                            <div class="w-100">
                                <table class="table w-100">
                                    <!-- Table Header -->
                                    <thead class="fs-6 fw-semibold text-gray-600">
                                        <tr>
                                            <th class="text-start w-15">{{ $t('sale.label.itemName') }}</th>
                                            <th class="text-end w-10">{{ $t('sale.label.quantity') }}</th>
                                            <th class="text-end w-10">{{ $t('sale.label.unitPrice') }}</th>
                                            <th class="text-end w-15">{{ $t('sale.label.totalPrice') }}</th>
                                            <th class="text-end w-10">{{ $t('label.actions') }}</th>
                                        </tr>
                                    </thead>

                                    <!-- Table Body -->
                                    <tbody>
                                        <tr v-for="(sale, index) in tableData" :key="sale.id">
                                            <td class="text-start">{{ sale.item_name }}{{ sale.brand_name ? `(${sale.brand_name})` : '' }}</td>
                                            <td class="text-end">{{ sale.quantity }}</td>
                                            <td class="text-end">{{ sale.unit_price }}</td>
                                            <td class="text-end">{{ sale.total_price }}</td>
                                            <td class="text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <ConfirmationButton
                                                        v-if="checkPermission('can-edit-sale')"
                                                        iconName="pencil"
                                                        :obj="sale"
                                                        title="Item Edit"
                                                        :messageTitle="`${$t('confirmation.sale.edit', { itemName: sale.item_name })}`"
                                                        :modalId="'kt_modal_update_other_item'"
                                                        :openEditModal="openEditModal"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Total Amount -->
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-end">
                                                <label class="fs-5 fw-semibold text-gray-600">{{ $t('sale.label.totalPrice') }}</label>
                                            </td>
                                            <td class="text-end d-flex justify-content-end">
                                                {{ totalAmount.toFixed(2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                        <!-- Discount Amount -->
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-end">
                                                <label class="fs-5 fw-semibold text-gray-600">{{ $t('sale.label.discountAmount') }}</label>
                                            </td>
                                            <td class="text-end d-flex justify-content-end">
                                                <Field type="text" :placeholder="$t('sale.placeholder.discountAmount')" class="form-control form-control-lg form-control-solid w-100" name="discount" v-model="formData.discount" style="max-width: 120px; font-size: 14px; text-align:right"/>
                                                <ErrorMessage :errorMessage="formData.errors.discount" />

                                            </td>
                                            <td></td>
                                        </tr>
                                        <!-- Discount Amount Error -->
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="" style="width: 150px;">
                                                <p v-if="errors.discount" class="text-danger">{{ errors.discount }}</p>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <!-- Grand Total -->
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-end">
                                                <label class="fs-5 fw-semibold text-gray-600">{{ $t('sale.label.grandTotal') }}</label>
                                            </td>
                                            <td class="text-end d-flex justify-content-end">
                                                {{ grandTotal.toFixed(2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                        <!-- Paid Amount -->
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-end">
                                                <label class="fs-5 fw-semibold text-gray-600">{{ $t('sale.label.paid') }}</label>
                                            </td>
                                            <td class="text-end d-flex justify-content-end">
                                                <Field type="text" :placeholder="$t('sale.placeholder.paid')" class="form-control form-control-lg form-control-solid w-100" name="paid_amount" v-model="formData.paid_amount" style="max-width: 120px; font-size: 14px; text-align:right"/>
                                                <ErrorMessage :errorMessage="formData.errors.paid_amount" />
                                            </td>
                                            <td></td>
                                        </tr>
                                        <!-- Paid Amount Error -->
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="" style="width: 150px;">
                                                <p v-if="errors.paid_amount" class="text-danger">{{ errors.paid_amount }}</p>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-end">
                                                <label class="fs-5 fw-semibold text-gray-600">{{ $t('sale.label.due') }}</label>
                                            </td>
                                            <td class="text-end d-flex justify-content-end">
                                                {{ dueAmount.toFixed(2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                       <div>
                            <SubmitButton :id="props.sale?.id"/>
                       </div>
                    </div>
                </VForm>
                <div style="margin-left: 32px; margin-top: -63px; margin-bottom: 20px">
                    <DeleteConfirmationButton
                        v-if="checkPermission('can-delete-other-sale')"
                        :obj="sale"
                        confirmRoute="sales.others.destroy"
                        :buttonText="$t('sale.buttonValue.deleteOther')"
                        :showIcon="false"
                        :messageTitle="`${$t('confirmation.sale.deleteOther', { buyer: props?.buyer?.name, saleDate: props?.sale?.sale_date})}`"
                    />
                </div>
            </div>
        </div>
        <UpdateOtherItemModal :item="editItem" :items="allItems" :itemUnits="props?.itemUnits" :brands="allBrands" :sale="props?.sale" @updateOtherSaleItem="updatedOtherSaleItem"></UpdateOtherItemModal>
    </AuthenticatedLayout>
</template>


<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, defineProps, watch, onMounted, onUnmounted, reactive } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Field, Form as VForm } from "vee-validate";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import Multiselect from "@vueform/multiselect";
import { checkPermission } from "@/Core/helpers/Permission";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import UpdateOtherItemModal from "@/Pages/Sale/Modal/UpdateOtherItemModal.vue";
import ConfirmationButton from "@/Components/Button/ConfirmationButton.vue";
import DeleteConfirmationButton from "@/Components/Button/DeleteConfirmationButton.vue";
import i18n from '@/Core/plugins/i18n';
import axios from "axios";

const { t } = i18n.global;

const props = defineProps({
    buyer: Object,
    items: Array as () => IItems[],
    itemList: Array as () => IItems[],
    itemUnits: Object,
    brands: Object,
    sale: Object,
    pageTitle: String,
    breadcrumbs: Array as () => Breadcrumb[],
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IItems {
    id: number;
    item_id: number;
    item_name: string;
    brand_name: string;
    name?:string;
    quantity: number;
    unit_price: number;
    total_price: number;
}

const formattedToday = ref(new Date().toISOString().substr(0, 10));

const formData = useForm({
    buyer_name: props.buyer?.name || '',
    items: props.sale?.sale_items || [],
    paid_amount: props.sale?.paid_amount || '',
    discount: props.sale?.discount || '',
    sale_date: props.sale?.sale_date || formattedToday,
});

const editItem = ref<any>({});
const openEditModal = (sale: any) => {
    editItem.value = sale;
};

const allItems = ref< Array < any >> ([]);
if (Array.isArray(props.items) && props.items.length > 0) {
    allItems.value = props.items.map((item) => ({
        value: item.id,
        text: item.name,
    }));
}

const allBrands = ref< Array < any >> ([]);
if (Array.isArray(props.brands) && props.brands.length > 0) {
    allBrands.value = props.brands.map((brand) => ({
        value: brand.id,
        text: brand.name,
    }));
}

const tableData = ref< IItems[]>([]);
const initItems = ref< IItems[] >([]);

const grandTotal = ref(0);
const dueAmount = ref(0);
const totalAmount = ref(0);

const errors = reactive({
    discount: "",
    paid_amount: ""
});

onMounted(() => {
    if (props.sale) {
        // Populate tableData with sale items
        tableData.value = props.sale?.sale_items.map((sale: any) => {
            const itemName = props.items?.find((item: any) => item.id === sale.item_id);
            const brandName = props.brands?.find((brand: any) => brand.id === sale.brand_id);
            const itemUnit = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === sale.item_id);
            const itemUnitValue = itemUnit ? itemUnit.value : 1;
            const total_price = Math.round((sale.quantity * sale.unit_price) / itemUnitValue);
            return {
                id: sale.id,
                item_id: itemName?.id,
                item_name: itemName?.name || '',
                unit_price: sale.unit_price,
                quantity: sale.quantity,
                total_price: total_price,
            };
        });
    }
});

watch(() => tableData.value, () => {
    totalAmount.value = tableData.value.reduce((sum, item) => sum + (item.total_price || 0), 0);
    grandTotal.value = Math.max(0, totalAmount.value - (Number(formData.discount) || 0));
    dueAmount.value = Math.max(0, grandTotal.value - (Number(formData.paid_amount) || 0));
}, { immediate: true });

watch(() => [formData.discount, formData.paid_amount], () => {
    totalAmount.value = tableData.value.reduce((sum, item) => sum + (item.total_price || 0), 0);
    grandTotal.value = Math.max(0, totalAmount.value - (Number(formData.discount) || 0));
    dueAmount.value = Math.max(0, grandTotal.value - (Number(formData.paid_amount) || 0));
});


onUnmounted(() => {
    // Clean up any resources or reset values if necessary
    tableData.value = [];
    initItems.value = [];
    grandTotal.value = 0;
    dueAmount.value = 0;
    totalAmount.value = 0;
});

const updatedOtherSaleItem = async () => {
    try {
        const response = await axios.get(route('sales.saleItems.index', { sale: props?.sale?.id }));
        tableData.value = response.data;
    } catch (error) {
        console.error('OtherSaleItem Error:', error);
    }
};

const validatedData = () => {
    let valid = true;
    errors.discount = "";
    errors.paid_amount = "";

    // Discount validation
    if (formData.discount !== "" && Number(formData.discount) > totalAmount.value) {
        errors.discount = "Discount can not be greater than total";
        valid = false;
    }

    if (formData.discount !== "" && Number(formData.discount) === 0) {
        errors.discount = "Discount must be greater than 0";
        valid = false;
    }

    if (formData.paid_amount !== "" && Number(formData.paid_amount) > grandTotal.value) {
        errors.paid_amount = "Paid amount can not be greater than Grand total";
        valid = false;
    }

    if (formData.paid_amount !== "" && Number(formData.paid_amount) === 0) {
        errors.paid_amount = "Paid amount must be greater than 0";
        valid = false;
    }

    return valid;
}

const submit = () => {
   if((validatedData())){
        formData.patch(route('sales.others.update', props.sale?.id),{
            onSuccess: () => {
                preserveScroll: true
            },
            onError: (errors) => {
                console.log("Sale Other Update Error:",errors);
            }
        });
    }
}
</script>

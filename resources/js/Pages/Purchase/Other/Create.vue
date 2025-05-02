<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('sale.title.addItem') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Buyer -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-5 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('purchase.label.selectedSupplier') }}</label>
                                <Multiselect
                                    :placeholder="$t('purchase.placeholder.selectedSupplier')"
                                    v-model="formData.supplier_id"
                                    :searchable="true"
                                    label="text"
                                    trackBy="text"
                                    :options="allSuppliers"
                                />
                                <ErrorMessage :errorMessage="formData.errors.supplier_id" />
                            </div>
                            <div class="col-md-5 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('label.date') }}</label>
                                <Field type="date" :placeholder="$t('placeholder.date')" class="form-control form-control-lg form-control-solid" name="purchase_date" v-model="formData.purchase_date"/>
                                <ErrorMessage :errorMessage="formData.errors.purchase_date" />
                            </div>
                            <div class="col-md-2 mt-13 d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <button v-if="checkPermission('can-create-supplier')" type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_supplier_for_purchase" style="width:150px">
                                    <KTIcon icon-name="plus" icon-class="fs-2"/>
                                    {{ $t('purchase.buttonValue.addSupplier') }}
                                </button>
                            </div>

                            <!-- Note -->
                            <div lass="fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t("label.note") }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('placeholder.note')" name="purchase_note" v-model="formData.purchase_note" />
                            </div>
                        </div>
                        <div class="rounded-3 mt-20" style="border: 1px solid #E9EAEC; padding: 10px">
                            <div class="row mb-5 g-4 border-0 cursor-pointer mt-5">
                                <div class="col-md-9 fv-row card-title m-0">
                                    <h3 class="fw-bold m-0">{{ $t('sale.label.items') }}</h3>
                                </div>
                                <div class="col-md-3 fv-row m-0">
                                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                        <button v-if="checkPermission('can-create-other-purchase')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_other_purchase">
                                            <KTIcon icon-name="plus" icon-class="fs-2"/>
                                            {{ $t('sale.buttonValue.addItem') }}
                                        </button>
                                        <!--end::Item-->
                                    </div>
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
                                        <tr v-for="(purchase, index) in tableData" :key="purchase.item_id">
                                            <td class="text-start">{{ purchase.item_name }}</td>
                                            <td class="text-end">{{ purchase.quantity }}</td>
                                            <td class="text-end">{{ purchase.unit_price }}</td>
                                            <td class="text-end">{{ purchase.total_price }}</td>
                                            <td class="text-end">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_other_purchase" @click="openEditModal(purchase)">
                                                        <in class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </in>
                                                    </button>
                                                    <!-- Delete -->
                                                    <button @click="deleteItem(purchase)" type="button" class="btn btn-icon btn-flex btn-active-light-danger w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.delete')">
                                                        <KTIcon icon-name="trash" icon-class="text-danger fs-3"/>
                                                    </button>
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
                        <SubmitButton />
                    </div>
                </VForm>
            </div>
        </div>

        <AddSupplierModal route-type="other"></AddSupplierModal>
        <AddOtherItemModal :items="allItems" @addItem="addItemList" :itemUnits="props?.itemUnits" :brands="allBrands"/>
        <EditOtherItemModal :item="editItem" :items="allItems" :itemUnits="props?.itemUnits" @updateItem="updateItemList" :brands="allBrands"></EditOtherItemModal>
    </AuthenticatedLayout>
</template>


<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, defineProps, watch, onMounted, reactive } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Field, Form as VForm } from "vee-validate";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import Multiselect from "@vueform/multiselect";
import { checkPermission } from "@/Core/helpers/Permission";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import AddSupplierModal from "@/Pages/Supplier/PurchaseSupplierModal/AddSupplierModal.vue";
import AddOtherItemModal from "@/Pages/Purchase/Modal/AddOtherItemModal.vue";
import EditOtherItemModal from "@/Pages/Purchase/Modal/EditOtherItemModal.vue";

import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    suppliers: Object,
    createdSupplierId: Number,
    items: Array as () => IItems[],
    otherItemList: Array as () => IItems[],
    itemUnits: Object,
    brands: Object,
    pageTitle: String,
    breadcrumbs: Array as () => Breadcrumb[],
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IItems {
    id: number;
    name?: string;
    item_id: number;
    item_name: string;
    quantity: number;
    unit_price: number;
    total_price: number;
    note: string;
}

const formattedToday = ref(new Date().toISOString().substr(0, 10));

const formData = useForm<{
    supplier_id: number | null;
    items: IItems[];
    paid_amount: string;
    discount: string;
    purchase_date: string;
    purchase_note: string;
    purchase_type: string;
}>({
    supplier_id: props.createdSupplierId || null,
    items: [],
    paid_amount: '',
    discount: '',
    purchase_date: formattedToday.value,
    purchase_note: '',
    purchase_type: ''
});

const itemList = ref< IItems[] >([]);
const addItemList = (addNewItem: any) =>{
    itemList.value.push(addNewItem);
};

const editItem = ref({});
const openEditModal = (purchase: any) =>{
    editItem.value = purchase;
};

const updateItemList = (purchase: any) => {
    const index = itemList.value.findIndex((item: any) => item.item_id === purchase.item_id);
    itemList.value.splice(index, 1, purchase);
}

const deleteItem = (purchase: any) => {
    const index = itemList.value.findIndex((item: any) => item.item_id === purchase.item_id);

    if(index !== -1){
        itemList.value.splice(index, 1);
        localStorage.removeItem('otherPurchaseItemList');
    }
};

const allSuppliers = ref < Array < any >> ([]);
if (Array.isArray(props.suppliers) && props.suppliers.length > 0) {
    allSuppliers.value = props.suppliers.map((supplier) => ({
        value: supplier.id,
        text: supplier.mobile_number ? `${supplier.name} (${supplier.mobile_number})` : supplier.name,
    }));
}

const allItems = ref< Array < any >> ([]);
if (Array.isArray(props.items) && props.items.length > 0) {
    allItems.value = props.items.map((item) => ({
        value: item.id,
        text: item.name,
    }));
}

const allBrands = ref< Array <any>> ([]);
if (Array.isArray(props.brands) && props.brands.length > 0) {
    allBrands.value = props.brands.map((brand) => ({
        value: brand.id,
        text: brand.name,
    }));
}

const tableData = ref< IItems[]>([]);
const initItems = ref< IItems[] >([]);

watch(itemList, (newItems) => {
    tableData.value = newItems.map((newItem: any) => {
        const itemName = props.items?.find((item: any) => item.id === newItem.item_id);
        const branName = props.brands?.find((brand: any) => brand.id === newItem.brand_id);
        const itemUnit = props.itemUnits?.find((itemUnit: any) => itemUnit.item_id === newItem.item_id);
        const itemUnitValue = itemUnit ? itemUnit.value : 1;
        const total_price = Math.round((newItem.quantity * newItem.unit_price)/itemUnitValue);
        return {
            id: newItem.id,
            item_id: itemName?.id ?? 0,
            item_name: itemName?.name || '',
            unit_price: itemUnit ? newItem.unit_price : newItem.total_price,
            quantity: itemUnit ? newItem.quantity : 1,
            total_price: itemUnit ? total_price : newItem.total_price,
            note: newItem.note
        };
    });

    totalAmount.value = Math.round(tableData.value.reduce((sum, item) => sum + (item.total_price || 0), 0));
    grandTotal.value = Math.max(0, totalAmount.value - (Number(formData.discount) || 0));
    dueAmount.value = grandTotal.value - (Number(formData.paid_amount) || 0);

    localStorage.setItem("otherPurchaseItemList", JSON.stringify(tableData.value));
}, { deep: true });

const grandTotal = ref(0);
const dueAmount = ref(0);
const totalAmount = ref(0);

watch(() => [formData.discount, formData.paid_amount], () => {
    totalAmount.value = Math.round(tableData.value.reduce((sum, item) => sum + (item.total_price || 0), 0));
    grandTotal.value = Math.max(0, totalAmount.value - (Number(formData.discount) || 0));
    dueAmount.value = Math.max(0, grandTotal.value - (Number(formData.paid_amount) || 0));
});

onMounted(() => {
    const storedData = localStorage.getItem("otherPurchaseItemList");
    if (storedData) {
        var parsed = JSON.parse(storedData);
        itemList.value = parsed;
        tableData.value = parsed;
    }
});

const errors = reactive({
    discount: "",
    paid_amount: ""
});

const validatedData = () => {
    let valid = true;

    errors.discount = "";
    errors.paid_amount = "";

    // Discount validation
    if(formData.discount !== "" && Number(formData.discount) > totalAmount.value) {
        errors.discount = "Discount can not be greater than total";
        valid = false;
    }

    if(formData.discount !== "" && Number(formData.discount) === 0) {
        errors.discount = "Discount must be greater than 0";
        valid = false;
    }

    // Paid amount validation
    if(formData.paid_amount !== "" && Number(formData.paid_amount) > grandTotal.value) {
        errors.paid_amount = "Paid amount can not be greater than Grand total";
        valid = false;
    }

    if(formData.paid_amount !== "" && Number(formData.paid_amount) === 0) {
        errors.paid_amount = "Paid amount must be greater than 0";
        valid = false;
    }

    return valid;
}

const submit = () =>{
    if(validatedData()){
        formData.items = itemList.value;
        formData.purchase_type = "other";
        formData.post(route("purchases.others.store"), {
            onSuccess: () => {
                localStorage.removeItem('otherPurchaseItemList');
                formData.reset();
            },
            onError: (errors) => {
                console.log("Form Submission Error:",errors);
            },
        });
    }
}
</script>

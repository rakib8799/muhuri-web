<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <ConfigurationHeader activeLink="BasicInfo" :configuration="props?.configuration"></ConfigurationHeader>

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">

            <VForm @submit="submit()" class="form">
                <!--begin::Card header-->
                <div class="card-header cursor-pointer">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">{{ $t('configuration.header.title.basicInformation') }}</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div>
                        <!-- Company Name (English) -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.companyName') }}</label>
                            <Field type="text" :placeholder="$t('configuration.placeholder.companyName')" name="name" class="form-control form-control-lg form-control-solid" v-model="formData.name"/>
                            <ErrorMessage :errorMessage="formData.errors.name"/>
                        </div>

                        <!-- Company Name (Bangla) -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.companyNameBangla') }}</label>
                            <Field type="text" :placeholder="$t('configuration.placeholder.companyNameBangla')" name="name_bn" class="form-control form-control-lg form-control-solid" v-model="formData.name_bn"/>
                            <ErrorMessage :errorMessage="formData.errors.name_bn"/>
                        </div>

                            <!-- Category Name -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.categoryName') }}</label>
                            <Field type="text" name="category" class="form-control form-control-lg form-control-solid" v-model="formData.category" readOnly/>
                            <ErrorMessage :errorMessage="formData.errors.category"/>
                        </div>

                        <!-- Trade License Number -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.tradeLicenseNumber') }}</label>
                            <Field type="text" :placeholder="$t('configuration.placeholder.tradeLicenseNumber')" name="trade_license_number" class="form-control form-control-lg form-control-solid" v-model="formData.trade_license_number"/>
                            <ErrorMessage :errorMessage="formData.errors.trade_license_number"/>
                        </div>

                        <!-- Country -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.country') }}</label>
                            <Multiselect
                                :placeholder="$t('configuration.placeholder.country')"
                                v-model="formData.country_id"
                                :searchable="true"
                                :options="allCountries"
                                label="name"
                                trackBy="name"
                            />
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!-- Submit Button-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <SubmitButton :buttonValue="$t('buttonValue.update')" />
                </div>
            </VForm>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfigurationHeader from '@/Pages/Configuration/ConfigurationHeader.vue';
import {Field, Form as VForm} from "vee-validate";
import {useForm} from '@inertiajs/vue3';
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { ref } from 'vue';
import Multiselect from '@vueform/multiselect';

const props = defineProps({
    countries: Object,
    configuration: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    name: props.configuration?.name || '',
    name_bn: props.configuration?.name_bn || '',
    category: props.configuration?.category || '',
    trade_license_number: props.configuration?.trade_license_number || '',
    country_id: props.configuration?.country_id || ''
});

// assign all the countries from props to allCountries variable.
const allCountries = ref<Array<any>>([]);
if (Array.isArray(props.countries) && props.countries.length > 0) {
    allCountries.value = props.countries.map((country: { id: any; name: any; }) => ({value: country.id, name:country.name}));
}

const submit = () => {
    formData.patch(route('configurations.updateBasicInfo', props.configuration?.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeHeader activeLink="AdditionalInfo" :id="`${props.employee?.id}`" :employee="props?.employee" :departureReasons="props?.departureReasons"></EmployeeHeader>

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('employee.header.additionalInfo.edit') }}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <VForm @submit="submit()" class="form">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div class="mb-5">
                        <!-- User Name-->
                        <div class="row mb-5">
                            <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.userName') }}</label>
                            <Multiselect
                                :placeholder="$t('employee.placeholder.userName')"
                                v-model="formData.user_id"
                                :searchable="true"
                                :options="allUsers"
                                label="name"
                                trackBy="name"
                            />
                        </div>

                        <!-- Country -->
                        <div class="row mb-5">
                            <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.country') }}</label>
                            <Multiselect
                                :placeholder="$t('employee.placeholder.country')"
                                v-model="formData.country_id"
                                :searchable="true"
                                :options="allCountries"
                                label="name"
                                trackBy="name"
                            />
                        </div>
                    </div>

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Permanent Address -->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('employee.label.permanentAddress') }}</h3>
                        <div class="d-flex flex-column fv-row">
                            <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.address') }}</label>
                            <Field name="permanent_address">
                                <textarea v-model="formData.permanent_address" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.address')"/>
                            </Field>
                            <ErrorMessage :errorMessage="formData.errors.permanent_address" />
                        </div>
                    </div>

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Present Address -->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('employee.label.presentAddress') }}</h3>
                        <Address
                            v-model:address_line_one="address_line_one"
                            v-model:address_line_two="address_line_two"
                            v-model:floor="floor"
                            v-model:city="city"
                            v-model:state="state"
                            v-model:zip_code="zip_code"
                            :errors="formData.errors"
                        ></Address>
                    </div>

                    <div class="separator separator-dashed mt-5"></div>

                    <!-- Emergency Contact Name, Relation and Number-->
                    <div>
                        <h3 class="text-dark font-weight-bold mt-7 mb-5">{{ $t('employee.label.emergencyContact.title') }}</h3>
                        <div class="row mb-5 g-4">
                            <!-- Name -->
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.emergencyContact.name') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.emergencyContact.name')" name="emergency_contact_name" v-model="formData.emergency_contact_name"/>
                                <ErrorMessage :errorMessage="formData.errors.emergency_contact_name" />
                            </div>

                            <!-- Relation -->
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.emergencyContact.relation') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.emergencyContact.relation')"  name="emergency_contact_relation" v-model="formData.emergency_contact_relation"/>
                                <ErrorMessage :errorMessage="formData.errors.emergency_contact_relation" />
                            </div>

                            <!-- Number -->
                            <div class="col-md-4 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('employee.label.emergencyContact.number') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('employee.placeholder.emergencyContact.number')" name="emergency_contact_number" v-model="formData.emergency_contact_number"/>
                                <ErrorMessage :errorMessage="formData.errors.emergency_contact_number" />
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!-- Submit Button-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <SubmitButton :id="props.employee?.id" />
                </div>
            </VForm>
        </div>
    </AuthenticatedLayout>
</template>


<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmployeeHeader from '@/Pages/Employee/EmployeeHeader.vue';
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import Address from "@/Components/Address.vue";
import { Field, Form as VForm } from "vee-validate";
import { ref, watch } from "vue";
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect';

const props = defineProps({
    users: Object,
    countries: Object,
    employee: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
    departureReasons: Object,
});

interface Breadcrumb {
    url: string;
    title: string;
}

// Fields of Address.vue
const address_line_one = ref(props.employee?.address_line_one || '');
const address_line_two = ref(props.employee?.address_line_two || '');
const floor = ref(props.employee?.floor || '');
const city = ref(props.employee?.city || '');
const state = ref(props.employee?.state || '');
const zip_code = ref(props.employee?.zip_code || '');

const formData = useForm({
    user_id: props.employee?.user_id || '',
    permanent_address: props.employee?.permanent_address || '',
    address_line_one: address_line_one.value,
    address_line_two: address_line_two.value,
    floor: floor.value,
    city: city.value,
    state: state.value,
    zip_code: zip_code.value,
    country_id: props.employee?.country_id || '',
    emergency_contact_name: props.employee?.emergency_contact_name || '',
    emergency_contact_relation: props.employee?.emergency_contact_relation || '',
    emergency_contact_number: props.employee?.emergency_contact_number || '',
});

// watcher to update values received from Address.vue
watch([address_line_one, address_line_two, floor, city, state, zip_code], ([address_line_one, address_line_two, floor, city, state, zip_code]) => {
    formData.address_line_one = address_line_one;
    formData.address_line_two = address_line_two;
    formData.floor = floor;
    formData.city = city;
    formData.state = state;
    formData.zip_code = zip_code;
});

// assign all the users from props to allUsers variable.
const allUsers = ref<Array<any>>([]);
if (Array.isArray(props.users) && props.users.length > 0) {
    allUsers.value = props.users.map(user => ({value: user.id, name:user.name}));
}

// assign all the countries from props to allCountries variable.
const allCountries = ref<Array<any>>([]);
if (Array.isArray(props.countries) && props.countries.length > 0) {
    allCountries.value = props.countries.map(country => ({value: country.id, name:country.name}));
}

const submit = () => {
    formData.patch(route('employees.updateAdditionalInfo', props.employee?.id), {
        preserveScroll: true,
    });
};
</script>

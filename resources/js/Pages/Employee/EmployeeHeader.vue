<template>
    <!--begin::Navbar-->
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <!--begin::Details-->
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3 align-items-center">
                <!--begin: Pic-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img :src="getAssetPath('media/svg/avatars/blank.svg')" alt="image"/>
                        <div
                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border-4 border-white h-20px w-20px">
                        </div>
                    </div>
                </div>
                <!--end::Pic-->

                <!--begin::Info-->
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::User-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bold me-1">
                                    {{ getFullName(props.employee) }}
                                </a>
                                <a href="#">
                                    <KTIcon icon-name="verify" icon-class="fs-1 text-primary"/>
                                </a>
                            </div>
                            <!--end::Name-->

                            <!--begin::Info-->
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="#"
                                    class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                    <KTIcon icon-name="profile-circle" icon-class="fs-4 me-1"/>
                                    {{ props.employee?.job_title }}
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                    <KTIcon icon-name="sms" icon-class="fs-4 me-1"/>
                                    {{ props.employee?.email }}
                                </a>
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::User-->
                        <div class="d-flex align-items-center">
                            <!-- Active Status -->
                            <div class="form-check form-check-solid form-switch">
                                <ChangeStatusButton v-if="checkPermission('can-edit-employee')" :obj="props?.employee" confirmRoute="employees.changeStatus"/>
                            </div>
                            <!-- Terminate / Rejoin -->
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="hover" data-kt-menu-placement="bottom-end">
                                <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                            </button>

                            <!--begin::Menu 3-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                                <!--begin::Heading-->
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        {{ $t('employee.header.departureInfo.departed') }}
                                    </div>
                                </div>
                                <!--end::Heading-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_departure_info" v-if="!props.employee?.is_departed">
                                        {{ $t('employee.header.departureInfo.terminate') }}
                                    </a>
                                    <a href="#" class="menu-link px-3" type="button" v-else @click="submit">
                                        {{ $t('employee.header.departureInfo.rejoin') }}
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu sub-->
                        </div>
                        <!-- end::Menu -->
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Info-->
            </div>
            <!--end::Details-->

            <!--begin::Navs-->
            <div class="d-flex overflow-auto h-55px">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
                    <li class="nav-item">
                        <Link :href="route('employees.show', props.id)" :class="{ active: isActive('Details') }"
                            class="nav-link text-active-primary me-6">{{ $t('employee.header.details') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="!props.employee?.is_departed && checkPermission('can-edit-employee')">
                        <Link :href="route('employees.basicInfo', props.id)" :class="{ active: isActive('BasicInfo') }"
                            class="nav-link text-active-primary me-6">{{ $t('employee.header.basicInfo.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="!props.employee?.is_departed && checkPermission('can-edit-employee')">
                        <Link :href="route('employees.additionalInfo', props.id)" class="nav-link text-active-primary me-6"
                            :class="{ active: isActive('AdditionalInfo') }">{{ $t('employee.header.additionalInfo.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="!props.employee?.is_departed && checkPermission('can-edit-employee')">
                        <Link :href="route('employees.workInfo', props.id)" class="nav-link text-active-primary me-6"
                            :class="{ active: isActive('WorkInfo') }">{{ $t('employee.header.workInfo.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="props.employee?.is_departed && checkPermission('can-edit-employee')">
                        <Link :href="route('employees.departureInfo', props.id)" class="nav-link text-active-primary me-6"
                            :class="{ active: isActive('DepartureInfo') }">{{ $t('employee.header.departureInfo.title') }}
                        </Link>
                    </li>
                </ul>
            </div>
            <!--end::Navs-->
        </div>
    </div>
    <slot></slot>
    <!--end::Navbar-->
    <DepartureModal :employee="props.employee" :departureReasons="props.departureReasons"></DepartureModal>
</template>

<script lang="ts" setup>
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import DepartureModal from './DepartureModal.vue'
import {getAssetPath} from "@/Core/helpers/Assets";
import {Link, router, useForm, usePage} from '@inertiajs/vue3'
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import {getFullName} from '@/Core/helpers/Helper';
import toastr from 'toastr';
import 'toastr/toastr.scss';

const props = defineProps({
    activeLink: String,
    id: String,
    employee: Object,
    departureReasons: Object
});

const headerActiveLink = props.activeLink;

const isActive = (linkName: String) => {
    return headerActiveLink == linkName;
};

const formData = useForm({
    departure_reason_id: null,
    departure_description: null,
    departure_date: null
});

const submit = () => {
    formData.patch(route('employees.processEmployeeRejoin', props.employee?.id), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('employees.show', props.employee?.id), { replace: true });
            const flash = usePage().props.flash;
            let {success} = flash as any;

            if (flash && success) {
                toastr.success(success);
            }
        }
    })
}
</script>

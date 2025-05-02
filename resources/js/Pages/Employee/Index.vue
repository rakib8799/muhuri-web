<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('commonComponents.employeeComponent.placeholder.searchEmployees')" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                    <!-- Start: Filter -->
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>{{ $t('buttonValue.filter') }}
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-600px w-md-625px" data-kt-menu="true">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">{{ $t('filterOptions.title') }}</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->

                            <!--begin::Content-->
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div class="row">
                                    <!-- Start: Job Position filter -->
                                    <div class="mb-10 col">
                                        <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.employee.subtitle.label.jobPosition') }}:</label>
                                        <Multiselect
                                            :placeholder="$t('filterOptions.employee.subtitle.placeholder.jobPosition')"
                                            mode="tags"
                                            v-model="selectedJobPosition"
                                            :searchable="true"
                                            :options="allJobPositions"
                                            label="text"
                                            trackBy="text"
                                        />
                                    </div>
                                    <!-- End: Job Position filter -->
                                </div>

                                <div class="row">
                                    <!-- Start: Status filter -->
                                    <div class="mb-10 col">
                                        <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.employee.subtitle.label.status') }}:</label>
                                        <Multiselect
                                            :placeholder="$t('filterOptions.employee.subtitle.placeholder.status')"
                                            v-model="selectedStatus"
                                            :searchable="true"
                                            :options="allStatus"
                                        />
                                    </div>
                                    <!-- End: Status filter -->
                                </div>

                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset" @click="applyReset()">{{ $t('buttonValue.reset') }}</button>
                                    <button class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" @click="applyFilter()">{{ $t('buttonValue.apply') }}</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--End: Filter-->
                        <!--begin::Add Employee-->
                        <Link v-if="checkPermission('can-create-employee')" :href="route('employees.create')" class="btn btn-primary">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                                {{ $t('employee.header.add') }}
                        </Link>
                        <!--end::Add Employee-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Employee Name -->
                    <template v-slot:first_name="{ row: employee }" >
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <Link v-if="checkPermission('can-view-details-employee')" :href="route('employees.show', employee.id)">
                                    <div class="symbol-label">
                                        <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                        <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getInitials(employee) }}</div>
                                    </div>
                                </Link>
                                <div v-else class="symbol-label">
                                    <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                    <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getInitials(employee) }}</div>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <Link v-if="checkPermission('can-view-details-employee')" :href="route('employees.show', employee.id)" class="text-gray-800 text-hover-primary mb-1">
                                    {{ getFullName(employee) }}
                                </Link>
                                <span v-else>{{ getFullName(employee) }}</span>
                                <span class = "text-gray-600">{{ employee.email }}</span>
                            </div>
                        </div>
                    </template>

                    <!-- Employee ID -->
                    <template v-slot:staff_id="{ row: employee }">
                        {{ employee.staff_id }}
                    </template>

                    <!-- Employee Job Title -->
                    <template v-slot:job_title="{ row: employee }">
                        {{ employee.job_title }}
                    </template>

                    <!-- Employee Active Status -->
                    <template v-slot:is_active="{ row: employee }">
                        <span class="badge" :class="{ 'badge-success': employee?.is_active, 'badge-danger': !employee?.is_active }">
                            {{ employee?.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </template>

                    <!-- Employee Joining Date -->
                    <template v-slot:joining_date="{ row: employee }">
                        {{ employee.joining_date }}
                    </template>

                    <!-- Employee Last Login -->
                    <template v-slot:last_login_at="{ row: employee }">
                        <div class="badge badge-light fw-bold">{{ employee.user.last_login_at }}</div>
                    </template>

                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, defineProps } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import { Link } from '@inertiajs/vue3';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import { getFullName, getInitials } from '@/Core/helpers/Helper';
import Multiselect from '@vueform/multiselect';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    employees: Object as() => IEmployee[] | undefined,
    jobPositions: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IUser {
    last_login_at: string;
}

interface IEmployee {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    staff_id: string;
    job_title: string;
    joining_date: string;
    user: IUser;
    job_positions: string[];
    is_active: boolean;
}

const tableHeader = ref([{
        columnName: t('label.name'),
        columnLabel: "first_name",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('commonComponents.employeeComponent.label.employeeId'),
        columnLabel: "staff_id",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('commonComponents.employeeComponent.label.jobTitle'),
        columnLabel: "job_title",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('label.status'),
        columnLabel: "is_active",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('commonComponents.employeeComponent.label.joiningDate'),
        columnLabel: "joining_date",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('commonComponents.employeeComponent.label.lastLogin'),
        columnLabel: "last_login_at",
        sortEnabled: true,
        columnWidth: 130
    },
]);

const tableData = ref < IEmployee[] > ([]);
const initEmployees = ref < IEmployee[] > ([]);

onMounted(() => {
    if (props.employees) {
        initEmployees.value = props.employees.map(employee => ({
            id: employee.id,
            first_name: employee.first_name,
            last_name: employee.last_name,
            email: employee.email,
            staff_id: employee.staff_id,
            job_title: employee.job_title,
            joining_date: employee.joining_date,
            user: {
                last_login_at: employee?.user.last_login_at,
            },
            job_positions: employee.job_positions,
            is_active: employee.is_active
        }));
        tableData.value = initEmployees.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initEmployees.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter(item => searchingFunc(item, search.value));
    }
    MenuComponent.reinitialization();
};

const searchingFunc = (obj: any, value: string): boolean => {
    for (let key in obj) {
        if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
            if (obj[key].includes(value)) {
                return true;
            }
        }
    }
    return false;
};

const selectedJobPosition = ref([]);
const selectedStatus = ref<string>('');

// assign all the jobPositions from props to allJobPositions variable.
const allJobPositions = ref<Array<any>>([]);
if (Array.isArray(props.jobPositions) && props.jobPositions.length > 0) {
    allJobPositions.value = props.jobPositions.map(jobPosition => ({value: jobPosition.id, text:jobPosition.name}));
}

// all Status
const allStatus = ['Active', 'Inactive'];

const applyFilter = () => {
    const jobPositionFilter = selectedJobPosition.value;
    const statusFilter = selectedStatus.value;

    if (jobPositionFilter.length == 0 && statusFilter == '') {
        // If nothing is selected, show all employees
        tableData.value = initEmployees.value;
    } else {
        if(jobPositionFilter.length > 0 && statusFilter != '') {
            // Filter employees based on selected department, job positions and status
            const isActive = statusFilter == 'Active' ? true : false;
            tableData.value = initEmployees.value.filter(employee => {
                return jobPositionFilter?.some((jobPosition: any) => {
                    return employee.job_positions?.some((employeeJobPosition: any) => {
                        return employeeJobPosition == jobPosition;
                    });
                });
            });
        }
        else if(jobPositionFilter.length > 0 && statusFilter == '') {
            // Filter employees based on selected department, job positions
            tableData.value = initEmployees.value.filter(employee => {
                return jobPositionFilter?.some((jobPosition: any) => {
                    return employee.job_positions?.some((employeeJobPosition: any) => {
                        return employeeJobPosition == jobPosition;
                    });
                });
            });
        }
        else if(jobPositionFilter.length > 0 && statusFilter == '') {
            // Filter employees based on selected department and job positions
            tableData.value = initEmployees.value.filter(employee => {
                return jobPositionFilter?.some((jobPosition: any) => {
                    return employee.job_positions?.some((employeeJobPosition: any) => {
                        return employeeJobPosition == jobPosition;
                    });
                });
            });
        }
        else if(jobPositionFilter.length > 0 && statusFilter == '') {
            // Filter employees based on selected job positions
            tableData.value = initEmployees.value.filter(employee => {
                return jobPositionFilter?.some((jobPosition: any) => {
                    return employee.job_positions?.some((employeeJobPosition: any) => {
                        return employeeJobPosition == jobPosition;
                    });
                });
            });
        }
        else if(jobPositionFilter.length > 0 && statusFilter == '') {
            // Filter employees based on selected job positions
            tableData.value = initEmployees.value.filter(employee => {
                return jobPositionFilter?.some((jobPosition: any) => {
                    return employee.job_positions?.some((employeeJobPosition: any) => {
                        return employeeJobPosition == jobPosition;
                    });
                });
            });
        }
        else {
            // Filter employees based on selected status
            const isActive = statusFilter == 'Active' ? true : false;
            tableData.value = initEmployees.value.filter(employee => employee.is_active == isActive);
        }
    }
};

const applyReset = () => {
    selectedJobPosition.value = [];
    selectedStatus.value = '';
    tableData.value = initEmployees.value;
}

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse
        });
    }
};
</script>

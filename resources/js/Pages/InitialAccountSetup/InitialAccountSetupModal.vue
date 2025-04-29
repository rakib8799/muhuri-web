<template>
    <div class="modal fade" id="kt_modal_add_branch_job_position" ref="addBranchJobPositionModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header d-flex justify-content-between">
                    <!--begin::Title-->
                    <h2>Initial Account Setup</h2>
                    <!--end::Title-->
                    <!--begin::Close-->
                    <div v-if="currentStep === 3" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <div class="modal-body py-lg-10 px-lg-10">
                    <!--begin::Stepper-->
                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
                        <!--begin::Aside-->
                        <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px align-items-center">
                            <!--begin::Nav-->
                            <div class="stepper-nav ps-lg-10">
                                <!--begin::Step 1-->
                                <div class="stepper-item" :class="currentStep === 1 ? 'current' : ''" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">1</span>
                                        </div>
                                        <!--end::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Branch</h3>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Line-->
                                    <div class="stepper-line h-40px"></div>
                                    <!--end::Line-->
                                </div>
                                <!--end::Step 1-->
                                <!--begin::Step 2-->
                                <div class="stepper-item" :class="currentStep === 2 ? 'current' : ''" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">2</span>
                                        </div>
                                        <!--begin::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Job Position</h3>
                                        </div>
                                        <!--begin::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Line-->
                                    <div class="stepper-line h-40px"></div>
                                    <!--end::Line-->
                                </div>
                                <!--end::Step 2-->
                                <!--begin::Step 3-->
                                <div class="stepper-item" :class="currentStep === 3 ? 'current' : ''" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">3</span>
                                        </div>
                                        <!--begin::Icon-->
                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Completed</h3>
                                        </div>
                                        <!--begin::Label-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Step 3-->
                            </div>
                            <!-- end::Nav -->
                        </div>
                        <!-- end::Aside -->
                        <!--begin::Content-->
                        <div class="flex-row-fluid py-lg-5 px-lg-15">
                            <!--begin::Form-->
                            <VForm @submit="submit()" :model="jobPositionData" ref="formRef" class="mx-auto mw-600px w-100 py-10" id="kt_modal_add_branch_job_position_form">
                                <!--begin::Step 1-->
                                <div v-if="currentStep === 1">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--begin::Heading-->
                                        <div class="pb-10 pb-lg-15">
                                            <!--begin::Title-->
                                            <h2 class="fw-bold text-gray-900">Setup Info</h2>
                                            <!--end::Title-->
                                            <!--begin::Notice-->
                                            <div class="text-muted fw-semibold fs-6">Please create both the branch and job position to continue.
                                            <!-- <a href="#" class="link-primary fw-bold">Help Page</a> -->
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        <!--end::Heading-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label mb-3">
                                                {{ $t('branch.label.name') }}
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('branch.placeholder.name')" name="name" v-model="branchData.name"/>
                                            <ErrorMessage :errorMessage="branchData.errors.name" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->

                                        <button @click="addBranch" type="button" class="btn btn-light-primary">
                                            Add More Branch
                                        </button>

                                        <div class="mt-10" v-if="addedBranches.length">
                                            <h5>Added Branches</h5>
                                            <ul>
                                                <li v-for="branch in addedBranches" :key="branch?.id">{{ branch?.name }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Step 1-->
                                <!--begin::Step 2-->
                                <div v-if="currentStep === 2">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
                                        <!--begin::Heading-->
                                        <div class="pb-10 pb-lg-15">
                                            <!--begin::Title-->
                                            <h2 class="fw-bold text-gray-900">Setup Info</h2>
                                            <!--end::Title-->
                                            <!--begin::Notice-->
                                            <div class="text-muted fw-semibold fs-6">Please create both the branch and job position to continue.
                                            <!-- <a href="#" class="link-primary fw-bold">Help Page</a>. -->
                                        </div>
                                            <!--end::Notice-->
                                        </div>
                                        <!--end::Heading-->

                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label mb-3">{{ $t('jobPosition.label.name') }}</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <Field type="text" :placeholder="$t('jobPosition.placeholder.name')" class="form-control form-control-lg form-control-solid" name="name" v-model="jobPositionData.name"/>
                                            <ErrorMessage :errorMessage="jobPositionData.errors.name" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->

                                        <button @click="addJobPosition" type="button" class="btn btn-light-primary">
                                            Add More Job Position
                                        </button>

                                        <div class="mt-10" v-if="addedJobPositions.length">
                                            <h5>Added Job Positions</h5>
                                            <ul>
                                                <li v-for="job in addedJobPositions" :key="job?.id">{{ job?.name }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Step 2-->

                                <!--begin::Step 3-->
                                    <div v-if="currentStep === 3">
                                        <!--begin::Wrapper-->
                                        <div class="w-100">
                                            <!--begin::Heading-->
                                            <div class="pb-8 pb-lg-10 text-center">
                                                <!--begin::Title-->
                                                <h2 class="fw-bold text-gray-900">You Are Done!</h2>
                                                <!--end::Title-->
                                                <!--begin::Notice-->
                                                <div class="text-muted fw-semibold fs-6">You can now create employees by using this link
                                                <a :href="route('employees.create')">Create Employee</a>.</div>
                                                <!--end::Notice-->
                                            </div>
                                            <!--end::Heading-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Step 3-->

                                <!--begin::Actions-->
                                <div class="d-flex flex-stack pt-15">
                                    <!--begin::Wrapper-->
                                    <div class="mr-2">
                                        <button v-if="currentStep === 2" @click="prevStep" class="btn btn-secondary">Back</button>
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Wrapper-->
                                    <div>
                                        <button @click="nextStep" type="button" class="btn btn-success" v-if="currentStep < 2">
                                            Next
                                        </button>
                                        <SubmitButton v-if="currentStep === 2" buttonValue="Submit" />
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Actions-->
                            </VForm>
                        </div>
                        <!-- end::Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { onMounted, ref, watch } from "vue";
import { Field, Form as VForm } from "vee-validate";
import { useForm } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import axios from "axios";

const formRef = ref<null | HTMLFormElement>(null);
const addBranchJobPositionModalRef = ref<any | HTMLElement>(null);

const props = defineProps({
    totalBranch: Number,
    totalJobPosition: Number
});

const branchData = useForm({
    name: ''
});

const jobPositionData = useForm({
    name: ''
});

interface Branch {
    id: number;
    name: string;
}

interface JobPosition {
    id: number;
    name: string;
}

const currentStep = ref(1);
const addedBranches = ref<Branch[]>([]);
const addedJobPositions = ref<JobPosition[]>([]);

const loadAddedBranches = async () => {
    try {
        const response = await axios.get('/branches/get-branches');
        addedBranches.value = response.data?.data?.branches as Branch[];
    } catch (error) {
        console.error('Failed to load branches:', error);
    }
};

const loadAddedJobPositions = async () => {
    try {
        const response = await axios.get('/job-positions/get-job-positions');
        addedJobPositions.value = response.data?.data?.jobPositions as JobPosition[];
    } catch (error) {
        console.error('Failed to load job positions:', error);
    }
};

const addBranch = () => {
    branchData.post(route('branches.storeBranch'), {
        preserveScroll: true,
        onSuccess: () => {
            branchData.reset();
            loadAddedBranches(); 
        }
    });
};

const addJobPosition = () => {
    jobPositionData.post(route('job-positions.storeJobPosition'), {
        preserveScroll: true,
        onSuccess: () => {
            jobPositionData.reset();
            loadAddedJobPositions(); 
        }
    });
};

watch(currentStep, (newStep) => {
    localStorage.setItem('currentStep', newStep.toString());
    if (currentStep.value === 3) {
        localStorage.removeItem('currentStep');
    }
});

const nextStep = () => {
    const branchExists = addedBranches.value.some(branch => branch.name === branchData.name);

    if (currentStep.value === 1) {
        if ((!branchData.name && addedBranches.value.length > 0) || (branchData.name && addedBranches.value.length === 0) || (branchData.name && addedBranches.value.length > 0 && !branchExists)) {
            currentStep.value++;
        }
        addBranch();
    }
};

const prevStep = () => {
    currentStep.value--;
    branchData.errors = {};
    loadAddedBranches();
}

const showModal = () => {
    if (addBranchJobPositionModalRef.value) {
        const modal = new Modal(addBranchJobPositionModalRef.value, {
            backdrop: 'static',
            keyboard: false 
        });
        modal.show();
    }
};

onMounted(() => {
    loadAddedBranches();
    loadAddedJobPositions();

    const savedStep = localStorage.getItem('currentStep');
    if (savedStep) {
        currentStep.value = parseInt(savedStep);
    }

    if ((props.totalBranch === 0 || props.totalJobPosition === 0) || (props.totalBranch !== 0 && props.totalJobPosition !== 0 && currentStep.value !== 1)) {
        showModal();
    }
});

const submit = async () => {
    const jobPositionExists = addedJobPositions.value.some(jobPosition => jobPosition.name === jobPositionData.name);

    if (currentStep.value === 2) {
        if ((!jobPositionData.name && addedJobPositions.value.length > 0) || (jobPositionData.name && addedJobPositions.value.length === 0) || (jobPositionData.name && addedJobPositions.value.length !== 0 && !jobPositionExists)) {
            currentStep.value++;
        }
        addJobPosition();
    } 
};
</script>


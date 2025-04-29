<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="d-flex flex-column min-vh-100">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center text-center p-10">
                <!--begin::Wrapper-->
                <div class="card card-flush w-lg-650px py-5 shadow">
                    <div class="card-body py-15 py-lg-20">
                        <!--begin::Title-->
                        <h1
                            class="fw-bolder fs-2hx mb-4"
                            :class="status === 'success' ? 'text-success' : 'text-danger'"
                        >
                            {{ title }}
                        </h1>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <div class="fw-semibold fs-2 text-gray-500 mb-7">
                            {{ message }}
                        </div>
                        <!--end::Text-->
                        <div class="fw-semibold fs-3 text-gray-500 mb-3" v-if="props?.paymentID">
                            Transaction ID: <span class="text-dark">{{ props?.paymentID }}</span>
                        </div>
                        <button
                            @click="goBack"
                            class="btn btn-lg mt-4"
                            :class="status === 'success' ? 'btn-success' : 'btn-danger'"
                        >
                            {{ buttonText }}
                        </button>
                    </div>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { defineProps, computed } from "vue";
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    paymentID: String,
    status: String,
    message: String,
    redirectRoute: { type: String, required: true },
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
    buttonText: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const status = computed(() => props.status || "failed");
const message = computed(() => props.message || "Something went wrong.");

const title = computed(() =>
    status.value === "success" ? "Payment Successful" : "Payment Unsuccessful"
);

const goBack = () => {
    router.visit(route(props.redirectRoute));
};
</script>

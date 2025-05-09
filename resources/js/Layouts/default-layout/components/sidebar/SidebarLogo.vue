<template>
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="">
            <div class="app-sidebar-logo-default d-flex align-items-center">
                <img
                    v-if="
                    layout === 'dark-sidebar' ||
                    (themeMode === 'dark' && layout === 'light-sidebar')
                    "
                    alt="Logo"
                    :src="getLogoSrc(logo)"
                    class="app-sidebar-logo-default" style="height: 15vh;"
                />
                <img
                    v-if="themeMode === 'light' && layout === 'light-sidebar'"
                    alt="Logo"
                    :src="getLogoSrc(logo)"
                    class="app-sidebar-logo-default" style="height: 15vh;"
                />
                <a href="/" class="d-flex flex-column">
                    <span class="m-3 lead fw-bold text-gray-800 app-sidebar-logo-default py-0 my-0">{{ caplitalize(workspace) }}</span>
                    <span class="m-3 text-blue-800 app-sidebar-logo-default py-0 my-0">{{ workspaceDomain }}</span>
                </a>
            </div>

            <div class="shadow-sm rounded-lg app-sidebar-logo-minimize">
                <img
                    alt="Logo"
                    :src="getLogoSrc(logo)"
                    class="app-sidebar-logo-minimize" style="height: 5vh;"
                />
            </div>
        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <div
            v-if="sidebarToggleDisplay"
            ref="toggleRef"
            id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-primary btn-sm btn-color-dark btn-active-color-dark w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize"
        >
            <KTIcon icon-name="black-left-line" icon-class="fs-3 rotate-180 ms-1" />
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import { ToggleComponent } from "@/Assets/ts/components";
import {
    layout,
    sidebarToggleDisplay,
    themeMode,
} from "@/Layouts/default-layout/config/helper";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { usePage } from '@inertiajs/vue3';
import { caplitalize, getLogoSrc } from '@/Core/helpers/Helper';

const workspace = usePage().props.workspace;
const workspaceDomain = usePage().props.workspace_domain;
const logo = usePage().props.logo;
const toggled = ref(true);

interface IProps {
    sidebarRef: HTMLElement | null;
}

const props = defineProps<IProps>();

const toggleRef = ref<HTMLFormElement | null>(null);

onMounted(() => {
    setTimeout(() => {
        const toggleObj = ToggleComponent.getInstance(
        toggleRef.value!
        ) as ToggleComponent | null;

        if (toggleObj === null) {
        return;
        }

        // Add a class to prevent sidebar hover effect after toggle click
        toggleObj.on("kt.toggle.change", function () {
        // Set animation state
        props.sidebarRef?.classList.add("animating");
        toggled.value = !toggled.value;
        // Wait till animation finishes
        setTimeout(function () {
            // Remove animation state
            props.sidebarRef?.classList.remove("animating");
        }, 300);
        });
    }, 1);
    });
</script>

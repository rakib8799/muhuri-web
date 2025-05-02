<template>
    <button @click="showDeclineConfirmation" class="btn btn-icon btn-flex btn-active-light-danger w-30px h-30px" data-bs-toggle="tooltip" title="Decline">
        <KTIcon icon-name="cross-circle" :icon-class="'text-danger ' + (iconClass ? iconClass : 'fs-3')"/>
    </button>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import Swal from 'sweetalert2';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import i18n from '@/Core/plugins/i18n';
import { router } from '@inertiajs/vue3';

const { t } = i18n.global;

const props = defineProps({
    obj: Object,
    confirmRoute: String,
    iconClass: String,
    title: String,
});

const showDeclineConfirmation = async () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    const result = await swalWithBootstrapButtons.fire({
        title: props?.title,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${t('buttonValue.confirm')}!`,
        cancelButtonText: `${t('buttonValue.cancel')}`,
        reverseButtons: true
    });

    if (result.isConfirmed) {
        router.visit(route(props?.confirmRoute as string, props.obj?.id), {
            method: 'patch',
            data: {},
            replace: true,
            preserveScroll: true,
        });
    }
};
</script>

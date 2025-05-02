<template>
</template>
<script setup lang="ts">
import Swal from "sweetalert2/dist/sweetalert2.js";
import i18n from '@/Core/plugins/i18n';
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3'

const { t } = i18n.global;

const props = defineProps({
    flash: Object,
})
onMounted(() => {
    if (props.flash && props.flash.message) {
        Swal.fire({
            text: props?.flash?.message,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: `${t('confirmation.gotIt')}!`,
            heightAuto: false,
            customClass: {
                confirmButton: "btn fw-semibold btn-light-primary",
            },
        }).then(() => {
            window.history.back();
        });
    } else {
        router.visit('/dashboard');
    }
});
</script>
<template>
    <button
        type="button"
        @click="showConfirmation"
        class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px"
        :title="props?.title"
    >
        <KTIcon :icon-name="props?.iconName" icon-class="fs-3 text-primary"/>
    </button>
</template>

<script setup lang="ts">
import { defineProps, ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import i18n from '@/Core/plugins/i18n';
import { Modal } from 'bootstrap';

const { t } = i18n.global;

const props = defineProps({
    iconName: String,
    obj: Object,
    title: String,
    messageTitle: String,
    openEditModal: Function,
    modalId: String
});

const isConfirmed = ref(false); // Flag to track confirmation

const showConfirmation = async () => {
    if (isConfirmed.value) {
        return;
    }

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    const result = await swalWithBootstrapButtons.fire({
        title: props?.messageTitle,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${t('buttonValue.confirm')}!`,
        cancelButtonText: `${t('buttonValue.cancel')}`,
        reverseButtons: true
    });

    if (result.isConfirmed) {
        isConfirmed.value = true; // Set the flag to true

        Swal.fire({
            title: `${t('confirmation.wait')}`,
            timer: 1000,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            // Show the modal directly using Bootstrap's Modal class
            const modalElement = document.getElementById(props?.modalId as string);
            if (modalElement) {
                const modal = new Modal(modalElement);
                modal.show();
            }

            // Call the openEditModal function if provided
            props.openEditModal?.(props.obj);
        });
    }
};

onMounted(() => {
    const modalElement = document.getElementById(props?.modalId as string);
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', () => {
            isConfirmed.value = false; // Reset the flag
        });
    }
});

</script>

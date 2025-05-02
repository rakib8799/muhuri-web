<template>
    <div class="form-check form-switch">
        <input
            @change="showStatusConfirmation"
            class="form-check-input w-45px h-30px cursor-pointer me-3"
            type="checkbox"
            data-bs-toggle="tooltip"
            :title="$t('tooltip.title.changeStatus')"
            id="toggleSwitch"
            v-model="isActive"
        />
        <label class="form-check-label" for="toggleSwitch"></label>
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref, watch } from 'vue';
import Swal from 'sweetalert2';
import i18n from '@/Core/plugins/i18n';
import { router } from '@inertiajs/vue3';

const { t } = i18n.global;

const props = defineProps({
    obj: Object,
    confirmRoute: String,
    cancelRoute: String,
    title: String,
    messageTitle: String,
    optionName: String,
});

// Ensure default state is off, but update when backend loads
const isActive = ref(false);

watch(
    () => (props.optionName ? props.obj?.[props.optionName] : undefined),
    (newValue) => {
        if (newValue !== undefined && newValue !== null) {
            isActive.value = newValue == 1 || newValue === true;
        }
    },
    { immediate: true }
);

const showStatusConfirmation = async () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    if (!props.obj || !props.optionName) {
        return;
    }

    const newValue = !isActive.value;

     const result = await swalWithBootstrapButtons.fire({
        title: props.messageTitle,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${t('buttonValue.confirm')}!`,
        cancelButtonText: `${t('buttonValue.cancel')}`,
        reverseButtons: true
    });

    if (result.isConfirmed) {
        Swal.fire({
            title: `${t('confirmation.wait')}`,
            timer: 1000,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        router.visit(route(props.confirmRoute as string), {
            method: 'patch',
            data: {
                option_name: props.optionName,
                option_value: newValue ? 0 : 1,
            },
            preserveScroll: true,
            replace: true,
            onSuccess: (page) => {
                isActive.value = Boolean(page.props.option_value);
            },
            onError: (errors) => {
                isActive.value = !newValue;
            }
        });
    }
};
</script>

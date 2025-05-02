<template>
    <!-- Company Header -->
    <div class="fw-bold fs-2 text-center mb-15" id="invoice-content">
        <h1 class="mb-3">{{ props.configuration?.name_bn }}</h1>
        <h4 class="mb-3">{{ props.configuration?.name }}</h4>
        <h5>
            <span v-if="props.configuration?.address">{{ props.configuration.address }}</span>
            <span v-if="props.configuration?.mobile_number">
            | {{ $t('label.mobileNumber') }}: {{ props.configuration.mobile_number }}
            {{ props.configuration.additional_mobile_number ? `, ${props.configuration.additional_mobile_number}` : '' }}
            </span>
        </h5>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    configuration: Object
});
</script>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    smsCredit: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});
console.log("credit", props.smsCredit);
interface Breadcrumb {
    url: string;
    title: string;
}

const permissions = usePage().props.permissions;
if (permissions) {
    localStorage.setItem('permissions', JSON.stringify(permissions));
}
</script>

<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div>
            <h1>{{ $t('sidebarMenu.dashboard') }}</h1>
            <div>
                <div v-if="props.smsCredit?.remaining_credits < 20" class="alert alert-warning fs-5 d-flex align-items-center mt-5">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        {{ $t('sms.title.remaining') }}
                        <strong class="mx-1">{{ props.smsCredit?.remaining_credits }}</strong>.
                        {{ $t('sms.title.please') }}
                        <Link :href="route('sms.purchase')" class="ms-1">{{ $t('sms.title.purchaseSMS') }}</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

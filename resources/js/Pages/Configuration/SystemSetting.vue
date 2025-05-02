<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <ConfigurationHeader activeLink="SystemSetting" :configuration="props?.configuration" />

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <div class="card-header cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t("configuration.header.systemSettings") }}</h3>
                </div>
            </div>
            <div class="card-body border-top p-9">
                <table class="table align-middle table-row-bordered fs-6 gy-5 no-footer">
                    <tbody>
                    <tr>
                        <td class=""><strong>{{ $t("configuration.label.smsSendPermission") }}</strong></td>
                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <PermissionConfirmationButton
                                    v-if="checkPermission('can-edit-configuration')"
                                    :obj="props.configuration"
                                    confirmRoute="configurations.updateSystemSetting"
                                    cancelRoute="configurations.systemSetting"
                                    :title="$t('configuration.label.smsSendPermission')"
                                    :messageTitle="`Are you sure you want to change the SMS Send Permission?`"
                                    optionName="is_sending_sms"
                                />
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfigurationHeader from '@/Pages/Configuration/ConfigurationHeader.vue';
import { checkPermission } from "@/Core/helpers/Permission";
import PermissionConfirmationButton from '@/Components/Button/PermissionConfirmationButton.vue';

type Breadcrumb = { url: string; title: string };

const props = defineProps({
    configuration: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});
</script>

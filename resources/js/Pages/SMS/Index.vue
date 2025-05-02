<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="row gy-5 g-xl-10">
            <!--begin::Col-->
            <div class="col-xl-8 mb-5 mb-xl-10">
                <ul class="nav nav-pills nav-pills-custom mb-3 d-flex justify-content-between flex-center overflow-hidden"
                    role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a :href="route('sms.purchase')"
                           class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-150px h-150px py-4 active">
                            <!--begin::Icon-->
                            <div class="nav-icon">
                                <i class="fa-solid fa-comment-dollar fs-5x text-success"
                                   style="font-size: 65px !important;"></i>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Subtitle-->
                            <span class="nav-text text-gray-700 fw-bold fs-6 lh-1"> {{
                                    $t('sms.header.purchase')
                                }} </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-150px h-150px py-4 active">
                            <!--begin::Icon-->
                            <div class="nav-icon">
                                <i class="fa-solid fa-comment-sms fs-5x text-primary"
                                   style="font-size: 65px !important;"></i>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Subtitle-->
                            <span class="nav-text text-gray-700 fw-bold fs-6 lh-1"> {{
                                    $t('sms.label.sendSMS')
                                }} </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a :href="route('sms.purchaseReport')"
                           class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-150px h-150px py-4 active">
                            <!--begin::Icon-->
                            <div class="nav-icon">
                                <i class="fa-solid fa-money-check-dollar fs-5x" style="font-size: 65px !important;"></i>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Subtitle-->
                            <span class="nav-text text-gray-700 fw-bold fs-6 lh-1 text-center"> {{
                                    $t('sms.header.smsPurchaseDetails')
                                }} </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a :href="route('sms.smsLog')"
                            class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-150px h-150px py-4 active">
                            <!--begin::Icon-->
                            <div class="nav-icon">
                                <i class="fa-solid fa-money-check-dollar fs-5x text-info"
                                   style="font-size: 65px !important;"></i>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Subtitle-->
                            <span class="nav-text text-gray-700 fw-bold fs-5 lh-1">{{ $t('sms.label.smsLog') }}</span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a :href="route('sms.templates.index')"
                            class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-150px h-150px py-4 active">
                            <!--begin::Icon-->
                            <div class="nav-icon">
                                <i class="fa-solid fa-gopuram fs-5x text-info"
                                   style="font-size: 65px !important;"></i>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Subtitle-->
                            <span class="nav-text text-gray-700 fw-bold fs-5 lh-1">{{ $t('sms.label.smsTemplate') }}</span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                </ul>
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xl-4 mb-xl-10">

                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10 h-150px"
                     style="background-color: #F1416C;background-image:url('/metronic8/demo1/assets/media/patterns/vector-1.png')">
                    <!--begin::Header-->
                    <div class="card-header pt-5">
                        <!--begin::Title-->
                        <div class="card-title d-flex flex-column">
                            <!--begin::Amount-->
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{smsCredit?.remaining_credits}}</span>
                            <!--end::Amount-->

                            <!--begin::Subtitle-->
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">{{
                                    $t('sms.label.remainingSMS')
                                }}</span>
                            <!--end::Subtitle-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Header-->

                    <!--begin::Card body-->
                    <div class="card-body d-flex align-items-end pt-0">

                    </div>
                    <!--end::Card body-->
                </div>

            </div>
            <!--end::Col-->

        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref, defineProps, onMounted} from "vue";
import {checkPermission} from "@/Core/helpers/Permission";
import i18n from '@/Core/plugins/i18n';

const {t} = i18n.global;

interface ISMSCredit {
    id: number;
    total_credits: number;
    used_credits: number;
    remaining_credits: number;

}

const props = defineProps({
    breadcrumbs: Array as () => Breadcrumb[],
    smsCredit: Object as () => ISMSCredit,
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

onMounted(() => {
});

</script>

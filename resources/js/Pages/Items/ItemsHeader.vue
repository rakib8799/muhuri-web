<template>
    <!--begin::Navbar-->
    <div class="card mb-9">
        <div class="card-body pb-0">
            <!--begin::Navs-->
            <div class="d-flex overflow-auto h-55px">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
                    <li class="nav-item" v-if="checkPermission('can-view-item')">
                        <Link :href="route('items.index', {type: 'sale'})" :class="{ active: isActive('sale') }"
                            class="nav-link text-active-primary me-6">
                            {{ $t("item.label.sale") }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-view-item')">
                        <Link :href="route('items.index', {type: 'purchase'})" :class="{ active: isActive('purchase') }"
                            class="nav-link text-active-primary me-6">
                             {{ $t("item.label.purchase") }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-view-item')">
                        <Link :href="route('items.index', {type: 'expense'})" :class="{ active: isActive('expense') }"
                            class="nav-link text-active-primary me-6">
                             {{ $t("item.label.expense") }}
                        </Link>
                    </li>
                </ul>
            </div>
            <!--end::Navs-->
        </div>
    </div>
    <slot></slot>
    <!--end::Navbar-->
</template>

<script lang="ts" setup>
import {Link} from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";
import { computed } from "vue";

const props = defineProps({
    activeTab: String
});

const headerActiveTab = computed(()=>props.activeTab);

const isActive = (linkName: String) => {
    return headerActiveTab.value === linkName;
};
</script>

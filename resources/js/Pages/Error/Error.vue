<template>
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Content-->
        <div class="d-flex flex-column flex-center text-center p-10">
            <!--begin::Wrapper-->
            <div class="card card-flush w-lg-650px py-5 shadow">
                <div class="card-body py-15 py-lg-20">
                    <!--begin::Title-->
                    <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">Oops!</h1>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fw-semibold fs-6 text-gray-500 mb-7">
                        {{ props?.message }}
                    </div>
                    <!--end::Text-->
                    <!--begin::Illustration-->
                    <div v-if="props?.status === 404" class="mb-3">
                        <img
                            :src="getAssetPath('media/auth/404-error.png')"
                            class="mw-100 mh-300px theme-light-show"
                            alt=""
                        />
                        <img
                            :src="getAssetPath('media/auth/404-error-dark.png')"
                            class="mw-100 mh-300px theme-dark-show"
                            alt=""
                        />
                    </div>

                    <div v-else-if="props?.status == 500" class="mb-3">
                        <img
                            :src="getAssetPath('media/auth/500-error.png')"
                            class="mw-100 mh-300px theme-light-show"
                            alt=""
                        />
                        <img
                            :src="getAssetPath('media/auth/500-error-dark.png')"
                            class="mw-100 mh-300px theme-dark-show"
                            alt=""
                        />
                    </div>
                    <!--end::Illustration-->
                    <!--begin::Link-->
                    <!-- <div class="mb-0">
                        <router-link to="/" class="btn btn-sm btn-primary"
                            >Return Home</router-link
                        >
                    </div> -->
                    <!--end::Link-->
                </div>
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->
    </div>
</template>

<script lang="ts">
import { getAssetPath, getIllustrationsPath } from "@/Core/helpers/Assets";
import { defineComponent, onMounted } from "vue";
import LayoutService from "@/Core/services/LayoutService";
import { useBodyStore } from "@/Stores/body";
import { themeMode } from "@/Layouts/default-layout/config/helper";

export default defineComponent({
    components: {},
    props: {
        status: {
            type: Number,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const storeBody = useBodyStore();
        // const bgImage =
        //     themeMode.value !== "dark"
        //         ? getAssetPath("media/auth/bg1.jpg")
        //         : getAssetPath("media/auth/bg1.jpg");

        // onMounted(() => {
        //     LayoutService.emptyElementClassesAndAttributes(document.body);

        //     storeBody.addBodyClassname("bg-body");
        //     storeBody.addBodyAttribute({
        //         qualifiedName: "style",
        //         value: `background-image: url("${bgImage}")`,
        //     });
        // });

        return {
            getIllustrationsPath,
            // bgImage,
            getAssetPath,
            props,
        };
    },
});
</script>

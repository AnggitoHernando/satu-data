<script setup></script>

<template>
    <section
        class="bg-gradient-to-r from-[#007d0d] via-green-500 to-emerald-400"
    >
        <div
            class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0"
        >
            <div
                class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl shadow-lg md:mt-0 sm:max-w-md xl:p-0"
            >
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <slot />
                </div>
            </div>
        </div>
    </section>
</template>

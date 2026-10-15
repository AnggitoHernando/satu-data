<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import {
    ChevronLeft,
    ChevronRight,
    Maximize2,
    X,
    Play,
    Pause,
    Image as ImageIcon,
} from "lucide-vue-next";

const props = defineProps({
    photos: {
        type: Array,
        default: () => [],
    },
    autoPlayInterval: {
        type: Number,
        default: 5000,
    },
});

// Index foto aktif
const currentIndex = ref(0);
const isLightboxOpen = ref(false);
const isPlaying = ref(false);
let timer = null;

const currentPhoto = computed(() => props.photos[currentIndex.value] || {});

// Navigasi Slider Utama
const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % props.photos.length;
};

const prevSlide = () => {
    currentIndex.value =
        (currentIndex.value - 1 + props.photos.length) % props.photos.length;
};

const goToSlide = (index) => {
    currentIndex.value = index;
};

// Lightbox Pop-up Modal
const openLightbox = (index = currentIndex.value) => {
    currentIndex.value = index;
    isLightboxOpen.value = true;
    stopAutoplay();
};

const closeLightbox = () => {
    isLightboxOpen.value = false;
};

// Auto Play Control
const startAutoplay = () => {
    isPlaying.value = true;
    timer = setInterval(nextSlide, props.autoPlayInterval);
};

const stopAutoplay = () => {
    isPlaying.value = false;
    if (timer) clearInterval(timer);
};

const toggleAutoplay = () => {
    if (isPlaying.value) {
        stopAutoplay();
    } else {
        startAutoplay();
    }
};

const handleKeyDown = (e) => {
    if (!isLightboxOpen.value) return;
    if (e.key === "ArrowLeft") prevSlide();
    if (e.key === "ArrowRight") nextSlide();
    if (e.key === "Escape") closeLightbox();
};

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
    stopAutoplay();
});
</script>

<template>
    <div class="w-full max-w-5xl mx-auto space-y-4">
        <div
            class="relative bg-slate-900 rounded-3xl overflow-hidden border border-slate-200/80 shadow-2xl group"
        >
            <div
                class="relative h-[320px] sm:h-[450px] md:h-[520px] w-full overflow-hidden bg-slate-950 flex items-center justify-center"
            >
                <img
                    v-if="currentPhoto.path"
                    :src="`/storage/${currentPhoto.path}`"
                    :alt="currentPhoto.alt_text || 'Foto Galeri'"
                    class="w-full h-full object-cover transition-all duration-500 ease-in-out cursor-pointer"
                    @click="openLightbox(currentIndex)"
                />
                <div
                    v-else
                    class="text-slate-500 flex flex-col items-center gap-2"
                >
                    <ImageIcon class="w-10 h-10 opacity-40" />
                    <p class="text-xs">Foto tidak tersedia</p>
                </div>

                <div
                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 pt-16 text-white pointer-events-none"
                >
                    <p
                        v-if="currentPhoto.caption"
                        class="text-sm sm:text-base font-semibold leading-relaxed drop-shadow-md"
                    >
                        {{ currentPhoto.caption }}
                    </p>
                    <span
                        class="inline-block mt-1 text-[0.7rem] font-bold tracking-wider text-emerald-400 uppercase bg-emerald-950/60 border border-emerald-500/30 px-2.5 py-0.5 rounded-full"
                    >
                        {{ currentIndex + 1 }} / {{ photos.length }}
                    </span>
                </div>
            </div>

            <button
                @click="prevSlide"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all opacity-90 sm:opacity-0 group-hover:opacity-100 hover:scale-105"
                aria-label="Foto Sebelumnya"
            >
                <ChevronLeft class="w-6 h-6" />
            </button>

            <button
                @click="nextSlide"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all opacity-90 sm:opacity-0 group-hover:opacity-100 hover:scale-105"
                aria-label="Foto Selanjutnya"
            >
                <ChevronRight class="w-6 h-6" />
            </button>

            <div class="absolute top-4 right-4 flex items-center gap-2">
                <button
                    @click="toggleAutoplay"
                    class="px-3 py-1.5 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/20 text-xs font-semibold flex items-center gap-1.5 transition-all"
                >
                    <component
                        :is="isPlaying ? Pause : Play"
                        class="w-3.5 h-3.5"
                    />
                    <span>{{ isPlaying ? "Jeda" : "Putar" }}</span>
                </button>

                <button
                    @click="openLightbox(currentIndex)"
                    class="p-2 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/20 transition-all hover:scale-105"
                    title="Perbesar Gambar"
                >
                    <Maximize2 class="w-4 h-4" />
                </button>
            </div>
        </div>

        <div
            v-if="photos.length > 1"
            class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-thin"
        >
            <button
                v-for="(photo, index) in photos"
                :key="photo.id || index"
                @click="goToSlide(index)"
                :class="[
                    'relative shrink-0 w-20 sm:w-24 h-14 sm:h-16 rounded-xl overflow-hidden border-2 transition-all duration-200 focus:outline-none',
                    currentIndex === index
                        ? 'border-[#0B6E4F] ring-2 ring-[#0B6E4F]/30 scale-105'
                        : 'border-transparent opacity-60 hover:opacity-100',
                ]"
            >
                <img
                    :src="`/storage/${photo.path}`"
                    :alt="photo.alt_text || 'Thumbnail'"
                    class="w-full h-full object-cover"
                />
            </button>
        </div>

        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="isLightboxOpen"
                    class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-8"
                >
                    <div
                        class="flex items-center justify-between text-white border-b border-white/10 pb-4"
                    >
                        <span
                            class="text-xs sm:text-sm font-semibold tracking-wider text-slate-300"
                        >
                            FOTO {{ currentIndex + 1 }} DARI {{ photos.length }}
                        </span>
                        <button
                            @click="closeLightbox"
                            class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors"
                        >
                            <X class="w-6 h-6" />
                        </button>
                    </div>

                    <div
                        class="relative flex-1 flex items-center justify-center my-4 overflow-hidden"
                    >
                        <img
                            :src="`/storage/${currentPhoto.path}`"
                            :alt="currentPhoto.alt_text || 'Foto Fullscreen'"
                            class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl"
                        />

                        <button
                            @click="prevSlide"
                            class="absolute left-2 sm:left-6 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all backdrop-blur-sm"
                        >
                            <ChevronLeft class="w-8 h-8" />
                        </button>

                        <button
                            @click="nextSlide"
                            class="absolute right-2 sm:right-6 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all backdrop-blur-sm"
                        >
                            <ChevronRight class="w-8 h-8" />
                        </button>
                    </div>

                    <div class="space-y-3 max-w-3xl mx-auto text-center">
                        <p
                            v-if="currentPhoto.caption"
                            class="text-xs sm:text-sm text-slate-200 font-medium"
                        >
                            {{ currentPhoto.caption }}
                        </p>

                        <div
                            class="flex items-center justify-center gap-2 overflow-x-auto py-1"
                        >
                            <button
                                v-for="(photo, index) in photos"
                                :key="'modal-' + (photo.id || index)"
                                @click="goToSlide(index)"
                                :class="[
                                    'w-12 h-9 rounded-md overflow-hidden border transition-all shrink-0',
                                    currentIndex === index
                                        ? 'border-emerald-400 ring-2 ring-emerald-400/50 scale-105'
                                        : 'border-transparent opacity-40 hover:opacity-100',
                                ]"
                            >
                                <img
                                    :src="`/storage/${photo.path}`"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

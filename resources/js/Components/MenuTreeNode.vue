<script setup>
import { ref, defineEmits } from "vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    node: { type: Object, required: true }, // { id, nama_menu, children: [] }
});

const emit = defineEmits(["setLoading"]);

const open = ref(true);
const isGroup = props.node.children && props.node.children.length > 0;

const handleDelete = async (item) => {
    const result = await Swal.fire({
        title: "Apakah Anda Yakin Ingin Menghapus?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#166534",
        confirmButtonText: "Ya, hapus!",
    });
    if (result.isConfirmed) {
        emit("setLoading", true);
        await router.delete(route("admin.ppid.menu-informasi.destroy", item), {
            onError: () => {
                emit("setLoading", false);
            },
            onSuccess: () => {
                emit("setLoading", false);
            },
            onFinish: () => {
                emit("setLoading", false);
            },
        });
    }
};
</script>

<template>
    <div>
        <div
            class="group flex items-center gap-2 rounded-md px-2 py-2 hover:bg-slate-50"
        >
            <button
                class="w-5 flex-none text-xs text-slate-400"
                :class="{ invisible: !isGroup }"
                @click="open = !open"
            >
                {{ isGroup ? (open ? "▾" : "▸") : "•" }}
            </button>

            <div class="min-w-0 flex-1 truncate text-sm font-semibold">
                {{ node.nama_menu }}
            </div>

            <span
                class="flex-none whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold"
                :class="
                    isGroup
                        ? 'bg-blue-50 text-blue-700'
                        : 'bg-emerald-50 text-emerald-700'
                "
            >
                {{
                    isGroup ? `grup · ${node.children.length} item` : "dokumen"
                }}
            </span>

            <div
                class="flex flex-none gap-1 opacity-0 transition-opacity group-hover:opacity-100"
            >
                <Link
                    :href="
                        route(
                            'admin.ppid.menu-informasi.create-sub-menu',
                            node.id,
                        )
                    "
                    class="rounded-md px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-100"
                    >+ Sub
                </Link>
                <Link
                    :href="route('admin.ppid.menu-informasi.edit', node)"
                    class="rounded-md px-2 py-1 text-xs font-medium text-slate-600 hover:bg-slate-100"
                    >Edit</Link
                >
                <button
                    class="rounded-md px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                    @click="handleDelete(node)"
                >
                    Hapus
                </button>
            </div>
        </div>

        <div
            v-if="isGroup && open"
            class="ml-[26px] border-l border-slate-200 pl-1.5"
        >
            <MenuTreeNode
                v-for="child in node.children"
                :key="child.id"
                :node="child"
            />
        </div>
    </div>
</template>

<script setup>
import { onBeforeUnmount, watch, ref } from "vue";
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import TextAlign from "@tiptap/extension-text-align";
import { TableKit } from "@tiptap/extension-table";
import { Table as TableIcon } from "lucide-vue-next";
import Link from "@tiptap/extension-link";
import { Link as LinkIcon, Unlink, X } from "lucide-vue-next";

const props = defineProps({
    modelValue: { type: String, default: "" },
    placeholder: { type: String, default: "Mulai menulis di sini…" },
});
const emit = defineEmits(["update:modelValue"]);

const showLinkModal = ref(false);
const linkUrl = ref("");
const linkText = ref("");
const linkError = ref("");

let savedSelection = null;

function setLink() {
    if (!editor.value) return;

    const instance = editor.value;
    const { from, to, empty } = instance.state.selection;

    savedSelection = { from, to };

    linkUrl.value = instance.getAttributes("link").href || "";
    linkText.value = empty ? "" : instance.state.doc.textBetween(from, to, " ");

    linkError.value = "";
    showLinkModal.value = true;
}

function closeLinkModal() {
    showLinkModal.value = false;
    linkError.value = "";
    editor.value?.commands.focus();
}

function saveLink() {
    if (!editor.value) return;

    const url = linkUrl.value.trim();
    const text = linkText.value.trim();

    if (!url || !text) {
        linkError.value = "URL dan teks link wajib diisi.";
        return;
    }

    let parsedUrl;

    try {
        parsedUrl = new URL(url);

        if (!["http:", "https:", "mailto:"].includes(parsedUrl.protocol)) {
            throw new Error("URL tidak valid");
        }
    } catch {
        linkError.value =
            "URL tidak valid. Gunakan http://, https://, atau mailto:.";
        return;
    }

    const instance = editor.value;
    const selection = savedSelection;

    if (selection) {
        instance.commands.setTextSelection(selection);
    }

    instance
        .chain()
        .focus()
        .insertContent({
            type: "text",
            text,
            marks: [
                {
                    type: "link",
                    attrs: {
                        href: parsedUrl.href,
                        target: "_blank",
                        rel: "noopener noreferrer",
                    },
                },
            ],
        })
        .run();

    showLinkModal.value = false;
    savedSelection = null;
    linkError.value = "";
}

function removeLink() {
    if (!editor.value) return;

    const instance = editor.value;

    if (savedSelection) {
        instance.commands.setTextSelection(savedSelection);
    }

    instance.chain().focus().extendMarkRange("link").unsetLink().run();

    showLinkModal.value = false;
    savedSelection = null;
}

const editor = useEditor({
    content: props.modelValue || `<p></p>`,
    extensions: [
        StarterKit.configure({
            link: false,
        }),
        TextAlign.configure({ types: ["heading", "paragraph"] }),
        // resizable: true -> kolom bisa digeser lebar-nya dengan drag
        // pada garis batas antar kolom.
        TableKit.configure({
            table: {
                resizable: true,
                cellMinWidth: 60,
                lastColumnResizable: true,
                allowTableNodeSelection: true,
            },
        }),
        Link.configure({
            openOnClick: false,
            autolink: true,
            linkOnPaste: true,
            HTMLAttributes: {
                target: "_blank",
                rel: "noopener noreferrer",
            },
        }),
    ],
    editorProps: {
        attributes: {
            class: "rich-text-content min-h-[220px] px-3.5 py-3 focus:outline-none",
            "data-placeholder": props.placeholder,
        },
    },
    onUpdate: ({ editor }) => {
        emit("update:modelValue", editor.getHTML());
    },
});

// Daftar aksi untuk toolbar tabel. Toolbar ini hanya muncul ketika kursor
// berada di dalam tabel. `can` dipakai supaya tombol otomatis nonaktif
// kalau aksinya tidak mungkin dilakukan (mis. gabung sel tanpa seleksi).
const tableActions = [
    {
        label: "+ Kolom kiri",
        title: "Tambah kolom di sebelah kiri",
        run: (e) => e.chain().focus().addColumnBefore().run(),
        can: (e) => e.can().addColumnBefore(),
    },
    {
        label: "+ Kolom kanan",
        title: "Tambah kolom di sebelah kanan",
        run: (e) => e.chain().focus().addColumnAfter().run(),
        can: (e) => e.can().addColumnAfter(),
    },
    {
        label: "− Kolom",
        title: "Hapus kolom ini",
        run: (e) => e.chain().focus().deleteColumn().run(),
        can: (e) => e.can().deleteColumn(),
        danger: true,
    },
    {
        label: "+ Baris atas",
        title: "Tambah baris di atas",
        run: (e) => e.chain().focus().addRowBefore().run(),
        can: (e) => e.can().addRowBefore(),
    },
    {
        label: "+ Baris bawah",
        title: "Tambah baris di bawah",
        run: (e) => e.chain().focus().addRowAfter().run(),
        can: (e) => e.can().addRowAfter(),
    },
    {
        label: "− Baris",
        title: "Hapus baris ini",
        run: (e) => e.chain().focus().deleteRow().run(),
        can: (e) => e.can().deleteRow(),
        danger: true,
    },
    {
        label: "Gabung sel",
        title: "Gabungkan sel yang dipilih (seret untuk memilih beberapa sel)",
        run: (e) => e.chain().focus().mergeCells().run(),
        can: (e) => e.can().mergeCells(),
    },
    {
        label: "Pisah sel",
        title: "Pisahkan sel yang sebelumnya digabung",
        run: (e) => e.chain().focus().splitCell().run(),
        can: (e) => e.can().splitCell(),
    },
    {
        label: "Baris judul",
        title: "Jadikan / lepas baris pertama sebagai judul",
        run: (e) => e.chain().focus().toggleHeaderRow().run(),
        can: (e) => e.can().toggleHeaderRow(),
    },
    {
        label: "Hapus tabel",
        title: "Hapus seluruh tabel",
        run: (e) => e.chain().focus().deleteTable().run(),
        can: (e) => e.can().deleteTable(),
        danger: true,
    },
];

// Kalau modelValue diubah dari LUAR komponen (misal form di-reset, atau
// data awal baru selesai di-fetch di mode edit), sinkronkan ke editor —
// tapi hanya kalau isinya beda, supaya tidak bikin kursor "loncat"
// setiap kali admin sendiri yang lagi ngetik.
watch(
    () => props.modelValue,
    (val) => {
        const isSame = editor.value && val === editor.value.getHTML();
        if (!isSame && editor.value) {
            editor.value.commands.setContent(val || "<p></p>", {
                emitUpdate: false,
            });
        }
    },
);

onBeforeUnmount(() => editor.value?.destroy());

function toolbarBtnClass(active) {
    return [
        "flex h-7 w-7 items-center justify-center rounded text-sm font-bold text-slate-600",
        active ? "bg-white shadow-sm text-emerald-700" : "hover:bg-white/70",
    ];
}

function tableBtnClass(danger, disabled) {
    return [
        "rounded px-2 py-1 text-xs font-medium",
        disabled
            ? "cursor-not-allowed text-slate-300"
            : danger
              ? "text-red-600 hover:bg-red-50"
              : "text-slate-600 hover:bg-white",
    ];
}
</script>

<template>
    <div class="overflow-hidden rounded-md border border-slate-200">
        <div
            v-if="editor"
            class="flex flex-wrap gap-0.5 border-b border-slate-200 bg-slate-50 p-1.5"
        >
            <button
                type="button"
                :class="toolbarBtnClass(editor.isActive('bold'))"
                @click="editor.chain().focus().toggleBold().run()"
            >
                B
            </button>
            <button
                type="button"
                :class="[toolbarBtnClass(editor.isActive('italic')), 'italic']"
                @click="editor.chain().focus().toggleItalic().run()"
            >
                I
            </button>
            <button
                type="button"
                :class="[
                    toolbarBtnClass(editor.isActive('underline')),
                    'underline',
                ]"
                @click="editor.chain().focus().toggleUnderline().run()"
            >
                U
            </button>

            <div class="mx-1 my-1 w-px bg-slate-200"></div>

            <button
                type="button"
                :class="
                    toolbarBtnClass(editor.isActive('heading', { level: 2 }))
                "
                @click="
                    editor.chain().focus().toggleHeading({ level: 2 }).run()
                "
            >
                H2
            </button>
            <button
                type="button"
                :class="
                    toolbarBtnClass(editor.isActive('heading', { level: 3 }))
                "
                @click="
                    editor.chain().focus().toggleHeading({ level: 3 }).run()
                "
            >
                H3
            </button>

            <div class="mx-1 my-1 w-px bg-slate-200"></div>

            <button
                type="button"
                :class="toolbarBtnClass(editor.isActive('bulletList'))"
                @click="editor.chain().focus().toggleBulletList().run()"
            >
                •≡
            </button>
            <button
                type="button"
                :class="toolbarBtnClass(editor.isActive('orderedList'))"
                @click="editor.chain().focus().toggleOrderedList().run()"
            >
                1≡
            </button>

            <div class="mx-1 my-1 w-px bg-slate-200"></div>

            <button
                type="button"
                :class="toolbarBtnClass(editor.isActive({ textAlign: 'left' }))"
                @click="editor.chain().focus().setTextAlign('left').run()"
            >
                ⟸
            </button>
            <button
                type="button"
                :class="
                    toolbarBtnClass(editor.isActive({ textAlign: 'center' }))
                "
                @click="editor.chain().focus().setTextAlign('center').run()"
            >
                ⇔
            </button>
            <button
                type="button"
                :class="toolbarBtnClass(editor.isActive('table'))"
                title="Sisipkan tabel"
                aria-label="Sisipkan tabel"
                @click="
                    editor
                        .chain()
                        .focus()
                        .insertTable({ rows: 3, cols: 3, withHeaderRow: true })
                        .run()
                "
            >
                <TableIcon class="h-4 w-4" aria-hidden="true" />
            </button>
            <button
                type="button"
                :class="toolbarBtnClass(editor.isActive('link'))"
                title="Tambah atau ubah link"
                aria-label="Tambah atau ubah link"
                @mousedown.prevent
                @click="setLink"
            >
                <LinkIcon class="h-4 w-4" />
            </button>

            <button
                type="button"
                :class="toolbarBtnClass(false)"
                title="Hapus link"
                aria-label="Hapus link"
                :disabled="!editor.isActive('link')"
                @mousedown.prevent
                @click="
                    editor
                        .chain()
                        .focus()
                        .extendMarkRange('link')
                        .unsetLink()
                        .run()
                "
            >
                <Unlink class="h-4 w-4" />
            </button>
        </div>

        <div
            v-if="editor && editor.isActive('table')"
            class="flex flex-wrap items-center gap-0.5 border-b border-slate-200 bg-emerald-50/60 p-1.5"
            role="toolbar"
            aria-label="Pengaturan tabel"
        >
            <span class="mr-1 px-1 text-xs font-semibold text-emerald-800">
                Tabel
            </span>
            <button
                v-for="action in tableActions"
                :key="action.label"
                type="button"
                :title="action.title"
                :disabled="!action.can(editor)"
                :class="tableBtnClass(action.danger, !action.can(editor))"
                @mousedown.prevent
                @click="action.run(editor)"
            >
                {{ action.label }}
            </button>
        </div>

        <EditorContent :editor="editor" />
    </div>

    <Teleport to="body">
        <div
            v-if="showLinkModal"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4"
            @mousedown.self="closeLinkModal"
            @keydown.esc="closeLinkModal"
        >
            <div
                class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl"
                role="dialog"
                aria-modal="true"
                aria-labelledby="link-modal-title"
            >
                <div class="mb-5 flex items-center justify-between">
                    <h2
                        id="link-modal-title"
                        class="text-base font-semibold text-slate-800"
                    >
                        Tambah Hyperlink
                    </h2>

                    <button
                        type="button"
                        class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100"
                        aria-label="Tutup modal"
                        @click="closeLinkModal"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <form class="space-y-4" @submit.prevent="saveLink">
                    <div>
                        <label
                            for="link-url"
                            class="mb-1.5 block text-sm font-medium text-slate-700"
                        >
                            URL tujuan
                        </label>

                        <input
                            id="link-url"
                            v-model="linkUrl"
                            type="text"
                            placeholder="https://contoh.com"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20"
                            autofocus
                        />
                    </div>

                    <div>
                        <label
                            for="link-text"
                            class="mb-1.5 block text-sm font-medium text-slate-700"
                        >
                            Teks yang ditampilkan
                        </label>

                        <input
                            id="link-text"
                            v-model="linkText"
                            type="text"
                            placeholder="Contoh: Website Resmi BWI"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20"
                        />
                    </div>

                    <p
                        v-if="linkError"
                        class="text-sm text-red-600"
                        role="alert"
                    >
                        {{ linkError }}
                    </p>

                    <div
                        class="flex justify-end gap-2 border-t border-slate-100 pt-4"
                    >
                        <button
                            type="button"
                            class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            @click="closeLinkModal"
                        >
                            Batal
                        </button>

                        <button
                            v-if="linkUrl"
                            type="button"
                            class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                            @click="removeLink"
                        >
                            Hapus Link
                        </button>

                        <button
                            type="submit"
                            class="rounded-lg bg-emerald-800 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-900"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>
<style>
.rich-text-content {
    font-size: 0.875rem;
    line-height: 1.75;
    color: #334035;
}
.rich-text-content p {
    margin: 0 0 0.75em;
}
.rich-text-content p:last-child {
    margin-bottom: 0;
}
.rich-text-content h2 {
    font-size: 1.15em;
    font-weight: 700;
    margin: 0.9em 0 0.4em;
}
.rich-text-content h3 {
    font-size: 1.05em;
    font-weight: 700;
    margin: 0.8em 0 0.4em;
}
.rich-text-content ul {
    list-style: disc;
    padding-left: 1.4em;
    margin: 0 0 0.75em;
}
.rich-text-content ol {
    list-style: decimal;
    padding-left: 1.4em;
    margin: 0 0 0.75em;
}
.rich-text-content strong {
    font-weight: 700;
}
.rich-text-content a {
    color: #125a38;
    text-decoration: underline;
    text-underline-offset: 2px;
    cursor: pointer;
}
.rich-text-content blockquote {
    border-left: 3px solid #dfe6df;
    padding-left: 0.9em;
    color: #7d8b7f;
    margin: 0 0 0.75em;
}

/* ---------- Tabel ---------- */

/* Pembungkus yang dibuat Tiptap saat tabel resizable; supaya tabel yang
   lebarnya melebihi editor bisa di-scroll horizontal, bukan jebol layout. */
.rich-text-content .tableWrapper {
    overflow-x: auto;
    /* overflow-y harus eksplisit: kalau overflow-x bukan "visible", browser
       otomatis menjadikan overflow-y "auto" juga, sehingga handle yang
       sedikit keluar dari tabel memunculkan scrollbar vertikal. */
    overflow-y: hidden;
    /* ruang untuk handle resize di kolom paling kanan, supaya tidak
       terpotong oleh overflow wrapper */
    padding-right: 6px;
    margin: 0 0 0.9em;
}
.rich-text-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 0 0 0.9em;
    table-layout: fixed;
}
.rich-text-content .tableWrapper table {
    margin: 0;
}
.rich-text-content th,
.rich-text-content td {
    border: 1px solid #dfe6df;
    padding: 0.5em 0.7em;
    vertical-align: top;
    text-align: left;
    position: relative; /* wajib: handle resize diposisikan relatif ke sel */
    min-width: 60px;
}
.rich-text-content th p,
.rich-text-content td p {
    margin: 0;
}
.rich-text-content th {
    background: #f1f5f1;
    font-weight: 700;
}
.rich-text-content .selectedCell {
    background: #d9efe3;
}

/* Garis hijau yang muncul saat kursor mouse di batas kolom / sedang di-drag */
.rich-text-content .column-resize-handle {
    position: absolute;
    right: -2px;
    top: 0;
    bottom: 0;
    width: 4px;
    background-color: #125a38;
    pointer-events: none;
    z-index: 5;
}
/* Kolom terakhir: handle ditaruh di dalam sel, bukan menjorok keluar */
.rich-text-content th:last-child .column-resize-handle,
.rich-text-content td:last-child .column-resize-handle {
    right: 0;
}
/* Kursor berubah jadi panah kiri-kanan saat di atas batas kolom */
.rich-text-content.resize-cursor,
.rich-text-content.resize-cursor * {
    cursor: col-resize;
}
</style>

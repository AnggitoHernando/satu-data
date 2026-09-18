<script setup>
import { onBeforeUnmount, watch } from "vue";
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import TextAlign from "@tiptap/extension-text-align";

const props = defineProps({
    modelValue: { type: String, default: "" },
    placeholder: { type: String, default: "Mulai menulis di sini…" },
});
const emit = defineEmits(["update:modelValue"]);

const editor = useEditor({
    content: props.modelValue || `<p></p>`,
    extensions: [
        StarterKit,
        TextAlign.configure({ types: ["heading", "paragraph"] }),
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

// Kalau modelValue diubah dari LUAR komponen (misal form di-reset, atau
// data awal baru selesai di-fetch di mode edit), sinkronkan ke editor —
// tapi hanya kalau isinya beda, supaya tidak bikin kursor "loncat"
// setiap kali admin sendiri yang lagi ngetik.
watch(
    () => props.modelValue,
    (val) => {
        const isSame = editor.value && val === editor.value.getHTML();
        if (!isSame && editor.value) {
            editor.value.commands.setContent(val || "<p></p>", false);
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
        </div>

        <EditorContent :editor="editor" />
    </div>
</template>
<style>
/* Tipografi untuk konten hasil rich text editor — dipakai di 2 tempat:
   1) area menulis di dalam RichTextEditor.vue (EditorContent)
   2) panel pratinjau yang me-render HTML yang sama lewat v-html
   Ditaruh terpisah (bukan "scoped") supaya class yang sama bisa dipakai
   di kedua tempat itu tanpa duplikasi style. */

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
}
.rich-text-content blockquote {
    border-left: 3px solid #dfe6df;
    padding-left: 0.9em;
    color: #7d8b7f;
    margin: 0 0 0.75em;
}
</style>

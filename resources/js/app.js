document.addEventListener('DOMContentLoaded', () => {
    const toast = document.querySelector('[data-toast]');
    if (toast) {
        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    initBiographyEditor();
});

async function initBiographyEditor() {
    const editorEl = document.querySelector('#biography-editor');
    if (!editorEl) return;

    const { default: Quill } = await import('quill');

    const quill = new Quill(editorEl, {
        theme: 'snow',
        placeholder: 'Ceritakan perjalanan panggilan Anda hingga tahbisan...',
        modules: {
            toolbar: {
                container: [
                    ['bold', 'italic', 'underline'],
                    [{ header: [2, 3, false] }],
                    ['blockquote'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link', 'image'],
                    ['clean'],
                ],
                handlers: {
                    image: () => uploadImage(quill, editorEl.dataset.imageUploadUrl),
                },
            },
        },
    });

    const form = document.querySelector('[data-biography-form]');
    form?.addEventListener('submit', () => {
        form.querySelector('textarea[name="biography"]').value = quill.root.innerHTML;
    });
}

function uploadImage(quill, uploadUrl) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/png, image/jpeg, image/webp, image/gif';
    input.onchange = async () => {
        const file = input.files[0];
        if (!file) return;

        const range = quill.getSelection(true);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch(uploadUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Upload failed');
            }

            const { url } = await response.json();
            quill.insertEmbed(range.index, 'image', url, 'user');
            quill.setSelection(range.index + 1);
        } catch (error) {
            window.alert('Gagal mengunggah gambar. Coba lagi.');
        }
    };
    input.click();
}

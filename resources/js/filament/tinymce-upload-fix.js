const uploadHandler = (blobInfo, progress) => {
  return new Promise((resolve, reject) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());

    fetch('/admin/editor/upload-image', {
      method: 'POST',
      headers: token ? { 'X-CSRF-TOKEN': token } : {},
      body: formData,
      credentials: 'same-origin',
    })
    .then(async (res) => {
      const text = await res.text();
      if (!res.ok) throw new Error(`HTTP ${res.status} ${text}`);
      const json = JSON.parse(text);
      if (!json.location) throw new Error('location missing: ' + text);
      resolve(json.location);
    })
    .catch((err) => reject(err.message));
  });
};

(function waitTinyMce() {
  if (!window.tinymce) return setTimeout(waitTinyMce, 200);

  window.tinymce.on('AddEditor', (e) => {
    const editor = e.editor;
    try {
      editor.options.set('images_upload_handler', uploadHandler);
      console.log('[tinymce-upload-fix] handler injected', editor.id);
    } catch (err) {
      console.error('[tinymce-upload-fix] inject failed', err);
    }
  });

  console.log('[tinymce-upload-fix] ready');
})();
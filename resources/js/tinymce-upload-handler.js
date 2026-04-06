// TinyMCE Promise版 images_upload_handler
window.tinymceImageUploadHandler = function(blobInfo, progress) {
    return new Promise(function(resolve, reject) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/editor/upload-image');
        
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
        }
        
        xhr.onload = function() {
            if (xhr.status < 200 || xhr.status >= 300) {
                return reject('Upload failed: HTTP ' + xhr.status + ' ' + xhr.responseText);
            }
            
            try {
                const json = JSON.parse(xhr.responseText);
                if (!json.location) {
                    return reject('Invalid response: ' + xhr.responseText);
                }
                resolve(json.location);
            } catch (e) {
                reject('Invalid JSON: ' + xhr.responseText);
            }
        };
        
        xhr.onerror = function() {
            reject('Network error');
        };
        
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    });
};
// imageUtils.js
export function formatImage(imageUrl) {
    return imageUrl.replace(/app\/public\//g, 'storage/');
  }

// Redimensiona y reencodea a JPEG en el navegador antes de subir, para no
// enviar fotos de celular de varios MB tal cual. Si el archivo ya es chico
// o no es una imagen procesable (gif/svg), lo deja igual.
export function compressImage(file, { maxWidth = 1600, maxHeight = 1600, quality = 0.75 } = {}) {
    return new Promise((resolve) => {
        if (!file.type || !file.type.startsWith('image/') || file.type === 'image/gif' || file.type === 'image/svg+xml') {
            resolve(file)
            return
        }

        const reader = new FileReader()
        reader.onload = (e) => {
            const img = new Image()
            img.onload = () => {
                let { width, height } = img

                if (width <= maxWidth && height <= maxHeight) {
                    resolve(file)
                    return
                }

                const ratio = Math.min(maxWidth / width, maxHeight / height)
                width = Math.round(width * ratio)
                height = Math.round(height * ratio)

                const canvas = document.createElement('canvas')
                canvas.width = width
                canvas.height = height
                canvas.getContext('2d').drawImage(img, 0, 0, width, height)

                canvas.toBlob((blob) => {
                    if (!blob) {
                        resolve(file)
                        return
                    }
                    resolve(new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() }))
                }, 'image/jpeg', quality)
            }
            img.onerror = () => resolve(file)
            img.src = e.target.result
        }
        reader.onerror = () => resolve(file)
        reader.readAsDataURL(file)
    })
}

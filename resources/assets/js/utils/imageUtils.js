// imageUtils.js
export function formatImage(imageUrl) {
    return imageUrl.replace(/app\/public\//g, 'storage/');
  }
  
self.addEventListener('install', function(e) {
  self.skipWaiting();
});

self.addEventListener('fetch', function(event) {
  // Simple pass-through for now
});

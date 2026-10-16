// ============================================
// Splash Screen / Loading Animation
// ============================================

(function() {
  'use strict';

  // Only show splash once per session
  const SPLASH_KEY = 'yi_splash_shown';
  const sessionShown = sessionStorage.getItem(SPLASH_KEY);

  if (sessionShown) {
    // Remove splash immediately if already shown this session
    const splash = document.getElementById('splashScreen');
    if (splash) {
      splash.style.display = 'none';
    }
    document.body.classList.remove('splash-active');
    return;
  }

  // Mark as shown for this session
  sessionStorage.setItem(SPLASH_KEY, 'true');

  // Wait for page to fully load, then fade out splash
  function hideSplash() {
    const splash = document.getElementById('splashScreen');
    if (!splash) return;

    // Minimum display time of 2 seconds for the animation to play
    const minDisplayTime = 2000;
    const startTime = performance.now();
    const elapsed = startTime - (window._splashStart || startTime);
    const remaining = Math.max(0, minDisplayTime - elapsed);

    setTimeout(function() {
      splash.classList.add('fade-out');
      // Remove from DOM after transition
      setTimeout(function() {
        splash.style.display = 'none';
        document.body.classList.remove('splash-active');
      }, 800);
    }, remaining);
  }

  // Record when splash started
  window._splashStart = performance.now();

  // Hide splash when page is fully loaded
  if (document.readyState === 'complete') {
    hideSplash();
  } else {
    window.addEventListener('load', hideSplash);
  }
})();
// ============================================
// YOUNG INNOVATORS - Real Frontend API Module
// ============================================

const API_BASE = '../backend/api/auth.php';
let currentUser = null;

function showToast(message, type = 'success') {
  const existing = document.querySelector('.toast');
  if (existing) existing.remove();
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `<div style="display:flex;align-items:center;gap:10px">
    <span style="font-size:1.2rem">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
    <span style="font-weight:600">${message}</span>
  </div>`;
  document.body.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

async function apiPost(url, data = {}) {
  const formData = data instanceof FormData ? data : new FormData();
  if (!(data instanceof FormData)) {
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined && value !== null) formData.append(key, value);
    });
  }
  const response = await fetch(url, { method: 'POST', credentials: 'include', body: formData });
  let result;
  try { result = await response.json(); } catch { result = { ok: false, error: 'Invalid server response' }; }
  if (!response.ok && result.ok !== false) result.ok = false;
  return result;
}

async function apiGet(url) {
  const response = await fetch(url, { credentials: 'include' });
  let result;
  try { result = await response.json(); } catch { result = { ok: false, error: 'Invalid server response' }; }
  if (!response.ok && result.ok !== false) result.ok = false;
  return result;
}

function getDashboardUrl(role) {
  switch (role) {
    case 'student': return 'student-dashboard.html';
    case 'parent': return 'parent-dashboard.html';
    case 'provider': return 'provider-dashboard.html';
    case 'admin': return 'admin-dashboard.html';
    default: return 'login.html';
  }
}

async function loadCurrentUser() {
  const result = await apiGet(`${API_BASE}?action=me`);
  currentUser = result.ok ? result.user : null;
  return currentUser;
}

async function requirePageRole(...roles) {
  const user = await loadCurrentUser();
  if (!user) {
    window.location.href = 'login.html';
    return null;
  }
  if (roles.length && !roles.includes(user.role)) {
    window.location.href = getDashboardUrl(user.role);
    return null;
  }
  return user;
}

async function login(identifier, password, role = 'parent') {
  const result = await apiPost(API_BASE, { action: 'login', identifier, password, role });
  if (!result.ok) {
    showToast(result.error || 'Login failed', 'error');
    return null;
  }
  currentUser = result.user;
  showToast('Login successful!', 'success');
  setTimeout(() => { window.location.href = getDashboardUrl(result.user.role); }, 500);
  return result.user;
}

async function register(data, type = 'parent') {
  const action = type === 'provider' ? 'register_provider' : 'register_parent';

  let payload;
  if (data instanceof FormData) {
    payload = data;
    payload.append('action', action);
  } else {
    payload = { action, ...data };
  }

  const result = await apiPost(API_BASE, payload);
  if (!result.ok) {
    showToast(result.error || 'Registration failed', 'error');
    return null;
  }
  currentUser = result.user || null;
  showToast(result.message || 'Registration successful!', 'success');
  setTimeout(() => { window.location.href = result.user ? getDashboardUrl(result.user.role) : 'login.html'; }, 700);
  return result.user || result;
}

async function logout() {
  await apiPost(API_BASE, { action: 'logout' });
  currentUser = null;
  window.location.href = 'index.html';
}

function getXPLevel(xp) {
  if (xp < 100) return { level: 1, name: 'Beginner', next: 100, progress: xp, start: 0 };
  if (xp < 300) return { level: 2, name: 'Explorer', next: 300, progress: xp - 100, start: 100 };
  if (xp < 600) return { level: 3, name: 'Adventurer', next: 600, progress: xp - 300, start: 300 };
  if (xp < 1000) return { level: 4, name: 'Champion', next: 1000, progress: xp - 600, start: 600 };
  return { level: 5, name: 'Master', next: 1500, progress: xp - 1000, start: 1000 };
}

function initNavbar() {
  const navbar = document.getElementById('navbar');
  if (navbar) window.addEventListener('scroll', () => navbar.classList.toggle('scrolled', window.scrollY > 20));
  const toggle = document.getElementById('mobileToggle');
  if (toggle) toggle.addEventListener('click', () => {
    document.getElementById('navLinks')?.classList.toggle('active');
    document.getElementById('navActions')?.classList.toggle('active');
  });
  updateNavbarForRole();
}

function updateNavbarForRole() {
  const actions = document.getElementById('navActions');
  if (!actions || !currentUser) return;
  actions.innerHTML = `<a href="${getDashboardUrl(currentUser.role)}" class="btn btn-outline btn-sm">Dashboard</a>
    <button class="btn btn-sm" style="background:var(--gradient-accent);color:white" onclick="logout()">Logout</button>`;
}

function initScrollAnimations() {
  const observer = new IntersectionObserver((entries) => entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  }), { threshold: 0.1 });
  document.querySelectorAll('.animate-fadeInUp, .animate-slideInLeft, .animate-slideInRight').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'all 0.6s ease';
    observer.observe(el);
  });
}

function initials(first = '', last = '', fallback = 'YI') {
  const out = `${first?.[0] || ''}${last?.[0] || ''}`.toUpperCase();
  return out || fallback;
}

function escapeHTML(value = '') {
  return String(value).replace(/[&<>'"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[c]));
}

document.addEventListener('DOMContentLoaded', async () => {
  await loadCurrentUser();
  initNavbar();
  initScrollAnimations();
});

window.apiPost = apiPost;
window.apiGet = apiGet;
window.login = login;
window.register = register;
window.logout = logout;
window.showToast = showToast;
window.getDashboardUrl = getDashboardUrl;
window.getXPLevel = getXPLevel;
window.loadCurrentUser = loadCurrentUser;
window.requirePageRole = requirePageRole;
window.getCurrentUser = () => currentUser;
window.initials = initials;
window.escapeHTML = escapeHTML;

function togglePassword(inputId, button) {

  const input = document.getElementById(inputId);
  const icon = button.querySelector('i');

  if (!input || !icon) return;

  if (input.type === 'password') {

    input.type = 'text';

    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');

    button.setAttribute(
      'aria-label',
      'Hide password'
    );

  } else {

    input.type = 'password';

    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');

    button.setAttribute(
      'aria-label',
      'Show password'
    );
  }
}


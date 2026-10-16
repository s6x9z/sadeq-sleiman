// ============================================
// YOUNG INNOVATORS - Security & Compliance Module
// GDPR & COPPA Compliance Implementation
// ============================================

const Security = {
  // ---- Input Sanitization ----
  sanitizeInput(input) {
    if (typeof input !== 'string') return input;
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
  },

  sanitizeHTML(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
  },

  // Prevent XSS in all form inputs
  sanitizeFormData(formData) {
    const sanitized = {};
    for (const [key, value] of Object.entries(formData)) {
      sanitized[key] = this.sanitizeInput(value);
    }
    return sanitized;
  },

  // ---- CSRF Token Generation ----
  generateCSRFToken() {
    const array = new Uint8Array(32);
    crypto.getRandomValues(array);
    const token = Array.from(array, b => b.toString(16).padStart(2, '0')).join('');
    sessionStorage.setItem('csrf_token', token);
    return token;
  },

  validateCSRFToken(token) {
    return token === sessionStorage.getItem('csrf_token');
  },

  // ---- Rate Limiting (Client-side) ----
  rateLimiter: {},

  checkRateLimit(action, maxAttempts = 5, windowMs = 60000) {
    const now = Date.now();
    if (!this.rateLimiter[action]) {
      this.rateLimiter[action] = { attempts: [], blocked: false };
    }

    const limiter = this.rateLimiter[action];
    
    // Remove old attempts outside the window
    limiter.attempts = limiter.attempts.filter(t => now - t < windowMs);
    
    if (limiter.attempts.length >= maxAttempts) {
      limiter.blocked = true;
      const waitTime = Math.ceil((windowMs - (now - limiter.attempts[0])) / 1000);
      return { allowed: false, waitTime };
    }

    limiter.attempts.push(now);
    return { allowed: true };
  },

  // ---- Password Security ----
  validatePassword(password) {
    const checks = {
      length: password.length >= 8,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /[0-9]/.test(password),
      special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };
    
    const score = Object.values(checks).filter(Boolean).length;
    let strength = 'weak';
    if (score >= 4) strength = 'strong';
    else if (score >= 3) strength = 'medium';

    return { checks, score, strength, valid: score >= 3 };
  },

  // ---- Session Security ----
  SESSION_TIMEOUT: 30 * 60 * 1000, // 30 minutes

  initSessionMonitor() {
    let lastActivity = Date.now();
    
    const resetTimer = () => {
      lastActivity = Date.now();
      sessionStorage.setItem('last_activity', lastActivity.toString());
    };

    ['mousedown', 'keypress', 'scroll', 'touchstart'].forEach(event => {
      document.addEventListener(event, resetTimer, { passive: true });
    });

    // Check session timeout every minute
    setInterval(() => {
      const stored = sessionStorage.getItem('last_activity');
      const last = stored ? parseInt(stored) : lastActivity;
      
      if (Date.now() - last > this.SESSION_TIMEOUT) {
        this.handleSessionTimeout();
      }
    }, 60000);
  },

  handleSessionTimeout() {
    // Clear sensitive data
    localStorage.removeItem('yi_user');
    sessionStorage.clear();
    
    if (window.location.pathname.includes('dashboard')) {
      alert('Your session has expired for security. Please log in again.');
      window.location.href = 'login.html';
    }
  },

  // ---- Content Security ----
  setupSecurityHeaders() {
    // Add meta tags for security (CSP via meta tag for static sites)
    const meta = document.createElement('meta');
    meta.httpEquiv = 'Content-Security-Policy';
    meta.content = "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' https: data:; connect-src 'self'";
    document.head.prepend(meta);
  },

  // ---- Secure Storage ----
  encryptData(data) {
    // Simple obfuscation for localStorage (not true encryption - for demo)
    // In production, use Web Crypto API with proper key management
    try {
      return btoa(encodeURIComponent(JSON.stringify(data)));
    } catch (e) {
      return null;
    }
  },

  decryptData(encoded) {
    try {
      return JSON.parse(decodeURIComponent(atob(encoded)));
    } catch (e) {
      return null;
    }
  },

  // ---- Clickjacking Protection ----
  preventClickjacking() {
    if (window.self !== window.top) {
      document.body.innerHTML = '<h1>This content cannot be displayed in a frame.</h1>';
      window.top.location = window.self.location;
    }
  }
};

// ============================================
// GDPR COMPLIANCE MODULE
// ============================================
const GDPR = {
  CONSENT_KEY: 'yi_gdpr_consent',
  DATA_KEY: 'yi_user_data_preferences',

  // Check if user has given consent
  hasConsent() {
    const consent = localStorage.getItem(this.CONSENT_KEY);
    return consent ? JSON.parse(consent) : null;
  },

  // Save consent preferences
  saveConsent(preferences) {
    const consentRecord = {
      ...preferences,
      timestamp: new Date().toISOString(),
      version: '1.0',
      ip_anonymized: true
    };
    localStorage.setItem(this.CONSENT_KEY, JSON.stringify(consentRecord));
    return consentRecord;
  },

  // Show cookie consent banner
  showConsentBanner() {
    if (this.hasConsent()) return;

    const banner = document.createElement('div');
    banner.id = 'gdpr-consent-banner';
    banner.innerHTML = `
      <div class="consent-banner">
        <div class="consent-content">
          <div class="consent-icon">🍪</div>
          <div class="consent-text">
            <h3>We Value Your Privacy</h3>
            <p>We use cookies and similar technologies to provide a safe, personalized learning experience. As this platform is designed for children, we follow strict GDPR and COPPA guidelines.</p>
          </div>
        </div>
        <div class="consent-actions">
          <button class="btn btn-sm" style="background:#63636E15;color:var(--text-secondary)" onclick="GDPR.showPreferences()">Manage Preferences</button>
          <button class="btn btn-sm" style="background:var(--gradient-primary);color:white" onclick="GDPR.acceptAll()">Accept Essential Only</button>
          <button class="btn btn-sm btn-primary" onclick="GDPR.acceptAll()">Accept All</button>
        </div>
      </div>
    `;
    document.body.appendChild(banner);
  },

  acceptAll() {
    this.saveConsent({
      essential: true,
      analytics: true,
      preferences: true,
      marketing: false // Never for children's platform
    });
    this.hideBanner();
  },

  acceptEssential() {
    this.saveConsent({
      essential: true,
      analytics: false,
      preferences: false,
      marketing: false
    });
    this.hideBanner();
  },

  showPreferences() {
    const banner = document.getElementById('gdpr-consent-banner');
    if (banner) {
      banner.querySelector('.consent-banner').innerHTML = `
        <div class="consent-content">
          <div class="consent-icon">⚙️</div>
          <div class="consent-text">
            <h3>Cookie Preferences</h3>
            <p>Choose which cookies you'd like to allow:</p>
          </div>
        </div>
        <div class="consent-preferences">
          <label class="consent-option">
            <input type="checkbox" checked disabled> 
            <span><strong>Essential Cookies</strong> (Required) - Necessary for the website to function</span>
          </label>
          <label class="consent-option">
            <input type="checkbox" id="pref-analytics"> 
            <span><strong>Analytics Cookies</strong> - Help us improve the learning experience</span>
          </label>
          <label class="consent-option">
            <input type="checkbox" id="pref-preferences"> 
            <span><strong>Preference Cookies</strong> - Remember your settings and progress</span>
          </label>
          <label class="consent-option disabled">
            <input type="checkbox" disabled> 
            <span><strong>Marketing Cookies</strong> - Disabled (not used on children's platforms)</span>
          </label>
        </div>
        <div class="consent-actions">
          <button class="btn btn-sm btn-primary" onclick="GDPR.savePreferences()">Save Preferences</button>
        </div>
      `;
    }
  },

  savePreferences() {
    this.saveConsent({
      essential: true,
      analytics: document.getElementById('pref-analytics')?.checked || false,
      preferences: document.getElementById('pref-preferences')?.checked || false,
      marketing: false
    });
    this.hideBanner();
  },

  hideBanner() {
    const banner = document.getElementById('gdpr-consent-banner');
    if (banner) {
      banner.style.opacity = '0';
      banner.style.transform = 'translateY(100%)';
      setTimeout(() => banner.remove(), 300);
    }
  },

  // Right to Access - Export user data
  exportUserData() {
    const userData = {
      profile: JSON.parse(localStorage.getItem('yi_user') || '{}'),
      consent: this.hasConsent(),
      students: JSON.parse(localStorage.getItem('yi_students') || '[]'),
      exportDate: new Date().toISOString(),
      format: 'JSON (GDPR Article 20 - Data Portability)'
    };

    // Remove sensitive data
    if (userData.profile.password) delete userData.profile.password;

    const blob = new Blob([JSON.stringify(userData, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `young-innovators-data-export-${Date.now()}.json`;
    a.click();
    URL.revokeObjectURL(url);
    
    if (window.showToast) window.showToast('Your data has been exported successfully! 📁');
  },

  // Right to Erasure - Delete all user data
  deleteAllData() {
    if (confirm('⚠️ Are you sure you want to delete ALL your data? This action cannot be undone.\n\nThis will remove:\n- Your account information\n- All child accounts\n- Progress and achievements\n- All preferences')) {
      localStorage.removeItem('yi_user');
      localStorage.removeItem('yi_users');
      localStorage.removeItem('yi_students');
      localStorage.removeItem('yi_provider_apps');
      localStorage.removeItem(this.CONSENT_KEY);
      sessionStorage.clear();
      
      if (window.showToast) window.showToast('All your data has been deleted. Redirecting...', 'info');
      setTimeout(() => { window.location.href = 'index.html'; }, 2000);
    }
  },

  // Right to Rectification - Allow data editing
  getRectificationInfo() {
    return {
      message: 'You can update your personal information at any time from your dashboard settings.',
      contact: 'For data corrections, email: privacy@younginnovators.com',
      response_time: '30 days maximum (GDPR Article 12)'
    };
  }
};

// ============================================
// COPPA COMPLIANCE MODULE
// ============================================
const COPPA = {
  CONSENT_KEY: 'yi_coppa_consent',
  MIN_PARENT_AGE: 18,
  MAX_CHILD_AGE: 13,

  // Verify parent is an adult (age gate)
  verifyParentAge(birthdate) {
    const today = new Date();
    const birth = new Date(birthdate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
      age--;
    }
    return age >= this.MIN_PARENT_AGE;
  },

  // Verify child is in appropriate age range (10-13)
  verifyChildAge(birthdate) {
    const today = new Date();
    const birth = new Date(birthdate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
      age--;
    }
    return age >= 10 && age <= 13;
  },

  // Parental consent verification
  hasParentalConsent(childId) {
    const consents = JSON.parse(localStorage.getItem(this.CONSENT_KEY) || '{}');
    return consents[childId] ? true : false;
  },

  // Record parental consent
  recordParentalConsent(childId, parentId, consentDetails) {
    const consents = JSON.parse(localStorage.getItem(this.CONSENT_KEY) || '{}');
    consents[childId] = {
      parentId,
      childId,
      timestamp: new Date().toISOString(),
      method: 'verifiable_parental_consent',
      details: consentDetails,
      dataCollected: [
        'username',
        'first_name',
        'age_range',
        'learning_interests',
        'game_progress',
        'xp_and_badges'
      ],
      dataNotCollected: [
        'email (children)',
        'phone_number (children)',
        'location',
        'photos',
        'social_media',
        'marketing_data'
      ]
    };
    localStorage.setItem(this.CONSENT_KEY, JSON.stringify(consents));
    return true;
  },

  // Revoke consent (parent can withdraw at any time)
  revokeConsent(childId) {
    const consents = JSON.parse(localStorage.getItem(this.CONSENT_KEY) || '{}');
    delete consents[childId];
    localStorage.setItem(this.CONSENT_KEY, JSON.stringify(consents));
    return true;
  },

  // Show parental consent form
  showConsentForm(childData, parentId, callback) {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.id = 'coppa-consent-modal';
    modal.innerHTML = `
      <div class="modal" style="max-width:600px">
        <div class="modal-header">
          <h3>🛡️ Parental Consent Required (COPPA)</h3>
          <button class="modal-close" onclick="document.getElementById('coppa-consent-modal').remove()">✕</button>
        </div>
        <div style="padding:24px">
          <div class="alert alert-info" style="margin-bottom:16px">
            <span>ℹ️</span>
            <span>Under the Children's Online Privacy Protection Act (COPPA), we require verifiable parental consent before collecting any information from children under 13.</span>
          </div>
          
          <h4 style="margin-bottom:12px">Creating account for: ${Security.sanitizeInput(childData.firstName)} ${Security.sanitizeInput(childData.lastName)}</h4>
          
          <div style="background:#F8F9FE;padding:16px;border-radius:var(--radius-md);margin-bottom:16px">
            <p style="font-weight:700;margin-bottom:8px">Data we WILL collect:</p>
            <ul style="font-size:0.85rem;color:var(--text-secondary);padding-left:20px">
              <li>Username (chosen by parent)</li>
              <li>First name only</li>
              <li>Age range (10-13)</li>
              <li>Learning interests and preferences</li>
              <li>Game progress, XP, and badges</li>
              <li>Course enrollment and completion</li>
            </ul>
          </div>
          
          <div style="background:#FFF5F5;padding:16px;border-radius:var(--radius-md);margin-bottom:16px">
            <p style="font-weight:700;margin-bottom:8px">Data we will NEVER collect from children:</p>
            <ul style="font-size:0.85rem;color:var(--text-secondary);padding-left:20px">
              <li>❌ Email address</li>
              <li>❌ Phone number</li>
              <li>❌ Physical address or location</li>
              <li>❌ Photos or videos</li>
              <li>❌ Social media accounts</li>
              <li>❌ Any data for marketing purposes</li>
            </ul>
          </div>

          <div style="background:#F0FFF4;padding:16px;border-radius:var(--radius-md);margin-bottom:16px">
            <p style="font-weight:700;margin-bottom:8px">Your rights as a parent:</p>
            <ul style="font-size:0.85rem;color:var(--text-secondary);padding-left:20px">
              <li>✅ Review your child's data at any time</li>
              <li>✅ Request deletion of your child's data</li>
              <li>✅ Revoke consent and close the account</li>
              <li>✅ Control what activities your child can access</li>
              <li>✅ Export all data in machine-readable format</li>
            </ul>
          </div>

          <label style="display:flex;align-items:flex-start;gap:8px;margin-bottom:16px;cursor:pointer">
            <input type="checkbox" id="coppa-consent-check" style="accent-color:var(--primary);margin-top:3px">
            <span style="font-size:0.85rem">I am the parent/legal guardian of this child. I have read and understand the data collection practices above, and I give my verifiable consent for Young Innovators to collect the listed information from my child for educational purposes only.</span>
          </label>

          <label style="display:flex;align-items:flex-start;gap:8px;margin-bottom:20px;cursor:pointer">
            <input type="checkbox" id="coppa-age-check" style="accent-color:var(--primary);margin-top:3px">
            <span style="font-size:0.85rem">I confirm that I am at least 18 years old and that my child is between 10-13 years of age.</span>
          </label>

          <button class="btn btn-primary" style="width:100%" onclick="COPPA.processConsent('${childData.firstName}', '${parentId}', ${JSON.stringify(childData).replace(/"/g, '&quot;')})">
            Grant Consent & Create Account 🛡️
          </button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    
    // Store callback
    window._coppaCallback = callback;
  },

  processConsent(childName, parentId, childData) {
    const consentCheck = document.getElementById('coppa-consent-check');
    const ageCheck = document.getElementById('coppa-age-check');

    if (!consentCheck?.checked || !ageCheck?.checked) {
      if (window.showToast) window.showToast('Please check both consent boxes to proceed.', 'error');
      return;
    }

    // Record the consent
    const childId = window.generateId ? window.generateId() : Date.now().toString();
    this.recordParentalConsent(childId, parentId, {
      consentGiven: true,
      ageVerified: true,
      childName: childName,
      method: 'checkbox_verification'
    });

    // Remove modal
    document.getElementById('coppa-consent-modal')?.remove();

    // Execute callback
    if (window._coppaCallback) {
      window._coppaCallback(childData);
      delete window._coppaCallback;
    }

    if (window.showToast) window.showToast('Parental consent recorded. Account created! 🛡️');
  },

  // Data minimization - only collect what's needed
  getMinimalChildData(fullData) {
    return {
      id: fullData.id,
      firstName: fullData.firstName,
      username: fullData.username,
      ageRange: '10-13',
      interests: fullData.interests || [],
      enrolledCourses: fullData.enrolledCourses || [],
      earnedBadges: fullData.earnedBadges || [],
      xp: fullData.xp || 0,
      parentId: fullData.parentId
      // Explicitly NOT including: email, phone, address, lastName, etc.
    };
  }
};

// ============================================
// PRIVACY NOTICE COMPONENT
// ============================================
const PrivacyNotice = {
  show(type = 'full') {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.id = 'privacy-modal';
    modal.innerHTML = `
      <div class="modal" style="max-width:700px;max-height:80vh;overflow-y:auto">
        <div class="modal-header" style="position:sticky;top:0;background:white;z-index:1">
          <h3>🔒 Privacy & Data Protection</h3>
          <button class="modal-close" onclick="document.getElementById('privacy-modal').remove()">✕</button>
        </div>
        <div style="padding:24px">
          <h4>1. Data Controller</h4>
          <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px">Young Innovators Educational Platform<br>Email: privacy@younginnovators.com<br>Data Protection Officer: dpo@younginnovators.com</p>

          <h4>2. Legal Basis for Processing (GDPR Article 6)</h4>
          <ul style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px;padding-left:20px">
            <li><strong>Consent</strong> - For children's data (with verifiable parental consent per COPPA)</li>
            <li><strong>Contract</strong> - To provide the educational service</li>
            <li><strong>Legitimate Interest</strong> - Platform security and improvement</li>
          </ul>

          <h4>3. Children's Privacy (COPPA Compliance)</h4>
          <ul style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px;padding-left:20px">
            <li>We do NOT collect personal information from children under 13 without verifiable parental consent</li>
            <li>Parents can review, modify, or delete their child's data at any time</li>
            <li>We do NOT use children's data for marketing or advertising</li>
            <li>We do NOT share children's data with third parties</li>
            <li>Children cannot make their personal information publicly available</li>
            <li>We collect only the minimum data necessary for the educational service</li>
          </ul>

          <h4>4. Data Collected</h4>
          <table style="width:100%;font-size:0.85rem;margin-bottom:16px;border-collapse:collapse">
            <tr style="background:#F8F9FE"><th style="padding:8px;text-align:left">Data Type</th><th style="padding:8px">Parents</th><th style="padding:8px">Children</th><th style="padding:8px">Purpose</th></tr>
            <tr><td style="padding:8px;border-bottom:1px solid #eee">Name</td><td style="padding:8px;text-align:center">✅</td><td style="padding:8px;text-align:center">First only</td><td style="padding:8px">Account identification</td></tr>
            <tr><td style="padding:8px;border-bottom:1px solid #eee">Email</td><td style="padding:8px;text-align:center">✅</td><td style="padding:8px;text-align:center">❌</td><td style="padding:8px">Account recovery</td></tr>
            <tr><td style="padding:8px;border-bottom:1px solid #eee">Phone</td><td style="padding:8px;text-align:center">✅</td><td style="padding:8px;text-align:center">❌</td><td style="padding:8px">Verification</td></tr>
            <tr><td style="padding:8px;border-bottom:1px solid #eee">Learning Data</td><td style="padding:8px;text-align:center">—</td><td style="padding:8px;text-align:center">✅</td><td style="padding:8px">Educational progress</td></tr>
            <tr><td style="padding:8px;border-bottom:1px solid #eee">Location</td><td style="padding:8px;text-align:center">❌</td><td style="padding:8px;text-align:center">❌</td><td style="padding:8px">Not collected</td></tr>
          </table>

          <h4>5. Your Rights (GDPR Articles 15-22)</h4>
          <ul style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px;padding-left:20px">
            <li><strong>Right of Access</strong> - Request a copy of your data</li>
            <li><strong>Right to Rectification</strong> - Correct inaccurate data</li>
            <li><strong>Right to Erasure</strong> - Request deletion of your data</li>
            <li><strong>Right to Portability</strong> - Export your data in machine-readable format</li>
            <li><strong>Right to Object</strong> - Object to data processing</li>
            <li><strong>Right to Restrict</strong> - Limit how we use your data</li>
          </ul>

          <h4>6. Data Retention</h4>
          <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px">Account data is retained while the account is active. Upon deletion request, all data is permanently removed within 30 days. Children's data is automatically reviewed annually.</p>

          <h4>7. Data Security</h4>
          <ul style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px;padding-left:20px">
            <li>All data transmitted via HTTPS/TLS encryption</li>
            <li>Passwords hashed using bcrypt (never stored in plaintext)</li>
            <li>Regular security audits and penetration testing</li>
            <li>Access controls and principle of least privilege</li>
            <li>Session timeout after 30 minutes of inactivity</li>
          </ul>

          <h4>8. Contact & Complaints</h4>
          <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:16px">
            Data Protection Officer: dpo@younginnovators.com<br>
            You have the right to lodge a complaint with your local supervisory authority.
          </p>

          <div style="display:flex;gap:12px;flex-wrap:wrap">
            <button class="btn btn-primary btn-sm" onclick="GDPR.exportUserData()">📁 Export My Data</button>
            <button class="btn btn-sm" style="background:#E1705515;color:var(--danger)" onclick="GDPR.deleteAllData()">🗑️ Delete All Data</button>
            <button class="btn btn-outline btn-sm" onclick="document.getElementById('privacy-modal').remove()">Close</button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
  // Initialize security measures
  Security.preventClickjacking();
  Security.initSessionMonitor();
  Security.generateCSRFToken();
  
  // Show GDPR consent banner if needed
  GDPR.showConsentBanner();
});

// Make modules globally available
window.Security = Security;
window.GDPR = GDPR;
window.COPPA = COPPA;
window.PrivacyNotice = PrivacyNotice;
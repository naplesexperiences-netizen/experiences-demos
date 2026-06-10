/**
 * Experiences Srl — Language Switcher Engine
 * Handles language switching, persistence, and DOM updates
 */

const LANG_META = {
  it: { label: 'Italiano',  flag: '🇮🇹', dir: 'ltr' },
  en: { label: 'English',   flag: '🇬🇧', dir: 'ltr' },
  es: { label: 'Español',   flag: '🇪🇸', dir: 'ltr' },
  fr: { label: 'Français',  flag: '🇫🇷', dir: 'ltr' },
  ru: { label: 'Русский',   flag: '🇷🇺', dir: 'ltr' },
};

let currentLang = localStorage.getItem('exp_lang') || 'it';
window.currentLang = currentLang;

// ── Apply translations to all [data-i18n] elements ─────────────
function applyLang(lang) {
  const t = TRANSLATIONS[lang];
  if (!t) return;
  currentLang = lang;
  window.currentLang = lang;
  localStorage.setItem('exp_lang', lang);

  // Text content
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (t[key] !== undefined) el.innerHTML = t[key];
  });

  // Placeholders
  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.getAttribute('data-i18n-placeholder');
    if (t[key] !== undefined) el.placeholder = t[key];
  });

  // Update <html lang="">
  document.documentElement.lang = lang;

  // Update typing questions
  if (window._typingQuestions) {
    window._typingQuestions = [
      t.typing_q1, t.typing_q2, t.typing_q3, t.typing_q4, t.typing_q5
    ];
  }

  // Update avatar suggested questions buttons
  const avatarBtns = document.querySelectorAll('[data-avatar-q]');
  avatarBtns.forEach(btn => {
    const idx = btn.getAttribute('data-avatar-q');
    const key = `avatar_q${idx}`;
    if (t[key]) {
      btn.textContent = t[key];
      btn.setAttribute('onclick', `avatarSendMessage('${t[key]}')`);
    }
  });

  // Update switcher UI
  updateSwitcherUI(lang);
}

// ── Build language switcher dropdown HTML ──────────────────────
function buildSwitcher(id, position = 'bottom') {
  const container = document.getElementById(id);
  if (!container) return;

  container.innerHTML = `
    <div class="lang-switcher relative" id="ls-${id}">
      <button
        onclick="toggleLangDropdown('ls-${id}')"
        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-light hover:border-secondary transition-all text-sm font-medium text-gray-700 shadow-sm"
        aria-label="Change language"
        title="Change language">
        <span class="text-base" id="ls-flag-${id}">🇮🇹</span>
        <span class="hidden sm:inline text-xs font-semibold" id="ls-label-${id}">IT</span>
        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="ls-arrow-${id}"></i>
      </button>
      <div id="ls-dropdown-${id}"
           class="lang-dropdown absolute ${position === 'top' ? 'bottom-full mb-2' : 'top-full mt-2'} right-0 w-44 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-[200] hidden">
        ${Object.entries(LANG_META).map(([code, meta]) => `
          <button
            onclick="setLang('${code}'); closeLangDropdowns();"
            class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-light transition-colors text-left group"
            data-lang-option="${code}">
            <span class="text-lg">${meta.flag}</span>
            <span class="text-sm font-medium text-gray-700 group-hover:text-secondary">${meta.label}</span>
            <i class="fas fa-check text-accent text-xs ml-auto opacity-0 lang-check-${code}"></i>
          </button>
        `).join('')}
      </div>
    </div>
  `;
}

function toggleLangDropdown(id) {
  const dd = document.getElementById(`${id}-dropdown-${id.replace('ls-','')}-${id}`);
  // simpler: find dropdown inside
  const container = document.getElementById(id);
  const dropdown = container.querySelector('.lang-dropdown');
  const arrow = container.querySelector('[id^="ls-arrow"]');
  const isOpen = !dropdown.classList.contains('hidden');
  closeLangDropdowns();
  if (!isOpen) {
    dropdown.classList.remove('hidden');
    if (arrow) arrow.style.transform = 'rotate(180deg)';
  }
}

function closeLangDropdowns() {
  document.querySelectorAll('.lang-dropdown').forEach(dd => dd.classList.remove('hidden'));
  document.querySelectorAll('[id^="ls-arrow"]').forEach(a => a.style.transform = '');
  // actually toggle
  document.querySelectorAll('.lang-dropdown:not(.hidden)').forEach(dd => dd.classList.add('hidden'));
}

// Close on outside click
document.addEventListener('click', e => {
  if (!e.target.closest('.lang-switcher')) closeLangDropdowns();
});

function updateSwitcherUI(lang) {
  const meta = LANG_META[lang];
  document.querySelectorAll('[id^="ls-flag-"]').forEach(el => el.textContent = meta.flag);
  document.querySelectorAll('[id^="ls-label-"]').forEach(el => el.textContent = lang.toUpperCase());
  // Check marks
  document.querySelectorAll('[data-lang-option]').forEach(btn => {
    const check = btn.querySelector('i');
    if (check) check.style.opacity = btn.getAttribute('data-lang-option') === lang ? '1' : '0';
    btn.style.background = btn.getAttribute('data-lang-option') === lang ? 'var(--tw-bg-light, #E8F4F4)' : '';
  });
}

window.setLang = function(lang) {
  applyLang(lang);
};

window.toggleLangDropdown = function(id) {
  const container = document.getElementById(id);
  if (!container) return;
  const dropdown = container.querySelector('.lang-dropdown');
  const arrow = container.querySelector('[id^="ls-arrow"]');
  const isOpen = !dropdown.classList.contains('hidden');
  // close all first
  document.querySelectorAll('.lang-dropdown').forEach(d => d.classList.add('hidden'));
  document.querySelectorAll('[id^="ls-arrow"]').forEach(a => a.style.transform = '');
  if (!isOpen) {
    dropdown.classList.remove('hidden');
    if (arrow) arrow.style.transform = 'rotate(180deg)';
  }
};

window.closeLangDropdowns = function() {
  document.querySelectorAll('.lang-dropdown').forEach(d => d.classList.add('hidden'));
  document.querySelectorAll('[id^="ls-arrow"]').forEach(a => a.style.transform = '');
};

// ── Init on DOM ready ──────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  buildSwitcher('lang-switcher-header', 'bottom');
  buildSwitcher('lang-switcher-mobile', 'bottom');
  buildSwitcher('lang-switcher-footer', 'top');
  applyLang(currentLang);
});

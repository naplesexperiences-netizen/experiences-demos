/*
 * Preset Tailwind (v3) per experiences-theme.
 * I colori puntano alle variabili di tokens.css: cambiare un valore lì
 * aggiorna sia le utility sia il CSS custom.
 *
 *   // tailwind.config.js del tema
 *   module.exports = {
 *     presets: [require('./design-system/tailwind.preset.js')],
 *     content: ['./**\/*.php', './assets/js/**\/*.js'],
 *   };
 */
const rgb = name => `rgb(var(--exp-${name}-rgb) / <alpha-value>)`;

module.exports = {
  theme: {
    extend: {
      colors: {
        // Nomi storici, mantenuti per non toccare i template in un colpo solo.
        primary: rgb('ink-700'),
        secondary: rgb('teal-600'),
        accent: rgb('teal-400'),
        dark: rgb('night-900'),
        light: rgb('mist-50'),
        // Scala esplicita: da preferire nel markup nuovo.
        teal: {
          200: rgb('teal-200'),
          300: rgb('teal-300'),
          400: rgb('teal-400'),
          600: rgb('teal-600'),
          700: rgb('teal-700'),
          800: rgb('teal-800'),
        },
        ink: rgb('ink-700'),
        night: rgb('night-900'),
        mist: rgb('mist-50'),
        // Ruoli
        action: {
          DEFAULT: 'var(--exp-color-action)',
          hover: 'var(--exp-color-action-hover)',
          active: 'var(--exp-color-action-active)',
        },
        link: {
          DEFAULT: 'var(--exp-color-link)',
          hover: 'var(--exp-color-link-hover)',
          'on-dark': 'var(--exp-color-link-on-dark)',
        },
      },
      fontFamily: {
        heading: 'var(--exp-font-heading)',
        sans: 'var(--exp-font-body)',
      },
      fontSize: {
        display: ['var(--exp-text-display)', { lineHeight: 'var(--exp-leading-tight)' }],
      },
      borderRadius: {
        btn: 'var(--exp-radius-md)',
        card: 'var(--exp-radius-lg)',
        panel: 'var(--exp-radius-xl)',
      },
      boxShadow: {
        'exp-sm': 'var(--exp-shadow-sm)',
        'exp-md': 'var(--exp-shadow-md)',
        'exp-lg': 'var(--exp-shadow-lg)',
        'exp-xl': 'var(--exp-shadow-xl)',
        'exp-glow': 'var(--exp-shadow-glow)',
      },
      transitionDuration: {
        fast: 'var(--exp-dur-fast)',
        base: 'var(--exp-dur-base)',
        slow: 'var(--exp-dur-slow)',
      },
      transitionTimingFunction: {
        exp: 'var(--exp-ease)',
        'exp-out': 'var(--exp-ease-out)',
      },
      zIndex: {
        header: 'var(--exp-z-header)',
        chat: 'var(--exp-z-chat)',
        modal: 'var(--exp-z-modal)',
        cookie: 'var(--exp-z-cookie)',
      },
    },
  },
};

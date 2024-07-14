// @ts-check

/**
 * @type {import('next-i18next').UserConfig}
 */
const i18nConfig = {
  i18n: {
    defaultLocale: process.env.NEXT_PUBLIC_DEFAULT_LANGUAGE || 'tr',
    locales: JSON.parse(process.env.NEXT_PUBLIC_AVAILABLE_LANGUAGES || '["tr", "en"]'),
  },
};

module.exports = i18nConfig;

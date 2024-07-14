import { useTranslation } from 'next-i18next';
import React, { useEffect, useState } from 'react';
import { setLocale } from '../../../utils/helpers/setLocale';

const LanguageSwitcher = () => {
  const [locale, setLocaleState] = useState('tr');
  const { t } = useTranslation('common');

  useEffect(() => {
    const cookies = document.cookie
      .split('; ')
      .find((row) => row.startsWith('NEXT_LOCALE='))
      ?.split('=')[1];
    setLocaleState(cookies || 'tr');
  }, []);

  const changeLanguage = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setLocale(e.target.value);
  };

  return (
    <select onChange={changeLanguage} value={locale}>
      <option value="tr">{t('turkish')}</option>
      <option value="en">{t('english')}</option>
    </select>
  );
};

export default LanguageSwitcher;

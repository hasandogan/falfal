export const setLocale = (locale: string) => {
  document.cookie = `NEXT_LOCALE=${locale}; path=/`;
  window.location.reload();
};

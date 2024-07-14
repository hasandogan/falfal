export const getMenuIconColor = (
  url: string,
  menuUrl: string | null
): string => {
  if (menuUrl && url?.includes(menuUrl)) {
    return '#54C5F8';
  }
  return '#ffffff';
};

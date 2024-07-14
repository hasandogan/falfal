export const parseCookies = (cookieHeader: string | undefined) => {
  if (!cookieHeader) return {};
  return Object.fromEntries(
    cookieHeader.split('; ').map((c) => {
      const [key, ...v] = c.split('=');
      return [key, v.join('=')];
    })
  );
};
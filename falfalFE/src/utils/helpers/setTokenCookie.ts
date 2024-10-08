import Cookies from 'js-cookie';
export const setTokenCookie = (token: string) => {
  Cookies.set('auth_token', token, {
    expires: 7,
    secure: true,
    sameSite: 'strict',
  });
};


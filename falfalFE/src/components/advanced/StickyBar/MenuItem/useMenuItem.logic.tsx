import { getMenuIconColor } from '../../../../utils/helpers/getMenuIconColor';
import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import MenuItemProps from './menu-item.type';

const useMenuItemLogic = ({ menuItem }: MenuItemProps) => {
  const router = useRouter();
  const [url, setUrl] = useState<string>('');

  const IconComponent = menuItem.icon;
  const iconColor = getMenuIconColor(url, menuItem.url);

  const navigate = (url: string | null) => {
    if (url) {
      router.push(url);
    }
  };

  useEffect(() => {
    if (typeof window !== undefined) {
      setUrl(window.location.pathname);
    }
  }, []);

  return { IconComponent, iconColor, navigate };
};
export default useMenuItemLogic;

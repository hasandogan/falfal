import IconFavourites from '../../icons/IconFavourites';
import IconGlobe from '../../icons/IconGlobe';
import IconMore from '../../icons/IconMore';
import { MenuItemType } from './MenuItem/menu-item.type';

const useStickyBarLogic = () => {
  const menuItems: MenuItemType[] = [
    {
      icon: IconGlobe,
      title: 'Home',
      url: '/home',
      subMenu: [],
      type: 0,
    },
    {
      icon: IconMore,
      title: 'Fallar',
      url: '/fortunes',
      type: 0,
      subMenu: [
        {
          icon: IconFavourites,
          title: 'Favourites',
          url: '/favourite',
          subMenu: [],
          type: 0,
        },
      ],
    },
  ];
  return { menuItems };
};
export default useStickyBarLogic;

import IconFavourites from '../../icons/IconFavourites';
import IconGlobe from '../../icons/IconGlobe';
import IconMore from '../../icons/IconMore';
import { MenuItemType } from './MenuItem/menu-item.type';

const useStickyBarLogic = () => {
  const menuItems: MenuItemType[] = [
    {
      icon: IconGlobe,
      title: 'Home',
      url: '/',
      subMenu: [],
      type: 0,
    },
    {
      icon: IconFavourites,
      title: 'Favourites',
      url: '/favourite',
      subMenu: [],
      type: 0,
    },
    {
      icon: IconMore,
      title: 'More',
      url: null,
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

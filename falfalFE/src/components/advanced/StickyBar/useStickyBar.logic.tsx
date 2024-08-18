import IconFavourites from '../../icons/IconFavourites';
import IconGlobe from '../../icons/IconGlobe';
import IconMore from '../../icons/IconMore';
import { MenuItemType } from './MenuItem/menu-item.type';
import IconProfile from "@/components/icons/IconProfile";

const useStickyBarLogic = () => {
  const menuItems: MenuItemType[] = [
    {
      icon: IconGlobe,
      title: 'Anasayfa',
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
    {
      icon: IconProfile,
      title: 'Profil',
      url: '/profile',
      subMenu: [],
      type: 0,
    },
  ];
  return { menuItems };
};
export default useStickyBarLogic;

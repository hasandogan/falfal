import { ComponentType } from 'react';

type MenuItemProps = {
  menuItem: MenuItemType;
};

export type MenuItemType = {
  icon: ComponentType<{ color?: string }>;
  title: string;
  url: string | null;
  subMenu: MenuItemType[];
  type: MenuTypes;
};

export default MenuItemProps;

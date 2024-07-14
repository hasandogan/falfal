import * as Styled from './menu-item.styled';
import MenuItemProps from './menu-item.type';
import useMenuItemLogic from './useMenuItem.logic';

const MenuItem = ({ menuItem }: MenuItemProps) => {
  const { IconComponent, iconColor, navigate } = useMenuItemLogic({ menuItem });

  return (
    <Styled.MenuItem onClick={() => navigate(menuItem.url)}>
      <IconComponent color={iconColor} />
      <span>{menuItem.title}</span>
    </Styled.MenuItem>
  );
};

export default MenuItem;

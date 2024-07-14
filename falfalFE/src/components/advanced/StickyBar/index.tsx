import MenuItem from './MenuItem';
import * as Styled from './sticky-bar.styled';
import useStickyBarLogic from './useStickyBar.logic';

const StickyBar = () => {
  const { menuItems } = useStickyBarLogic();
  return (
    <Styled.StickyBar>
      {menuItems?.map((menuItem, index) => (
        <MenuItem menuItem={menuItem} key={`menu-item-${index}`} />
      ))}
    </Styled.StickyBar>
  );
};

export default StickyBar;

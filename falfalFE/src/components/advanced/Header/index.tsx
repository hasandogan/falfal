import Image from 'next/image';
import * as Styled from './header.styled';
import HeaderProps from './header.type';
import useHeaderLogic from './useHeader.logic';

const Header = ({ pageName }: HeaderProps) => {
  const { onProfileClick } = useHeaderLogic();

  return (
    <Styled.Header>
      <div className={'general-title'}>
        {process.env.NEXT_PUBLIC_PROJECT_NAME}
      </div>
      <div className={'page-name'}>{pageName}</div>
      <div className="header-buttons">
        <div className="header-buttons-left">
        </div>
      </div>
    </Styled.Header>
  );
};
export default Header;

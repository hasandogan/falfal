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
          <div className={'button'}>
            <Image
              src={'/images/coin-count.svg'}
              alt={'Coin Count'}
              width={22}
              height={22}
            />
            <span>{'coinCount'}</span>
          </div>
          <div className={'button'}>
            <Image
              src={'/images/basket.svg'}
              alt={'market'}
              width={22}
              height={22}
            />
            <span>{'Market'}</span>
          </div>
        </div>
        <div className="header-buttons-right">
          <div className={'profile'} onClick={onProfileClick}>
            <Image
              src={'/images/profile.svg'}
              alt={'Profile'}
              width={24}
              height={24}
            />
          </div>
        </div>
      </div>
    </Styled.Header>
  );
};
export default Header;

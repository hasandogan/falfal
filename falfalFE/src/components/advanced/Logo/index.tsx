import Image from 'next/image';
import Link from 'next/link';
import * as Styled from './logo.styled';

const Logo = () => {
  return (
    <Styled.Logo>
      <Link href={'/'}>
        <Image
          src={'/images/logo.png'}
          alt={process.env.NEXT_PUBLIC_PROJECT_NAME ?? ''}
          width={73}
          height={60}
        />
      </Link>
    </Styled.Logo>
  );
};

export default Logo;

import Link from 'next/link';
import LanguageSwitcher from '../LanguageSwitcher';

const TempMenu = () => {
  return (
    <div
      style={{ display: 'flex', justifyContent: 'space-between', padding: 10 }}
    >
      <LanguageSwitcher />
      <div>
        <Link href={'/'}> Main</Link>
      </div>
      <div>
        <Link href={'/sign-in'}> Sign In </Link>
      </div>
      <div>
        <Link href={'/sign-up'}> Sign up</Link>
      </div>
      <div>
        <Link href={'/home'}> Home</Link>
      </div>
    </div>
  );
};

export default TempMenu;

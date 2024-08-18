import { useRouter } from 'next/router';

const useHeaderLogic = () => {
  const router = useRouter();
  const onProfileClick = () => {
    router.push('/profile');
  };

  return { onProfileClick };
};

export default useHeaderLogic;

import { useRouter } from 'next/router';
import { useEffect } from 'react';
import Loader from '../components/advanced/Loader';

const Index = () => {
  const router = useRouter();

  useEffect(() => {
    setTimeout(() => {
      router.push('/home');
    }, 3000);
  }, []);

  return <Loader />;
};

export default Index;
